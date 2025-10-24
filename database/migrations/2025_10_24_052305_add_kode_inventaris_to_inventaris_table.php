<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('inventaris', function (Blueprint $table) {
            // Tambahkan setelah kolom 'keterangan'
            $table->string('kode_inventaris')->unique()->nullable()->after('keterangan');
        });
    }
    public function down(): void {
        Schema::table('inventaris', function (Blueprint $table) {
            $table->dropColumn('kode_inventaris');
        });
    }
};
