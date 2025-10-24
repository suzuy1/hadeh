<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase; // Gunakan ini agar database di-reset setiap test
use Tests\TestCase;
use App\Models\User;
use App\Models\Unit;
use App\Models\Room;
use App\Models\Inventaris;

class InventarisFeatureTest extends TestCase
{
    use RefreshDatabase; // Aktifkan reset database

    /**
     * Test user tidak terautentikasi tidak bisa akses index inventaris.
     */
    public function test_guest_cannot_access_inventaris_index(): void
    {
        $response = $this->get(route('inventaris.index'));
        $response->assertRedirect(route('login')); // Harusnya redirect ke login
    }

    /**
     * Test user 'dosen' tidak bisa membuat inventaris.
     */
    public function test_dosen_cannot_create_inventaris(): void
    {
        $user = User::factory()->create(['role' => 'dosen']);
        $unit = Unit::factory()->create(); // Buat data dummy jika perlu
        $room = Room::factory()->create(['unit_id' => $unit->id]);

        $response = $this->actingAs($user)->get(route('inventaris.create'));
        $response->assertForbidden(); // Harusnya dilarang (403)

        $response = $this->actingAs($user)->post(route('inventaris.store'), [
             // Data inventaris dummy
            'nama_barang' => 'Test Barang Dosen',
            'kategori' => 'tidak_habis_pakai',
            'pemilik' => 'test',
            'sumber_dana' => 'test',
            'tahun_beli' => now()->toDateString(),
            'nomor_unit' => 1,
            'kondisi_baik' => 1,
            'kondisi_rusak_ringan' => 0,
            'kondisi_rusak_berat' => 0,
            'unit_id' => $unit->id,
            'room_id' => $room->id,
        ]);
        $response->assertForbidden(); // Harusnya dilarang (403)
    }


    /**
     * Test user 'staff' bisa membuat inventaris.
     */
    public function test_staff_can_create_inventaris(): void
    {
        $user = User::factory()->create(['role' => 'staff']);
        $unit = Unit::factory()->create();
        $room = Room::factory()->create(['unit_id' => $unit->id]);

        $inventarisData = [
            'nama_barang' => 'Test Barang Staff',
            'kategori' => 'tidak_habis_pakai',
            'pemilik' => 'fak',
            'sumber_dana' => 'dana',
            'tahun_beli' => '2025-10-24',
            'nomor_unit' => 5,
            'kondisi_baik' => 1,
            'kondisi_rusak_ringan' => 0,
            'kondisi_rusak_berat' => 0,
            'keterangan' => null,
            'lokasi' => null,
            'unit_id' => $unit->id,
            'room_id' => $room->id,
        ];

        $response = $this->actingAs($user)->post(route('inventaris.store'), $inventarisData);

        $response->assertRedirect(route('inventaris.index')); // Harusnya redirect ke index
        $response->assertSessionHas('success'); // Ada pesan sukses

        // Cek apakah data tersimpan di database
        $this->assertDatabaseHas('inventaris', [
            'nama_barang' => 'Test Barang Staff',
            'pemilik' => 'fak',
            'kode_inventaris' => 'inv/fak/dana/2025/005' // Cek kode yang di-generate observer
        ]);
    }

     // Tambahkan test case lain untuk update, delete, view, import, export, print, dll.
     // Juga test untuk barang habis pakai dan stoknya.

     // Perlu setup Factory untuk model lain jika belum ada
     // Contoh: Buat UnitFactory, RoomFactory
     // Jalankan: php artisan make:factory UnitFactory --model=Unit
     // Edit file factory yang dibuat
     // Gunakan $unit = Unit::factory()->create(); dalam test
}
