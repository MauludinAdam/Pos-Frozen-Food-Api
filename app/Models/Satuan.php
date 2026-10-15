<?php

namespace App\Models;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;

class Satuan extends Model
{
    protected $fillable = [
        'name',
        'symbol',
        'description',
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
