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
        Schema::create('presensis', function (Blueprint $table) {
            $table->id();

            $table->foreignId('karyawan_id')
                ->constrained('karyawans')
                ->cascadeOnDelete();

            $table->date('tanggal');
            $table->string('shift', 20); // pagi, siang, malam, full_day, sakit, izin

            $table->dateTime('check_in')->nullable();
            $table->dateTime('check_out')->nullable();

            // hadir, telat, sakit, izin, dll
            $table->string('status', 20)->nullable();

            // catatan koreksi oleh bos, dsb
            $table->string('catatan')->nullable();

            $table->timestamps();

            $table->unique(['karyawan_id', 'tanggal', 'shift'], 'presensi_unique_per_shift');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presensis');
    }
};
