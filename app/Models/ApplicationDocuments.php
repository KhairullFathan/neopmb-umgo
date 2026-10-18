<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;


#[Fillable(['application_id', 'pmb_registration_document_id', 'file_path', 'original_filename', 'mime_type', 'file_size', 'verification_status', 'verified_by', 'verified_at', 'verification_notes'])]

class ApplicationDocuments extends Model
{

    protected function casts(): array {
        return [
            'file_size' => 'integer',
            'verified_at' => 'datetime',
        ];
    }
    public function application(){
        return $this->belongsTo(Application::class, 'application_id', 'id');
    }

    public function registrationDocument(){
        return $this->belongsTo(PmbRegistrationDocument::class,'pmb_registration_document_id', 'id');
    }

    public function verifier(){
        return $this->belongsTo(User::class,'verified_by','id');
    }

}
