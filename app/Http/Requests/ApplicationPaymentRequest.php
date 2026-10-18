<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ApplicationPaymentRequest extends FormRequest
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
                'paid_at'=> 'required|date',
                'file' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            ];
        }
        if($this->isMethod('put')){
            $rules = [
                'paid_at'=> 'required|date',
                'sender_account_name'=> 'required|string',
                'sender_bank_name'=> 'required|string',
                'paid_amount'=> 'required|string',
                'verification_status'=> 'required|string',
                'verification_notes'=> 'nullable|string',
            ];
        }
        return $rules;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'paid_amount' => preg_replace(
                '/\D/',
                '',
                $this->paid_amount
            ),
        ]);
    }

    public function messages(): array
    {
        return [
            'paid_at.required' => 'Tanggal bayar tidak boleh kosong.',
            'file.required' => 'File wajib diunggah.',
            'file.file' => 'Input harus berupa file yang valid.',
            'file.mimes' => 'Format file tidak diizinkan.',
            'file.max' => 'Ukuran file maksimal 2 MB.',
            'sender_account_name.required'=> 'Nama rekening pengirim tidak boleh kosong.',
            'sender_bank_name.required'=> 'Nama bank pengirim tidak boleh kosong.',
            'paid_amount.required'=> 'Nominal transfer tidak boleh kosong.',
            'verification_status.required'=> 'Status verifikasi tidak boleh kosong.',
        ];
    }
}
