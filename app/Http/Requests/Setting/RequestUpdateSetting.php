<?php

namespace App\Http\Requests\Setting;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class RequestUpdateSetting extends FormRequest
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
            'email'         => 'required|string|email|max:100',
            'addrees'       => 'required|string|max:500',
            'logo'          => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ];
    }

    public function attributes()
    {
        return [
            'name'          => 'Nama',
            'phone'         => 'No.Telp',
            'email'         => 'Email',
            'address'       => 'Alamat',
            'logo'          => 'Logo',
        ];
    }

    public function messages()
    {
        return [
            'name.required'          => ':attribute harus diisi',
            'phone.required'         => ':attribute harus diisi',
            'email.required'         => ':attribute harus diisi',
            'email.email'         => ':attribute harus email yang valid',
            'address.required'       => ':attribute harus diisi',
            'logo.image'            => ':attribute harus berupa gambar',
            'logo.mimes'            => ':attribute harus berupa PNG, JPG, JPEG',
        ];
    }
}
