<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class FacultyRequest extends FormRequest
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
        $code = $this->route('faculty')?->code ?? '';
        return [
            'code'=> 'required|string|unique:faculties,code,'.$code.',code',
            'name'=> 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'code.required'=> 'Kode fakultas tidak boleh kosong.',
            'code.unique'=> 'Kode fakultas telah terdaftar.',
            'name.required'=> 'Nama fakultas tidak boleh kosong.',
        ];
    }
}
