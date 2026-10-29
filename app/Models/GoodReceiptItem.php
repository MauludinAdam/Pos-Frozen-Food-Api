<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoodReceiptItem extends Model
{
    protected $fillable = [
        'goods_receipt_id',
        'purchase_order_item_id',
        'quantity',
        'unit_price'
    ];
}
