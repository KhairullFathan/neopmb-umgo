<?php

namespace App\Http\Controllers;

use App\Models\Applicant;
use App\Models\Application;
use App\Models\ApplicationStudyProgram;
use App\Models\PmbPeriod;
use Illuminate\Http\Request;
use App\Models\PmbWavePath;
use App\Models\User;
use App\Models\PmbWave;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class HomeController extends Controller
{
    public function index(){
        // TODO: ganti array di bawah dengan query model (Gelombang, JalurPendaftaran, ProgramStudi, KontakBantuan).
        $slides = [
            [
                'gambar' => '/homeslide/slide01.png',
                'fit' => 'cover',
                'link' => '#',
                'label' => 'Tahun akademik ' . config('pmb.tahun_akademik'),
                'judul' => 'Penerimaan Mahasiswa Baru sudah dibuka',
                'teks' => 'Daftar online dari rumah, pantau status seleksi kapan saja.',
                'bg' => 'from-blue-700 via-brand-600 to-cyan-400'
            ],
            [
                'gambar' => '/homeslide/slide02.png',
                'fit' => 'cover',
                'link' => '#',
                'label' => 'Tahun akademik ' . config('pmb.tahun_akademik'),
                'judul' => 'Penerimaan Mahasiswa Baru sudah dibuka',
                'teks' => 'Daftar online dari rumah, pantau status seleksi kapan saja.',
                'bg' => 'from-blue-700 via-brand-600 to-cyan-400'
            ],
        ];

        $prodi = [
            ['nama' => 'Teknik Informatika', 'info' => 'S1 · Akreditasi Unggul'],
            ['nama' => 'Sistem Informasi', 'info' => 'S1 · Akreditasi Unggul'],
            ['nama' => 'Manajemen', 'info' => 'S1 · Akreditasi Baik Sekali'],
            ['nama' => 'Keperawatan', 'info' => 'S1 · Akreditasi Baik Sekali'],
        ];

        // Data FAQ contoh, ganti dengan data asli (atau ambil dari model Faq).
        $faq = [
            ['tanya' => 'Bagaimana cara mendaftar sebagai mahasiswa baru?', 'jawab' => 'Klik tombol Daftar Sekarang, buat akun dengan email dan nomor WhatsApp aktif, pilih jalur pendaftaran, lalu lengkapi formulir dan unggah berkas yang diminta.'],
            ['tanya' => 'Berkas apa saja yang perlu disiapkan?', 'jawab' => 'Siapkan pas foto, scan KTP atau kartu identitas, ijazah atau surat keterangan lulus, serta nilai rapor. Jalur tertentu, seperti Prestasi, Kesehatan, dan Kedokteran, dapat meminta berkas tambahan.'],
            ['tanya' => 'Apa perbedaan setiap jalur pendaftaran?', 'jawab' => 'Jalur Prestasi menyeleksi berdasarkan nilai rapor dan prestasi. Jalur Reguler melalui tes seleksi untuk program studi umum. Reguler Kesehatan dan Reguler Kedokteran khusus untuk rumpun masing-masing dan memiliki tahap seleksi tambahan. Detailnya ada di tombol Detail pada tiap jalur.'],
            ['tanya' => 'Berapa biaya pendaftaran dan bagaimana cara membayarnya?', 'jawab' => 'Biaya formulir berbeda untuk setiap jalur dan tertera pada kartu jalur pendaftaran. Pembayaran dilakukan melalui metode yang tersedia di akun pendaftar setelah formulir dibuat.'],
            ['tanya' => 'Kapan pengumuman hasil seleksi?', 'jawab' => 'Jadwal tes dan pengumuman tercantum pada informasi gelombang yang sedang dibuka. Hasil seleksi juga dapat dilihat dengan masuk ke akun pendaftar.'],
            ['tanya' => 'Apakah pilihan program studi bisa diubah setelah mendaftar?', 'jawab' => 'Perubahan pilihan program studi bisa diajukan selama gelombang masih dibuka dan belum ada pengumuman. Hubungi panitia melalui layanan bantuan untuk prosesnya.'],
            ['tanya' => 'Siapa yang bisa saya hubungi jika mengalami kendala?', 'jawab' => 'Hubungi penanggung jawab fakultas pada tabel Layanan Bantuan, panitia pusat melalui telepon, atau gunakan WA Bot lewat tombol hijau di kanan bawah halaman.'],
        ];

        return view('home.index',[
            'title'=> 'Beranda',
            'slides'=> $slides,
            'prodi'=> $prodi,
            'faq'=> $faq,
        ]);
    }

    public function pmbWavePath(PmbWavePath &$pmb_wave_path){
        // $jalur = config('jalur')[$slug] ?? abort(404);
        $other = PmbWavePath::query()
            ->where('pmb_wave_id',$pmb_wave_path->pmb_wave_id)
            ->except($pmb_wave_path)->get();
        // dd($other);

        return view('home.wave-path.show', [
            'title'=> 'Detail Jalur',
            'other'=>$other,
            'data'=> $pmb_wave_path,
        ]);
    }

    public function registration(PmbWavePath &$pmb_wave_path){
        return view('home.registration.create', [
            'title'=> 'Pendaftaran',
            'pmb_wave_path'=> $pmb_wave_path,
        ]);
    }

    public function registration_store(\App\Http\Requests\RegisterRequest &$request){
        $applicant = DB::transaction(function() use ($request){
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
            return $applicant->load('user');
        });
        Auth::login($applicant->user);
        return redirect()->route('my.application.show', $applicant);
    }

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
}
