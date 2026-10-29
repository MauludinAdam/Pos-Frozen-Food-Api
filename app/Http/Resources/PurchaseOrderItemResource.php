<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseOrderItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'purchaseOrder' => $this->whenLoaded('purchaseOrder'),
            'product'       => $this->whenLoaded('product'),
            'quantity'      => $this->quantity,
            'unit_price'    => $this->unit_price,
            'subtotal'      => $this->subtotal,
        ];
    }
}
