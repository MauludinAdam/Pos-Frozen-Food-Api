<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StockTransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                    => $this->id,
            'number_transaction'    => $this->number_transaction,
            'type'                  => $this->type,
            'date'                  => $this->date,
            'total_quantity'        => $this->total_quantity,
            'user'                  => $this->whenLoaded('user', function() {
                return [
                    'id'            => $this->user->id,
                    'name'          => $this->user->name,
                ];
            }),

            'sender_name'           => $this->sender_name,
            'receiver_name'         => $this->receiver_name,
            'phone'                 => $this->phone,
            'description'           => $this->description,

            'items'                 => $this->whenLoaded('stockTransactionItems', function() {
                return $this->stockTransactionItems->map(function ($item) {
                    return [
                        'id'            => $item->id,
                        'product_id'    => $item->product_id,
                        'quantity'      => $item->quantity,
                        'unit_price'    => $item->unit_price,
                        'subtotal'      => $item->subtotal,
                    ];
                });
            })
        ];
    }
}
