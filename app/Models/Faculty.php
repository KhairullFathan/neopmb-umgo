<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['code', 'name'])]

class Faculty extends Model
{
    protected $primaryKey = 'code';
    protected $keyType = 'string';
    public $incrementing = false;
    public function studyPrograms(){
        return $this->hasMany(StudyProgram::class, 'faculty_code', 'code');
    }
}
