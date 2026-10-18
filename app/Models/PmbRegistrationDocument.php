<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;

#[Fillable(['pmb_registration_path_id', 'name', 'description', 'is_required', 'allowed_mimes', 'is_active'])]

class PmbRegistrationDocument extends Model
{
    protected function casts(): array {
        return [
            'is_required' => 'boolean',
            'allowed_mimes' => 'array',
            'is_active' => 'boolean',
        ];
    }

    protected function allowedMimesLabel(): Attribute
    {
        return Attribute::get(function () {
            $mimes = $this->allowed_mimes ?? [];

            if (is_string($mimes)) {
                $mimes = json_decode($mimes, true) ?? [];
            }

            $extensions = [
                'image/jpg' => 'JPG',
                'image/jpeg' => 'JPEG',
                'image/png' => 'PNG',
                'image/gif' => 'GIF',
                'image/webp' => 'WEBP',
                'image/bmp' => 'BMP',
                'image/svg+xml' => 'SVG',
                'image/tiff' => 'TIFF',
                'image/avif' => 'AVIF',
                'image/heic' => 'HEIC',
                'image/heif' => 'HEIF',
                'image/x-icon' => 'ICO',
                'application/pdf' => 'PDF',
            ];

            return collect($mimes)
                ->map(fn ($mime) => $extensions[$mime] ?? $mime)
                ->implode(', ');
        });
    }

    public static function generateAllowedMimes(array $allowedMimes){
        $mimeTypes = [];
        $mimeMap = [
            'image' => [
                'image/jpeg',
                'image/png',
                'image/jpg',
            ],
            'pdf' => [
                'application/pdf',
            ],
        ];
        foreach ($allowedMimes as $type => $enabled) {
            if ($enabled && isset($mimeMap[$type])) {
                $mimeTypes = array_merge($mimeTypes, $mimeMap[$type]);
            }
        }
        return array_values(array_unique($mimeTypes));
    }

    public function pmbRegistrationPath(){
        return $this->belongsTo(PmbRegistrationPath::class, 'pmb_registration_path_id', 'id');
    }

    public function applicationDocuments(){
        return $this->hasMany(ApplicationDocuments::class, 'pmb_registration_document_id', 'id');
    }
}
