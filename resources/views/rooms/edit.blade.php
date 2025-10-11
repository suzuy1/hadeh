@extends('dashboard')

@section('content')
    <div class="max-w-7xl mx-auto">
        <h1 class="text-3xl font-bold leading-tight text-gray-900 mb-8">Edit Ruangan: {{ $room->nama_ruangan }}</h1>

        <div class="bg-white shadow-md rounded-lg p-6">
            <form action="{{ route('rooms.update', $room->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="nama_ruangan" class="block text-gray-700 text-sm font-bold mb-2">Nama Ruangan:</label>
                    <input type="text" name="nama_ruangan" id="nama_ruangan" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('nama_ruangan') border-red-500 @enderror" value="{{ old('nama_ruangan', $room->nama_ruangan) }}" required>
                    @error('nama_ruangan')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="lokasi" class="block text-gray-700 text-sm font-bold mb-2">Lokasi (Opsional):</label>
                    <input type="text" name="lokasi" id="lokasi" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('lokasi') border-red-500 @enderror" value="{{ old('lokasi', $room->lokasi) }}">
                    @error('lokasi')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="unit_id" class="block text-gray-700 text-sm font-bold mb-2">Unit Kerja/Fakultas:</label>
                    <select name="unit_id" id="unit_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('unit_id') border-red-500 @enderror">
                        <option value="">Pilih Unit</option>
                        @foreach ($units as $unit)
                            <option value="{{ $unit->id }}" {{ old('unit_id', $room->unit_id) == $unit->id ? 'selected' : '' }}>{{ $unit->nama_unit }}</option>
                        @endforeach
                    </select>
                    @error('unit_id')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        Perbarui Ruangan
                    </button>
                    <a href="{{ route('rooms.index') }}" class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
