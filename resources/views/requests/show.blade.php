@extends('dashboard')

@section('content')
    <div class="max-w-7xl mx-auto">
        <h1 class="text-3xl font-bold leading-tight text-gray-900 mb-8">Detail Permintaan Inventaris: {{ $request->id }}</h1>

        <div class="bg-white shadow-md rounded-lg p-6">
            <div class="mb-4">
                <p class="text-gray-700 text-sm font-bold">ID Permintaan:</p>
                <p class="text-gray-900">{{ $request->id }}</p>
            </div>
            <div class="mb-4">
                <p class="text-gray-700 text-sm font-bold">Inventaris:</p>
                <p class="text-gray-900">{{ $request->item->nama_barang ?? 'N/A' }} ({{ $request->item->kode_inventaris ?? 'N/A' }}) - {{ $request->item->kategori ?? 'N/A' }}</p>
            </div>
            <div class="mb-4">
                <p class="text-gray-700 text-sm font-bold">Jumlah:</p>
                <p class="text-gray-900">{{ $request->jumlah }}</p>
            </div>
            <div class="mb-4">
                <p class="text-gray-700 text-sm font-bold">Tanggal Permintaan:</p>
                <p class="text-gray-900">{{ $request->tanggal_request }}</p>
            </div>
            <div class="mb-4">
                <p class="text-gray-700 text-sm font-bold">Pemohon:</p>
                <p class="text-gray-900">{{ $request->requester->name ?? 'N/A' }}</p>
            </div>
            <div class="mb-4">
                <p class="text-gray-700 text-sm font-bold">Penyetuju:</p>
                <p class="text-gray-900">{{ $request->approver->name ?? 'N/A' }}</p>
            </div>
            <div class="mb-4">
                <p class="text-gray-700 text-sm font-bold">Status:</p>
                <p class="text-gray-900">{{ $request->status }}</p>
            </div>
            <div class="mb-4">
                <p class="text-gray-700 text-sm font-bold">Dibuat Pada:</p>
                <p class="text-gray-900">{{ $request->created_at }}</p>
            </div>
            <div class="mb-4">
                <p class="text-gray-700 text-sm font-bold">Terakhir Diperbarui Pada:</p>
                <p class="text-gray-900">{{ $request->updated_at }}</p>
            </div>

            <div class="flex items-center justify-between mt-6">
                <a href="{{ route('requests.edit', $request->id) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Edit Status Permintaan
                </a>
                <form action="{{ route('requests.destroy', $request->id) }}" method="POST" class="inline-block">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" onclick="return confirm('Apakah Anda yakin ingin menghapus permintaan ini?')">
                        Hapus Permintaan
                    </button>
                </form>
                <a href="{{ route('requests.index') }}" class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">
                    Kembali ke Daftar Permintaan
                </a>
            </div>
        </div>
    </div>
@endsection
