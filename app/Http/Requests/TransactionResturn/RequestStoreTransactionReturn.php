<?php

namespace App\Http\Requests\TransactionResturn;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class RequestStoreTransactionReturn extends FormRequest
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
            'number_return'         => 'required|string|max:100',
            'number_transaction'    => 'required|string|max:100',
            'quantity'              => 'required|numeric|100',
            'total_price'           => 'required|numeric|max:100',
            'petugas'               => 'required|string|max:150',
        ];
    }

    public function attributes()
    {
        return [
            'number_return'         => 'Nomor Return',
            'number_transaction'    => 'Nomor Transaksi',
            'quantity'              => 'Quantity',
            'total_price'           => 'jumlah Harga',
            'petugas'               => 'Petugas',
        ];
    }

    public function messages()
    {
        return [
            'number_return'         => ':attribute harus diisi',
            'number_transaction'    => ':attribute harus diisi',
            'quantity'              => ':attribute harus diisi',
            'total_price'           => ':attribute harus diisi',
            'petugas'               => ':attribute harus diisi',
        ];
    }
}
