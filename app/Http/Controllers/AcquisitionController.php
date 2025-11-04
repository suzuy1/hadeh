<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Acquisition;
use App\Models\Inventaris; // Ganti dari Item
use App\Models\StokHabisPakai; // Tambahkan ini
use App\Models\User;
use Illuminate\Support\Facades\DB; // Tambahkan ini
use Illuminate\Support\Facades\Log; // Tambahkan ini

class AcquisitionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request) // Tambahkan Request
    {
        $this->authorize('viewAny', Acquisition::class); // Proteksi
        // Perbaiki relasi dan foreign key, dan tambahkan paginasi
        $acquisitions = Acquisition::with(['inventaris', 'user'])
                            ->paginate(10); 
        return view('acquisitions.index', compact('acquisitions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Acquisition::class); // Proteksi
        // Ambil SEMUA inventaris (untuk dropdown)
        // Idealnya, ini hanya 'habis_pakai' atau punya logika khusus
        $inventarisItems = Inventaris::all(); 
        $users = User::all();
        return view('acquisitions.create', compact('inventarisItems', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Acquisition::class); // Proteksi

        $validatedData = $request->validate([
            'inventaris_id' => 'required|exists:inventaris,id', // Ganti ke inventaris_id
            'quantity' => 'required|integer|min:1',
            'acquisition_date' => 'required|date',
            'source' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
            'user_id' => 'required|exists:users,id',
        ]);

        DB::beginTransaction();
        try {
            // 1. Catat Akuisisinya
            $acquisition = Acquisition::create($validatedData);

            // 2. Temukan barang inventaris yang diakuisisi
            $inventaris = Inventaris::find($validatedData['inventaris_id']);

            // 3. Jika barangnya 'habis_pakai', tambahkan stoknya
            if ($inventaris && $inventaris->kategori === 'habis_pakai') {
                StokHabisPakai::create([
                    'inventaris_id' => $inventaris->id,
                    'jumlah_masuk' => $validatedData['quantity'],
                    'jumlah_keluar' => 0,
                    'tanggal' => $validatedData['acquisition_date'],
                ]);
            }
            
            // CATATAN: Jika kategori BUKAN habis_pakai,
            // Logika saat ini tidak menambah unit baru, hanya mencatat akuisisi.
            // Ini bisa disesuaikan nanti jika butuh membuat data inventaris baru.

            DB::commit();
            return redirect()->route('acquisitions.index')->with('success', 'Acquisition recorded and stock updated.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal simpan akuisisi: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menyimpan data akuisisi: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Acquisition $acquisition)
    {
        $this->authorize('view', $acquisition);
        $acquisition->load(['inventaris', 'user']); // Pastikan relasi benar
        return view('acquisitions.show', compact('acquisition'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Acquisition $acquisition)
    {
        $this->authorize('update', $acquisition);
        $inventarisItems = Inventaris::all();
        $users = User::all();
        return view('acquisitions.edit', compact('acquisition', 'inventarisItems', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Acquisition $acquisition)
    {
        $this->authorize('update', $acquisition);
        
        $validatedData = $request->validate([
            'inventaris_id' => 'required|exists:inventaris,id',
            'quantity' => 'required|integer|min:1',
            'acquisition_date' => 'required|date',
            'source' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
            'user_id' => 'required|exists:users,id',
        ]);
        
        // TODO: Update logic untuk stok jika quantity/item berubah (rumit)
        // Untuk saat ini, kita update datanya saja
        $acquisition->update($validatedData);

        return redirect()->route('acquisitions.index')->with('success', 'Acquisition updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Acquisition $acquisition)
    {
        $this->authorize('delete', $acquisition);
        
        // TODO: Logic untuk mengurangi stok jika akuisisi dihapus (rumit)
        $acquisition->delete();
        return redirect()->route('acquisitions.index')->with('success', 'Acquisition deleted successfully.');
    }
}
