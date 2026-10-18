<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class PmbRegistrationPathRequest extends FormRequest
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
        $id = $this->route('pmb_registration_path')?->id ?? '';
        return [
            'code'=> 'required|string|unique:pmb_registration_paths,code,'.$id,
            'name'=> 'required|string',
            'description'=> 'nullable',
        ];
    }

    public function messages(): array
    {
        return [
            'code.required'=> 'Kode jalur pendaftaran tidak boleh kosong.',
            'code.unique'=> 'Kode jalur pendaftaran telah terdaftar.',
            'name.required'=> 'Nama jalur pendaftaran tidak boleh kosong.',
        ];
    }
}
