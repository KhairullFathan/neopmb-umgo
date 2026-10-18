<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['code', 'code_province', 'name'])]
class Regency extends Model
{
    public function province(){
        return $this->belongsTo(Province::class, 'code_province', 'code');
    }

    public function districts(){
        return $this->hasMany(District::class, 'code_regency', 'code');
    }
}
