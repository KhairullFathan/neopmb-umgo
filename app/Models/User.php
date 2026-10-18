<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;

#[Fillable(['username', 'email', 'phone', 'password', 'is_active', 'last_login_at'])]
#[Hidden(['password'])]

class User extends Authenticatable
{
    use HasRoles;
    use SoftDeletes;

    protected function casts(): array {
        return [
            'is_active'=> 'boolean',
            'password'=> 'hashed',
            'last_login_at'=> 'datetime',
        ];
    }

    public function studyPrograms(){
        return $this->belongsToMany(StudyProgram::class, 'user_study_programs', 'user_id', 'study_program_code', 'id', 'code');
    }
    public function applicant(){
        return $this->hasOne(Applicant::class, 'user_id', 'id');
    }
}
