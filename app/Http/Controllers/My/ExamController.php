<?php

namespace App\Http\Controllers\My;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ExamSession;
use App\Models\Application;
use App\Models\Question;
use App\Models\QuestionOption;
use App\Models\QuestionAnswer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ExamController extends Controller
{
    private const STAGE_CODE = 'UJIAN_ONLINE';
    private const TITLE = 'Tes Potensi Akademik';
    private const DURATION_MINUTES = 25;   // $this->timeout versi lama (60 * 25 detik)
    private const MAX_QUESTIONS = 25;      // $this->maxSoal versi lama
    private const GRACE_SECONDS = 10;      // toleransi simpan jawaban setelah waktu habis

    public function start(Request $request, Application $application) {
        $this->authorizeOwner($application);
        $row = ExamSession::where('application_id', $application->id)->first();

        if ($row) {
            return $this->resume($request, $application, $row);
        }

        // Cek batas tahap (padanan cekBatasTahap versi lama).
        [$stage, $problem] = $this->checkStage($application);

        if ($problem) {
            return $this->backToApplication($application, 'error', $problem);
        }

        $questions = Question::with('questionOptions')
            ->inRandomOrder()
            ->limit(self::MAX_QUESTIONS)
            ->get();

        if ($questions->isEmpty()) {
            return $this->backToApplication($application, 'error', 'Soal ujian belum tersedia. Silakan hubungi panitia.');
        }

        $now = now();

        $row = ExamSession::firstOrCreate(
            ['application_id' => $application->id],
            ['status' => 'proses', 'score' => 0, 'started_at' => $now]
        );

        if (!$row->wasRecentlyCreated) {
            return $this->resume($request, $application, $row); // klik ganda / request bersamaan
        }

        // Batas waktu: 25 menit, tapi tidak melewati akhir jadwal tahap.
        $endsAt = $now->copy()->addMinutes(self::DURATION_MINUTES);
        if ($stage->execution_type === 'scheduled' && $stage->end_at && $stage->end_at->lt($endsAt)) {
            $endsAt = $stage->end_at->copy();
        }

        // Soal + pilihan (diacak) disimpan di session, seperti createSession() versi lama.
        $payload = $questions->map(function ($q) {
            $options = $q->questionOptions->shuffle()->values()->map(fn ($o, $i) => [
                'id'    => $o->id,
                'label' => range('A', 'Z')[$i] ?? (string) ($i + 1),
                'option'  => $o->option,
                'media_path' => $o->media_path ? asset('soal/'.$o->media_path): null,
            ])->all();

            return [
                'id' => $q->id,
                'description'=> $q->description,
                'question' => $q->question,
                'media_path' => $q->media_path ? asset('soal/'.$q->media_path): null,
                'options' => $options,
            ];
        })->all();

        $request->session()->put($this->key($application), [
            'started_at' => $now->timestamp,
            'ends_at'    => $endsAt->timestamp,
            'questions'  => $payload,
            'answers'    => [],   // [question_id => option_id]  (UserPilihan)
            'flags'      => [],   // [question_id => true]       (ragu-ragu)
        ]);

        return redirect()->route('my.exam.show', [$application, 'no' => 1]);
    }

    public function show(Request $request, Application $application) {
        $this->authorizeOwner($application);

        $row = ExamSession::where('application_id', $application->id)->first();

        if (! $row) {
            return $this->backToApplication($application, 'error', 'Anda belum memulai ujian.');
        }

        if ($row->status === 'selesai') {
            return $this->backToApplication($application, 'success', 'Ujian Anda sudah selesai.');
        }

        $state = $request->session()->get($this->key($application));
        if (! $state) {
            return $this->lostSession($application);
        }

        if ($this->isExpired($state)) {
            $this->finalize($request, $application, $row, $state, 'timeout');
            return $this->backToApplication($application, 'success', 'Waktu ujian telah habis. Jawaban Anda sudah dikirim otomatis.');
        }

        $questions = $state['questions'];
        $total = count($questions);
        $number = min(max((int) $request->query('no', 1), 1), $total);

        // Array dari session diubah ke objek agar cocok dengan view.
        $question = json_decode(json_encode($questions[$number - 1]));

        $questionIds = array_column($questions, 'id');
        $answers = $state['answers'];
        $flags = array_keys($state['flags']);

        $exam = (object) [
            'title'        => self::TITLE,
            'instructions' => null,
            'ends_at'      => \Illuminate\Support\Carbon::createFromTimestamp($state['ends_at']),
        ];

        $title = self::TITLE;
        return view('home.my.exam.show', [
            'title'=> $title,
            'exam'=> $exam,
            'question'=> $question,
            'number'=> $number,
            'questionIds'=> $questionIds,
            'answers'=> $answers,
            'flags'=> $flags,
            'data'=> $application,

        ]);
    }

    public function answer(Request $request, Application $application){
        $this->authorizeOwner($application);

        $row = ExamSession::where('application_id', $application->id)->first();

        if (! $row) {
            return response()->json(['message' => 'Sesi ujian tidak ditemukan.'], 404);
        }

        if ($row->status === 'selesai') {
            return response()->json(['message' => 'Ujian sudah selesai.'], 409);
        }

        $state = $request->session()->get($this->key($application));

        if (! $state) {
            return response()->json(['message' => 'Sesi ujian tidak ditemukan. Hubungi panitia.'], 409);
        }

        if ($this->isExpired($state, self::GRACE_SECONDS)) {
            $this->finalize($request, $application, $row, $state, 'timeout');

            return response()->json(['message' => 'Waktu ujian telah habis.'], 409);
        }

        $validated = $request->validate([
            'question_id' => ['required', 'integer'],
            'option_id'   => ['nullable', 'integer'],
            'flagged'     => ['required', 'boolean'],
        ]);

        $questionId = (int) $validated['question_id'];

        $question = collect($state['questions'])->firstWhere('id', $questionId);

        if (! $question) {
            return response()->json(['message' => 'Soal tidak valid.'], 422);
        }

        // Hanya ubah jawaban jika pilihan dikirim; tanda ragu-ragu saja tidak menghapus jawaban.
        if (! empty($validated['option_id'])) {
            $optionId = (int) $validated['option_id'];

            if (! in_array($optionId, array_column($question['options'], 'id'), true)) {
                return response()->json(['message' => 'Pilihan jawaban tidak valid.'], 422);
            }

            $state['answers'][$questionId] = $optionId;
        }

        if ($validated['flagged']) {
            $state['flags'][$questionId] = true;
        } else {
            unset($state['flags'][$questionId]);
        }

        $request->session()->put($this->key($application), $state);

        return response()->json(['ok' => true, 'answered' => count($state['answers'])]);
    }

    public function finish(Request $request, Application $application){
        $this->authorizeOwner($application);

        $row = ExamSession::where('application_id', $application->id)->first();

        if (! $row) {
            return $this->backToApplication($application, 'error', 'Anda belum memulai ujian.');
        }

        if ($row->status === 'selesai') {
            return $this->backToApplication($application, 'success', 'Ujian Anda sudah selesai.');
        }

        $state = $request->session()->get($this->key($application));

        if (! $state) {
            return $this->lostSession($application);
        }

        $saved = $this->finalize(
            $request, $application, $row, $state,
            $this->isExpired($state) ? 'timeout' : 'submitted'
        );

        if (! $saved) {
            return $this->backToApplication($application, 'error', 'Gagal menyimpan hasil ujian. Silakan hubungi panitia untuk info lanjut.');
        }

        return $this->backToApplication(
            $application,
            'success',
            'Selamat, Anda telah selesai mengikuti ujian tes masuk. Silakan menunggu hasil pengumuman kelulusan.'
        );
    }


    // ------------------------------------------------------------------
    // Helper
    // ------------------------------------------------------------------

    /** Hanya pemilik pendaftaran yang boleh mengakses ujiannya. */
    private function authorizeOwner(Application $application){
        abort_unless($application->applicant && (int) $application->applicant->user_id === (int) auth()->id(),
            403
        );
    }

    private function key(Application $data){
        return 'exam.' . $data->id;
    }

    private function isExpired(array $state, int $graceSeconds = 0){
        return now()->timestamp > ($state['ends_at'] + $graceSeconds);
    }

    /**
     * Cek tahap UJIAN_ONLINE: ada, aktif, sedang berjalan, dan masih dalam jadwal.
     *
     * @return array{0: mixed, 1: ?string}  [stage, pesan masalah]
     */
    private function checkStage(Application $data){
        $wavePath = $data->pmbWavePath()->first();

        $stage = $wavePath?->stages()
            ->where('is_active', true)
            ->whereHas('pmbRegistrationStage', fn ($q) => $q->where('code', self::STAGE_CODE))
            ->first();

        if (! $stage) {
            return [null, 'Jalur pendaftaran ini tidak memiliki ujian online.'];
        }

        if ($data->current_stage !== self::STAGE_CODE) {
            return [$stage, 'Ujian online belum dapat dimulai pada tahap pendaftaran Anda saat ini.'];
        }

        if ($stage->execution_type === 'scheduled') {
            $now = now();

            if ($stage->start_at && $now->lt($stage->start_at)) {
                return [$stage, 'Ujian belum dibuka. Jadwal mulai: ' . $stage->start_at->translatedFormat('d M Y, H:i') . '.'];
            }

            if ($stage->end_at && $now->gte($stage->end_at)) {
                return [$stage, 'Batas ujian telah selesai. Silakan menunggu waktu pengumuman atau hubungi panitia.'];
            }
        }

        return [$stage, null];
    }

    /** Peserta sudah punya baris ujian: lanjutkan, atau tolak jika sudah selesai / session hilang. */
    private function resume(Request $request, Application $application, ExamSession $row){
        if ($row->status === 'selesai') {
            return $this->backToApplication($application, 'success', 'Anda sudah melakukan ujian. Silakan menunggu waktu pengumuman atau hubungi panitia.');
        }

        $state = $request->session()->get($this->key($application));

        if (! $state) {
            return $this->lostSession($application);
        }

        if ($this->isExpired($state)) {
            $this->finalize($request, $application, $row, $state, 'timeout');

            return $this->backToApplication($application, 'success', 'Waktu ujian telah habis. Jawaban Anda sudah dikirim otomatis.');
        }

        return redirect()->route('my.exam.show', [$application, 'no' => 1]);
    }

    private function lostSession(Application $data) {
        return $this->backToApplication(
            $data,
            'error',
            'Anda sudah melakukan ujian. Sesi ujian tidak ditemukan; silakan hubungi panitia.'
        );
    }

    /**
     * Hitung skor dari jawaban di session vs kunci di database, update baris ujian SEKALI,
     * lalu bersihkan session. Aman dipanggil berulang.
     */
    private function finalize(Request $request, Application $application, ExamSession $row, array $state, string $reason){
        try {
            DB::transaction(function () use ($row, $state, $reason, $application) {
                $locked = ExamSession::lockForUpdate()->find($row->id);

                if (! $locked || $locked->status === 'selesai') {
                    return;
                }

                $questions = $state['questions'];
                $total = count($questions);

                // Kunci jawaban (JawabanID) dibaca dari database.
                $key = Question::whereHas('questionAnswer')
                    ->whereIn('id', array_column($questions, 'id'))
                    ->with('questionAnswer:id,question_id,question_option_id')
                    ->get()
                    ->mapWithKeys(function ($question) {
                        return [
                            $question->id => $question->questionAnswer->question_option_id,
                        ];
                    });

                $now = now();
                $correct = 0;

                foreach ($questions as $q) {
                    $chosen = $state['answers'][$q['id']] ?? null;

                    // Soal yang tidak dijawab tidak dibuatkan baris (dihitung salah).
                    if ($chosen === null) {
                        continue;
                    }

                    // Pastikan pilihan memang milik soal ini.
                    $validIds = array_map('intval', array_column($q['options'], 'id'));
                    if (! in_array((int) $chosen, $validIds, true)) {
                        continue;
                    }

                    if ((int) $chosen === (int) ($key[$q['id']] ?? 0)) {
                        $correct++;
                    }
                }

                $locked->update([
                    'status'        => 'selesai',
                    'score'         => $total > 0 ? round($correct / $total * 100, 2) : 0,
                    'finished_at'   => $now,
                    'finish_reason' => $reason,
                ]);
                $application->update([
                    'current_stage'=> 'PENGUMUMAN_KELULUSAN',
                ]);
            });
        } catch (\Throwable $e) {
            report($e);

            return false;
        }

        $request->session()->forget($this->key($application));

        return true;
    }

    private function backToApplication(Application $application, string $type, string $message){
        return redirect()->route('my.application.show', $application)->with($type, $message);
    }
}
