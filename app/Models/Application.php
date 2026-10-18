<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Casts\Attribute;


#[Fillable(['applicant_id', 'pmb_period_id', 'pmb_wave_id', 'pmb_registration_path_id', 'registration_number', 'status', 'current_stage'])]

class Application extends Model
{
    public function applicant(){
        return $this->belongsTo(Applicant::class, 'applicant_id', 'id');
    }

    public function pmbPeriod(){
        return $this->belongsTo(PmbPeriod::class, 'pmb_period_id','id');
    }

    public function pmbWave(){
        return $this->belongsTo(PmbWave::class, 'pmb_wave_id', 'id');
    }

    public function pmbRegistrationPath(){
        return $this->belongsTo(PmbRegistrationPath::class, 'pmb_registration_path_id', 'id');
    }

    public function pmbWavePath(){
        return \App\Models\PmbWavePath::where('pmb_wave_id', $this->pmb_wave_id)
            ->where('pmb_registration_path_id', $this->pmb_registration_path_id);
    }

    public function documents(){
        return $this->hasMany(ApplicationDocuments::class, 'application_id', 'id');
    }

    public function payments(){
        return $this->hasMany(ApplicationPayment::class, 'application_id', 'id');
    }

    public function graduate(){
        return $this->hasOne(ApplicationGraduate::class, 'application_id', 'id');
    }

    public function studyPrograms(){
        return $this->hasMany(ApplicationStudyProgram::class, 'application_id','id')->orderBy('choice_number','ASC');
    }

    protected function statusBadge(): Attribute
    {
        return Attribute::get(function () {
            return match ($this->status) {
                'in_progress' => [
                    'label' => 'Dalam Proses',
                    'class' => 'bg-[#dbeafe] text-[#1e40af]',
                ],
                'passed' => [
                    'label' => 'Lulus',
                    'class' => 'bg-[#dcfce7] text-[#166534]',
                ],
                'not_passed' => [
                    'label' => 'Tidak Lulus',
                    'class' => 'bg-[#fddfdf] text-[#991b1b]',
                ],
                'withdrawn' => [
                    'label' => 'Mengundurkan Diri',
                    'class' => 'bg-[#f3f4f6] text-[#374151]',
                ],
                default => [
                    'label' => 'Belum Diproses',
                    'class' => 'bg-[#f3f4f6] text-[#374151]',
                ],
            };
        });
    }

    protected function currentStageBadge(): Attribute
    {
        return Attribute::get(function () {
            $code = $this->current_stage;

            $stages = [
                'PENDAFTARAN' => [
                    'label' => 'Pendaftaran',
                    'class' => 'bg-[#fddfdf] text-[#991b1b]',
                ],
                'PEMBAYARAN_FORMULIR' => [
                    'label' => 'Pembayaran Formulir',
                    'class' => 'bg-[#fef3c7] text-[#92400e]',
                ],
                'LENGKAPI_DATA_BERKAS' => [
                    'label' => 'Lengkapi Data & Berkas',
                    'class' => 'bg-[#dbeafe] text-[#1e40af]',
                ],
                'SELEKSI_BERKAS' => [
                    'label' => 'Seleksi Berkas',
                    'class' => 'bg-[#e0e7ff] text-[#3730a3]',
                ],
                'UJIAN_ONLINE' => [
                    'label' => 'Ujian Online',
                    'class' => 'bg-[#f3e8ff] text-[#6b21a8]',
                ],
                'TES_OFFLINE' => [
                    'label' => 'Tes Offline',
                    'class' => 'bg-[#ede9fe] text-[#5b21b6]',
                ],
                'WAWANCARA' => [
                    'label' => 'Wawancara',
                    'class' => 'bg-[#cffafe] text-[#155e75]',
                ],
                'PSIKOTES' => [
                    'label' => 'Psikotes',
                    'class' => 'bg-[#fce7f3] text-[#9d174d]',
                ],
                'PENGUMUMAN_SELEKSI_BERKAS' => [
                    'label' => 'Pengumuman Seleksi Berkas',
                    'class' => 'bg-[#ffedd5] text-[#9a3412]',
                ],
                'PENGUMUMAN_KELULUSAN' => [
                    'label' => 'Pengumuman Kelulusan',
                    'class' => 'bg-[#dcfce7] text-[#166534]',
                ],
                'REGISTRASI_ULANG' => [
                    'label' => 'Registrasi Ulang',
                    'class' => 'bg-[#ccfbf1] text-[#115e59]',
                ],
            ];

            return $stages[$code] ?? [
                'label' => $code ?: 'Belum Diproses',
                'class' => 'bg-[#f3f4f6] text-[#374151]',
            ];
        });
    }
}
