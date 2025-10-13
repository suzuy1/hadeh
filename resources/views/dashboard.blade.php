<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dasbor - Inventaris Kampus</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animated-fade-in {
            animation: fadeIn 0.5s ease-out forwards;
        }
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        ::-webkit-scrollbar-thumb {
            background: #94a3b8;
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #64748b;
        }
    </style>
</head>
<body class="bg-gray-100 font-sans antialiased">
    <div class="flex h-screen bg-gray-100">
        <!-- Sidebar -->
        <div class="w-64 bg-gray-800 text-gray-200 flex flex-col animated-fade-in shadow-lg">
            <div class="flex items-center justify-center h-16 bg-gray-900">
                <img src="{{ asset('logo/ubbg.jpg') }}" alt="Logo Universitas" class="w-8 h-8 mr-2 rounded-full">
                <span class="text-xl font-semibold text-white">Inventaris</span>
            </div>
            <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
                <a href="{{ route('dashboard') }}" class="flex items-center py-2 px-3 rounded-md transition-colors duration-200 {{ request()->routeIs('dashboard') ? 'bg-indigo-700 text-white shadow-md' : 'hover:bg-gray-700 hover:text-white' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    Dasbor
                </a>
                <div x-data="{ open: {{ request()->routeIs('inventaris.*') ? 'true' : 'false' }} }">
                    <button @click="open = !open" class="w-full flex items-center py-2 px-3 rounded-md transition-colors duration-200 {{ request()->routeIs('inventaris.*') ? 'bg-indigo-700 text-white shadow-md' : 'hover:bg-gray-700 hover:text-white' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
                        <span class="flex-1 text-left">Barang</span>
                        <svg :class="{'rotate-90': open}" class="w-4 h-4 ml-auto transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </button>
                    <ul x-show="open" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="pl-8 mt-1 space-y-1 text-sm">
                        <li>
                            <a href="{{ route('inventaris.index', ['kategori' => 'tidak_habis_pakai']) }}" class="flex items-center py-2 px-3 rounded-md transition-colors duration-200 {{ request('kategori') == 'tidak_habis_pakai' ? 'bg-gray-700 text-white' : 'hover:bg-gray-700 hover:text-white' }}">
                                Barang Tidak Habis Pakai
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('inventaris.index', ['kategori' => 'habis_pakai']) }}" class="flex items-center py-2 px-3 rounded-md transition-colors duration-200 {{ request('kategori') == 'habis_pakai' ? 'bg-gray-700 text-white' : 'hover:bg-gray-700 hover:text-white' }}">
                                Barang Habis Pakai
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('inventaris.index', ['kategori' => 'aset_tetap']) }}" class="flex items-center py-2 px-3 rounded-md transition-colors duration-200 {{ request('kategori') == 'aset_tetap' ? 'bg-gray-700 text-white' : 'hover:bg-gray-700 hover:text-white' }}">
                                Aset Tetap
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('inventaris.index') }}" class="flex items-center py-2 px-3 rounded-md transition-colors duration-200 {{ request()->routeIs('inventaris.index') && !request('kategori') ? 'bg-gray-700 text-white' : 'hover:bg-gray-700 hover:text-white' }}">
                                Semua Barang
                            </a>
                        </li>
                    </ul>
                </div>
                <a href="{{ route('acquisitions.index') }}" class="flex items-center py-2 px-3 rounded-md transition-colors duration-200 {{ request()->routeIs('acquisitions.*') ? 'bg-indigo-700 text-white shadow-md' : 'hover:bg-gray-700 hover:text-white' }}">
                    <svg class="w-5 h-5 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182.553-.44 1.282-.659 2.003-.659c.768 0 1.536.219 2.121.659L15 9.182" /></svg>
                    Akuisisi
                </a>
                <a href="{{ route('rooms.index') }}" class="flex items-center py-2 px-3 rounded-md transition-colors duration-200 {{ request()->routeIs('rooms.*') ? 'bg-indigo-700 text-white shadow-md' : 'hover:bg-gray-700 hover:text-white' }}">
                   <svg class="w-5 h-5 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" /></svg>
                    Ruangan
                </a>
                <a href="{{ route('units.index') }}" class="flex items-center py-2 px-3 rounded-md transition-colors duration-200 {{ request()->routeIs('units.*') ? 'bg-indigo-700 text-white shadow-md' : 'hover:bg-gray-700 hover:text-white' }}">
                    <svg class="w-5 h-5 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m-1.5 3h1.5M12 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m-1.5 3h1.5M15 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m-1.5 3h1.5M18 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m-1.5 3h1.5" /></svg>
                    Unit
                </a>
                <a href="{{ route('users.index') }}" class="flex items-center py-2 px-3 rounded-md transition-colors duration-200 {{ request()->routeIs('users.*') ? 'bg-indigo-700 text-white shadow-md' : 'hover:bg-gray-700 hover:text-white' }}">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M15 21a6 6 0 00-9-5.197M15 11a4 4 0 110-5.292M12 4.354a4 4 0 010 5.292"></path></svg>
                    Pengguna
                </a>
                <a href="#" class="flex items-center py-2 px-3 rounded-md transition-colors duration-200 {{ request()->routeIs('settings.*') ? 'bg-indigo-700 text-white shadow-md' : 'hover:bg-gray-700 hover:text-white' }}">
                   <svg class="w-5 h-5 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.242 1.417l-1.072.932c-.067.058-.12.13-.157.208-.037.077-.05.163-.05.252 0 .089.013.175.05.252.037.078.09.15.157.208l1.072.932a1.125 1.125 0 0 1 .242 1.417l-1.296 2.247a1.125 1.125 0 0 1-1.37.49l-1.217-.456c-.355-.133-.75-.072-1.075.124a6.57 6.57 0 0 1-.22.127c-.332.183-.582.495-.645.87l-.213 1.281c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.063-.374-.313-.686-.645-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.37-.49l-1.296-2.247a1.125 1.125 0 0 1 .242-1.417l1.072-.932c.067-.058.12-.13.157-.208.037-.077-.05.163-.05-.252 0-.089-.013-.175-.05-.252-.037-.078-.09-.15-.157-.208l-1.072-.932a1.125 1.125 0 0 1-.242-1.417l1.296-2.247a1.125 1.125 0 0 1 1.37-.49l1.217.456c.355.133.75.072 1.076-.124.072-.044.146-.087.22-.127.332-.183.582-.495-.645-.87l.213-1.281Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /></svg>
                    Pengaturan
                </a>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <header class="flex items-center justify-between px-6 py-4 bg-white border-b border-gray-200 shadow-sm sticky top-0 z-20">
                <div class="flex items-center">
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </span>
                        <input type="text" class="w-full md:w-80 pl-10 pr-4 py-2 border border-gray-300 rounded-lg bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 ease-in-out" placeholder="Cari barang...">
                    </div>
                </div>

                <div class="flex items-center space-x-4">
                    <button class="relative p-2 text-gray-600 hover:text-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 rounded-full transition duration-150 ease-in-out">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                        <span class="absolute top-1 right-1 block h-2.5 w-2.5 rounded-full bg-red-500 ring-2 ring-white"></span>
                    </button>

                    <div class="relative flex items-center space-x-2">
                        <img class="w-9 h-9 rounded-full object-cover border-2 border-gray-300" src="https://placehold.co/40x40/E2E8F0/475569?text=A" alt="Avatar">
                        <div class="hidden md:block text-left">
                            <div class="font-medium text-sm text-gray-800">Admin</div>
                            <div class="text-xs text-gray-500">Administrator</div>
                        </div>
                    </div>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition duration-150 ease-in-out" title="Keluar">
                           <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15M12 9l-3 3m0 0 3 3m-3-3h12.75" /></svg>
                           Keluar
                        </button>
                    </form>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto p-6 md:p-8 bg-gray-100">
                <div class="max-w-7xl mx-auto animated-fade-in">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>
</body>
</html>
