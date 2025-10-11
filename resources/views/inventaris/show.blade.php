@extends('dashboard')

@section('content')
    <div class="max-w-7xl mx-auto">
        <h1 class="text-3xl font-bold leading-tight text-gray-900 mb-8">Detail Inventaris: {{ $inventaris->nama_barang }}</h1>

        <div class="bg-white shadow-md rounded-lg p-6">
            <div class="mb-4">
                <p class="text-gray-700 text-sm font-bold">Kode Inventaris:</p>
                <p class="text-gray-900">{{ $inventaris->kode_inventaris }}</p>
            </div>
            <div class="mb-4">
                <p class="text-gray-700 text-sm font-bold">Nama Barang:</p>
                <p class="text-gray-900">{{ $inventaris->nama_barang }}</p>
            </div>
            <div class="mb-4">
                <p class="text-gray-700 text-sm font-bold">Kategori Inventaris:</p>
                <p class="text-gray-900">{{ $inventaris->kategori }}</p>
            </div>
            <div class="mb-4">
                <p class="text-gray-700 text-sm font-bold">Pemilik:</p>
                <p class="text-gray-900">{{ $inventaris->pemilik }}</p>
            </div>
            <div class="mb-4">
                <p class="text-gray-700 text-sm font-bold">Sumber Dana:</p>
                <p class="text-gray-900">{{ $inventaris->sumber_dana }}</p>
            </div>
            <div class="mb-4">
                <p class="text-gray-700 text-sm font-bold">Tanggal & Tahun Beli:</p>
                <p class="text-gray-900">{{ \Carbon\Carbon::parse($inventaris->tahun_beli)->format('d-m-Y') }}</p>
            </div>
            <div class="mb-4">
                <p class="text-gray-700 text-sm font-bold">Nomor Unit:</p>
                <p class="text-gray-900">{{ $inventaris->nomor_unit }}</p>
            </div>
            <div class="mb-4">
                <p class="text-gray-700 text-sm font-bold">Kondisi:</p>
                <p class="text-gray-900">{{ $inventaris->kondisi ?? 'N/A' }}</p>
            </div>
            <div class="mb-4">
                <p class="text-gray-700 text-sm font-bold">Lokasi:</p>
                <p class="text-gray-900">{{ $inventaris->lokasi ?? 'N/A' }}</p>
            </div>
            <div class="mb-4">
                <p class="text-gray-700 text-sm font-bold">Ruangan:</p>
                <p class="text-gray-900">{{ $inventaris->room->nama_ruangan ?? 'N/A' }}</p>
            </div>
            <div class="mb-4">
                <p class="text-gray-700 text-sm font-bold">Unit Kerja/Fakultas:</p>
                <p class="text-gray-900">{{ $inventaris->unit->nama_unit ?? 'N/A' }}</p>
            </div>
            <div class="mb-4">
                <p class="text-gray-700 text-sm font-bold">Dibuat Pada:</p>
                <p class="text-gray-900">{{ $inventaris->created_at }}</p>
            </div>
            <div class="mb-4">
                <p class="text-gray-700 text-sm font-bold">Terakhir Diperbarui Pada:</p>
                <p class="text-gray-900">{{ $inventaris->updated_at }}</p>
            </div>

            <div class="flex items-center justify-between mt-6">
                    <a href="{{ route('inventaris.edit', $inventaris) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        Edit Inventaris
                    </a>
                <form action="{{ route('inventaris.destroy', $inventaris) }}" method="POST" class="inline-block">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-500 hover:bg-red-70 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" onclick="return confirm('Apakah Anda yakin ingin menghapus inventaris ini?')">
                        Hapus Inventaris
                    </button>
                </form>
                <a href="{{ route('inventaris.print.single', $inventaris) }}" target="_blank" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Cetak Inventaris</a>
                <a href="{{ route('inventaris.index') }}" class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">
                    Kembali ke Daftar Inventaris
                </a>
            </div>
        </div>
    </div>
@endsection
