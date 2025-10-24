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
use App\Http\Requests\StoreInventarisRequest; // Tambahkan ini di atas
use Illuminate\Foundation\Auth\Access\AuthorizesRequests; // Tambahkan ini

class InventarisController extends Controller // Changed class name
{
    use AuthorizesRequests; // Tambahkan trait ini
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Inventaris::class); // Otorisasi viewAny
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
        $this->authorize('create', Inventaris::class); // Otorisasi create
        // Ambil semua data unit dan ruangan
        $units = \App\Models\Unit::all();
        $rooms = \App\Models\Room::all();

        // Kirim data ke view
        return view('inventaris.create', compact('units', 'rooms'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreInventarisRequest $request) // Ganti tipe parameter
    {
        $this->authorize('create', Inventaris::class); // Otorisasi create (double check, bagus)
        // 1. Validasi sudah otomatis dilakukan oleh Form Request
        $validatedData = $request->validated(); // Ambil data yang sudah divalidasi

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
            ]);

            // 4. Jika barang habis pakai, buat entri stok
            if ($validatedData['kategori'] === 'habis_pakai' && isset($validatedData['initial_stok'])) {
                StokHabisPakai::create([
                    // Pastikan foreign key sesuai (inventaris_id atau id_inventaris)
                    'inventaris_id' => $inventaris->id,
                    'jumlah_masuk' => $validatedData['initial_stok'],
                    'jumlah_keluar' => 0, // Awalnya keluar 0
                    'tanggal' => now()->toDateString(), // Tanggal stok awal
                ]);
            }

            // 5. Commit transaksi
            DB::commit();

            // 6. Redirect ke halaman index dengan pesan sukses
            return redirect()->route('inventaris.index')->with('success', 'Data inventaris berhasil ditambahkan.');

        } catch (\Illuminate\Validation\ValidationException $e) {
             // Jika validasi gagal (seharusnya sudah ditangani Form Request, tapi sebagai backup)
             DB::rollBack();
             return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            // 7. Jika terjadi error lain, rollback transaksi
            DB::rollBack();
            Log::error('Gagal menyimpan inventaris baru: ' . $e->getMessage());

            // Beri pesan error yang lebih spesifik jika memungkinkan
            $errorMessage = 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage();
            // Redirect kembali ke form dengan pesan error
            return redirect()->back()->withInput()->with('error', $errorMessage);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Inventaris $inventaris) // Ganti $inventari jadi $inventaris
    {
        $this->authorize('view', $inventaris); // Otorisasi view spesifik
        // Load relasi jika diperlukan
        $inventaris->load(['room', 'unit']);
        return view('inventaris.show', compact('inventaris'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Inventaris $inventaris) // Ganti $inventari jadi $inventaris
    {
        $this->authorize('update', $inventaris); // Otorisasi update
        $units = Unit::all();
        $rooms = Room::all();
        return view('inventaris.edit', compact('inventaris', 'units', 'rooms'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Inventaris $inventaris) // Ganti $inventari jadi $inventaris
    {
        $this->authorize('update', $inventaris); // Otorisasi update
        // Lakukan validasi seperti di store atau gunakan UpdateInventarisRequest
        $validatedData = $request->validate([ /* ... rules ... */ ]);
        $inventaris->update($validatedData);
        // ... (handle stok jika kategori berubah atau jumlah berubah)
        return redirect()->route('inventaris.index')->with('success', 'Inventaris updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Inventaris $inventaris) // Ganti $inventari jadi $inventaris
    {
        $this->authorize('delete', $inventaris); // Otorisasi delete
        // ... (logika hapus stok jika perlu)
        $inventaris->delete();
        return redirect()->route('inventaris.index')->with('success', 'Inventaris deleted successfully.');
    }

    public function export()
    {
        $this->authorize('export', Inventaris::class); // Otorisasi export
        return Excel::download(new InventarisExport, 'inventaris.xlsx');
    }

    public function import(Request $request)
    {
        $this->authorize('import', Inventaris::class); // Otorisasi import
        $request->validate(['file' => 'required|mimes:xlsx,xls,csv']);
        Excel::import(new InventarisImport, $request->file('file'));
        return redirect()->route('inventaris.index')->with('success', 'Data inventaris berhasil diimpor.');
    }

    public function printAll()
    {
        $this->authorize('print', Inventaris::class); // Otorisasi print
        $inventaris = Inventaris::with(['room', 'unit'])->get();
         // Tambah eager load stok untuk habis pakai
         $inventaris->load(['stokHabisPakai' => function ($query) {
             $query->select('inventaris_id', DB::raw('SUM(jumlah_masuk) as total_masuk, SUM(jumlah_keluar) as total_keluar'))
                   ->groupBy('inventaris_id');
         }]);
        return view('inventaris.print_all', compact('inventaris'));
    }

    public function printSingle($id)
    {
        $inventaris = Inventaris::with(['room', 'unit', 'stokHabisPakai'])->findOrFail($id);
        $this->authorize('print', $inventaris); // Otorisasi print
        return view('inventaris.print_single', compact('inventaris'));
    }

    /**
     * Get current stock for a specific inventaris item (especially for 'habis_pakai').
     *
     * @param  \App\Models\Inventaris $inventaris
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStock(Inventaris $inventaris)
    {
        if ($inventaris->kategori === 'habis_pakai') {
            $sisaStok = StokHabisPakai::where('inventaris_id', $inventaris->id) // Sesuaikan dengan nama kolom foreign key yang benar
                                    ->sum(DB::raw('jumlah_masuk - jumlah_keluar'));
            return response()->json(['sisa_stok' => $sisaStok]);
        } else {
            // Untuk barang tidak habis pakai, stok tidak dikelola per kuantitas di tabel stok
            // Anda bisa mengembalikan jumlah kondisi baik, atau 0, atau pesan 'N/A'
            return response()->json(['sisa_stok' => $inventaris->kondisi_baik]); // Contoh: mengembalikan jumlah kondisi baik
            // Atau: return response()->json(['sisa_stok' => 'Tidak Berlaku']);
        }
    }

    public function showGrouped($nama_barang)
    {
         $this->authorize('viewAny', Inventaris::class); // Asumsi sama dengan viewAny
         $inventarisDetails = Inventaris::with(['room', 'unit'])
             ->where('nama_barang', $nama_barang)
             ->paginate(10);
         $namaBarang = $nama_barang;
         return view('inventaris.show_grouped', compact('inventarisDetails', 'namaBarang'));
    }
}
