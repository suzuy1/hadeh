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
            $table->string('kategori', 50)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inventaris', function (Blueprint $table) {
            // Jika Anda ingin mengembalikan ke enum, Anda perlu menentukan nilai-nilai enum di sini
            // $table->enum('kategori', ['habis_pakai', 'tidak_habis_pakai', 'aset_tetap'])->change();
        });
    }
};
