<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inventaris; // Changed from Item
use App\Models\Room;
use App\Models\Unit;
use App\Models\StokHabisPakai; // New import
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ItemsExport; // Will need to be updated
use App\Imports\ItemsImport; // Will need to be updated

class InventarisController extends Controller // Changed class name
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Inventaris::with(['room', 'unit']); // Eager load new relationships

        $inventaris = $query->get(); // Changed variable name
        return view('inventaris.index', compact('inventaris')); // Changed view name and variable
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
            'kategori_inventaris' => 'required|string|max:10', // This is the 'kategori' field in inventaris table
            'pemilik' => 'required|string|max:50',
            'sumber_dana' => 'required|string|max:50',
            'tahun_beli' => 'required|date',
            'nomor_unit' => 'required|integer|min:1',
            'kondisi' => 'nullable|string|max:50',
            'lokasi' => 'nullable|string|max:100',
            'initial_stok' => 'nullable|integer|min:0', // For habis_pakai items
        ]);

        // Generate kode_inventaris
        $kode_inventaris = sprintf(
            '%s/%s/%s/%s/%03d',
            $validatedData['kategori_inventaris'],
            $validatedData['pemilik'],
            $validatedData['sumber_dana'],
            $validatedData['tahun_beli'],
            $validatedData['nomor_unit']
        );

        $inventaris = Inventaris::create([
            'kategori' => $validatedData['kategori_inventaris'],
            'pemilik' => $validatedData['pemilik'],
            'sumber_dana' => $validatedData['sumber_dana'],
            'tahun_beli' => $validatedData['tahun_beli'],
            'nomor_unit' => $validatedData['nomor_unit'],
            'kode_inventaris' => $kode_inventaris,
            'nama_barang' => $validatedData['nama_barang'],
            'kondisi' => $validatedData['kondisi'],
            'lokasi' => $validatedData['lokasi'],
        ]);


        return redirect()->route('inventaris.index')->with('success', 'Inventaris created successfully!'); // Changed route and message
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
    public function update(Request $request, Inventaris $inventaris) // Changed model type
    {
        $validatedData = $request->validate([
            'nama_barang' => 'required|string|max:100',
            'kategori_inventaris' => 'required|string|max:10',
            'pemilik' => 'required|string|max:50',
            'sumber_dana' => 'required|string|max:50',
            'tahun_beli' => 'required|date',
            'nomor_unit' => 'required|integer|min:1',
            'kondisi' => 'nullable|string|max:50',
            'lokasi' => 'nullable|string|max:100',
        ]);

        // Re-generate kode_inventaris if any relevant fields changed
        $kode_inventaris = sprintf(
            '%s/%s/%s/%s/%03d',
            $validatedData['kategori_inventaris'],
            $validatedData['pemilik'],
            $validatedData['sumber_dana'],
            $validatedData['tahun_beli'],
            $validatedData['nomor_unit']
        );
        $validatedData['kode_inventaris'] = $kode_inventaris;

        $inventaris->update($validatedData);

        return redirect()->route('inventaris.index')->with('success', 'Inventaris updated successfully!'); // Changed route and message
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
        // return Excel::download(new ItemsExport, 'items.xlsx'); // Needs update for Inventaris
        return redirect()->route('inventaris.index')->with('info', 'Export functionality needs to be updated for new schema.');
    }

    /**
     * Import items from Excel.
     */
    public function import(Request $request)
    {
        // $request->validate([
        //     'file' => 'required|mimes:xlsx,xls,csv',
        // ]);
        // Excel::import(new ItemsImport, $request->file('file')); // Needs update for Inventaris
        return redirect()->route('inventaris.index')->with('info', 'Import functionality needs to be updated for new schema.');
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
        // Since jenisBarang is removed, this functionality needs to be re-evaluated or removed.
        // For now, returning 'Tidak Berlaku' as stock is tied to 'habis_pakai' type.
        return response()->json(['sisa_stok' => 'Tidak Berlaku']);
    }
}
