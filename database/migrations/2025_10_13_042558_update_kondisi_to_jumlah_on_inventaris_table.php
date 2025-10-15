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
        Schema::table('inventaris', function (Blueprint $table) {
            // Hapus kolom 'kondisi' yang lama
            $table->dropColumn('kondisi');

            // Tambahkan kolom baru untuk jumlah per kondisi setelah kolom 'nama_barang'
            $table->integer('kondisi_baik')->default(0)->after('nama_barang');
            $table->integer('kondisi_rusak_ringan')->default(0)->after('kondisi_baik');
            $table->integer('kondisi_rusak_berat')->default(0)->after('kondisi_rusak_ringan');

            // Tambahkan kolom keterangan
            $table->text('keterangan')->nullable()->after('kondisi_rusak_berat');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inventaris', function (Blueprint $table) {
            // Kembalikan kolom 'kondisi' jika migration di-rollback
            $table->string('kondisi')->nullable()->after('nama_barang');

            // Hapus kolom-kolom baru
            $table->dropColumn('kondisi_baik');
            $table->dropColumn('kondisi_rusak_ringan');
            $table->dropColumn('kondisi_rusak_berat');
            $table->dropColumn('keterangan');
        });
    }
};
