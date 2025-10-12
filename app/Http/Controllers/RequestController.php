<?php

namespace App\Http\Controllers;

use App\Models\Request as ItemRequest; // Alias Request model to avoid conflict with Illuminate\Http\Request
use App\Models\Inventaris; // Changed from Item
use App\Models\User;
use App\Models\Transaction;
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
        $requests = ItemRequest::with(['item', 'requester', 'approver'])->get();
        return view('requests.index', compact('requests'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $inventaris = Inventaris::all();
        return view('requests.create', compact('inventaris'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'item_id' => 'required|exists:inventaris,id',
            'jumlah' => 'required|integer|min:1',
            'tanggal_request' => 'required|date',
        ]);

        $inventaris = Inventaris::findOrFail($validatedData['item_id']);

        if ($inventaris->kategori === 'habis_pakai') {
            $currentStock = StokHabisPakai::where('id_inventaris', $inventaris->id)->sum(DB::raw('jumlah_masuk - jumlah_keluar'));
            if ($currentStock < $validatedData['jumlah']) {
                return back()->withErrors(['jumlah' => 'Stok barang habis pakai tidak mencukupi untuk permintaan ini.'])->withInput();
            }
        }

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
        $request->load(['item', 'requester', 'approver']);
        return view('requests.show', compact('request'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ItemRequest $request)
    {
        $users = User::all();
        $inventaris = Inventaris::all();
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

        $inventaris = $requestModel->item;

        DB::transaction(function () use ($validatedData, $requestModel, $inventaris) {
            $requestModel->update([
                'status' => $validatedData['status'],
                'approver_id' => $validatedData['approver_id'] ?? Auth::id(),
            ]);

            if ($validatedData['status'] === 'disetujui') {
                if ($inventaris->kategori === 'habis_pakai') {
                    $currentStock = StokHabisPakai::where('id_inventaris', $inventaris->id)->sum(DB::raw('jumlah_masuk - jumlah_keluar'));
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
                    'jenis' => 'penggunaan',
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
