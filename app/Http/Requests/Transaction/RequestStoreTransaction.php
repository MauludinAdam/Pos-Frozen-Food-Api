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
            'subtotal'          => 'required|numeric|min:0',
            'discount_amount'   => 'nullable|numeric|min:0',
            'total_amount'      => 'required|numeric|min:0',
            'paid_amount'       => 'required|numeric|min:0',
            'change_amount'     => 'required|numeric|min:0',
            'status_payment'    => 'required|in:paid',

            'payment_method'    => 'required|in:cash,transfer,qris',

            // 'notes'             => 'nullable|string|max:500',

            'items'             => 'required|array|min:1',
            'items.*.product_id'    => 'required|integer|exists:products,id',
            'items.*.quantity'  => 'required|numeric|gt:0',
        ];
    }

    public function attributes()
    {
        return [
            'subtotal'          => 'Subtotal',
            'discount_amount'   => 'Diskon',
            'total_amount'      => 'Jumlah Total',
            'paid_amount'       => 'Jumlah Pembayaran',
            'change_amount'     => 'Jumlah Kembalian',
            'status_payment'    => 'Status Pembayaran',
            // 'notes'             => 'Catatan Transaction',

            'items'             => 'Daftar Product',
            'items.*.product_id'    => 'Product',
            'items.*.quantity'  => 'Jumlah Product',
        ];
    }

    public function messages()
    {
        return [
            'subtotal.rquired'          => ':attribute harus diisi',
            'discount_amount.required'   => ':attribute harus diisi',
            'total_amount.required'      => ':attribute harus diisi',
            'paid_amount.required'       => ':attribute harus diisi',
            'change_amount.required'     => ':attribute harus diisi',
            'status_payment.required'    => ':attribute harus diisi',
            'items.required'            => ':attribute harus diisi',
        ];
    }
}
