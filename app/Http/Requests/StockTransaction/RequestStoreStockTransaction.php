<?php

namespace App\Http\Requests\StockTransaction;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class RequestStoreStockTransaction extends FormRequest
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
            'number_transaction'    => 'required|string|max:100',
            'type'                  => 'required|string|in:In,Out',
            'date'                  => 'required|date',
            'total_quantity'        => 'required|string|max:100',
            'user_id'               => 'required|exists:users,id',
            'sender_name'           => 'required|string|max:150',
            'receiver_name'         => 'required|string|max:150',
            'phone'                 => 'required|string|max:30',
            'description'           => 'nullable|string|max:500',

            'items'                 => 'required|array|min:1',
            'items.*.product_id'    => 'required|exists:products,id',
            'items.*.quantity'      => 'required|numeric|gt:0',
        ];
    }

    public function attributes()
    {
        return [
            'number_transaction'    => 'Nomor Transaksi',
            'type'                  => 'Type',
            'date'                  => 'Tanggal',
            'total_quantity'        => 'Total Quantity',
            'user_id'               => 'User',
            'sender_name'           => 'Nama Pengirim',
            'receiver_name'         => 'Nama Peneriman',
            'phone'                 => 'No.Telp',
            'description'           => 'Deskripsi',
        ];
    }

    public function messages()
    {
        return [
            'number_transaction'    => ':attribute harus diisi',
            'type'                  => ':attribute harus diisi',
            'date'                  => ':attribute harus diisi',
            'total_quantity'        => ':attribute harus diisi',
            'user_id'               => ':attribute harus diisi',
            'sender_name'           => ':attribute harus diisi',
            'receiver_name'         => ':attribute harus diisi',
            'phone'                 => ':attribute harus diisi',

        ];
    }
}
