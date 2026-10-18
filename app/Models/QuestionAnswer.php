<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['question_id', 'question_option_id', 'media_path'])]
class QuestionAnswer extends Model
{
    public function question(){
        return $this->belongsTo(Question::class, 'question_id', 'id');
    }

    public function questionOption(){
        return $this->belongsTo(QuestionOption::class, 'question_option_id', 'id');
    }
}
