<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Inventaris; // Changed from Item
use App\Models\User;
use App\Models\StokHabisPakai; // New import
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; // For transactions

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Transaction::class); // Proteksi
        $transactions = Transaction::with('user')->paginate(10); // Ganti .get()
        return view('transactions.index', compact('transactions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Transaction::class); // Proteksi
        $inventaris = Inventaris::all();
        $users = User::all();
        return view('transactions.create', compact('inventaris', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Transaction::class); // Proteksi
        $validatedData = $request->validate([
            'inventaris_id' => 'required|exists:inventaris,id',
            'jenis' => 'required|in:penggunaan,peminjaman,pengembalian,mutasi',
            'jumlah' => 'required|integer|min:1',
            'tanggal' => 'required|date',
            'user_id' => 'required|exists:users,id',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $inventaris = Inventaris::findOrFail($validatedData['inventaris_id']);

        try {
            DB::transaction(function () use ($validatedData, $inventaris) {
                if ($inventaris->kategori === 'habis_pakai') {
                    if ($validatedData['jenis'] === 'penggunaan' || $validatedData['jenis'] === 'peminjaman') {
                        $currentStock = StokHabisPakai::where('inventaris_id', $inventaris->id)->sum(DB::raw('jumlah_masuk - jumlah_keluar'));
                        if ($currentStock < $validatedData['jumlah']) {
                            throw \Illuminate\Validation\ValidationException::withMessages([
                                'jumlah' => 'Stok barang habis pakai tidak mencukupi (tersedia: ' . $currentStock . ').',
                            ]);
                        }
                        StokHabisPakai::create([
                            'inventaris_id' => $inventaris->id,
                            'jumlah_masuk' => 0,
                            'jumlah_keluar' => $validatedData['jumlah'],
                            'tanggal' => $validatedData['tanggal'],
                        ]);
                    } elseif ($validatedData['jenis'] === 'pengembalian') {
                        StokHabisPakai::create([
                            'inventaris_id' => $inventaris->id,
                            'jumlah_masuk' => $validatedData['jumlah'],
                            'jumlah_keluar' => 0,
                            'tanggal' => $validatedData['tanggal'],
                        ]);
                    }
                }
                Transaction::create([
                    'inventaris_id' => $validatedData['inventaris_id'],
                    'jenis' => $validatedData['jenis'],
                    'jumlah' => $validatedData['jumlah'],
                    'tanggal' => $validatedData['tanggal'],
                    'user_id' => $validatedData['user_id'],
                    'keterangan' => $validatedData['keterangan'],
                ]);
            });
            return redirect()->route('transactions.index')->with('success', 'Transaction created successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Gagal membuat transaksi: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal membuat transaksi: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Transaction $transaction)
    {
        $this->authorize('view', $transaction); // Proteksi
        $transaction->load('user');
        return view('transactions.show', compact('transaction'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction $transaction)
    {
        $this->authorize('delete', $transaction); // Proteksi
        DB::transaction(function () use ($transaction) {
            // Gunakan relasi inventaris()
            $inventaris = $transaction->inventaris;
            if ($inventaris && $inventaris->kategori === 'habis_pakai') {
                // ... (logika revert stok)
                // Pastikan menggunakan $inventaris->id
                if ($transaction->jenis === 'penggunaan' || $transaction->jenis === 'peminjaman') {
                    StokHabisPakai::create([
                        'inventaris_id' => $inventaris->id, // <-- Pastikan ini inventaris_id
                        'jumlah_masuk' => $transaction->jumlah,
                        'jumlah_keluar' => 0,
                        'tanggal' => now()->toDateString(),
                        'keterangan' => 'Revert from deleted transaction: ' . $transaction->id,
                    ]);
                } elseif ($transaction->jenis === 'pengembalian') {
                     StokHabisPakai::create([
                        'inventaris_id' => $inventaris->id, // <-- Pastikan ini inventaris_id
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
