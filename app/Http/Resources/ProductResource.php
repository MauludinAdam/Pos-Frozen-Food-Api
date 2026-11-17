<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            'category_id'   => $this->category_id,
            'satuan_id'     => $this->satuan_id,
            'category'      => $this->whenLoaded('category', function() {
                return [
                    'id'    => $this->category->id,
                    'name'  => $this->category->name,
                ];
            }),
            'satuan'        => $this->whenLoaded('satuan', function() {
                return [
                    'id'    => $this->satuan->id,
                    'name'  => $this->satuan->name,
                ];
            }),
            'name'          => $this->name,
            'price'         => $this->price,
            'stock'         => $this->stock,
            'description'   => $this->description,
            'image'         => $this->image ? asset('storage/' . $this->image) : null, 
        ];
    }
}
