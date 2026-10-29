<?php

namespace App\Models;

use App\Models\StockTransactionItem;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class StockTransaction extends Model
{

    protected $fillable = [
        'number_transaction',
        'type',
        'date',
        'total_quantity',
        'user_id',
        'sender_name',
        'receiver_name',
        'phone',
        'description'
    ];

    public function scopeSearch($query, $search)
    {
        return $query->whereHas('user', function($query) use ($search){
            $query->where('name', 'LIKE', "%{$search}%");
        })
        ->orWhere('number_transaction', 'LIKE', "%{$search}%")
        ->orWhere('sender_name', 'LIKE', "%{$search}%")
        ->orWhere('receiver_name', 'LIKE', "%{$search}%");
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function stockTransactionItems()
    {
        return $this->hasMany(StockTransactionItem::class);
    }
}
