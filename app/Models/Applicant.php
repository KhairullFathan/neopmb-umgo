<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Attributes\Fillable;


#[Fillable(['user_id', 'name', 'identity_number', 'gender', 'religion', 'birth_place', 'birth_date', 'school_origin', 'school_type', 'school_major', 'phone','graduate_year', 'mother_name', 'parent_phone', 'nationality', 'country_of_origin', 'code_village', 'address'])]

class Applicant extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected function casts(){
        return [
            'birth_date'=> 'date',
        ];
    }

    protected array $uppercaseFields = [
        'name',
        'gender',
        'religion',
        'birth_place',
        'school_origin',
        'school_major',
        'mother_name',
        'nationality',
        'country_of_origin',
        'address',
    ];

    public function setAttribute($key, $value){
        if(in_array($key, $this->uppercaseFields) && is_string($value)){
            $value = strtoupper($value);
        }
        return parent::setAttribute($key, $value);
    }

    public static function religions() {
        return [
            'ISLAM'=>'ISLAM',
            'KRISTEN'=>'KRISTEN',
            'KATOLIK'=>'KATOLIK',
            'HINDU'=>'HINDU',
            'BUDDHA'=>'BUDDHA',
            'KONGHUCHU'=>'KONGHUCHU',
        ];
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function village(){
        return $this->belongsTo(Village::class, 'code_village', 'code');
    }

    public function applications(){
        return $this->hasMany(Application::class);
    }
}
