<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['user_id', 'study_program_code'])]

class UserStudyProgram extends Model
{
    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function studyProgram(){
        return $this->belongsTo(StudyProgram::class, 'study_program_code', 'code');
    }
}
