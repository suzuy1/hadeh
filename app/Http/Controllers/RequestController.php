<?php

namespace App\Http\Controllers;

use App\Models\Request as ItemRequest; // Alias Request model to avoid conflict with Illuminate\Http\Request
use App\Models\Inventaris; // Changed from Item
use App\Models\User;
use App\Models\Transaction;
use App\Models\JenisBarang; // New import
use App\Models\StokHabisPakai; // New import
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; // For transactions

class RequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $requests = ItemRequest::with(['item.jenisBarang', 'requester', 'approver'])->get(); // Eager load jenisBarang
        return view('requests.index', compact('requests'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $inventaris = Inventaris::with('jenisBarang')->get(); // Get all inventaris with their jenis
        return view('requests.create', compact('inventaris')); // Changed variable name
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'item_id' => 'required|exists:inventaris,id', // Changed table name
            'jumlah' => 'required|integer|min:1',
            'tanggal_request' => 'required|date',
        ]);

        $inventaris = Inventaris::with('jenisBarang')->findOrFail($validatedData['item_id']);

        if ($inventaris->jenisBarang->tipe === 'habis_pakai') {
            $currentStock = StokHabisPakai::where('id_inventaris', $inventaris->id)->sum('sisa_stok');
            if ($currentStock < $validatedData['jumlah']) {
                return back()->withErrors(['jumlah' => 'Stok barang habis pakai tidak mencukupi untuk permintaan ini.'])->withInput();
            }
        }
        // For non-consumable items, stock is not managed by quantity, so no stock check here.

        ItemRequest::create([
            'item_id' => $validatedData['item_id'],
            'jumlah' => $validatedData['jumlah'],
            'tanggal_request' => $validatedData['tanggal_request'],
            'requester_id' => Auth::id(),
            'status' => 'pending',
        ]);

        return redirect()->route('requests.index')
            ->with('success', 'Request created successfully and is pending approval.');
    }

    /**
     * Display the specified resource.
     */
    public function show(ItemRequest $request)
    {
        $request->load(['item.jenisBarang', 'requester', 'approver']); // Eager load jenisBarang
        return view('requests.show', compact('request'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ItemRequest $request)
    {
        $users = User::all();
        $inventaris = Inventaris::with('jenisBarang')->get(); // For selecting the item if needed, though not directly used in current edit view
        return view('requests.edit', compact('request', 'users', 'inventaris'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ItemRequest $requestModel)
    {
        $validatedData = $request->validate([
            'status' => 'required|in:pending,disetujui,ditolak',
            'approver_id' => 'nullable|exists:users,id',
        ]);

        $inventaris = $requestModel->item; // Get the associated inventaris item

        DB::transaction(function () use ($validatedData, $requestModel, $inventaris) {
            $requestModel->update([
                'status' => $validatedData['status'],
                'approver_id' => $validatedData['approver_id'] ?? Auth::id(),
            ]);

            if ($validatedData['status'] === 'disetujui') {
                if ($inventaris->jenisBarang->tipe === 'habis_pakai') {
                    $currentStock = StokHabisPakai::where('id_inventaris', $inventaris->id)->sum('sisa_stok');
                    if ($currentStock < $requestModel->jumlah) {
                        throw new \Exception('Stok barang habis pakai tidak mencukupi untuk menyetujui permintaan ini.');
                    }

                    StokHabisPakai::create([
                        'id_inventaris' => $inventaris->id,
                        'jumlah_masuk' => 0,
                        'jumlah_keluar' => $requestModel->jumlah,
                        'tanggal' => now()->toDateString(),
                    ]);
                }

                Transaction::create([
                    'item_id' => $requestModel->item_id,
                    'jenis' => 'penggunaan', // Assuming approved requests are for 'penggunaan'
                    'jumlah' => $requestModel->jumlah,
                    'tanggal' => now()->toDateString(),
                    'user_id' => $requestModel->requester_id,
                    'keterangan' => 'Permintaan disetujui oleh ' . ($requestModel->approver->name ?? 'Admin/Staff'),
                ]);
            }
        });

        return redirect()->route('requests.index')
            ->with('success', 'Request updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ItemRequest $request)
    {
        $request->delete();

        return redirect()->route('requests.index')
            ->with('success', 'Request deleted successfully.');
    }
}
