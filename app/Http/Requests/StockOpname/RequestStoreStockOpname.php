<?php

namespace App\Http\Requests\StockOpname;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class RequestStoreStockOpname extends FormRequest
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
            'date'          => 'required|date',
            'product_id'          => 'required|exists:product,id',
            'stock_system'      => 'required|string|max:100',
            'stock_physical'    => 'required|string|max:100',
            'difference'        => 'required|string|max:100',
            'user_id'           => 'required|exists:users,id',
            'notes'             => 'required|string|max:500',
        ];
    }

    public function attributes()
    {
        return [
            'date'              => 'Tanggal',
            'product_id'        => 'Nama Product',
            'stock_system'      => 'Stok Sistem',
            'stock_physical'    => 'STok Fisik',
            'difference'        => 'Selisih',
            'user_id'           => 'Nama Petugas',
            'notes'             => 'Catatan'
        ];
    }

    public function messages()
    {
        return [
            'date'              => ':attribute harus diisi',
            'product_id'        => ':attribute harus diisi',
            'stock_system'      => ':attribute harus diisi',
            'stock_physical'    => ':attribute harus diisi',
            'difference'        => ':attribute harus diisi',
            'user_id'           => ':attribute harus diisi',
            'notes'             => ':attribute harus diisi',
        ];
    }
}
