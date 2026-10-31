<?php

namespace App\Http\Resources;

use App\Http\Resources\GoodReceiptItemResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GoodReceiptResource extends JsonResource
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
            'purchaseOrder' => $this->whenLoaded('purchaseOrder'),
            'user'          => $this->whenLoaded('user'),
            'date'          => $this->date,
            'notes'         => $this->notes,

            'items'         => GoodReceiptItemResource::collection($this->whenLoaded('goodReceiptItems')),
        ];
    }
}
