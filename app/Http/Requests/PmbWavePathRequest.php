<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class PmbWavePathRequest extends FormRequest
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
        $id = $this->route('pmb_wave_path')?->id ?? '';
        return [
            'pmb_wave_id'=> 'required|exists:pmb_waves,id',
            'pmb_registration_path_id'=> 'required|exists:pmb_registration_paths,id',
            'registration_fee'=> 'required|string',
            'is_active'=> 'nullable',
            'study_program_code'=> 'nullable|array',
            'study_program_code.*'=> 'string|exists:study_programs,code',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'registration_fee' => preg_replace(
                '/\D/',
                '',
                $this->registration_fee
            ),
        ]);
    }

    public function messages(): array
    {
        return [
            'pmb_wave_id.required'=> 'Gelombang pendaftaran tidak boleh kosong.',
            'pmb_wave_id.exists'=> 'Gelombang pendaftaran tidak valid.',
            'pmb_registration_path_id.required'=> 'Jalur pendaftaran tidak boleh kosong.',
            'pmb_registration_path_id.exists'=> 'Jalur pendaftaran tidak valid.',
            'registration_fee.required'=> 'Biaya registrasi tidak boleh kosong.',
            'study_program_code.exists'=> 'Program studi dipilih tidak ditemukan.',
        ];
    }
}
