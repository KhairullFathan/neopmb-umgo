<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['id', 'name', 'is_active'])]

class PmbPeriod extends Model
{
    use SoftDeletes;
    protected function casts(){
        return [
            'is_active'=> 'boolean'
        ];
    }

    public function pmbWave(){
        return $this->hasMany(PmbWave::class, 'pmb_period_id', 'id');
    }
}
