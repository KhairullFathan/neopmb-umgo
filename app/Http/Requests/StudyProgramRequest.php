<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StudyProgramRequest extends FormRequest
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
     */
    public function rules(): array
    {
        $code = $this->route('study_program')?->code ?? '';
        return [
            'code'=> 'required|string|unique:study_programs,code,'.$code.',code',
            'faculty_code'=> 'required|string|exists:faculties,code',
            'name'=> 'required|string',
            'degree_level'=> 'required|string|in:DI,DII,DIII,DIV,S1,S2,S3,S4,PROFESI',
            'accreditation'=> 'nullable|string',
            'degree_title'=> 'nullable|string',
            'description'=> 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'code.required' => 'Kode program studi tidak boleh kosong.',
            'code.unique' => 'Kode program studi telah terdaftar.',
            'faculty_code.required' => 'Fakultas wajib dipilih.',
            'faculty_code.exists' => 'Fakultas yang dipilih tidak ditemukan.',
            'name.required' => 'Nama program studi tidak boleh kosong.',
            'degree_level.required' => 'Jenjang pendidikan tidak boleh kosong.',
            'degree_level.in' => 'Jenjang pendidikan tidak valid.',
        ];
    }
}
