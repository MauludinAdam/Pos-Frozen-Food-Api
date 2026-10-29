<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionReturnResource extends JsonResource
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
            'number_return'         => $this->number_return,
            'number_transaction'    => $this->number_transaction,
            'quantity'              => $this->quantity,
            'total_price'           => $this->total_price,
            'petugas'               => $this->petugas, 
        ];
    }
}
