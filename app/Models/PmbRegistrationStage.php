<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['code', 'name', 'description', 'is_active'])]

class PmbRegistrationStage extends Model
{
    protected function casts(){
        return [
            'is_active'=>'boolean',
        ];
    }
}
