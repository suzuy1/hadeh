@extends('dashboard')

@section('content')
    <div class="max-w-7xl mx-auto">
        <h1 class="text-3xl font-bold leading-tight text-gray-900 mb-8">Acquisition Details</h1>

        <div class="bg-white shadow-md rounded-lg p-6">
            <div class="mb-4">
                <p class="text-gray-700 text-sm font-bold">Item Name:</p>
                <p class="text-gray-900">{{ $acquisition->item->name ?? 'N/A' }}</p>
            </div>
            <div class="mb-4">
                <p class="text-gray-700 text-sm font-bold">Quantity:</p>
                <p class="text-gray-900">{{ $acquisition->quantity }}</p>
            </div>
            <div class="mb-4">
                <p class="text-gray-700 text-sm font-bold">Acquisition Date:</p>
                <p class="text-gray-900">{{ $acquisition->acquisition_date }}</p>
            </div>
            <div class="mb-4">
                <p class="text-gray-700 text-sm font-bold">Source:</p>
                <p class="text-gray-900">{{ $acquisition->source }}</p>
            </div>
            <div class="mb-4">
                <p class="text-gray-700 text-sm font-bold">Price:</p>
                <p class="text-gray-900">{{ $acquisition->price ? 'Rp ' . number_format($acquisition->price, 2, ',', '.') : 'N/A' }}</p>
            </div>
            <div class="mb-4">
                <p class="text-gray-700 text-sm font-bold">Notes:</p>
                <p class="text-gray-900">{{ $acquisition->notes ?? 'N/A' }}</p>
            </div>
            <div class="mb-4">
                <p class="text-gray-700 text-sm font-bold">Recorded By:</p>
                <p class="text-gray-900">{{ $acquisition->user->name ?? 'N/A' }}</p>
            </div>
            <div class="mb-4">
                <p class="text-gray-700 text-sm font-bold">Created At:</p>
                <p class="text-gray-900">{{ $acquisition->created_at }}</p>
            </div>
            <div class="mb-4">
                <p class="text-gray-700 text-sm font-bold">Last Updated At:</p>
                <p class="text-gray-900">{{ $acquisition->updated_at }}</p>
            </div>

            <div class="flex items-center justify-between mt-6">
                <a href="{{ route('acquisitions.edit', $acquisition->id) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Edit Acquisition
                </a>
                <form action="{{ route('acquisitions.destroy', $acquisition->id) }}" method="POST" class="inline-block">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" onclick="return confirm('Are you sure you want to delete this acquisition?')">
                        Delete Acquisition
                    </button>
                </form>
                <a href="{{ route('acquisitions.index') }}" class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">
                    Back to Acquisitions
                </a>
            </div>
        </div>
    </div>
@endsection
