<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SupplierResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'        => $this->id,
            'code'      => $this->code,
            'name'      => $this->name,
            'phone'     => $this->phone,
            'email'     => $this->email,
            'is_active' => $this->is_active,
            'address'   => $this->address,
            'notes'     => $this->notes,
        ];
    }
}
