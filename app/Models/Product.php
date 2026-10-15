<?php

namespace App\Models;

use App\Models\Category;
use App\Models\Satuan;
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
        'image'];

    public function categories()
    {
        return $this->belongsTo(Category::class);
    }

    public function satuans()
    {
        return $this->belongsTo(Satuan::class);
    }

    public function detailTransactions()
    {
        return $this->hasMany(DetailTransaction::class);
    }
}
