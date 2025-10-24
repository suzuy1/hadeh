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
        // Periksa apakah kolom 'stok' ada, lalu hapus
        if (Schema::hasColumn('inventaris', 'stok')) {
            Schema::table('inventaris', function (Blueprint $table) {
                $table->dropColumn('stok');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Jika ingin rollback, tambahkan kembali kolom 'stok'
        // (Opsional, tapi praktik yang baik)
        Schema::table('inventaris', function (Blueprint $table) {
            // Sesuaikan default atau nullability jika Anda ingat
            $table->integer('stok')->default(0)->after('kondisi_rusak_berat'); 
        });
    }
};
