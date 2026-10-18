<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['application_id', 'status', 'score', 'started_at', 'finished_at', 'finish_reason'])]

class ExamSession extends Model
{
    public function application(){
        return $this->belongsTo(Application::class, 'application_id', 'id');
    }
}
