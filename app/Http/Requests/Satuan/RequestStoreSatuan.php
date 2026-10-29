<?php

namespace App\Http\Requests\Satuan;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class RequestStoreSatuan extends FormRequest
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
            'symbol'        => 'required|string|max:50',
            'description'   => 'required|string|max:500',
        ];
    }

    public function attributes()
    {
        return [
            'name'          => 'Nama',
            'symbol'        => 'Symbol',
            'description'   => 'Deskripsi'
        ];
    }

    public function messages()
    {
        return [
            'name.required'     => ':attribute harus diisi',
            'symbol.required'   => ':attribute harus diisi',
            'description'       => ':attribute harus diisi',
        ];
    }
}
