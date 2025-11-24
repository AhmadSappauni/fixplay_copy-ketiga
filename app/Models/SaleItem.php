<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaleItem extends Model
{
    protected $table = 'sale_items';

    // HANYA 1 fillable, pakai kolom yang benar di tabel sale_items
    protected $fillable = [
        'sale_id',
        'product_id',
        'description',
        'qty',
        'unit_price',
        'subtotal',
    ];

    protected $casts = [
        'qty'        => 'integer',
        'unit_price' => 'integer',
        'subtotal'   => 'integer',
    ];

    // RELATIONS
    public function sale()
    {
        return $this->belongsTo(Sale::class, 'sale_id');
    }

    public function product()
    {
        // product_id bisa null (untuk sesi PS), jadi belongsTo tetap benar
        return $this->belongsTo(Product::class, 'product_id');
    }
}
