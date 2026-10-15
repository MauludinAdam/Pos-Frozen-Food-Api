<?php

namespace App\Models;

use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Model;

class DetailTransaction extends Model
{
    protected $fillable = [
        'transaction_id',
        'product_id',
        'quantity',
        'unit_price',
        'subtotal',
    ];

    public function transactions()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function products()
    {
        return $this->belongsTo(Product::class);
    }
}
