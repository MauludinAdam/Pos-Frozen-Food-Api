<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StockOpnameResource extends JsonResource
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
            'date'              => $this->date,
            'product'           => $this->whenLoaded('product', function() {
                return [
                    'id'        => $this->product->id,
                    'code'      => $this->product->code,
                    'name'      => $this->product->name,
                ];
            }),
            'stock_system'      => $this->stock_system,
            'stock_physical'    => $this->stock_physical,
            'difference'        => $this->difference,
            'user'              => $this->whenLoaded('user', function() {
                return [
                    'id'        => $this->user->id,
                    'name'      => $this->user->name,
                ];
            }),
            'notes'             => $this->notes,
        ];
    }
}
