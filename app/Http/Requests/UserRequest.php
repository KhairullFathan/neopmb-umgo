<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
        $id = $this->route('user')?->id ?? '';
        $rules = [
            'role'=> 'required|exists:roles,name',
            'username'=> 'required|string|unique:users,username,'.$id,
            'email'=> 'required|string|unique:users,email,'.$id,
            'phone'=> 'nullable|string|min:10|max:15',
            'study_program_code'=> 'nullable|array',
            'study_program_code.*'=> 'string|exists:study_programs,code',
        ];
        if($this->isMethod('post')){
            $rules['password'] = 'required|string|min:8';
            $rules['cpassword'] = 'required|same:password';
        }
        if($this->isMethod('put')){
            $rules['password'] = 'nullable|string|min:8';
            $rules['cpassword'] = 'nullable|same:password';
        }
        return $rules;
    }

    public function messages(): array
    {
        return [
            'role.required'=> 'Peran tidak boleh kosong.',
            'role.exists'=> 'Peran yang dipilih tidak ditemukan.',
            'username.required'=> 'Username tidak boleh kosong.',
            'username.unique'=> 'Username telah terdaftar.',
            'email.required'=> 'Email tidak boleh kosong.',
            'email.unique'=> 'Email telah terdaftar.',
            'password.required'=> 'Password tidak boleh kosong.',
            'password.min'=> 'Password minimal 8 karakter.',
            'cpassword.required'=> 'Konfirmasi password tidak boleh kosong.',
            'cpassword.same'=> 'Konfirmasi password berbeda.',
            'study_program_code.exists'=> 'Program studi dipilih tidak ditemukan.',
        ];
    }
}
