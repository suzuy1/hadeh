@extends('dashboard')

@section('content')
    <div class="max-w-7xl mx-auto">
        <h1 class="text-3xl font-bold leading-tight text-gray-900 mb-8">Tambah Transaksi Baru</h1>

        <div class="bg-white shadow-md rounded-lg p-6">
            <form action="{{ route('transactions.store') }}" method="POST" x-data="{
                selectedInventarisId: '{{ old('item_id') }}',
                inventarisData: {{ $inventaris->toJson() }},
                currentStock: 0,
                itemType: '',
                updateStockInfo() {
                    const selectedItem = this.inventarisData.find(item => item.id == this.selectedInventarisId);
                    if (selectedItem) {
                        this.itemType = selectedItem.kategori;
                        if (this.itemType === 'habis_pakai') {
                            // Fetch current stock for consumable item
                            fetch(`/api/inventaris/${selectedItem.id}/stock`)
                                .then(response => response.json())
                                .then(data => {
                                    this.currentStock = data.sisa_stok;
                                })
                                .catch(error => {
                                    console.error('Error fetching stock:', error);
                                    this.currentStock = 'N/A';
                                });
                        } else {
                            this.currentStock = 'Tidak Berlaku';
                        }
                    } else {
                        this.currentStock = 0;
                        this.itemType = '';
                    }
                }
            }" x-init="updateStockInfo()">
                @csrf

                <div class="mb-4">
                    <label for="item_id" class="block text-gray-700 text-sm font-bold mb-2">Inventaris:</label>
                    <select name="item_id" id="item_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('item_id') border-red-500 @enderror" x-model="selectedInventarisId" @change="updateStockInfo()" required>
                        <option value="">Pilih Inventaris</option>
                        @foreach ($inventaris as $item)
                            <option value="{{ $item->id }}" {{ old('item_id') == $item->id ? 'selected' : '' }}>
                                {{ $item->nama_barang }} ({{ $item->kode_inventaris }}) - {{ $item->kategori ?? 'N/A' }}
                            </option>
                        @endforeach
                    </select>
                    @error('item_id')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4" x-show="itemType === 'habis_pakai'">
                    <p class="text-gray-700 text-sm font-bold mb-2">Stok Tersedia (Habis Pakai): <span x-text="currentStock" class="font-normal"></span></p>
                </div>

                <div class="mb-4">
                    <label for="jenis" class="block text-gray-700 text-sm font-bold mb-2">Jenis Transaksi:</label>
                    <select name="jenis" id="jenis" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('jenis') border-red-500 @enderror" required>
                        <option value="">Pilih Jenis Transaksi</option>
                        <option value="penggunaan" {{ old('jenis') == 'penggunaan' ? 'selected' : '' }}>Penggunaan</option>
                        <option value="peminjaman" {{ old('jenis') == 'peminjaman' ? 'selected' : '' }}>Peminjaman</option>
                        <option value="pengembalian" {{ old('jenis') == 'pengembalian' ? 'selected' : '' }}>Pengembalian</option>
                        <option value="mutasi" {{ old('jenis') == 'mutasi' ? 'selected' : '' }}>Mutasi</option>
                    </select>
                    @error('jenis')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="jumlah" class="block text-gray-700 text-sm font-bold mb-2">Jumlah:</label>
                    <input type="number" name="jumlah" id="jumlah" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('jumlah') border-red-500 @enderror" value="{{ old('jumlah') }}" required min="1">
                    @error('jumlah')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="tanggal" class="block text-gray-700 text-sm font-bold mb-2">Tanggal Transaksi:</label>
                    <input type="date" name="tanggal" id="tanggal" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('tanggal') border-red-500 @enderror" value="{{ old('tanggal', date('Y-m-d')) }}" required>
                    @error('tanggal')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="user_id" class="block text-gray-700 text-sm font-bold mb-2">Pengguna:</label>
                    <select name="user_id" id="user_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('user_id') border-red-500 @enderror" required>
                        <option value="">Pilih Pengguna</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                        @endforeach
                    </select>
                    @error('user_id')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="keterangan" class="block text-gray-700 text-sm font-bold mb-2">Keterangan (Opsional):</label>
                    <textarea name="keterangan" id="keterangan" rows="3" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('keterangan') border-red-500 @enderror">{{ old('keterangan') }}</textarea>
                    @error('keterangan')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        Tambah Transaksi
                    </button>
                    <a href="{{ route('transactions.index') }}" class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
