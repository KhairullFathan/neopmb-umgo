<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['code', 'name'])]

class Province extends Model
{
    public function regencies(){
        return $this->hasMany(Regency::class, 'code_province', 'code');
    }
}
