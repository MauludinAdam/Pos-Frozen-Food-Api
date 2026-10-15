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
        'customer_id',
        'subtotal',
        'discount_amount',
        'total_amount',
        'paid_amount',
        'change_amount',
        'status_payment',
        'notes'
    ];

    public function users()
    {
        return $this->belongsTo(User::class);
    }

    public function customers()
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
