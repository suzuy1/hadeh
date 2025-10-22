<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InventarisController; // Changed from ItemController
use App\Http\Controllers\AcquisitionController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\TransactionController; // Import the TransactionController
use App\Http\Controllers\RequestController; // Import the RequestController
use App\Http\Controllers\ReportController; // Import the ReportController

Route::get('/', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'store'])->name('login.post');
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Inventaris (PASTIKAN SEMUA INI ADA)
    Route::get('/inventaris/print_all', [InventarisController::class, 'printAll'])->name('inventaris.print_all');
    Route::get('/inventaris/print_single/{id}', [InventarisController::class, 'printSingle'])->name('inventaris.print_single');
    Route::post('inventaris/import', [InventarisController::class, 'import'])->name('inventaris.import');
    Route::get('inventaris/export', [InventarisController::class, 'export'])->name('inventaris.export');
    Route::get('inventaris/grouped/{nama_barang}', [InventarisController::class, 'showGrouped'])->name('inventaris.show_grouped'); // Rute baru kita
    Route::resource('inventaris', InventarisController::class);

    Route::resource('acquisitions', AcquisitionController::class);
    Route::resource('rooms', RoomController::class);
    Route::resource('users', UserController::class);
    Route::resource('units', UnitController::class);
    Route::resource('transactions', TransactionController::class)->except(['edit', 'update']);
    Route::resource('requests', RequestController::class);

    // API route for fetching inventaris stock
    Route::get('api/inventaris/{inventaris}/stock', [InventarisController::class, 'getStock'])->name('api.inventaris.stock');


    // Report Routes
    Route::get('reports/transactions', [ReportController::class, 'transactionReport'])->name('reports.transactions');
    Route::get('reports/item-history', [ReportController::class, 'itemHistoryReport'])->name('reports.item_history');
});
