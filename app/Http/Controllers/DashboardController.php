<?php

namespace App\Http\Controllers;

use App\Models\Inventaris; // Changed from Item
use App\Models\Room;
use App\Models\Unit;
use App\Models\Transaction;
use App\Models\Request; // Removed alias
use Illuminate\Http\Request as HttpRequest; // Added alias for Illuminate\Http\Request

class DashboardController extends Controller
{
    public function index()
    {
        $totalInventaris = Inventaris::count();
        $totalRooms = Room::count();
        $totalUnits = Unit::count();
        $pendingRequests = Request::where('status', 'pending')->count();
        
        // Fetch low stock items, considering habis_pakai items
        $lowStockItems = Inventaris::where('kategori', 'habis_pakai')
            ->with('stokHabisPakai') // Eager load relasi
            ->get()
            ->map(function ($item) {
                $item->total_sisa_stok = $item->stokHabisPakai->sum('jumlah_masuk') - $item->stokHabisPakai->sum('jumlah_keluar');
                return $item;
            })
            ->where('total_sisa_stok', '<', 10) // Filter after calculation
            ->sortBy('total_sisa_stok');

        $recentTransactions = Transaction::with(['item', 'user'])->orderBy('created_at', 'desc')->take(5)->get();

        return view('dashboard.home', compact(
            'totalInventaris',
            'totalRooms',
            'totalUnits',
            'pendingRequests',
            'lowStockItems',
            'recentTransactions'
        ));
    }
}
