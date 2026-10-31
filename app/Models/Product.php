<?php

namespace App\Models;

use App\Models\Category;
use App\Models\DetailTransaction;
use App\Models\PurchaseOrderItem;
use App\Models\Satuan;
use App\Models\StockOpname;
use App\Models\StockTransactionItem;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'code', 
        'category_id', 
        'satuan_id', 
        'name', 
        'price', 
        'stock', 
        'description', 
        'image'
    ];

    public function scopeSearch($query, $search)
    {
        return $query->where('name', 'LIKE', "%{$search}%")
            ->orWhere('code', 'LIKE', "%{$search}%");
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function satuan()
    {
        return $this->belongsTo(Satuan::class);
    }

    public function detailTransactions()
    {
        return $this->hasMany(DetailTransaction::class);
    }

    public function stockTransactionItems()
    {
        return $this->hasMany(StockTransactionItem::class);
    }

    public function purchaseOrderItems()
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }

    public function stockOpname()
    {
        return $this->hasMany(StockOpname::class);
    }
}
