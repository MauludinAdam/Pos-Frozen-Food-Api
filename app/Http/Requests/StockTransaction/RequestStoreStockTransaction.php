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
            'transaction_id'        => 'nullable|exists:transactions,id',
            'date'                  => 'required|date',
            'sender_name'           => 'required|string|max:150',
            'phone'                 => 'required|string|max:30',
            'description'           => 'nullable|string|max:500',

            'items'                 => 'required|array|min:1',
            'items.*.product_id'    => 'required|exists:products,id',
            'items.*.quantity'      => 'required|numeric|gt:0',
            'items.*.unit_price'      => 'required|numeric|gt:0',
        ];
    }

    public function attributes()
    {
        return [
            'date'                  => 'Tanggal',
            'sender_name'           => 'Nama Pengirim',
            'phone'                 => 'No.Telp',
            'description'           => 'Deskripsi',
        ];
    }

    public function messages()
    {
        return [
            'date'                  => ':attribute harus diisi',
            'sender_name'           => ':attribute harus diisi',
            'phone'                 => ':attribute harus diisi',

        ];
    }
}
