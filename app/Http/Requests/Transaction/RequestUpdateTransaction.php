<?php

namespace App\Http\Requests\Transaction;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class RequestUpdateTransaction extends FormRequest
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
            'subtotal'          => 'required|string|max:150',
            'discount_amount'   => 'required|string|max:150',
            'total_amount'      => 'required|string|max:150',
            'paid_amount'       => 'required|string|max:150',
            'change_amount'     => 'required|string|max:150',
            'status_payment'    => 'required|string|max:100',
            'notes'             => 'nullable|string|max:500',
        ];
    }

    public function attributes()
    {
        return [
            'user_id'           => 'User',
            'subtotal'          => 'Subtotal',
            'discount_amount'   => 'Jumlah Diskon',
            'total_amount'      => 'Jumlah Total',
            'paid_amount'       => 'Jumlah Pembayaran',
            'change_amount'     => 'Jumlah Kembalian',
            'status_payment'    => 'Status Pembayaran',
            'notes'             => 'Catatan Transaksi',
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
            'notes'             => ':atribute boleh kosong'
        ];
    }
}
