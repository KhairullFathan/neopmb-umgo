<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class PmbRegistrationDocumentRequest extends FormRequest
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
        $id = $this->route('pmb_registration_document')?->id ?? '';
        return [
            'pmb_registration_path_id'=> 'required|exists:pmb_registration_paths,id',
            'name'=> 'required|string',
            'description'=> 'nullable',
            'allowed_mimes'=>'required|array',
        ];
    }

    public function messages(): array
    {
        return [
            'pmb_registration_path_id.required'=> 'Jalur pendaftaran tidak boleh kosong.',
            'pmb_registration_path_id.exists'=> 'Jalur pendaftaran tidak valid.',
            'name.required'=> 'Nama berkas pendaftaran tidak boleh kosong.',
            'allowed_mimes.required'=> 'Tipe berkas pendaftaran tidak boleh kosong.',
        ];
    }
}
