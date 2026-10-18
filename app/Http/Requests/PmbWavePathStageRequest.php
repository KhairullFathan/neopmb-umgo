<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class PmbWavePathStageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     *
     */
    public function rules(): array
    {
        // dd($this->all());
        return [
            'pmb_wave_path_id'=> 'required|exists:pmb_wave_paths,id',
            'pmb_registration_stage_id'=> 'required|exists:pmb_registration_stages,id',
        ];
    }

    public function messages(): array
    {
        return [
            'pmb_wave_path_id.required'=> 'Jalur gelombang pendaftaran tidak boleh kosong.',
            'pmb_wave_path_id.exists'=> 'Jalur gelombang pendaftaran tidak valid.',
            'pmb_registration_stage_id.required'=> 'Tahapan pendaftaran pendaftaran tidak boleh kosong.',
            'pmb_registration_stage_id.exists'=> 'Tahapan pendaftaran pendaftaran tidak valid.',
        ];
    }
}
