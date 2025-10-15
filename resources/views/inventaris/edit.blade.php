@extends('dashboard')

@section('content')
    <div class="max-w-7xl mx-auto">
        <h1 class="text-3xl font-bold leading-tight text-gray-900 mb-8">Edit Inventaris: {{ $inventaris->nama_barang }}</h1>

        <div class="bg-white shadow-md rounded-lg p-6">
            <form action="{{ route('inventaris.update', $inventaris) }}" method="POST" x-data="{ kategori: '{{ old('kategori', $inventaris->kategori) }}' }">
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
                    <label for="kategori" class="block text-gray-700 text-sm font-bold mb-2">Kategori Inventaris:</label>
                    <select name="kategori" id="kategori" x-model="kategori" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('kategori') border-red-500 @enderror" required>
                        <option value="tidak_habis_pakai" {{ old('kategori', $inventaris->kategori) == 'tidak_habis_pakai' ? 'selected' : '' }}>Barang Tidak Habis Pakai</option>
                        <option value="habis_pakai" {{ old('kategori', $inventaris->kategori) == 'habis_pakai' ? 'selected' : '' }}>Barang Habis Pakai</option>
                        <option value="aset_tetap" {{ old('kategori', $inventaris->kategori) == 'aset_tetap' ? 'selected' : '' }}>Aset Tetap</option>
                    </select>
                    @error('kategori')
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
                    <label class="block text-gray-700 text-sm font-bold mb-2">Jumlah Kondisi Barang:</label>
                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <label for="kondisi_baik" class="block text-sm font-medium text-gray-500">Baik</label>
                            <input type="number" name="kondisi_baik" id="kondisi_baik" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" value="{{ old('kondisi_baik', $inventaris->kondisi_baik) }}" min="0">
                        </div>
                        <div>
                            <label for="kondisi_rusak_ringan" class="block text-sm font-medium text-gray-500">Rusak Ringan</label>
                            <input type="number" name="kondisi_rusak_ringan" id="kondisi_rusak_ringan" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" value="{{ old('kondisi_rusak_ringan', $inventaris->kondisi_rusak_ringan) }}" min="0">
                        </div>
                        <div>
                            <label for="kondisi_rusak_berat" class="block text-sm font-medium text-gray-500">Rusak Berat</label>
                            <input type="number" name="kondisi_rusak_berat" id="kondisi_rusak_berat" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" value="{{ old('kondisi_rusak_berat', $inventaris->kondisi_rusak_berat) }}" min="0">
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="keterangan" class="block text-gray-700 text-sm font-bold mb-2">Keterangan (Opsional):</label>
                    <textarea name="keterangan" id="keterangan" rows="3" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700">{{ old('keterangan', $inventaris->keterangan) }}</textarea>
                </div>

                <div class="mb-4">
                    <label for="lokasi" class="block text-gray-700 text-sm font-bold mb-2">Lokasi (Opsional):</label>
                    <input type="text" name="lokasi" id="lokasi" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('lokasi') border-red-500 @enderror" value="{{ old('lokasi', $inventaris->lokasi) }}">
                    @error('lokasi')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="unit_id" class="block text-gray-700 text-sm font-bold mb-2">Unit:</label>
                    <select name="unit_id" id="unit_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('unit_id') border-red-500 @enderror" required>
                        <option value="">Pilih Unit</option>
                        @foreach($units as $unit)
                            <option value="{{ $unit->id }}" {{ old('unit_id', $inventaris->unit_id) == $unit->id ? 'selected' : '' }}>{{ $unit->nama_unit }}</option>
                        @endforeach
                    </select>
                    @error('unit_id')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="room_id" class="block text-gray-700 text-sm font-bold mb-2">Ruangan:</label>
                    <select name="room_id" id="room_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('room_id') border-red-500 @enderror" required>
                        <option value="">Pilih Ruangan</option>
                        @foreach($rooms as $room)
                            <option value="{{ $room->id }}" {{ old('room_id', $inventaris->room_id) == $room->id ? 'selected' : '' }}>{{ $room->nama_ruangan }}</option>
                        @endforeach
                    </select>
                    @error('room_id')
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
