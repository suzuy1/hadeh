@extends('dashboard')

@section('content')
    <div class="max-w-7xl mx-auto">
        <h1 class="text-3xl font-bold leading-tight text-gray-900 mb-8">Detail Transaksi Inventaris: {{ $transaction->id }}</h1>

        <div class="bg-white shadow-md rounded-lg p-6">
            <div class="mb-4">
                <p class="text-gray-700 text-sm font-bold">ID Transaksi:</p>
                <p class="text-gray-900">{{ $transaction->id }}</p>
            </div>
            <div class="mb-4">
                <p class="text-gray-700 text-sm font-bold">Inventaris:</p>
                <p class="text-gray-900">{{ $transaction->item->nama_barang ?? 'N/A' }} ({{ $transaction->item->kode_inventaris ?? 'N/A' }}) - {{ $transaction->item->jenisBarang->nama_jenis ?? 'N/A' }}</p>
            </div>
            <div class="mb-4">
                <p class="text-gray-700 text-sm font-bold">Jenis Transaksi:</p>
                <p class="text-gray-900">{{ $transaction->jenis }}</p>
            </div>
            <div class="mb-4">
                <p class="text-gray-700 text-sm font-bold">Jumlah:</p>
                <p class="text-gray-900">{{ $transaction->jumlah }}</p>
            </div>
            <div class="mb-4">
                <p class="text-gray-700 text-sm font-bold">Tanggal:</p>
                <p class="text-gray-900">{{ $transaction->tanggal }}</p>
            </div>
            <div class="mb-4">
                <p class="text-gray-700 text-sm font-bold">Pengguna:</p>
                <p class="text-gray-900">{{ $transaction->user->name ?? 'N/A' }}</p>
            </div>
            <div class="mb-4">
                <p class="text-gray-700 text-sm font-bold">Keterangan:</p>
                <p class="text-gray-900">{{ $transaction->keterangan ?? 'N/A' }}</p>
            </div>
            <div class="mb-4">
                <p class="text-gray-700 text-sm font-bold">Dibuat Pada:</p>
                <p class="text-gray-900">{{ $transaction->created_at }}</p>
            </div>
            <div class="mb-4">
                <p class="text-gray-700 text-sm font-bold">Terakhir Diperbarui Pada:</p>
                <p class="text-gray-900">{{ $transaction->updated_at }}</p>
            </div>

            <div class="flex items-center justify-between mt-6">
                <form action="{{ route('transactions.destroy', $transaction->id) }}" method="POST" class="inline-block">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" onclick="return confirm('Apakah Anda yakin ingin menghapus transaksi ini?')">
                        Hapus Transaksi
                    </button>
                </form>
                <a href="{{ route('transactions.index') }}" class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">
                    Kembali ke Daftar Transaksi
                </a>
            </div>
        </div>
    </div>
@endsection
