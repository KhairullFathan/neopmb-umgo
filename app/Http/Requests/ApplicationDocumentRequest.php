<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ApplicationDocumentRequest extends FormRequest
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
        $rules = [];
        if($this->isMethod('post')){
            $rules = [
                'pmb_registration_document_id' => 'required|integer|exists:pmb_registration_documents,id',
                'file' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            ];
        }
        if($this->isMethod('put')){
            $rules = [
                'verification_status'=> 'required|string',
                'verification_notes'=> 'nullable|string',
            ];
        }
        return $rules;
    }

    public function messages(): array
    {
        return [
            'pmb_registration_document_id.required' => 'Jenis berkas tidak boleh kosong.',
            'pmb_registration_document_id.exists' => 'Jenis berkas tidak terdaftar di sisitem.',
            'file.required' => 'File wajib diunggah.',
            'file.file' => 'Input harus berupa file yang valid.',
            'file.mimes' => 'Format file tidak diizinkan.',
            'file.max' => 'Ukuran file maksimal 2 MB.',
        ];
    }
}
