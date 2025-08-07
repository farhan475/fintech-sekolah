@extends('layouts.bank') {{-- Kita bisa menggunakan layout admin yang sama --}}

@section('content')
<h1 class="text-3xl font-bold text-gray-800 mb-6">Dashboard Bank Mini</h1>

<p class="text-gray-600 mb-8">Selamat datang, {{ Auth::user()->nama }}. Silakan pilih menu di bawah untuk memulai.</p>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <!-- Menu Top Up -->
    <a href="{{ route('bank.topup.create') }}" class="block bg-white rounded-lg shadow-md p-6 hover:bg-gray-50 transition">
        <div class="flex items-center">
            <i class="fas fa-wallet text-4xl text-green-500"></i>
            <div class="ml-4">
                <h2 class="text-xl font-bold text-gray-900">Top-up Saldo</h2>
                <p class="text-gray-600">Isi saldo untuk siswa.</p>
            </div>
        </div>
    </a>

    <!-- Menu Tarik Tunai -->
    <a href="{{ route('bank.withdrawal.create') }}" class="block bg-white rounded-lg shadow-md p-6 hover:bg-gray-50 transition">
        <div class="flex items-center">
            <i class="fas fa-money-bill-wave text-4xl text-blue-500"></i>
            <div class="ml-4">
                <h2 class="text-xl font-bold text-gray-900">Tarik Tunai</h2>
                <p class="text-gray-600">Lakukan penarikan tunai untuk siswa.</p>
            </div>
        </div>
    </a>

    <!-- Menu Laporan -->
    <a href="{{ route('bank.laporan.index') }}" class="block bg-white rounded-lg shadow-md p-6 hover:bg-gray-50 transition">
        <div class="flex items-center">
            <i class="fas fa-chart-line text-4xl text-yellow-500"></i>
            <div class="ml-4">
                <h2 class="text-xl font-bold text-gray-900">Laporan Harian</h2>
                <p class="text-gray-600">Lihat riwayat transaksi hari ini.</p>
            </div>
        </div>
    </a>
</div>
@endsection