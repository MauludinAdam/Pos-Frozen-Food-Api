<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                => $this->id,
            'user'              => $this->whenLoaded('user'),
            'subtotal'          => $this->subtotal,
            'discount_amount'   => $this->discount_amount,
            'total_amount'      => $this->total_amount,
            'paid_amount'       => $this->paid_amount,
            'change_amount'     => $this->change_amount,
            'status_payment'    => $this->status_payment,
            
            'payment'           => $this->whenLoaded('paymentTransactions',function() {
                return [
                    'payment_method' => $this->paymentTransaction->payment_method,
                    'amount'         => $this->paymentTransaction->amount,
                    'status'         => $this->paymentTransaction->status,
                ];
            }),

            'details'           => $this->whenLoaded('detailTransactions',function() {
                return $this->detailTransactions->map(function($detail) {
                    return [
                        'id'            => $detail->id,
                        'product_name'  => $detail->product->name,
                        'quantity'      => $detail->quantity,
                        'unit_price'    => $detail->unit_price,
                        'subtotal'      => $detail->subtotal,
                    ];
                });
            }),

            'created_at'    => $this->created_at,
        ];
    }
}
