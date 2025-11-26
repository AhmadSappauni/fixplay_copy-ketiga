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
        Schema::table('ps_units', function (Blueprint $table) {
            // Menambahkan kolom 'type' setelah kolom 'name'
            // Default diset 'PS4' agar data lama otomatis dianggap PS4 dan tidak error
            $table->string('type')->default('PS4')->after('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ps_units', function (Blueprint $table) {
            // Menghapus kolom jika migrasi dibatalkan (rollback)
            $table->dropColumn('type');
        });
    }
};