<?php

namespace App\Http\Requests\Transaction;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class RequestStoreTransaction extends FormRequest
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
            'user_id'           => 'required|exists:users,id',
            'subtotal'          => 'required|string|max:100',
            'discount_amount'   => 'nullable|string|max:100',
            'total_amount'      => 'required|string|max:100',
            'paid_amount'       => 'required|string|max:100',
            'change_amount'     => 'required|string|max:100',
            'status_payment'    => 'required|string|max:100',
            'notes'             => 'nullable|string|max:500',

            'items'             => 'required|array|min:1',
            'items.*.product_id'    => 'required|integer|exists:products,id',
            'items.*.quantity'  => 'required|numeric|gt:0',
        ];
    }

    public function attributes()
    {
        return [
            'user_id'           => 'User',
            'subtotal'          => 'Subtotal',
            'discount_amount'   => 'Diskon',
            'total_amount'      => 'Jumlah Total',
            'paid_amount'       => 'Jumlah Pembayaran',
            'change_amount'     => 'Jumlah Kembalian',
            'status_payment'    => 'Status Pembayaran',
            'notes'             => 'Catatan Transaction',

            'items'             => 'Daftar Product',
            'items.*.product_id'    => 'Product',
            'items.*.quantity'  => 'Jumlah Product',
        ];
    }

    public function messages()
    {
        return [
            'user_id'           => ':attribute harus diisi',
            'subtotal'          => ':attribute harus diisi',
            'discount_amount'   => ':attribute harus diisi',
            'total_amount'      => ':attribute harus diisi',
            'paid_amount'       => ':attribute harus diisi',
            'change_amount'     => ':attribute harus diisi',
            'status_payment'    => ':attribute harus diisi',
            'notes'             => ':attribute boleh kosong'
        ];
    }
}
