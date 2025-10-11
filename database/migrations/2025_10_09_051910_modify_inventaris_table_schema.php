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
            // Drop existing foreign keys
            $table->dropForeign('items_room_id_foreign');
            $table->dropForeign('items_unit_id_foreign');

            // Drop existing columns
            $table->dropColumn(['kategori', 'stok', 'room_id', 'unit_id']);

            // Modify existing columns
            $table->string('nama_barang', 100)->change();
            $table->string('kondisi', 50)->default('baik')->change();

            // Add new columns
            $table->string('lokasi', 100)->nullable()->after('kondisi'); // Add lokasi as a new column
            $table->string('kategori', 10)->default('inv')->after('id'); // New 'kategori' column
            $table->string('pemilik', 50)->notNullable()->after('kategori');
            $table->string('sumber_dana', 50)->notNullable()->after('pemilik');
            $table->date('tahun_beli')->notNullable()->after('sumber_dana');
            $table->unsignedInteger('id_jenis')->after('tahun_beli'); // Foreign key to jenis_barang
            $table->integer('nomor_unit')->notNullable()->after('id_jenis');
            $table->string('kode_inventaris', 100)->unique()->nullable()->after('nomor_unit');
            $table->foreignId('room_id')->nullable()->constrained('rooms')->onDelete('set null')->after('lokasi');
            $table->foreignId('unit_id')->nullable()->constrained('units')->onDelete('set null')->after('room_id');

            // Add new foreign key
            $table->foreign('id_jenis')->references('id_jenis')->on('jenis_barangs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inventaris', function (Blueprint $table) {
            // Drop new foreign key (commented out to avoid error if it doesn't exist)
            // $table->dropForeign(['id_jenis']);

            // Drop new columns
            $table->dropColumn(['kategori', 'pemilik', 'sumber_dana', 'id_jenis', 'nomor_unit', 'kode_inventaris']);
            $table->dropColumn('tahun_beli');

            // Revert modified columns (assuming original state)
            $table->string('nama_barang')->change(); // Revert to default string length
            $table->string('kondisi')->nullable()->change(); // Revert to nullable string
            $table->string('lokasi')->nullable()->change(); // Revert to default string length

            // Re-add original columns (assuming original state)
            $table->enum('kategori', ['habis_pakai', 'tidak_habis_pakai', 'aset_tetap'])->after('id');
            $table->integer('stok')->default(0)->after('kategori');
            $table->year('tahun_beli')->notNullable()->after('sumber_dana'); // Re-add as year for rollback
            $table->foreignId('room_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('unit_id')->nullable()->constrained('units')->onDelete('set null');
        });
    }
};
