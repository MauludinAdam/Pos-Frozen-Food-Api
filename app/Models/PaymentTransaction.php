<?php

namespace App\Models;

use App\Models\Transaction;
use Illuminate\Database\Eloquent\Model;

class PaymentTransaction extends Model
{
    protected $fillabale = [
        'transaction_id',
        'payment_method',
        'amount',
        'status',
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
}
