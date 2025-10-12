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
        Schema::create('stok_habis_pakais', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_inventaris');
            $table->integer('jumlah_masuk')->notNullable();
            $table->integer('jumlah_keluar')->default(0);
            $table->date('tanggal')->notNullable();
            $table->timestamps();

            $table->foreign('id_inventaris')->references('id')->on('inventaris')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stok_habis_pakais');
    }
};
