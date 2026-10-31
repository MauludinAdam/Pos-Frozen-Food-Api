<?php

namespace App\Http\Requests\User;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class RequestUpdateUser extends FormRequest
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
            'role_id'   => 'required|exists:roles,id',
            'name'      => 'sometimes|string|max:250',
            'email'     => 'sometimes|email|max:255',

            'current_password'  => 'nullable|required_with:password|current_password',
            'password'          => 'nullable|min:8|confirmed',
        ];
    }

    public function attributes()
    {
        return [
            'role_id'   => 'Role',
            'name'      => 'Nama',
            'email'     => 'Email',
            'current_password' => 'Password lama',
            'password' => 'Password baru',
            'password_confirmation' => 'Konfirmasi password',
        ];
    }

    public function messages()
    {
        return [
            'role_id.required'     => ':attribute harus diisi',
            'name.required'     => ':attribute harus diisi',
            'email.required'    => ':attribute harus diisi',
            'email.email'       => ':attribute harus berupa email yang valid',

            'current_password.required_with'    => ':attribute harus diisi ketika ingin menggati password',
            'password.min'                      => ':attribute minimal 8 karakter',
            'password.confirmed'                => ':attribute dan konfimasi password harus sama',
        ];
    }
}
