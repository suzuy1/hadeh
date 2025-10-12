<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inventaris; // Changed from Item
use App\Models\Room;
use App\Models\Unit;
use App\Models\StokHabisPakai; // New import
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\InventarisExport;
use App\Imports\InventarisImport;
use Illuminate\Support\Facades\DB;

class InventarisController extends Controller // Changed class name
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Inventaris::with(['room', 'unit', 'stokHabisPakai']);

        $inventaris = $query->get();
        return view('inventaris.index', compact('inventaris'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $rooms = Room::all();
        $units = Unit::all();
        return view('inventaris.create', compact('rooms', 'units'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama_barang' => 'required|string|max:100',
            'kategori' => 'required|in:habis_pakai,tidak_habis_pakai,aset_tetap',
            'pemilik' => 'required|string|max:50',
            'sumber_dana' => 'required|string|max:50',
            'tahun_beli' => 'required|date',
            'nomor_unit' => 'required|integer|min:1',
            'kondisi' => 'nullable|string|max:50',
            'lokasi' => 'nullable|string|max:100',
            'initial_stok' => 'nullable|integer|min:0',
            'unit_id' => 'required|exists:units,id',
            'room_id' => 'required|exists:rooms,id',
        ]);

        // Generate kode_inventaris
        $kode_inventaris = sprintf(
            '%s/%s/%s/%s/%03d',
            $validatedData['kategori'],
            $validatedData['pemilik'],
            $validatedData['sumber_dana'],
            date('Y', strtotime($validatedData['tahun_beli'])),
            $validatedData['nomor_unit']
        );

        $inventaris = Inventaris::create([
            'kategori' => $validatedData['kategori'],
            'pemilik' => $validatedData['pemilik'],
            'sumber_dana' => $validatedData['sumber_dana'],
            'tahun_beli' => $validatedData['tahun_beli'],
            'nomor_unit' => $validatedData['nomor_unit'],
            'kode_inventaris' => $kode_inventaris,
            'nama_barang' => $validatedData['nama_barang'],
            'kondisi' => $validatedData['kondisi'],
            'lokasi' => $validatedData['lokasi'],
            'unit_id' => $validatedData['unit_id'],
            'room_id' => $validatedData['room_id'],
        ]);

        // Hanya buat entri stok jika kategorinya 'habis_pakai' dan stok awal diisi
        if ($validatedData['kategori'] === 'habis_pakai' && isset($validatedData['initial_stok']) && $validatedData['initial_stok'] > 0) {
            StokHabisPakai::create([
                'id_inventaris' => $inventaris->id,
                'jumlah_masuk' => $validatedData['initial_stok'],
                'jumlah_keluar' => 0,
                'tanggal' => now()->toDateString(),
            ]);
        }

        return redirect()->route('inventaris.index')->with('success', 'Inventaris berhasil dibuat!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Inventaris $inventaris) // Changed model type
    {
        $inventaris->load(['room', 'unit']); // Eager load new relationships
        return view('inventaris.show', compact('inventaris')); // Changed view name and variable
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Inventaris $inventaris) // Changed model type
    {
        $rooms = Room::all();
        $units = Unit::all();
        return view('inventaris.edit', compact('inventaris', 'rooms', 'units')); // Changed view name and variable
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Inventaris $inventaris)
    {
        $validatedData = $request->validate([
            'nama_barang' => 'required|string|max:100',
            'kategori' => 'required|in:habis_pakai,tidak_habis_pakai,aset_tetap',
            'pemilik' => 'required|string|max:50',
            'sumber_dana' => 'required|string|max:50',
            'tahun_beli' => 'required|date',
            'nomor_unit' => 'required|integer|min:1',
            'kondisi' => 'nullable|string|max:50',
            'lokasi' => 'nullable|string|max:100',
            'unit_id' => 'required|exists:units,id',
            'room_id' => 'required|exists:rooms,id',
        ]);

        // Generate kode_inventaris
        $kode_inventaris = sprintf(
            '%s/%s/%s/%s/%03d',
            $validatedData['kategori'],
            $validatedData['pemilik'],
            $validatedData['sumber_dana'],
            date('Y', strtotime($validatedData['tahun_beli'])),
            $validatedData['nomor_unit']
        );
        $validatedData['kode_inventaris'] = $kode_inventaris;

        $inventaris->update($validatedData);

        return redirect()->route('inventaris.index')->with('success', 'Inventaris berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Inventaris $inventaris) // Changed model type
    {
        $inventaris->delete();

        return redirect()->route('inventaris.index')->with('success', 'Inventaris deleted successfully!'); // Changed route and message
    }

    /**
     * Export items to Excel.
     */
    public function export()
    {
        return Excel::download(new InventarisExport, 'inventaris.xlsx');
    }

    /**
     * Import items from Excel.
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);
        Excel::import(new InventarisImport, $request->file('file'));
        return redirect()->route('inventaris.index')->with('success', 'Inventaris imported successfully!');
    }

    /**
     * Print all items.
     */
    public function printAll()
    {
        $inventaris = Inventaris::with(['room', 'unit'])->get(); // Eager load new relationships
        return view('inventaris.print_all', compact('inventaris')); // Changed view name and variable
    }

    /**
     * Print a single item.
     */
    public function printSingle(Inventaris $inventaris) // Changed model type
    {
        $inventaris->load(['room', 'unit']); // Eager load new relationships
        return view('inventaris.print_single', compact('inventaris')); // Changed view name and variable
    }

    /**
     * Get the current stock of an inventaris item (for consumable items).
     */
    public function getStock(Inventaris $inventaris)
    {
        if ($inventaris->kategori === 'habis_pakai') {
            $currentStock = StokHabisPakai::where('id_inventaris', $inventaris->id)->sum(DB::raw('jumlah_masuk - jumlah_keluar'));
            return response()->json(['sisa_stok' => $currentStock]);
        }
        return response()->json(['sisa_stok' => 'Tidak Berlaku']);
    }
}
