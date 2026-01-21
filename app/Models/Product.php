<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';

    protected $fillable = [
        'name',
        'category',
        'price',
        'cost_price', // <--- TAMBAHAN BARU
        'stock',
        'unit',
        'active',
    ];

    protected $casts = [
        'price'      => 'integer',
        'cost_price' => 'integer', // <--- TAMBAHAN BARU
        'stock'      => 'integer',
        'active'     => 'boolean',
    ];
    
    // Helper untuk menghitung profit per item
    public function getProfitAttribute()
    {
        return $this->price - $this->cost_price;
    }
}