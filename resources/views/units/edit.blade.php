@extends('dashboard')

@section('content')
    <div class="max-w-7xl mx-auto">
        <h1 class="text-3xl font-bold leading-tight text-gray-900 mb-8">Edit Unit: {{ $unit->nama_unit }}</h1>

        <div class="bg-white shadow-md rounded-lg p-6">
            <form action="{{ route('units.update', $unit->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="nama_unit" class="block text-gray-700 text-sm font-bold mb-2">Nama Unit:</label>
                    <input type="text" name="nama_unit" id="nama_unit" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('nama_unit') border-red-500 @enderror" value="{{ old('nama_unit', $unit->nama_unit) }}" required>
                    @error('nama_unit')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        Perbarui Unit
                    </button>
                    <a href="{{ route('units.index') }}" class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
