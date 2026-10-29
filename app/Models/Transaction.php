<?php

namespace App\Models;

use App\Models\Customer;
use App\Models\DetailTransaction;
use App\Models\PaymentTransaction;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'user_id',
        'subtotal',
        'discount_amount',
        'total_amount',
        'paid_amount',
        'change_amount',
        'status_payment',
        'notes'
    ];

    public function scopeSearch($query, $search)
    {
        return $query->whereHas('user', function ($query) use ($search) {
                $query->where('name', 'LIKE', "%{$search}%");
        });
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function detailTransactions()
    {
        return $this->hasMany(DetailTransaction::class);
    }

    public function paymentTransactions()
    {
        return $this->hasMany(PaymentTransaction::class);
    }
}
