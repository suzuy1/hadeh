@extends('dashboard')

@section('content')
<h1 class="text-3xl font-bold text-slate-800 mb-6">Dasbor</h1>

<!-- Stat Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white p-6 rounded-xl shadow-md flex items-center justify-between">
        <div>
            <div class="text-sm font-medium text-gray-500">Total Inventaris</div>
            <div class="text-3xl font-bold text-slate-800 mt-1">{{ $totalInventaris }}</div>
        </div>
        <div class="bg-blue-100 text-blue-600 p-3 rounded-full">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
        </div>
    </div>
    <div class="bg-white p-6 rounded-xl shadow-md flex items-center justify-between">
        <div>
            <div class="text-sm font-medium text-gray-500">Jumlah Ruangan</div>
            <div class="text-3xl font-bold text-slate-800 mt-1">{{ $totalRooms }}</div>
        </div>
        <div class="bg-green-100 text-green-600 p-3 rounded-full">
            <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3m0 0-3-3m3 3H9" /></svg>
        </div>
    </div>
    <div class="bg-white p-6 rounded-xl shadow-md flex items-center justify-between">
        <div>
            <div class="text-sm font-medium text-gray-500">Jumlah Unit</div>
            <div class="text-3xl font-bold text-slate-800 mt-1">{{ $totalUnits }}</div>
        </div>
        <div class="bg-yellow-100 text-yellow-600 p-3 rounded-full">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M15 21a6 6 0 00-9-5.197M15 11a4 4 0 110-5.292M12 4.354a4 4 0 010 5.292"></path></svg>
        </div>
    </div>
    <div class="bg-white p-6 rounded-xl shadow-md flex items-center justify-between">
        <div>
            <div class="text-sm font-medium text-gray-500">Permintaan Pending</div>
            <div class="text-3xl font-bold text-slate-800 mt-1">{{ $pendingRequests }}</div>
        </div>
        <div class="bg-indigo-100 text-indigo-600 p-3 rounded-full">
            <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" /></svg>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <div class="bg-white shadow-md rounded-xl p-6">
        <h2 class="text-xl font-bold text-slate-800 mb-4">Inventaris Stok Rendah (Habis Pakai)</h2>
        @if ($lowStockItems->isEmpty())
            <p class="text-gray-600">Tidak ada inventaris dengan stok rendah.</p>
        @else
            <ul class="divide-y divide-gray-200">
                @foreach ($lowStockItems as $item)
                    <li class="py-3 flex justify-between items-center">
                        <div>
                            <p class="text-gray-800 font-medium">{{ $item->nama_barang }} ({{ $item->kode_inventaris }}) - {{ $item->kategori ?? 'N/A' }}</p>
                            <p class="text-sm text-gray-500">Stok: {{ $item->total_sisa_stok }}</p>
                        </div>
                        <a href="{{ route('inventaris.show', $item) }}" class="text-blue-600 hover:text-blue-900 text-sm">Lihat Detail</a>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

    <div class="bg-white shadow-md rounded-xl p-6">
        <h2 class="text-xl font-bold text-slate-800 mb-4">Transaksi Terbaru</h2>
        @if ($recentTransactions->isEmpty())
            <p class="text-gray-600">Tidak ada transaksi terbaru.</p>
        @else
            <ul class="divide-y divide-gray-200">
                @foreach ($recentTransactions as $transaction)
                    <li class="py-3">
                        <p class="text-gray-800 font-medium">{{ $transaction->jenis }} - {{ $transaction->item->nama_barang ?? 'N/A' }} ({{ $transaction->item->kode_inventaris ?? 'N/A' }}) - {{ $transaction->item->kategori ?? 'N/A' }} ({{ $transaction->jumlah }})</p>
                        <p class="text-sm text-gray-500">Oleh: {{ $transaction->user->name ?? 'N/A' }} pada {{ $transaction->tanggal }}</p>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</div>

<div class="bg-white shadow-md rounded-xl p-6">
    <h2 class="text-xl font-bold text-slate-800 mb-4">Selamat Datang di Dasbor Inventaris Anda!</h2>
    <p class="text-gray-600">Dari sini, Anda dapat mengelola seluruh inventaris kampus Anda. Gunakan navigasi di sebelah kiri untuk mengakses berbagai modul.</p>
</div>
@endsection
