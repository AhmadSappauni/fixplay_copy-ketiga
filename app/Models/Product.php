<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';

    // HANYA 1 fillable → gabungan dari semua field yang benar
    protected $fillable = [
        'name',
        'category',
        'price',
        'stock',
        'unit',
        'active',   // kembalikan field active jika memang ada di tabel
    ];

    protected $casts = [
        'price' => 'integer',
        'stock' => 'integer',
        'active' => 'boolean',
    ];
}
