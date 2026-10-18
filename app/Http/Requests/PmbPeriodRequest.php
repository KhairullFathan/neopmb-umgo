<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class PmbPeriodRequest extends FormRequest
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
        $id = $this->route('pmb_period')?->id ?? '';
        return [
            'id'=> 'required|string|unique:pmb_periods,id,'.$id,
            'name'=> 'required|string',
            'is_active'=> 'nullable',
        ];
    }

    public function messages(): array
    {
        return [
            'id.required'=> 'Id periode tidak boleh kosong.',
            'id.unique'=> 'Id periode telah terdaftar.',
            'name.required'=> 'Nama periode tidak boleh kosong.',
        ];
    }
}
