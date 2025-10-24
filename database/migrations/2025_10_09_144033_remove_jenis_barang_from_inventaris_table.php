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
            if (Schema::hasColumn('inventaris', 'id_jenis')) {
                $table->dropForeign(['id_jenis']); // Drop the foreign key constraint
                $table->dropColumn('id_jenis'); // Drop the column
            }
        });
        if (Schema::hasTable('jenis_barangs')) {
            Schema::dropIfExists('jenis_barangs'); // Drop the table
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inventaris', function (Blueprint $table) {
            // Re-create the jenis_barangs table and add id_jenis back to inventaris table
            Schema::create('jenis_barangs', function (Blueprint $table) {
                $table->increments('id_jenis');
                $table->string('nama_jenis');
                $table->enum('tipe', ['habis_pakai', 'tidak_habis_pakai']);
                $table->timestamps();
            });
            Schema::table('inventaris', function (Blueprint $table) {
                $table->unsignedInteger('id_jenis')->after('tahun_beli');
                $table->foreign('id_jenis')->references('id_jenis')->on('jenis_barangs')->onDelete('cascade');
            });
        });
    }
};
