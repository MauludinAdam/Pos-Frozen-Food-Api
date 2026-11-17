<?php

namespace App\Http\Requests\Product;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class RequestStoreProduct extends FormRequest
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
            'code'          => 'required|string|max:50',
            'category_id'   => 'required|exists:categories,id',
            'satuan_id'     => 'required|exists:satuans,id',
            'name'          => 'required|string|max:150',
            'price'         => 'required|string|max:100',
            'stock'         => 'required|string|max:100',
            'description'   => 'required|string|max:500',
            'image'         => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ];
    }

    public function attributes()
    {
        return [
            'code'          => 'Kode',
            'category_id'   => 'Kategori',
            'satuan_id'     => 'Satuan',
            'name'          => 'Nama',
            'price'         => 'Harga',
            'stock'         => 'Stock',
            'description'   => 'Deskripsi',
            'image'         => 'Gambar',
        ];
    }

    public function messages()
    {
        return [
            'code'                   => ':attribute harus diisi',
            'category_id.required'   => ':attribute harus diisi',
            'satuan_id.required'     => ':attribute harus diisi',
            'name.required'          => ':attribute harus diisi',
            'price.required'         => ':attribute harus diisi',
            'stock.required'         => ':attribute harus diisi',
            'description'            => ':attribute harus diisi',
            'image.required'         => ':attribute harus diisi',
            'image.image'            => ':attribute harus gambar',
            'image.mimes'            => ':attribute harus bertype jpg,png, jpeg',
        ];
    }
}
