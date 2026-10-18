<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['code', 'faculty_code', 'name', 'degree_level', 'accreditation', 'degree_title', 'description'])]

class StudyProgram extends Model
{
    protected $primaryKey = 'code';
    protected $keyType = 'string';
    public $incrementing = false;
    public function faculty(){
        return $this->belongsTo(Faculty::class, 'faculty_code', 'code');
    }
}
