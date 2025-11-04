<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Inventaris; // Jangan lupa tambahkan ini

class UpdateKodeInventaris extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    // Ubah signature agar mudah dipanggil
    protected $signature = 'inventaris:update-kode';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Memperbarui format kode_inventaris untuk semua data yang ada';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Memulai proses pembaruan kode inventaris...');

        // Ambil semua data inventaris
        $inventarisItems = Inventaris::all();

        foreach ($inventarisItems as $item) {
            // Buat kode baru sesuai format yang diinginkan
            $newKode = sprintf(
                'inv/%s/%s/%s/%03d',
                $item->pemilik,
                $item->sumber_dana,
                date('Y', strtotime($item->tahun_beli)),
                $item->nomor_unit
            );

            // Update data di database
            $item->kode_inventaris = $newKode;
            $item->save();
        }

        $this->info('Selesai! Semua kode inventaris telah berhasil diperbarui.');
        return 0;
    }
}
