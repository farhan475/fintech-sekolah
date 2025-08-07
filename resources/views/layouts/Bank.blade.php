<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bank Mini | Fintech Sekolah</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.tailwindcss.com"></script>
    {{-- Jika Anda menggunakan Vite, ganti dengan @vite --}}
</head>
<body class="bg-gray-100 font-sans flex min-h-screen">

    <div class="flex w-full">
        <!-- Sidebar -->
        <div class="bg-gray-800 text-white w-64 min-h-screen p-4 flex flex-col justify-between">
            <div>
                <div class="text-xl font-bold pb-4 border-b border-gray-700 mb-4">Fintech - Bank Mini</div>
                <nav class="space-y-2">
                    <a href="{{ route('bank.dashboard') }}" class="flex items-center p-2 rounded-lg hover:bg-gray-700
                        {{ Request::is('bank/dashboard') ? 'bg-blue-600' : '' }}">
                        <i class="fas fa-fw fa-tachometer-alt mr-2"></i>Dashboard
                    </a>
                    <a href="{{ route('bank.topup.create') }}" class="flex items-center p-2 rounded-lg hover:bg-gray-700
                        {{ Request::is('bank/topup*') ? 'bg-blue-600' : '' }}">
                        <i class="fas fa-fw fa-wallet mr-2"></i>Top-up Saldo
                    </a>
                    <a href="{{ route('bank.withdrawal.create') }}" class="flex items-center p-2 rounded-lg hover:bg-gray-700
                        {{ Request::is('bank/withdrawal*') ? 'bg-blue-600' : '' }}">
                        <i class="fas fa-fw fa-money-bill-wave mr-2"></i>Tarik Tunai
                    </a>
                    <a href="{{ route('bank.laporan.index') }}" class="flex items-center p-2 rounded-lg hover:bg-gray-700
                        {{ Request::is('bank/laporan*') ? 'bg-blue-600' : '' }}">
                        <i class="fas fa-fw fa-chart-line mr-2"></i>Laporan Harian
                    </a>
                </nav>
            </div>

            <div class="mt-auto pt-4 border-t border-gray-700">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center p-2 rounded-lg hover:bg-red-700 bg-red-600 text-white">
                        <i class="fas fa-sign-out-alt mr-2"></i>Logout
                    </button>
                </form>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col">
            <nav class="bg-white shadow-sm py-4 px-6 flex justify-between items-center">
                <h2 class="text-lg font-semibold">Selamat Datang, {{ Auth::user()->nama }}</h2>
                {{-- Anda bisa menambahkan menu dropdown user di sini jika perlu --}}
            </nav>

            <main class="p-6 flex-1">
                @yield('content')
            </main>

            <footer class="bg-white p-4 text-center text-gray-600 border-t border-gray-200">
                © {{ date('Y') }} Fintech Sekolah. All rights reserved.
            </footer>
        </div>
    </div>

    @stack('scripts')
</body>
</html>