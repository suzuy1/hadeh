<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('inventaris', function (Blueprint $table) {
            $table->string('pemilik')->nullable()->after('kategori');
            $table->string('sumber_dana')->nullable()->after('pemilik');
        });
    }
    public function down(): void {
        Schema::table('inventaris', function (Blueprint $table) {
            $table->dropColumn('pemilik');
            $table->dropColumn('sumber_dana');
        });
    }
};
