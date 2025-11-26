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
        Schema::create('game_sessions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('ps_unit_id')->constrained('ps_units');

            // kolom waktu yang dipakai di controller / query:
            $table->dateTime('start_time');
            $table->dateTime('end_time')->nullable();

            // durasi dalam menit
            $table->unsignedInteger('minutes')->default(0);

            // tambahan stick & arcade
            $table->unsignedInteger('extra_controllers')->default(0);
            $table->unsignedInteger('arcade_controllers')->default(0);

            // tagihan
            $table->unsignedInteger('bill')->default(0);

            // metode & jumlah bayar (kalau mau disimpan di sini)
            $table->string('payment_method')->nullable();
            $table->unsignedInteger('paid_amount')->default(0);

            // status sesi: open / closed
            $table->string('status')->default('open');

            // relasi ke tabel sales (boleh kosong waktu baru dibuat)
            $table->foreignId('sale_id')
                  ->nullable()
                  ->constrained('sales')
                  ->nullOnDelete();

            // catatan opsional
            $table->text('note')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('game_sessions');
    }
};