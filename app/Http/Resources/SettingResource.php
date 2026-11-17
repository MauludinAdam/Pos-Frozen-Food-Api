<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SettingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name'      => $this->name,
            'phone'     => $this->phone,
            'email'     => $this->email,
            'address'   => $this->address,
            'logo'      => $this->logo ? asset('storage/' . $this->logo) : null,
        ];
    }
}
