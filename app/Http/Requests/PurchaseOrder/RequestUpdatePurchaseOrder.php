<?php

namespace App\Http\Requests\PurchaseOrder;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class RequestUpdatePurchaseOrder extends FormRequest
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
            // 'code'              => 'required|string|max:50',
            'supplier_id'       => 'required|exists:suppliers,id',
            'order_date'        => 'required|date',
            'expected_date'     => 'required|date',
            'shipping_cost'     => 'required|nullable|max:100',
            'notes'             => 'nullable|string|max:500',

            'items'                 => 'required|array|min:1',
            'items.*.product_id'    => 'required|exists:products,id',
            'items.*.quantity'      => 'required|numeric|min:1',
            'items.*.unit_price'    => 'required|numeric|min:1',
        ];
    }

    public function attibutes()
    {
        return [
            'code'              => 'Kode',
            'supplier_id'       => 'Supplier',
            'order_date'        => 'Tanggal Order',
            'expected_date'     => 'Tanggal Tiba',
            'subtotal'          => 'Subtotal',
            'total'             => 'Total',
            'shipping_cost'     => 'Biaya Pengiriman',
            'notes'             => 'Catatan Pembelian',
        ];
    }

    public function messages()
    {
        return [
            'code'              => ':attribute harus diisi',
            'supplier_id'       => ':attribute harus diisi',
            'order_date'        => ':attribute harus diisi',
            'expected_date'     => ':attribute harus diisi',
            'subtotal'          => ':attribute harus diisi',
            'total'             => ':attribute harus diisi',
            'shipping_cost'     => ':attribute harus diisi',
            'notes'             => ':attribute boleh kosong',
        ];
    }
}
