@extends('dashboard')

@section('content')
    <div class="max-w-7xl mx-auto">
        <h1 class="text-3xl font-bold leading-tight text-gray-900 mb-8">Detail Unit: {{ $unit->nama_unit }}</h1>

        <div class="bg-white shadow-md rounded-lg p-6">
            <div class="mb-4">
                <p class="text-gray-700 text-sm font-bold">Nama Unit:</p>
                <p class="text-gray-900">{{ $unit->nama_unit }}</p>
            </div>
            <div class="mb-4">
                <p class="text-gray-700 text-sm font-bold">Dibuat Pada:</p>
                <p class="text-gray-900">{{ $unit->created_at }}</p>
            </div>
            <div class="mb-4">
                <p class="text-gray-700 text-sm font-bold">Terakhir Diperbarui Pada:</p>
                <p class="text-gray-900">{{ $unit->updated_at }}</p>
            </div>

            <div class="flex items-center justify-between mt-6">
                    <a href="{{ route('units.edit', $unit->id) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        Edit Unit
                    </a>
                <form action="{{ route('units.destroy', $unit->id) }}" method="POST" class="inline-block">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" onclick="return confirm('Apakah Anda yakin ingin menghapus unit ini?')">
                        Hapus Unit
                    </button>
                </form>
                <a href="{{ route('units.index') }}" class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">
                    Kembali ke Daftar Unit
                </a>
            </div>
        </div>
    </div>
@endsection
