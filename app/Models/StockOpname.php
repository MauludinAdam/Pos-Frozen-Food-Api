<?php

namespace App\Models;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class StockOpname extends Model
{
    protected $fillabale = [
        'date',
        'product_id',
        'stock_system',
        'stock_physical',
        'difference',
        'user_id',
        'notes'
    ];

    public function scopeSearch($query, $search)
    {
        return $query->whereHas('product', function($query) use ($search) {
            $query->where('name', 'LIKE', "%{$search}%");
        });
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
