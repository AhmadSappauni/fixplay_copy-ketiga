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
            // PERBAIKAN: Gunakan uuid() sebagai primary key, bukan id()
            // Ini agar cocok dengan trait HasUuids di Model
            $table->uuid('id')->primary();

            // Note: ps_unit_id dan sale_id tetap foreignId (integer) 
            // karena tabel ps_units dan sales kemungkinan masih pakai id() biasa (integer).
            $table->foreignId('ps_unit_id')->constrained('ps_units');

            $table->dateTime('start_time');
            $table->dateTime('end_time')->nullable();

            $table->unsignedInteger('minutes')->default(0);

            $table->unsignedInteger('extra_controllers')->default(0);
            $table->unsignedInteger('arcade_controllers')->default(0);

            $table->unsignedInteger('bill')->default(0);

            $table->string('payment_method')->nullable();
            $table->unsignedInteger('paid_amount')->default(0);

            $table->string('status')->default('open');

            $table->foreignId('sale_id')
                  ->nullable()
                  ->constrained('sales')
                  ->nullOnDelete();

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