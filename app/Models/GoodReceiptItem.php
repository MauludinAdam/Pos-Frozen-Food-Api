<?php

namespace App\Models;

use App\Models\GoodReceipt;
use App\Models\PurchaseOrderItem;
use Illuminate\Database\Eloquent\Model;

class GoodReceiptItem extends Model
{
    protected $table = 'goods_receipt_items';

    protected $fillable = [
        'goods_receipt_id',
        'purchase_order_item_id',
        'quantity',
        'unit_price'
    ];

    public function goodsReceipt()
    {
        return $this->belongsTo(GoodReceipt::class, 'goods_receipt_id');
    }

    public function purchaseOrderItem()
    {
        return $this->belongsTo(PurchaseOrderItem::class);
    }
}
