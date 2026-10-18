<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['description', 'question', 'media_path'])]

class Question extends Model
{
    public function questionOptions(){
        return $this->hasMany(QuestionOption::class, 'question_id', 'id');
    }

    public function questionAnswer(){
        return $this->hasOne(QuestionAnswer::class, 'question_id', 'id');
    }
}
