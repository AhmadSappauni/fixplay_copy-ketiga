<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jadwal_karyawans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('karyawan_id')
                  ->constrained('karyawans')
                  ->onDelete('cascade');
            $table->date('tanggal');
            $table->string('shift', 30);      // contoh: pagi, siang, malam, full_day, izin, sakit, dll
            $table->string('catatan', 255)->nullable();
            $table->timestamps();

            $table->unique(['karyawan_id', 'tanggal']); // 1 jadwal per hari per karyawan
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jadwal_karyawans');
    }
};
