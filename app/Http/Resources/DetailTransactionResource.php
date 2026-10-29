<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DetailTransactionResource extends JsonResource
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
            'transaction'       => $this->whenLoaded('transaction'),
            'product'           => $this->whenLoaded('product'),
            'quantity'          => $this->quantity,
            'unit_price'        => $this->unit_price,
            'subtotal'          => $this->subtotal,
        ];
    }
}
