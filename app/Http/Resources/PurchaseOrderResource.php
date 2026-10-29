<?php

namespace App\Http\Resources;

use App\Http\Resources\PurchaseOrderItemResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseOrderResource extends JsonResource
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
            'code'          => $this->code,
            'supplier'      => $this->whenLoaded('supplier'),
            'order_date'    => $this->order_date,
            'expected_date' => $this->expected_date,
            'subtotal'      => $this->subtotal,
            'total'         => $this->total,
            'shipping_cost' => $this->shipping_cost,
            'notes'         => $this->notes,

            'items'         => PurchaseOrderItemResource::collection($this->whenLoaded('purchaseOrderItems')),
        ];
    }
}
