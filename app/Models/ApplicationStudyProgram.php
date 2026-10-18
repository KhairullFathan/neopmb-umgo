<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;


#[Fillable(['application_id', 'study_program_code', 'choice_number'])]

class ApplicationStudyProgram extends Model
{
    protected function casts(): array {
        return [
            'choice_number' => 'integer',
        ];
    }

    public function application(){
        return $this->belongsTo(Application::class, 'application_id', 'id');
    }

    public function studyProgram(){
        return $this->belongsTo(StudyProgram::class, 'study_program_code', 'code');
    }
}
