<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Inventaris; // Changed from Item
use App\Models\User;
use App\Models\JenisBarang; // New import
use App\Models\StokHabisPakai; // New import
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; // For transactions

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $transactions = Transaction::with(['item.jenisBarang', 'user'])->get(); // Eager load jenisBarang
        return view('transactions.index', compact('transactions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $inventaris = Inventaris::with('jenisBarang')->get(); // Get all inventaris with their jenis
        $users = User::all();
        return view('transactions.create', compact('inventaris', 'users')); // Changed variable name
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'item_id' => 'required|exists:inventaris,id', // Changed table name
            'jenis' => 'required|in:penggunaan,peminjaman,pengembalian,mutasi',
            'jumlah' => 'required|integer|min:1',
            'tanggal' => 'required|date',
            'user_id' => 'required|exists:users,id',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $inventaris = Inventaris::with('jenisBarang')->findOrFail($validatedData['item_id']);

        DB::transaction(function () use ($validatedData, $inventaris) {
            if ($inventaris->jenisBarang->tipe === 'habis_pakai') {
                // For consumable items, update stok_habis_pakai
                if ($validatedData['jenis'] === 'penggunaan' || $validatedData['jenis'] === 'peminjaman') {
                    $currentStock = StokHabisPakai::where('id_inventaris', $inventaris->id)->sum('sisa_stok');
                    if ($currentStock < $validatedData['jumlah']) {
                        throw new \Exception('Stok barang habis pakai tidak mencukupi.');
                    }
                    // Deduct from stock, prioritizing older batches if needed (complex, simplified here)
                    StokHabisPakai::create([
                        'id_inventaris' => $inventaris->id,
                        'jumlah_masuk' => 0, // No new stock coming in
                        'jumlah_keluar' => $validatedData['jumlah'],
                        'tanggal' => $validatedData['tanggal'],
                    ]);
                } elseif ($validatedData['jenis'] === 'pengembalian') {
                    // For pengembalian of consumable, it implies returning unused portion or adding back
                    // This might be less common for 'penggunaan' but possible for 'peminjaman'
                    StokHabisPakai::create([
                        'id_inventaris' => $inventaris->id,
                        'jumlah_masuk' => $validatedData['jumlah'],
                        'jumlah_keluar' => 0,
                        'tanggal' => $validatedData['tanggal'],
                    ]);
                }
            } else {
                // For non-consumable items, stock is not managed by quantity in stok_habis_pakai
                // 'Peminjaman' and 'Pengembalian' would imply status changes or assignment, not quantity.
                // 'Mutasi' would imply location change.
                // The current logic doesn't directly affect 'stok' on Inventaris, as it no longer exists.
                // Further logic would be needed here for non-consumable specific actions.
            }

            Transaction::create($validatedData);
        });


        return redirect()->route('transactions.index')
            ->with('success', 'Transaction created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Transaction $transaction)
    {
        $transaction->load(['item.jenisBarang', 'user']); // Eager load jenisBarang
        return view('transactions.show', compact('transaction'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction $transaction)
    {
        DB::transaction(function () use ($transaction) {
            $inventaris = $transaction->item;
            if ($inventaris && $inventaris->jenisBarang->tipe === 'habis_pakai') {
                // Revert stock changes for consumable items
                if ($transaction->jenis === 'penggunaan' || $transaction->jenis === 'peminjaman') {
                    // Revert deduction by adding back to stock
                    StokHabisPakai::create([
                        'id_inventaris' => $inventaris->id,
                        'jumlah_masuk' => $transaction->jumlah,
                        'jumlah_keluar' => 0,
                        'tanggal' => now()->toDateString(),
                        'keterangan' => 'Revert from deleted transaction: ' . $transaction->id,
                    ]);
                } elseif ($transaction->jenis === 'pengembalian') {
                    // Revert addition by deducting from stock
                    StokHabisPakai::create([
                        'id_inventaris' => $inventaris->id,
                        'jumlah_masuk' => 0,
                        'jumlah_keluar' => $transaction->jumlah,
                        'tanggal' => now()->toDateString(),
                        'keterangan' => 'Revert from deleted transaction: ' . $transaction->id,
                    ]);
                }
            }
            $transaction->delete();
        });

        return redirect()->route('transactions.index')
            ->with('success', 'Transaction deleted successfully.');
    }
}
