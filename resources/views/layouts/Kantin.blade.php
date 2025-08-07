<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Kantin | Fintech Sekolah')</title>
    {{-- Memuat Font Awesome untuk Ikon --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    {{-- Memuat Tailwind CSS melalui CDN untuk kemudahan. Ganti dengan @vite jika Anda menginstalnya secara lokal. --}}
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans flex min-h-screen">

    <div class="flex w-full">
        <!-- Sidebar Navigasi -->
        <aside class="bg-gray-800 text-white w-64 min-h-screen p-4 flex flex-col justify-between">
            <div>
                <div class="text-xl font-bold pb-4 border-b border-gray-700 mb-4">Fintech - Kantin</div>
                <nav class="space-y-2">
                    <a href="{{ route('kantin.dashboard') }}" class="flex items-center py-2 px-3 rounded-lg hover:bg-gray-700 transition-colors
                        {{ Request::is('kantin/dashboard') ? 'bg-blue-600' : '' }}">
                        <i class="fas fa-fw fa-tachometer-alt mr-3"></i>Dashboard
                    </a>
                    <a href="{{ route('kantin.barang.index') }}" class="flex items-center py-2 px-3 rounded-lg hover:bg-gray-700 transition-colors
                        {{ Request::is('kantin/barang*') ? 'bg-blue-600' : '' }}">
                        <i class="fas fa-fw fa-box-open mr-3"></i>Manajemen Barang
                    </a>
                    {{-- Link untuk fitur berikutnya --}}
                    <a href="{{ route('kantin.transaksi.create') }}" class="flex items-center py-2 px-3 rounded-lg hover:bg-gray-700 transition-colors {{ Request::is('kantin/transaksi*') ? 'bg-blue-600' : '' }}">
                        <i class="fas fa-fw fa-cash-register mr-3"></i>Transaksi
                    </a>
                    <a href="{{ route('kantin.laporan.index') }}" class="flex items-center py-2 px-3 rounded-lg hover:bg-gray-700 transition-colors {{ Request::is('kantin/laporan*') ? 'bg-blue-600' : '' }}">
                        <i class="fas fa-fw fa-chart-line mr-3"></i>Laporan
                    </a>
                </nav>
            </div>

            <!-- Tombol Logout di Bawah -->
            <div class="mt-auto pt-4 border-t border-gray-700">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center p-2 rounded-lg hover:bg-red-700 bg-red-600 text-white">
                        <i class="fas fa-sign-out-alt mr-2"></i>Logout
                    </button>
                </form>
            </div>
        </aside>

        <!-- Konten Utama -->
        <div class="flex-1 flex flex-col">
            <header class="bg-white shadow-sm py-4 px-6 flex justify-end items-center">
                {{-- Bisa ditambahkan menu dropdown user di sini jika perlu --}}
                <div class="text-gray-600">
                    Selamat Datang, <span class="font-semibold">{{ Auth::user()->nama }}</span>
                </div>
            </header>

            <main class="p-6 flex-1">
                @yield('content')
            </main>

            <footer class="bg-white p-4 text-center text-gray-600 text-sm border-t border-gray-200">
                © {{ date('Y') }} Fintech Sekolah. All rights reserved.
            </footer>
        </div>
    </div>

    @stack('scripts')
</body>
</html>