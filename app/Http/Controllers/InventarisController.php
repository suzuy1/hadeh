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
use Illuminate\Support\Facades\Log; // Opsional, untuk debugging

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
        // Ambil semua data unit dan ruangan
        $units = \App\Models\Unit::all();
        $rooms = \App\Models\Room::all();

        // Kirim data ke view
        return view('inventaris.create', compact('units', 'rooms'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validasi data
        $validatedData = $request->validate([
            'nama_barang' => 'required|string|max:255',
            'kategori' => 'required|string|in:tidak_habis_pakai,habis_pakai,aset_tetap',
            'pemilik' => 'required|string|max:255',
            'sumber_dana' => 'required|string|max:255',
            'tahun_beli' => 'required|date',
            'nomor_unit' => 'required|integer|min:1',
            'kondisi_baik' => 'required|integer|min:0',
            'kondisi_rusak_ringan' => 'required|integer|min:0',
            'kondisi_rusak_berat' => 'required|integer|min:0',
            'keterangan' => 'nullable|string',
            'lokasi' => 'nullable|string',
            'unit_id' => 'required|exists:units,id',
            'room_id' => 'required|exists:rooms,id',
            'initial_stok' => 'nullable|integer|min:0|required_if:kategori,habis_pakai',
        ]);

        // 2. Mulai Transaksi Database
        DB::beginTransaction();

        try {
            // 3. Buat entri Inventaris baru
            $inventaris = Inventaris::create([
                'nama_barang' => $validatedData['nama_barang'],
                'kategori' => $validatedData['kategori'],
                'pemilik' => $validatedData['pemilik'],
                'sumber_dana' => $validatedData['sumber_dana'],
                'tahun_beli' => $validatedData['tahun_beli'],
                'nomor_unit' => $validatedData['nomor_unit'],
                'kondisi_baik' => $validatedData['kondisi_baik'],
                'kondisi_rusak_ringan' => $validatedData['kondisi_rusak_ringan'],
                'kondisi_rusak_berat' => $validatedData['kondisi_rusak_berat'],
                'keterangan' => $validatedData['keterangan'],
                'lokasi' => $validatedData['lokasi'],
                'unit_id' => $validatedData['unit_id'],
                'room_id' => $validatedData['room_id'],
                // kode_inventaris akan di-generate oleh Observer (InventarisObserver)
            ]);

            // 4. Jika barang habis pakai, buat entri stok
            if ($validatedData['kategori'] === 'habis_pakai') {
                StokHabisPakai::create([
                    'inventaris_id' => $inventaris->id,
                    'stok' => $validatedData['initial_stok'] ?? 0,
                ]);
            }

            // 5. Commit transaksi
            DB::commit();

            // 6. Redirect ke halaman index dengan pesan sukses
            return redirect()->route('inventaris.index')->with('success', 'Data inventaris berhasil ditambahkan.');

        } catch (\Exception $e) {
            // 7. Jika terjadi error, rollback transaksi
            DB::rollBack();

            // Opsional: Catat error untuk debugging
            Log::error('Gagal menyimpan inventaris baru: ' . $e->getMessage());

            // Redirect kembali ke form dengan pesan error
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan saat menyimpan data. Silakan coba lagi.');
        }
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
