<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Inventaris; // Changed from Item
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function transactionReport(Request $request)
    {
        $query = Transaction::with(['item', 'user']);

        if ($request->filled('start_date')) {
            $query->whereDate('tanggal', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('tanggal', '<=', $request->end_date);
        }

        if ($request->filled('jenis')) {
            $query->where('jenis', $request->jenis);
        }

        $transactions = $query->orderBy('tanggal', 'desc')->get();

        return view('reports.transactions', compact('transactions'));
    }

    public function itemHistoryReport(Request $request)
    {
        $query = Transaction::with(['item', 'user']);

        if ($request->filled('item_id')) {
            $query->where('item_id', $request->item_id);
        }

        if ($request->filled('start_date')) {
            $query->whereDate('tanggal', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('tanggal', '<=', $request->end_date);
        }

        $items = Inventaris::all(); // For the filter dropdown, changed from Item
        $transactions = $query->orderBy('tanggal', 'desc')->get();

        return view('reports.item_history', compact('transactions', 'items'));
    }
}
