<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['code', 'code_regency', 'name'])]

class District extends Model
{
    public function regency(){
        return $this->belongsTo(Regency::class, 'code_regency', 'code');
    }

    public function villages(){
        return $this->hasMany(Village::class, 'code_district', 'code');
    }
}
