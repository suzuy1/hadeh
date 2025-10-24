<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            // Hapus foreign key lama
            $table->dropForeign(['item_id']);
            // Ubah nama kolom
            $table->renameColumn('item_id', 'inventaris_id');
            // Tambahkan foreign key baru
            $table->foreign('inventaris_id')->references('id')->on('inventaris')->onDelete('cascade');
        });

        Schema::table('requests', function (Blueprint $table) {
            $table->dropForeign(['item_id']);
            $table->renameColumn('item_id', 'inventaris_id');
            $table->foreign('inventaris_id')->references('id')->on('inventaris')->onDelete('cascade');
        });

        Schema::table('acquisitions', function (Blueprint $table) {
            $table->dropForeign(['item_id']);
            $table->renameColumn('item_id', 'inventaris_id');
            // Pastikan tabel inventaris sudah ada saat migration ini dijalankan
            $table->foreign('inventaris_id')->references('id')->on('inventaris')->onDelete('cascade');
        });
         Schema::table('stok_habis_pakais', function (Blueprint $table) {
             // Juga perbaiki nama kolom di tabel ini
             $table->renameColumn('id_inventaris', 'inventaris_id');
         });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign(['inventaris_id']);
            $table->renameColumn('inventaris_id', 'item_id');
            // Tambahkan kembali foreign key lama jika perlu rollback
             $table->foreign('item_id')->references('id')->on('inventaris')->onDelete('cascade');
        });
         Schema::table('requests', function (Blueprint $table) {
            $table->dropForeign(['inventaris_id']);
            $table->renameColumn('inventaris_id', 'item_id');
             $table->foreign('item_id')->references('id')->on('inventaris')->onDelete('cascade');
        });
         Schema::table('acquisitions', function (Blueprint $table) {
            $table->dropForeign(['inventaris_id']);
            $table->renameColumn('inventaris_id', 'item_id');
             $table->foreign('item_id')->references('id')->on('inventaris')->onDelete('cascade');
        });
         Schema::table('stok_habis_pakais', function (Blueprint $table) {
             $table->renameColumn('inventaris_id', 'id_inventaris');
         });
    }
};
