@extends('dashboard')

@section('content')
    <div class="max-w-7xl mx-auto">
        <h1 class="text-3xl font-bold leading-tight text-gray-900 mb-8">User Details: {{ $user->name }}</h1>

        <div class="bg-white shadow-md rounded-lg p-6">
            <div class="mb-4">
                <p class="text-gray-700 text-sm font-bold">Name:</p>
                <p class="text-gray-900">{{ $user->name }}</p>
            </div>
            <div class="mb-4">
                <p class="text-gray-700 text-sm font-bold">Email:</p>
                <p class="text-gray-900">{{ $user->email }}</p>
            </div>
            <div class="mb-4">
                <p class="text-gray-700 text-sm font-bold">Email Verified At:</p>
                <p class="text-gray-900">{{ $user->email_verified_at ?? 'N/A' }}</p>
            </div>
            <div class="mb-4">
                <p class="text-gray-700 text-sm font-bold">Created At:</p>
                <p class="text-gray-900">{{ $user->created_at }}</p>
            </div>
            <div class="mb-4">
                <p class="text-gray-700 text-sm font-bold">Last Updated At:</p>
                <p class="text-gray-900">{{ $user->updated_at }}</p>
            </div>

            <div class="flex items-center justify-between mt-6">
                <a href="{{ route('users.edit', $user->id) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Edit User
                </a>
                <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="inline-block">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" onclick="return confirm('Are you sure you want to delete this user?')">
                        Delete User
                    </button>
                </form>
                <a href="{{ route('users.index') }}" class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">
                    Back to Users
                </a>
            </div>
        </div>
    </div>
@endsection
