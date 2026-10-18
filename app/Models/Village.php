<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['code', 'code_district', 'name'])]

class Village extends Model
{
    public function district(){
        return $this->belongsTo(District::class, 'code_district', 'code');
    }
}
