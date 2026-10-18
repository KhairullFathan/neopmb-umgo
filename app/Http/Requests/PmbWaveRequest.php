<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class PmbWaveRequest extends FormRequest
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
        $id = $this->route('pmb_wave')?->id ?? '';
        return [
            'pmb_period_id'=> 'required|exists:pmb_periods,id',
            'code'=> 'required|string',
            'name'=> 'required|string',
            'registration_start'=> 'required|date|',
            'registration_end'=> 'required|date|after:registration_start',
            'is_active'=> 'nullable',
            'is_show'=> 'nullable',
        ];
    }

    public function messages(): array
    {
        return [
            'pmb_period_id.required'=> 'Periode pendaftaran tidak boleh kosong.',
            'pmb_period_id.exists'=> 'Periode pendaftaran tidak valid.',
            'code.required'=> 'Kode gelombang pendaftaran tidak boleh kosong.',
            'name.required'=> 'Nama gelombang pendaftaran tidak boleh kosong.',
            'registration_start.required'=> 'Tanggal mulai pendaftaran tidak boleh kosong.',
            'registration_end.required'=> 'Tanggal selesai pendaftaran tidak boleh kosong.',
            'registration_end.after'=> 'Tanggal selesai pendaftaran harus lebih besar daripada tanggal mulai pendaftaran.',
        ];
    }
}
