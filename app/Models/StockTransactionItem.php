<?php

namespace App\Models;

use App\Models\Product;
use App\Models\StockTransaction;
use Illuminate\Database\Eloquent\Model;

class StockTransactionItem extends Model
{
    protected $fillable = [
        'stock_transaction_id',
        'product_id',
        'quantity',
        'unit_price',
        'subtotal',
    ];

    public function stockTransaction()
    {
        return $this->belongsTo(StockTransaction::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
