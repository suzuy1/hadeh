@extends('dashboard')

@section('content')
    <div class="max-w-7xl mx-auto">
        <h1 class="text-3xl font-bold leading-tight text-gray-900 mb-8">Daftar Permintaan Inventaris</h1>

        <div class="bg-white shadow-md rounded-lg p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-2xl font-bold">Semua Permintaan</h2>
                <a href="{{ route('requests.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Buat Permintaan Baru</a>
            </div>

            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <table class="min-w-full bg-white">
                <thead>
                    <tr>
                        <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">ID</th>
                        <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Inventaris</th>
                        <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Jumlah</th>
                        <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tanggal Request</th>
                        <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Pemohon</th>
                        <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Penyetuju</th>
                        <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($requests as $request)
                        <tr>
                            <td class="py-2 px-4 border-b border-gray-200">{{ $request->id }}</td>
                            <td class="py-2 px-4 border-b border-gray-200">{{ $request->item->nama_barang ?? 'N/A' }} ({{ $request->item->kode_inventaris ?? 'N/A' }}) - {{ $request->item->kategori ?? 'N/A' }}</td>
                            <td class="py-2 px-4 border-b border-gray-200">{{ $request->jumlah }}</td>
                            <td class="py-2 px-4 border-b border-gray-200">{{ $request->tanggal_request }}</td>
                            <td class="py-2 px-4 border-b border-gray-200">{{ $request->requester->name ?? 'N/A' }}</td>
                            <td class="py-2 px-4 border-b border-gray-200">{{ $request->approver->name ?? 'N/A' }}</td>
                            <td class="py-2 px-4 border-b border-gray-200">{{ $request->status }}</td>
                            <td class="py-2 px-4 border-b border-gray-200">
                                <a href="{{ route('requests.show', $request->id) }}" class="text-blue-600 hover:text-blue-900 mr-2">Lihat</a>
                                <a href="{{ route('requests.edit', $request->id) }}" class="text-yellow-600 hover:text-yellow-900 mr-2">Edit Status</a>
                                <form action="{{ route('requests.destroy', $request->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Apakah Anda yakin ingin menghapus permintaan ini?')">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-4 px-4 text-center text-gray-500">Tidak ada permintaan yang ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $requests->links() }}
        </div>
    </div>
@endsection
