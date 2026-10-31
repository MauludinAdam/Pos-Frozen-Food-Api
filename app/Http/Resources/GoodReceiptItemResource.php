<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GoodReceiptItemResource extends JsonResource
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
            'goodsReceipt'      => $this->whenLoaded('goodsReceipt'),
            'purchaseOrderItem' => $this->whenLoaded('purchaseOrderItem'),
            'quantity'          => $this->quantity,
            'unit_price'        => $this->unit_price,
        ];
    }
}
