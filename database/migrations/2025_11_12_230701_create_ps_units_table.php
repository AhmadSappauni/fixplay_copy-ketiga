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
        Schema::create('ps_units', function (Blueprint $table) {
            $table->id();

            // Nama unit PS, contoh: "PS 4", "PS 5 VIP"
            $table->string('name');

            // Tarif per jam (dalam rupiah)
            $table->integer('hourly_rate')->default(0);

            // Apakah unit ini VIP atau bukan
            $table->boolean('is_vip')->default(false);

            // Status aktif/tidak
            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ps_units');
    }
};
