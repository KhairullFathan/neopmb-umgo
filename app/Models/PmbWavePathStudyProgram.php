<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['pmb_wave_path_id', 'study_program_code'])]

class PmbWavePathStudyProgram extends Model
{
    public function pmbWavePath(){
        return $this->belongsTo(PmbWavePath::class, 'pmb_wave_path_id', 'id');
    }
    public function studyProgram(){
        return $this->belongsTo(StudyProgram::class, 'study_program_code', 'code');
    }

}
