<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
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
            'name'=> 'required|string',
            'identity_number'=> 'required|string|unique:users,username',
            'phone'=> 'required|string|min:10|max:15',
            'school_origin'=> 'required|string',
            'school_type'=> 'required|string',
            'school_major'=> 'required|string',
            'study_programs'=> 'required|array|min:1|max:2',
            'study_programs.*'=> 'nullable|string|distinct',
            'setuju'=> 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'pmb_wave_path_id.required'=> 'Jalur pendaftaran tidak boleh kosong.',
            'pmb_wave_path_id.exists'=> 'Jalur pendaftaran tidak valid.',
            'name.required'=> 'Nama pendaftaran tidak boleh kosong.',
            'identity_number.required'=> 'Nomor identitas tidak boleh kosong.',
            'identity_number.unique'=> 'Nomor identitas telah terdaftar.',
            'phone.required'=> 'Nomor telephone tidak boleh kosong.',
            'school_origin.required'=> 'Asal sekolah/universitas tidak boleh kosong.',
            'school_type.required'=> 'Jenis sekolah/universitas tidak boleh kosong.',
            'school_major.required'=> 'Jurusan sekolah/universitas tidak boleh kosong.',
            'study_programs.required'=> 'Pilihan program studi tidak boleh kosong.',
            'setuju.required'=> 'Harus disetujui.',
        ];
    }
}
