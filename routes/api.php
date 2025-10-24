<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InventarisController; // Tambahkan ini

/*
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
*/

// Pindahkan route getStock ke sini
Route::get('/inventaris/{inventaris}/stock', [InventarisController::class, 'getStock'])
     ->name('api.inventaris.stock'); // Middleware bisa ditambahkan jika perlu otentikasi API
