<?php

namespace App\Models;

use App\Models\PurchaseOrder;
use App\Models\GoodReceiptItem;
use Illuminate\Database\Eloquent\Model;

class GoodReceipt extends Model
{
    protected $table = 'goods_receipts'; 

    protected $fillable = [
        'code',
        'purchase_order_id',
        'user_id',
        'date',
        'notes',
    ];

    public function scopeSerach($query, $search)
    {
        return $query->whereHas('user', function($query) use ($search) {
            $query->where('name', 'like', "%{$search}%");
        })->orWhere('code', 'like', "%{$search}%");
    }

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function goodReceiptItems()
    {
        return $this->hasMany(GoodReceiptItem::class, 'goods_receipt_id');
    }
}
