<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="antialiased bg-gray-100 text-gray-800">

<!-- NAVBAR -->
<nav class="bg-white border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
        <span class="text-lg font-semibold">
            Telkom University Report
        </span>

        <div class="text-sm font-medium">
            @auth
                <a href="{{ url('/dashboard') }}" class="hover:text-red-600">Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="hover:text-red-600">Login</a>
            @endauth
        </div>
    </div>
</nav>

<!-- HERO SECTION -->
<main class="bg-gray-50">
    <div class="max-w-5xl mx-auto px-6 py-20 text-center">

    <div class="mb-10">
            <img
                src="{{ asset('images/Telkom.png') }}"
                alt="Telkom University"
                class="mx-auto w-full max-w-2xl h-30 md:h-32 object-contain"
            >
        </div>
        <h1 class="text-3xl md:text-4xl font-bold mb-6">
            Sistem Pelaporan Telkom University
        </h1>

        <p class="text-gray-600 max-w-3xl mx-auto mb-10 leading-relaxed">
            Sistem terintegrasi untuk melaporkan fasilitas rusak, mencari barang hilang,
            serta memantau kondisi lalu lintas di lingkungan kampus Telkom University.
            Aplikasi ini dirancang agar proses pelaporan menjadi cepat, transparan,
            dan mudah untuk ditindaklanjuti.
        </p>

        <!-- BUTTON -->
        <div class="flex justify-center gap-4">
            @guest
                <a href="{{ route('login') }}"
                   class="px-6 py-2 bg-red-600 text-white rounded-md text-sm font-medium hover:bg-red-700 transition">
                    Masuk
                </a>

                @if (Route::has('register'))
                    <a href="{{ route('register') }}"
                       class="px-6 py-2 border border-gray-300 rounded-md text-sm font-medium hover:bg-gray-100 transition">
                        Daftar
                    </a>
                @endif
            @else
                <a href="{{ url('/dashboard') }}"
                   class="px-6 py-2 bg-red-600 text-white rounded-md text-sm font-medium hover:bg-red-700 transition">
                    Dashboard
                </a>
            @endguest
        </div>

    </div>
</main>

</body>
</html>
