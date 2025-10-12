@extends('dashboard')

@section('content')
    <div class="max-w-7xl mx-auto">
        <h1 class="text-3xl font-bold leading-tight text-gray-900 mb-8">Edit Inventaris: {{ $inventaris->nama_barang }}</h1>

        <div class="bg-white shadow-md rounded-lg p-6">
            <form action="{{ route('inventaris.update', $inventaris) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="nama_barang" class="block text-gray-700 text-sm font-bold mb-2">Nama Barang:</label>
                    <input type="text" name="nama_barang" id="nama_barang" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('nama_barang') border-red-500 @enderror" value="{{ old('nama_barang', $inventaris->nama_barang) }}" required>
                    @error('nama_barang')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>


                <div class="mb-4">
                    <label for="kategori_inventaris" class="block text-gray-700 text-sm font-bold mb-2">Kategori Inventaris (ex: inv):</label>
                    <input type="text" name="kategori_inventaris" id="kategori_inventaris" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('kategori_inventaris') border-red-500 @enderror" value="{{ old('kategori_inventaris', $inventaris->kategori) }}" required>
                    @error('kategori_inventaris')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="pemilik" class="block text-gray-700 text-sm font-bold mb-2">Pemilik (ex: feb):</label>
                    <input type="text" name="pemilik" id="pemilik" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('pemilik') border-red-500 @enderror" value="{{ old('pemilik', $inventaris->pemilik) }}" required>
                    @error('pemilik')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="sumber_dana" class="block text-gray-700 text-sm font-bold mb-2">Sumber Dana (ex: pp-pts):</label>
                    <input type="text" name="sumber_dana" id="sumber_dana" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('sumber_dana') border-red-500 @enderror" value="{{ old('sumber_dana', $inventaris->sumber_dana) }}" required>
                    @error('sumber_dana')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="tahun_beli" class="block text-gray-700 text-sm font-bold mb-2">Tahun Beli:</label>
                    <input type="date" name="tahun_beli" id="tahun_beli" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('tahun_beli') border-red-500 @enderror" value="{{ old('tahun_beli', \Carbon\Carbon::parse($inventaris->tahun_beli)->format('Y-m-d')) }}" required>
                    @error('tahun_beli')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="nomor_unit" class="block text-gray-700 text-sm font-bold mb-2">Nomor Unit (ex: 1):</label>
                    <input type="number" name="nomor_unit" id="nomor_unit" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('nomor_unit') border-red-500 @enderror" value="{{ old('nomor_unit', $inventaris->nomor_unit) }}" required min="1">
                    @error('nomor_unit')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="kondisi" class="block text-gray-700 text-sm font-bold mb-2">Kondisi (Opsional, default 'baik'):</label>
                    <input type="text" name="kondisi" id="kondisi" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('kondisi') border-red-500 @enderror" value="{{ old('kondisi', $inventaris->kondisi) }}">
                    @error('kondisi')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="lokasi" class="block text-gray-700 text-sm font-bold mb-2">Lokasi (Opsional):</label>
                    <input type="text" name="lokasi" id="lokasi" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('lokasi') border-red-500 @enderror" value="{{ old('lokasi', $inventaris->lokasi) }}">
                    @error('lokasi')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        Perbarui Inventaris
                    </button>
                    <a href="{{ route('inventaris.index') }}" class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
