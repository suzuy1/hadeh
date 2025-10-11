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
            // Temporarily make the column nullable to allow data manipulation
            $table->date('tahun_beli')->nullable()->change();

            // Update existing data to a valid date format (e.g., YYYY-01-01)
            DB::statement('UPDATE inventaris SET tahun_beli = CONCAT(tahun_beli, "-01-01") WHERE tahun_beli IS NOT NULL');

            // Change the column to date and make it not nullable if desired
            $table->date('tahun_beli')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inventaris', function (Blueprint $table) {
            // Revert the column type back to year
            DB::statement('UPDATE inventaris SET tahun_beli = YEAR(tahun_beli) WHERE tahun_beli IS NOT NULL');
            $table->year('tahun_beli')->change();
        });
    }
};
