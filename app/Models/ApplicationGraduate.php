<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;


#[Fillable(['application_id', 'study_program_code', 'notes', 'decided_by', 'decided_at'])]

class ApplicationGraduate extends Model
{
    public function application(){
        return $this->belongsTo(Application::class, 'application_id', 'id');
    }

    public function studyProgram() {
        return $this->belongsTo(StudyProgram::class,'study_program_code','code');
    }

    public function decidedBy() {
        return $this->belongsTo(User::class, 'decided_by');
    }
}
