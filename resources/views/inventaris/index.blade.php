@extends('dashboard')

@section('content')
    <div class="max-w-7xl mx-auto">
        <h1 class="text-3xl font-bold leading-tight text-gray-900 mb-8">Daftar Inventaris</h1>

        <div class="bg-white shadow-md rounded-lg p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-2xl font-bold">Semua Inventaris</h2>
                <div class="flex space-x-2">
                    <a href="{{ route('inventaris.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Tambah Inventaris Baru</a>
                    <a href="{{ route('inventaris.export') }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Ekspor Inventaris</a>
                    <form action="{{ route('inventaris.import') }}" method="POST" enctype="multipart/form-data" class="inline-block">
                        @csrf
                        <input type="file" name="file" class="hidden" id="import_file" onchange="this.form.submit()">
                        <button type="button" onclick="document.getElementById('import_file').click()" class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded">Impor Inventaris</button>
                    </form>
                    <a href="{{ route('inventaris.print.all') }}" target="_blank" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Cetak Semua</a>
                </div>
            </div>

            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif
            @if (session('info'))
                <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline">{{ session('info') }}</span>
                </div>
            @endif

            <div class="overflow-x-auto">
                <table class="min-w-full bg-white">
                    <thead>
                        <tr>
                            <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Kode Inventaris</th>
                            <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nama Barang</th>
                            <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Kategori</th>
                            <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Pemilik</th>
                            <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Sumber Dana</th>
                            <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tahun Beli</th>
                            <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nomor Unit</th>
                            <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Kondisi</th>
                            <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Lokasi</th>
                            <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Unit</th>
                            <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Ruangan</th>
                            <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Stok (Habis Pakai)</th>
                            <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($inventaris as $item)
                            <tr>
                                <td class="py-2 px-4 border-b border-gray-200">{{ $item->kode_inventaris }}</td>
                                <td class="py-2 px-4 border-b border-gray-200">{{ $item->nama_barang }}</td>
                                <td class="py-2 px-4 border-b border-gray-200">{{ $item->kategori }}</td>
                                <td class="py-2 px-4 border-b border-gray-200">{{ $item->pemilik ?? 'N/A' }}</td>
                                <td class="py-2 px-4 border-b border-gray-200">{{ $item->sumber_dana ?? 'N/A' }}</td>
                                <td class="py-2 px-4 border-b border-gray-200">{{ \Carbon\Carbon::parse($item->tahun_beli)->format('d-m-Y') ?? 'N/A' }}</td>
                                <td class="py-2 px-4 border-b border-gray-200">{{ $item->nomor_unit ?? 'N/A' }}</td>
                                <td class="py-2 px-4 border-b border-gray-200">{{ $item->kondisi ?? 'N/A' }}</td>
                                <td class="py-2 px-4 border-b border-gray-200">{{ $item->lokasi ?? 'N/A' }}</td>
                                <td class="py-2 px-4 border-b border-gray-200">{{ $item->unit->nama_unit ?? 'N/A' }}</td>
                                <td class="py-2 px-4 border-b border-gray-200">{{ $item->room->nama_ruangan ?? 'N/A' }}</td>
                                <td class="py-2 px-4 border-b border-gray-200">
                                    @if ($item->kategori === 'habis_pakai')
                                        {{ $item->stokHabisPakai->sum('jumlah_masuk') - $item->stokHabisPakai->sum('jumlah_keluar') }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="py-2 px-4 border-b border-gray-200">
                                    <a href="{{ route('inventaris.show', $item) }}" class="text-blue-600 hover:text-blue-900 mr-2">Lihat</a>
                                    <a href="{{ route('inventaris.edit', $item) }}" class="text-yellow-600 hover:text-yellow-90 mr-2">Edit</a>
                                    <form action="{{ route('inventaris.destroy', $item) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Apakah Anda yakin ingin menghapus inventaris ini?')">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="13" class="py-4 px-4 text-center text-gray-500">Tidak ada inventaris yang ditemukan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
