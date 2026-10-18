<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use RuntimeException;

#[Fillable(['pmb_wave_id', 'pmb_registration_path_id', 'registration_fee', 'is_active'])]

class PmbWavePath extends Model
{
    use SoftDeletes;

    protected function casts(){
        return [
            'is_active'=> 'boolean',
        ];
    }

    public function pmbWave(){
        return $this->belongsTo(PmbWave::class, 'pmb_wave_id', 'id');
    }

    public function pmbRegistrationPath(){
        return $this->belongsTo(PmbRegistrationPath::class, 'pmb_registration_path_id', 'id');
    }

    public function studyPrograms(){
        return $this->belongsToMany(StudyProgram::class, 'pmb_wave_path_study_programs', 'pmb_wave_path_id', 'study_program_code', 'id', 'code');
    }

    public function stages(){
        return $this->hasMany(PmbWavePathStage::class, 'pmb_wave_path_id', 'id')->orderBy('sequence','ASC');
    }

    public function getRegistrationFeeFormattedAttribute(){
        return 'Rp. '.number_format($this->registration_fee,0,',','.');
    }

    // generate stage
    public function generateAllStages(): int
    {
        $stageCodes = [
            'PENDAFTARAN',
            'PEMBAYARAN_FORMULIR',
            'LENGKAPI_DATA_BERKAS',
            'SELEKSI_BERKAS',
            'PENGUMUMAN_SELEKSI_BERKAS',
            'UJIAN_ONLINE',
            'TES_OFFLINE',
            'WAWANCARA',
            'PSIKOTES',
            'PENGUMUMAN_KELULUSAN',
            'REGISTRASI_ULANG',
        ];

        return DB::transaction(function () use ($stageCodes) {
            // Cegah generate ulang jika tahapan sudah tersedia.
            if ($this->stages()->exists()) {
                throw new RuntimeException(
                    'Tahapan PMB sudah tersedia untuk konfigurasi ini.'
                );
            }

            $stages = \App\Models\PmbRegistrationStage::query()
                ->whereIn('code', $stageCodes)
                ->where('is_active', true)
                ->get()
                ->keyBy('code');

            $missingCodes = array_diff(
                $stageCodes,
                $stages->keys()->all()
            );

            if (!empty($missingCodes)) {
                throw new RuntimeException(
                    'Master tahapan belum lengkap: ' .
                    implode(', ', $missingCodes)
                );
            }

            foreach ($stageCodes as $index => $code) {
                $this->stages()->create([
                    'pmb_registration_stage_id' => $stages[$code]->id,
                    'sequence' => $index + 1,
                    'is_required' => true,
                    'execution_type' => 'flexible',
                    'is_active' => true,
                ]);
            }

            return count($stageCodes);
        });
    }
}
