<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['question_id', 'option', 'media_path'])]

class QuestionOption extends Model
{
    public function question(){
        return $this->belongsTo(Question::class, 'question_id', 'id');
    }

    public function questionAnswer(){
        return $this->hasOne(QuestionAnswer::class, 'question_option_id', 'id');
    }
}
