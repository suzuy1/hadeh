<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Dasbor NSOC</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        .animated-fade-in {
            animation: fadeIn 0.8s ease-out forwards;
        }

        /* Styling untuk animasi cahaya */
        .light {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            box-shadow: 0 0 20px rgba(255, 255, 255, 0.1), 0 0 40px rgba(255, 255, 255, 0.1);
            animation: moveLight 15s linear infinite;
        }

        .x1 { width: 200px; height: 200px; top: 20%; left: 15%; animation-duration: 12s; }
        .x2 { width: 150px; height: 150px; top: 60%; left: 70%; animation-duration: 15s; animation-delay: -2s; }
        .x3 { width: 50px; height: 50px; top: 80%; left: 10%; animation-duration: 18s; animation-delay: -5s; }
        .x4 { width: 100px; height: 100px; top: 10%; left: 80%; animation-duration: 10s; animation-delay: -1s; }
        .x5 { width: 80px; height: 80px; top: 40%; left: 40%; animation-duration: 20s; animation-delay: -8s; }

        @keyframes moveLight {
            0% { transform: translate(0, 0) scale(1); opacity: 0.5; }
            25% { transform: translate(40px, -60px) scale(0.8); opacity: 0.3; }
            50% { transform: translate(-30px, 50px) scale(1.1); opacity: 0.6; }
            75% { transform: translate(50px, 20px) scale(0.9); opacity: 0.4; }
            100% { transform: translate(0, 0) scale(1); opacity: 0.5; }
        }
    </style>
</head>
<body class="bg-gray-100">

    <!-- Kontainer Utama -->
    <div class="flex flex-col md:flex-row bg-white w-screen h-screen overflow-hidden animated-fade-in">

        <!-- Panel Kiri (Ilustrasi Animasi) -->
        <div class="hidden md:flex w-full md:w-1/2 p-8 sm:p-12 flex-col justify-center items-center text-center bg-gradient-to-br from-blue-800 to-blue-900 text-white relative overflow-hidden">
            <!-- Animasi Cahaya Latar Belakang -->
            <div class="absolute top-0 left-0 w-full h-full">
                <div class="light x1"></div>
                <div class="light x2"></div>
                <div class="light x3"></div>
                <div class="light x4"></div>
                <div class="light x5"></div>
            </div>
             <!-- Konten Teks -->
            <div class="max-w-sm relative z-10">
                <h2 class="text-3xl font-bold">Selamat Datang di Inventaris Kampus</h2>
                <p class="text-blue-200 mt-4">Kelola dan lacak semua aset universitas dengan mudah.</p>
            </div>
        </div>

        <!-- Panel Kanan (Form Login) -->
        <div class="w-full md:w-1/2 p-8 sm:p-12 flex flex-col justify-center">
             <!-- Logo & Judul -->
            <div class="text-center mb-8">
                <img src="logo/ubbg.jpg" alt="Logo Universitas" class="w-16 h-16 mx-auto mb-3 rounded-full">
                <h1 class="text-2xl font-bold text-gray-800">Universitas Bina Bangsa Getsempena</h1>
                <p class="text-gray-500 mt-1">Masuk ke akun Anda</p>
            </div>
            
            <!-- Form Login -->
            <form action="{{ route('login.post') }}" method="POST">
                @csrf
                <div class="mb-5">
                    <label for="email" class="block text-gray-700 text-sm font-medium mb-2">Email Anda</label>
                    <input type="email" id="email" name="email" class="bg-gray-50 border border-gray-300 text-gray-900 placeholder-gray-400 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-3 transition duration-300" placeholder="Masukkan email Anda" required>
                </div>
                <div class="mb-5">
                    <label for="password" class="block text-gray-700 text-sm font-medium mb-2">Kata Sandi</label>
                    <input type="password" id="password" name="password" class="bg-gray-50 border border-gray-300 text-gray-900 placeholder-gray-400 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-3 transition duration-300" placeholder="Masukkan kata sandi Anda" required>
                </div>
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center">
                        <input id="remember" type="checkbox" value="" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                        <label for="remember" class="ml-2 text-sm font-medium text-gray-600">Ingat saya</label>
                    </div>
                    <a href="#" class="text-sm text-blue-600 hover:text-blue-800 transition duration-300">Lupa kata sandi</a>
                </div>
                <div>
                    <button type="submit" class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-3 text-center transition duration-300 transform hover:scale-105">MASUK</button>
                </div>
    
                <div class="mt-6 text-center">
                    <p class="text-sm text-gray-600">
                        Belum punya akun? 
                        <a href="#" class="font-medium text-blue-600 hover:text-blue-800">
                            Daftar
                        </a>
                    </p>
                </div>
            </form>
        </div>
    </div>

</body>
</html>

