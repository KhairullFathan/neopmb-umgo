<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['code', 'name', 'description'])]

class PmbRegistrationPath extends Model
{
    use SoftDeletes;

    public function pmbRegistrationDocument(){
        return $this->hasMany(PmbRegistrationDocument::class, 'pmb_registration_path_id','id');
    }
}
