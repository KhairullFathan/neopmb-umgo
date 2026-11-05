<?php

namespace App\Http\Controllers\Pmb;

use App\Http\Controllers\Controller;
use App\Models\ApplicationPayment;
use App\Models\StudyProgram;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Application;
use App\Models\User;
use App\Models\Applicant;
use App\Models\PmbPeriod;
use App\Models\PmbWave;
use App\Models\PmbWavePath;
use App\Models\ApplicationDocuments;
use App\Models\ApplicationStudyProgram;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ApplicationsExport;
use App\Exports\ApplicationSheetExport;


class PmbApplicationController extends Controller
{

    private function generateRegistrationNumber($pmb_period_id, $pmb_wave_id, $pmb_registration_path_id){
        $pmb_wave = PmbWave::findOrFail($pmb_wave_id);
        $pmb_wave_path = PmbWavePath::query()
            ->with('pmbRegistrationPath')
            ->where('pmb_wave_id', $pmb_wave_id)
            ->where('pmb_registration_path_id', $pmb_registration_path_id)
            ->firstOrFail();
        $pmb_wave_number = (int) str($pmb_wave->code)->after('-')->toString();
        $prefix = $pmb_wave_path->pmbRegistrationPath->code
            . $pmb_period_id
            . str_pad((string) $pmb_wave_number, 2, '0', STR_PAD_LEFT);
        // Cari nomor urut terbesar untuk kombinasi ini.
        $last_application = Application::query()
            ->where('pmb_period_id', $pmb_period_id)
            ->where('pmb_wave_id', $pmb_wave_id)
            ->where('pmb_registration_path_id', $pmb_registration_path_id)
            ->where('registration_number', 'like', $prefix . '%')
            ->orderByDesc('id')
            ->first();
        $next_number = $last_application
            ? ((int) substr(
                $last_application->registration_number,
                strlen($prefix)
            )) + 1
            : 1;
        return $prefix . str_pad(
            (string) $next_number,
            5,
            '0',
            STR_PAD_LEFT
        );
    }

    private function getApplicationQuery(Request &$request): Builder{
        $user = auth()->user();
        $query = Application::query();

        // Filter berdasarkan program studi yang boleh diakses user
        $query->whereHas('studyPrograms', function ($q) use ($user, $request) {
            if ($request->filled('study_program_code')) {
                $q->where('study_program_code', $request->study_program_code)
                    ->where('choice_number', 1);
            } else {
                $q->whereIn(
                    'study_program_code',
                    $user->studyPrograms()->select('study_programs.code')
                );
            }
        });

        // Pencarian
        if ($request->filled('search')) {
            $search = $request->search;

            $query->whereHas('applicant', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('identity_number', 'like', "%{$search}%");
            });
        }

        // Filter dokumen
        if ($request->filled('documents')) {
            switch ($request->documents) {
                case 'complete':
                    $query->whereHas('pmbRegistrationPath.pmbRegistrationDocument')
                        ->whereDoesntHave(
                            'pmbRegistrationPath.pmbRegistrationDocument',
                            function ($requiredDocument) {
                                $requiredDocument->whereDoesntHave(
                                    'applicationDocuments',
                                    function ($uploadedDocument) {
                                        $uploadedDocument
                                            ->whereColumn(
                                                'application_documents.application_id',
                                                'applications.id'
                                            )
                                            ->where('verification_status', 'approved');
                                    }
                                );
                            }
                        );
                    break;

                case 'incomplete':
                    $query->whereHas(
                        'pmbRegistrationPath.pmbRegistrationDocument',
                        function ($requiredDocument) {
                            $requiredDocument->whereDoesntHave(
                                'applicationDocuments',
                                function ($uploadedDocument) {
                                    $uploadedDocument
                                        ->whereColumn(
                                            'application_documents.application_id',
                                            'applications.id'
                                        )
                                        ->where('verification_status', 'approved');
                                }
                            );
                        }
                    );
                    break;
            }
        }

        // Filter periode
        if ($request->filled('period_id')) {
            $query->whereHas('pmbWave', function ($q) use ($request) {
                $q->where('pmb_waves.pmb_period_id', $request->period_id);
            });
        }

        // Filter gelombang
        if ($request->filled('wave_id')) {
            $query->whereHas('pmbWave', function ($q) use ($request) {
                $q->where('pmb_waves.id', $request->wave_id);
            });
        }

        // Filter jalur pendaftaran
        if ($request->filled('registration_path_id')) {
            $query->whereHas('pmbRegistrationPath', function ($q) use ($request) {
                $q->where(
                    'pmb_registration_paths.id',
                    $request->registration_path_id
                );
            });
        }

        return $query->orderBy('registration_number', 'DESC');
    }

    public function index(Request &$request){
        $user = auth()->user();
        $query = $this->getApplicationQuery($request);
        $data = $query->paginate(25)->withQueryString();

        $studyPrograms = StudyProgram::query()
            ->whereIn(
                'code',
                $user->studyPrograms()->select('study_programs.code')
            )
            ->orderBy('faculty_code')
            ->orderBy('code')
            ->get();

        $pmbWaves = PmbWave::query()
            ->when($request->filled('period_id'), function ($q) use ($request) {
                $q->where('pmb_period_id', $request->period_id);
            })
            ->orderBy('pmb_period_id', 'desc')
            ->orderBy('code', 'desc')
            ->get();

        $pmbWavePaths = PmbWavePath::query()
            ->when($request->filled('wave_id'), function ($q) use ($request) {
                $q->where('pmb_wave_id', $request->wave_id);
            })
            ->get();

        return view('pmb.pmb-application.index', [
            'title' => 'Data Formulir',
            'periods' => PmbPeriod::orderBy('id', 'desc')->pluck('name', 'id'),
            'pmbWaves' => $pmbWaves,
            'pmbWavePaths' => $pmbWavePaths,
            'studyPrograms' => $studyPrograms,
            'data' => $data,
        ]);
    }

    public function export(Request &$request){
        $user = auth()->user();
        $studyPrograms = StudyProgram::query()
            ->whereIn(
                'code',
                $user->studyPrograms()->select('study_programs.code')
            )
            ->when(
                $request->filled('study_program_code'),
                function ($q) use ($request) {
                    $q->where('code', $request->study_program_code);
                }
            )
            ->orderBy('faculty_code', 'ASC')
            ->orderBy('code', 'ASC')
            ->get();
        $sheets = [];

        foreach ($studyPrograms as $studyProgram) {
            // Gunakan query utama dengan seluruh filter index.
            $query = $this->getApplicationQuery($request);
            // Batasi data untuk program studi yang sedang diproses.
            $query->whereHas('studyPrograms', function ($q) use ($studyProgram, $request) {
                $q->where(
                    'study_program_code',
                    $studyProgram->code
                );
                // Mengikuti aturan index:
                // jika prodi dipilih, hanya pilihan pertama.
                if ($request->filled('study_program_code')) {
                    $q->where('choice_number', 1);
                }
            });
            // Nama sheet menggunakan nama program studi.
            $sheets[] = new ApplicationSheetExport(
                $query,
                $studyProgram->name
            );
        }
        /*
        |--------------------------------------------------------------------------
        | Validasi Sheet
        |--------------------------------------------------------------------------
        */
        if (empty($sheets)) {
            return redirect()
                ->back()
                ->with('error', 'Tidak ada program studi yang dapat diekspor.');
        }
        /*
        |--------------------------------------------------------------------------
        | Download Excel
        |--------------------------------------------------------------------------
        */

        return Excel::download(
            new ApplicationsExport($sheets),
            'data-pendaftaran-' . now()->format('Ymd-His') . '.xlsx'
        );
    }

    public function create(Request $request){
        $pmbWaves = PmbWave::query();
        $pmbWavePaths = PmbWavePath::query();
        $pmb_wave_path = null;
        if($request->has('period_id') && $request->period_id != ''){
            $period_id = $request->period_id;
            $pmbWaves->where('pmb_period_id', $period_id);
        }

        if($request->has('pmb_wave_id') && $request->pmb_wave_id != ''){
            $pmb_wave_id = $request->pmb_wave_id;
            $pmbWavePaths->where('pmb_wave_id', $pmb_wave_id);
        }

        if($request->has('pmb_wave_path_id') && $request->pmb_wave_path_id != ''){
            $path_id = $request->pmb_wave_path_id;
            $pmb_wave_path = PmbWavePath::where('id', $path_id)->first();
        }

        return view('pmb.pmb-application.create',[
            'title'=> 'Data Formulir',
            'periods'=> PmbPeriod::orderBy('id', 'desc')->pluck('name', 'id'),
            'pmbWaves'=> $pmbWaves->orderBy('pmb_period_id','desc')->orderBy('code', 'desc')->get(),
            'pmbWavePaths'=> $pmbWavePaths->get(),
            'pmb_wave_path'=> $pmb_wave_path,
        ]);
    }

    public function store(\App\Http\Requests\RegisterRequest &$request){
        DB::transaction(function() use ($request){
            $pmb_wave_path = PmbWavePath::query()
                ->where('id', $request->pmb_wave_path_id)
                ->where('is_active', true)
                ->firstOrFail();
            $pmb_wave = PmbWave::query()
                ->where('id', $pmb_wave_path->pmb_wave_id)
                ->where('is_active', true)
                ->firstOrFail();
            $pmb_period = PmbPeriod::findOrFail($pmb_wave->pmb_period_id);
            $now = now();

            if (
                $now->lt($pmb_wave->registration_start) ||
                $now->gt($pmb_wave->registration_end)
            ) {
                throw ValidationException::withMessages([
                    'pmb_wave_path_id' =>
                        'Pendaftaran pada gelombang ini belum dibuka atau sudah ditutup.',
                ]);
            }

            // validasi prodi available
            $available_study_program = $pmb_wave_path->studyPrograms()
                ->pluck('study_program_code')
                ->map(fn ($code) => (string) $code)
                ->all();
            $selected_study_programs = collect(
                $request->input('study_programs', [])
            )
                ->filter(fn ($code) => filled($code))
                ->map(fn ($code) => (string) $code)
                ->values()
                ->all();
            foreach ($selected_study_programs as $study_program_code) {
                if (!in_array($study_program_code, $available_study_program)) {
                    throw ValidationException::withMessages([
                        'study_program' =>
                            'Salah satu program studi tidak tersedia pada jalur pendaftaran ini.',
                    ]);
                }
            }

            // Ambil biaya formulir dari database, bukan dari input pengguna.
            $registration_fee = (float) $pmb_wave_path->registration_fee;
            $stages = $pmb_wave_path->stages()
                ->with('pmbRegistrationStage')
                ->where('is_active', true)
                ->orderBy('sequence')
                ->get();
            $current_stage = $stages->first(function ($stage) use ($registration_fee) {
                $code = $stage->pmbRegistrationStage?->code;
                if (empty($code)) {
                    return false;
                }
                if ($code === 'PENDAFTARAN') {
                    return false;
                }
                if (
                    $registration_fee <= 0 &&
                    $code === 'PEMBAYARAN_FORMULIR'
                ) {
                    return false;
                }
                return true;
            });

            if (!$current_stage) {
                throw ValidationException::withMessages([
                    'pmb_wave_path_id' =>
                        'Tahapan PMB belum dikonfigurasi untuk jalur dan gelombang ini.',
                ]);
            }
            $current_stage_code = $current_stage->pmbRegistrationStage->code;
            // dd($current_stage_code);
            // create account
            $user = User::create([
                'username'=> $request->identity_number,
                'phone'=> $request->phone,
                'password'=> Hash::make($request->identity_number),
                'is_active'=> true,
            ]);
            $user->assignRole('camaba');
            // create applicant
            $applicant = Applicant::create([
                'user_id'=>$user->id,
                'name'=>$request->name,
                'identity_number'=>$request->identity_number,
                'school_origin'=>$request->school_origin,
                'school_type'=>$request->school_type,
                'school_major'=>$request->school_major,
                'phone'=>$request->phone,
                'graduate_year'=>$request->graduate_year,
            ]);
            // create application
            $application = Application::create([
                'applicant_id'=> $applicant->id,
                'pmb_period_id'=> $pmb_period->id,
                'pmb_wave_id'=> $pmb_wave->id,
                'pmb_registration_path_id'=> $pmb_wave_path->pmb_registration_path_id,
                'registration_number'=> 'TMP-' . Str::uuid(),
                'status' => 'in_progress',
                'current_stage'=> $current_stage_code,

            ]);
            // create registration number
            $registration_number = $this->generateRegistrationNumber($pmb_period->id, $pmb_wave->id, $pmb_wave_path->pmb_registration_path_id);
            $application->update([
                'registration_number' => $registration_number,
            ]);
            // save prodi
            foreach($selected_study_programs as $index=>$value){
                ApplicationStudyProgram::create([
                    'application_id'=> $application->id,
                    'study_program_code'=> $value,
                    'choice_number'=> $index+1,
                ]);
            }
        });
        return redirect()->route('pmb.application.index')->with('success', 'Berhasil menyimpan data');

    }

    public function show(Application &$application){
        $payment = $application->payments()->where(
            'payment_type','formulir'
        )->first();
        return view('pmb.pmb-application.show',[
            'title'=> 'Data Formulir '.$application->registration_number,
            'payment'=> $payment,
            'data'=> $application,
        ]);
    }

    public function destroy(Application &$application){
        // graduate studyPrograms payments documents
        try {
            $applicant = $application->applicant;
            $applicantuser = $applicant->user;
            DB::beginTransaction();
            $application->graduate?->delete();
            $application->studyPrograms()?->delete();
            $application->payments()?->delete();
            $application->documents()?->delete();
            $application->delete();

            $applicant?->delete();
            $applicantuser?->delete();
            DB::commit();
            return redirect()->route('pmb.application.index')->with('success', 'Berhasil menghapus data');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }

    }

    public function payment_verify(\App\Http\Requests\ApplicationPaymentRequest &$request, ApplicationPayment &$payment){
        // dd($request->all());
        $application = Application::findOrFail($payment->application_id);
        $stages = $application->pmbWavePath()->first()->stages()
            ->with('pmbRegistrationStage')
            ->where('is_active', true)
            ->orderBy('sequence')
            ->get();
        try {
            DB::beginTransaction();
            $payment->update([
                'sender_account_name'=> $request->sender_account_name,
                'sender_bank_name'=> $request->sender_bank_name,
                'paid_amount'=> $request->paid_amount,
                'paid_at'=> $request->paid_at,
                'verification_status'=> $request->verification_status,
                'verification_notes'=> $request->verification_notes,
                'verified_by'=> auth()->user()->id,
                'verified_at'=> now(),
            ]);
            if($request->verification_status === 'approved'){
                $current_stage = $stages->first(function ($stage) {
                    $code = $stage->pmbRegistrationStage?->code;
                    if (empty($code)) {
                        return false;
                    }
                    if ($code === 'PENDAFTARAN' || $code === 'PEMBAYARAN_FORMULIR') {
                        return false;
                    }
                    return true;
                });
                $application->update([
                    'current_stage'=> $current_stage->pmbRegistrationStage->code,
                ]);
            }else {
                $application->update([
                    'current_stage'=> 'PEMBAYARAN_FORMULIR',
                ]);
            }
            DB::commit();
            return redirect()->route('pmb.application.show', $application)->with('success', 'Berhasil menyimpan data');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function document_verify(\App\Http\Requests\ApplicationDocumentRequest &$request, ApplicationDocuments &$document){
        $application = Application::findOrFail($document->application_id);
        $stages = $application->pmbWavePath()->first()->stages()
            ->with('pmbRegistrationStage')
            ->where('is_active', true)
            ->orderBy('sequence')
            ->get();
        try {
            DB::beginTransaction();
            $document->update([
                'verification_status'=> $request->verification_status,
                'verification_notes'=> $request->verification_notes,
                'verified_by'=> auth()->user()->id,
                'verified_at'=> now(),
            ]);
            DB::commit();
            return redirect()->route('pmb.application.show', $application)->with('success', 'Berhasil menyimpan data');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function stage_set(Request &$request, Application &$application){
        try {
            DB::beginTransaction();
            $application->update([
                'current_stage'=> $request->current_stage,
            ]);
            DB::commit();
            return redirect()->route('pmb.application.show', $application)->with('success', 'Berhasil menyimpan data');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
