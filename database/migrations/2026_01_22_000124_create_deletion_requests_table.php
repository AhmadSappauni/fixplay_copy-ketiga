<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('deletion_requests', function (Blueprint $table) {
            $table->id();
            
            // Siapa yang request? (Karyawan)
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            
            // Apa yang mau dihapus? (Simpan nama tabel dan ID-nya)
            // Contoh: target_table = 'sales', target_id = 15
            $table->string('target_table'); 
            $table->unsignedBigInteger('target_id');
            
            // Informasi tambahan agar Boss mudah baca (misal: "Mie Goreng x2 - Rp 24.000")
            $table->string('description')->nullable();
            
            // Alasan penghapusan (Opsional/Wajib)
            $table->text('reason')->nullable();
            
            // Status: 'pending', 'approved', 'rejected'
            $table->string('status')->default('pending');
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('deletion_requests');
    }
};