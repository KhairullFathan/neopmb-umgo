<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['pmb_wave_path_id', 'pmb_registration_stage_id', 'sequence', 'is_required', 'execution_type', 'start_at', 'end_at', 'is_active'])]

class PmbWavePathStage extends Model
{
    protected function casts(){
        return [
            'is_required' => 'boolean',
            'is_active' => 'boolean',
            'start_at' => 'datetime',
            'end_at' => 'datetime',
        ];
    }

    public function pmbWavePath(){
        return $this->belongsTo(PmbWavePath::class, 'pmb_wave_path_id', 'id');
    }

    public function pmbRegistrationStage(){
        return $this->belongsTo(PmbRegistrationStage::class, 'pmb_registration_stage_id', 'id');
    }
}
