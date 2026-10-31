<?php

namespace App\Http\Requests\GoodReceipt;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class RequestStoreGoodReceipt extends FormRequest
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
            'purchase_order_id' => 'required|exists:purchase_orders,id',
            'user_id'           => 'required|exists:users,id',
            'date'              => 'required|date',
            'notes'             => 'nullable|string|max:500',

            'items'                             => 'required|array|min:1',
            'items.*.purchase_order_item_id'    => 'required|exists:purchase_order_items,id',
            'items.*.quantity'                  => 'required|numeric|min:1,',
            'items.*.unit_price'                => 'required|numeric|min:1',
        ];
    }

    public function attributes()
    {
        return [
            'purchase_order_id' => 'Pesanan Pembelian',
            'user_id'           => 'Nama Admin Gudang',
            'date'              => 'Tanggal',
        ];
    }

    public function messages()
    {
        return [
            'purchase_order_id' => ':attribute harus diisi',
            'user_id'           => ':attribute harus diisi',
            'date'              => ':attribute harus diisi',
        ];
    }
}
