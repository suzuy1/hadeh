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
        // Logika untuk mengelompokkan inventaris berdasarkan nama_barang
        $query = Inventaris::select(
            'nama_barang',
            DB::raw('SUM(kondisi_baik) as total_baik'),
            DB::raw('SUM(kondisi_rusak_ringan) as total_rusak_ringan'),
            DB::raw('SUM(kondisi_rusak_berat) as total_rusak_berat'),
            // Ambil data lokasi dan keterangan dari salah satu item sebagai perwakilan
            DB::raw('MAX(keterangan) as keterangan'),
            DB::raw('MAX(room_id) as room_id')
        )->groupBy('nama_barang');

        if ($request->has('search')) {
            $query->where('nama_barang', 'like', '%' . $request->search . '%');
        }

        $inventaris = $query->paginate(10);

        return view('inventaris.index', compact('inventaris'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // ... (kode di fungsi ini tidak perlu diubah)
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // ... (kode di fungsi ini tidak perlu diubah)
    }

    /**
     * Display the specified resource.
     */
    public function show(Inventaris $inventari)
    {
        // ... (kode di fungsi ini tidak perlu diubah, karena masih bisa dipakai untuk detail per-unit)
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Inventaris $inventari)
    {
       // ... (kode di fungsi ini tidak perlu diubah)
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Inventaris $inventari)
    {
        // ... (kode di fungsi ini tidak perlu diubah)
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Inventaris $inventari)
    {
        // ... (kode di fungsi ini tidak perlu diubah)
    }

    public function export()
    {
        return Excel::download(new InventarisExport, 'inventaris.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        Excel::import(new InventarisImport, $request->file('file'));

        return redirect()->route('inventaris.index')->with('success', 'Data inventaris berhasil diimpor.');
    }

    public function printAll()
    {
        $inventaris = Inventaris::with(['room', 'unit'])->get();
        return view('inventaris.print_all', compact('inventaris'));
    }

    public function printSingle($id)
    {
        $inventaris = Inventaris::with(['room', 'unit'])->findOrFail($id);
        return view('inventaris.print_single', compact('inventaris'));
    }

    // FUNGSI BARU UNTUK MENAMPILKAN DETAIL GRUP
    public function showGrouped($nama_barang)
    {
        $inventarisDetails = Inventaris::with(['room', 'unit'])
            ->where('nama_barang', $nama_barang)
            ->paginate(10);
            
        // Ambil nama barang untuk judul halaman
        $namaBarang = $nama_barang;

        return view('inventaris.show_grouped', compact('inventarisDetails', 'namaBarang'));
    }
}
