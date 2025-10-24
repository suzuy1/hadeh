@extends('dashboard')

@section('content')
    <div class="max-w-7xl mx-auto">
        <h1 class="text-3xl font-bold leading-tight text-gray-900 mb-8">Buat Permintaan Inventaris Baru</h1>

        <div class="bg-white shadow-md rounded-lg p-6">
            <form action="{{ route('requests.store') }}" method="POST" x-data="stockChecker({{ $inventaris->toJson() }})" x-init="init()">
                @csrf

                <div class="mb-4">
                    <label for="inventaris_id" class="block text-gray-700 text-sm font-bold mb-2">Inventaris:</label>
                    <select name="inventaris_id" id="inventaris_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('inventaris_id') border-red-500 @enderror" x-model="selectedInventarisId" @change="updateStockInfo()" required>
                        <option value="">Pilih Inventaris</option>
                        @foreach ($inventaris as $item)
                            <option value="{{ $item->id }}" {{ old('inventaris_id') == $item->id ? 'selected' : '' }}>
                                {{ $item->nama_barang }} ({{ $item->kode_inventaris }}) - {{ $item->kategori ?? 'N/A' }}
                            </option>
                        @endforeach
                    </select>
                    @error('inventaris_id')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4" x-show="itemType === 'habis_pakai'">
                     <p class="text-gray-700 text-sm font-bold mb-2">Stok Tersedia (Habis Pakai):
                          <span x-text="currentStock" class="font-normal"></span>
                     </p>
                </div>
                 <div class="mb-4" x-show="itemType === 'tidak_habis_pakai' || itemType === 'aset_tetap'">
                     <p class="text-gray-700 text-sm font-bold mb-2">Jumlah Kondisi Baik:
                          <span x-text="currentStock" class="font-normal"></span>
                     </p>
                </div>

                <div class="mb-4">
                    <label for="jumlah" class="block text-gray-700 text-sm font-bold mb-2">Jumlah:</label>
                    <input type="number" name="jumlah" id="jumlah" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('jumlah') border-red-500 @enderror" value="{{ old('jumlah') }}" required min="1">
                    @error('jumlah')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="tanggal_request" class="block text-gray-700 text-sm font-bold mb-2">Tanggal Permintaan:</label>
                    <input type="date" name="tanggal_request" id="tanggal_request" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('tanggal_request') border-red-500 @enderror" value="{{ old('tanggal_request', date('Y-m-d')) }}" required>
                    @error('tanggal_request')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        Kirim Permintaan
                    </button>
                    <a href="{{ route('requests.index') }}" class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
