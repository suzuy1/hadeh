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
        ->join('stok_habis_pakais', 'inventaris.id', '=', 'stok_habis_pakais.id_inventaris')
        ->select('inventaris.*', \DB::raw('SUM(stok_habis_pakais.sisa_stok) as total_sisa_stok'))
        ->groupBy('inventaris.id')
        ->havingRaw('SUM(stok_habis_pakais.sisa_stok) < 10') // Example: stock less than 10
        ->orderByRaw('SUM(stok_habis_pakais.sisa_stok) asc')
        ->get();

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
