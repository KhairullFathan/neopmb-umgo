<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ApplicantRequest extends FormRequest
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
        $id = $this->route('applicant')?->id ?? '';
        return [
            'name' => 'nullable|string|max:50',
            'identity_number' => 'nullable|string|max:20|unique:applicants,identity_number,'.$id.',id',
            'religion' => 'nullable|string|max:50',
            'gender' => 'nullable|in:MALE,FEMALE',
            'birth_place' => 'nullable|string|max:50',
            'birth_date' => 'nullable|date|before_or_equal:today',
            'school_origin' => 'nullable|string|max:150',
            'school_type' => 'nullable|string|max:20',
            'school_major' => 'nullable|string|max:150',
            'phone' => 'nullable|string|max:15',
            'mother_name' => 'nullable|string|max:50',
            'parent_phone' => 'nullable|string|max:15',
            'nationality' => 'nullable|string|max:15',
            'country_of_origin' => 'nullable|string|max:50',
            'code_village' => 'nullable|string|exists:villages,code',
            'address' => 'nullable|string|max:255',
        ];
    }
    public function messages(): array
    {
        return [
            'name.string' => 'Nama lengkap harus berupa teks.',
            'name.max' => 'Nama lengkap maksimal 50 karakter.',
            'identity_number.string' => 'Nomor identitas harus berupa teks.',
            'identity_number.max' => 'Nomor identitas maksimal 20 karakter.',
            'identity_number.unique' => 'Nomor identitas telah terdaftar.',
            'religion.string' => 'Agama harus berupa teks.',
            'religion.max' => 'Agama maksimal 50 karakter.',
            'birth_place.string' => 'Tempat lahir harus berupa teks.',
            'birth_place.max' => 'Tempat lahir maksimal 50 karakter.',
            'birth_date.date' => 'Format tanggal lahir tidak valid.',
            'birth_date.before_or_equal' => 'Tanggal lahir tidak boleh melebihi hari ini.',
            'school_origin.string' => 'Asal sekolah harus berupa teks.',
            'school_origin.max' => 'Asal sekolah maksimal 150 karakter.',
            'school_type.string' => 'Jenis sekolah harus berupa teks.',
            'school_type.max' => 'Jenis sekolah maksimal 20 karakter.',
            'school_major.string' => 'Jurusan sekolah harus berupa teks.',
            'school_major.max' => 'Jurusan sekolah maksimal 150 karakter.',
            'phone.string' => 'Nomor handphone harus berupa teks.',
            'phone.max' => 'Nomor handphone maksimal 15 karakter.',
            'mother_name.string' => 'Nama ibu harus berupa teks.',
            'mother_name.max' => 'Nama ibu maksimal 50 karakter.',
            'parent_phone.string' => 'Nomor handphone orang tua harus berupa teks.',
            'parent_phone.max' => 'Nomor handphone orang tua maksimal 15 karakter.',
            'nationality.string' => 'Kewarganegaraan harus berupa teks.',
            'nationality.max' => 'Kewarganegaraan maksimal 15 karakter.',
            'country_of_origin.string' => 'Negara asal harus berupa teks.',
            'country_of_origin.max' => 'Negara asal maksimal 50 karakter.',
            'code_village.exists' => 'Desa/kelurahan yang dipilih tidak valid.',
            'address.string' => 'Alamat harus berupa teks.',
            'address.max' => 'Alamat maksimal 255 karakter.',
        ];
    }
}
