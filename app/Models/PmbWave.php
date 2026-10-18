<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['pmb_period_id', 'code', 'name', 'registration_start', 'registration_end', 'is_active', 'is_show'])]

class PmbWave extends Model
{
    use SoftDeletes;

    protected function casts(){
        return [
            'is_active'=> 'boolean',
            'is_show'=> 'boolean',
            'registration_start'=> 'datetime',
            'registration_end'=> 'datetime',
        ];
    }

    public function pmbPeriod(){
        return $this->belongsTo(PmbPeriod::class, 'pmb_period_id', 'id');
    }

    public function pmbWavePath(){
        return $this->hasMany(PmbWavePath::class, 'pmb_wave_id', 'id');
    }
}
