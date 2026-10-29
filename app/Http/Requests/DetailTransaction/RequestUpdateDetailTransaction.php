<?php

namespace App\Http\Requests\DetailTransaction;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class RequestUpdateDetailTransaction extends FormRequest
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
            'transaction_id'        => 'required|exists:transactions,id',
            'product_id'            => 'required|exists:products,id',
            'quantity'              => 'required|string|max:100',
            'unit_price'            => 'required|string|max:100',
            'subtotal'              => 'required|string|max:150'
        ];
    }

    public function attributes()
    {
        return [
            'transaction_id'        => 'Transaksi',
            'product_id'            => 'Product',
            'quantity'              => 'Quantity',
            'unit_price'            => 'Harga Satuan',
            'subtotal'              => 'Subtotal',
        ];
    }

    public function messages()
    {
        return [
            'transaction_id'        => ':attribute harus diisi',
            'product_id'            => ':attribute harus diisi',
            'quantity'              => ':attribute harus diisi',
            'unit_price'            => ':attribute harus diisi',
            'subtotal'              => ':attribute harus diisi',
        ];
    }
}
