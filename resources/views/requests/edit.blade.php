@extends('dashboard')

@section('content')
    <div class="max-w-7xl mx-auto">
        <h1 class="text-3xl font-bold leading-tight text-gray-900 mb-8">Edit Permintaan Inventaris: {{ $request->id }}</h1>

        <div class="bg-white shadow-md rounded-lg p-6">
            <form action="{{ route('requests.update', $request->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="item_name" class="block text-gray-700 text-sm font-bold mb-2">Inventaris:</label>
                    <input type="text" id="item_name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="{{ $request->item->nama_barang ?? 'N/A' }} ({{ $request->item->kode_inventaris ?? 'N/A' }}) - {{ $request->item->kategori ?? 'N/A' }}" disabled>
                </div>

                <div class="mb-4">
                    <label for="jumlah" class="block text-gray-700 text-sm font-bold mb-2">Jumlah:</label>
                    <input type="number" id="jumlah" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="{{ $request->jumlah }}" disabled>
                </div>

                <div class="mb-4">
                    <label for="tanggal_request" class="block text-gray-700 text-sm font-bold mb-2">Tanggal Permintaan:</label>
                    <input type="date" id="tanggal_request" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="{{ $request->tanggal_request }}" disabled>
                </div>

                <div class="mb-4">
                    <label for="requester_name" class="block text-gray-700 text-sm font-bold mb-2">Pemohon:</label>
                    <input type="text" id="requester_name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="{{ $request->requester->name ?? 'N/A' }}" disabled>
                </div>

                <div class="mb-4">
                    <label for="status" class="block text-gray-700 text-sm font-bold mb-2">Status:</label>
                    <select name="status" id="status" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('status') border-red-500 @enderror" required>
                        <option value="pending" {{ old('status', $request->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="disetujui" {{ old('status', $request->status) == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                        <option value="ditolak" {{ old('status', $request->status) == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                    @error('status')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="approver_id" class="block text-gray-700 text-sm font-bold mb-2">Penyetuju (Opsional):</label>
                    <select name="approver_id" id="approver_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('approver_id') border-red-500 @enderror">
                        <option value="">Pilih Penyetuju</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}" {{ old('approver_id', $request->approver_id) == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                        @endforeach
                    </select>
                    @error('approver_id')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        Perbarui Permintaan
                    </button>
                    <a href="{{ route('requests.index') }}" class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
