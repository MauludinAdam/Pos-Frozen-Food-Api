<?php

namespace App\Http\Requests\Supplier;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class RequestUpdateSupplier extends FormRequest
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
        return [
            'name'          => 'required|string|max:150',
            'phone'         => 'required|string|max:20',
            'email'         => 'required|string|email|max:30',
            'is_active'     => 'sometimes|boolean',
            'address'       => 'nullable|string|max:500',
            'notes'         => 'nullable|string|max:500',
        ];
    }

    public function attributes()
    {
        return [
            'name'          => 'Nama',
            'phone'         => 'No.Telp',
            'email'         => 'Email',
            'is_active'     => 'Is_Active',
            
        ];
    }

    public function messages()
    {
        return [
            'name'          => ':attribute harus diisi',
            'phone'         => ':attribute harus diisi',
            'email'         => ':attribute harus diisi',
            'is_active'     => ':attribute harus diisi',
            
        ];
    }
}
