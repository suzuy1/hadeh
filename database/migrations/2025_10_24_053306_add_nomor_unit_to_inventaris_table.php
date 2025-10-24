<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('inventaris', function (Blueprint $table) {
            $table->integer('nomor_unit')->nullable()->after('tahun_beli');
        });
    }
    public function down(): void {
        Schema::table('inventaris', function (Blueprint $table) {
            $table->dropColumn('nomor_unit');
        });
    }
};
