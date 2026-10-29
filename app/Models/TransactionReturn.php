<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionReturn extends Model
{
    protected $fillable = [
        'number_return',
        'number_transaction',
        'quantity',
        'total_price',
        'petugas',
    ];
}
