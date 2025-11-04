@extends('dashboard')

@section('content')
    <div class="max-w-7xl mx-auto">
        <h1 class="text-3xl font-bold leading-tight text-gray-900 mb-8">Create New Acquisition</h1>

        <div class="bg-white shadow-md rounded-lg p-6">
            <form action="{{ route('acquisitions.store') }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label for="inventaris_id" class="block text-gray-700 text-sm font-bold mb-2">Inventaris:</label>
                    <select name="inventaris_id" id="inventaris_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                        <option value="">Pilih Barang</option>
                        @foreach($inventarisItems as $item) 
                            <option value="{{ $item->id }}" {{ old('inventaris_id') == $item->id ? 'selected' : '' }}>
                                {{ $item->nama_barang }} ({{ $item->kategori }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label for="quantity" class="block text-gray-700 text-sm font-bold mb-2">Quantity:</label>
                    <input type="number" name="quantity" id="quantity" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('quantity') border-red-500 @enderror" value="{{ old('quantity') }}" required>
                    @error('quantity')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="acquisition_date" class="block text-gray-700 text-sm font-bold mb-2">Acquisition Date:</label>
                    <input type="date" name="acquisition_date" id="acquisition_date" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('acquisition_date') border-red-500 @enderror" value="{{ old('acquisition_date') }}" required>
                    @error('acquisition_date')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="source" class="block text-gray-700 text-sm font-bold mb-2">Source:</label>
                    <input type="text" name="source" id="source" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('source') border-red-500 @enderror" value="{{ old('source') }}" required>
                    @error('source')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="price" class="block text-gray-700 text-sm font-bold mb-2">Price (Optional):</label>
                    <input type="number" step="0.01" name="price" id="price" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('price') border-red-500 @enderror" value="{{ old('price') }}">
                    @error('price')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="notes" class="block text-gray-700 text-sm font-bold mb-2">Notes (Optional):</label>
                    <textarea name="notes" id="notes" rows="3" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('notes') border-red-500 @enderror">{{ old('notes') }}</textarea>
                    @error('notes')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="user_id" class="block text-gray-700 text-sm font-bold mb-2">User (Recorded By):</label>
                    <select name="user_id" id="user_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('user_id') border-red-500 @enderror" required>
                        <option value="">Select a User</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                        @endforeach
                    </select>
                    @error('user_id')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        Create Acquisition
                    </button>
                    <a href="{{ route('acquisitions.index') }}" class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
