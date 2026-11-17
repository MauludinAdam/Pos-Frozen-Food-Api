<?php

namespace App\Http\Requests\User;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class RequestStoreUser extends FormRequest
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
            'role_id'           => 'required|exists:users,id',
            'name'              => 'required|string|max:100',
            'email'             => 'required|string|email|max:100',
            'current_password'  => 'nullable|required_with:password|current_password',
            'password'          => 'nullable|min:8|confirmed',
        ];
    }

    public function attributes()
    {
        return [
            'role_id'               => 'Role',
            'name'                  => 'Nama',
            'email'                 => 'Email',
            'current_password'      => 'Password Lama',
            'password'              => 'Password Baru',
            'password_confirmation' => 'Konfirmasi Password'
        ];
    }

    public function messages()
    {
        return [
            'role_id.required'                => ':attribute harus diisi',
            'name.required'                   => ':attribute harus diisi',
            'email.required'                  => ':attribute harus diisi',
            'email.email'                     => ':attribute harus berupa email yang valid',
            'current_password.required_with'  => ':attribute harus diisi ketika ingin menggantikan password',
            'password.min'                    => ':attribute minimal 8 karakter',
            'password.confirmation'           => ':attribute dan konfirmasi password harus sama',
        ];
    } 
}
