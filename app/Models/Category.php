<?php

namespace App\Models;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name', 'description'];

    public function scopeSearch($query, $search)
    {
        return $query->where('name', 'Like', "%{$search}%");
    }
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
