<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $table = 'sales';

    // SATU fillable saja, gabungan dari semua field yang kamu pakai
    protected $fillable = [
        // field yang sebelumnya ada di fillable pertama
        'name',
        'unit',
        'stock',
        'price',
        'active',

        // field yang sebelumnya ada di fillable kedua
        'total',
        'paid_amount',
        'change_amount',
        'payment_method',
        'note',
        'user_id',
        'sold_at',
    ];

    protected $casts = [
        'sold_at'     => 'datetime',
        'total'       => 'integer',
        'paid_amount' => 'integer',
    ];

    // relasi ke sale_items
    public function items()
    {
        return $this->hasMany(\App\Models\SaleItem::class, 'sale_id');
    }
}
