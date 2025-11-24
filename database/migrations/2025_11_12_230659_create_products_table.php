<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            
            $table->string('name');
            $table->string('category')->nullable();

            // Field angka
            $table->integer('price')->default(0);  // harga
            $table->integer('stock')->default(0);  // stok

            // Satuan barang (pcs, cup, dll)
            $table->string('unit')->nullable()->default('pcs');

            // Status aktif / nonaktif
            $table->boolean('active')->default(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
