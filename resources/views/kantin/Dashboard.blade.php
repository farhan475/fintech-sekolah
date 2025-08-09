@extends('layouts.kantin')
@section('title', 'Dashboard Kantin')

@section('content')
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Dashboard</h1>

    <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 mb-8" role="alert">
        <p class="font-bold">Selamat Datang!</p>
        <p>Anda login sebagai Kantin, {{ Auth::user()->nama }}. Silakan kelola barang dagangan dan transaksi Anda di sini.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <a href="{{ route('kantin.barang.index') }}" class="block bg-white rounded-lg shadow-lg p-6 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
            <div class="flex items-center justify-center mb-4">
                <div class="bg-sky-100 p-4 rounded-full">
                    <i class="fas fa-box-open text-3xl text-sky-600"></i>
                </div>
            </div>
            <h2 class="text-xl text-center font-bold text-gray-800">Manajemen Barang</h2>
            <p class="text-gray-600 text-center mt-2">Tambah, lihat, edit, dan hapus barang dagangan Anda.</p>
        </a>
            <a href="{{ route('kantin.transaksi.create') }}" class="block bg-white rounded-lg shadow-lg p-6 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                <div class="flex items-center justify-center mb-4">
                    <div class="bg-green-100 p-4 rounded-full">
                        <i class="fas fa-cash-register text-3xl text-green-600"></i>
                    </div>
                </div>
                    <h2 class="text-xl text-center font-bold text-gray-800">Transaksi Penjualan</h2>
                        <p class="text-gray-600 text-center mt-2">Lakukan transaksi penjualan barang kepada siswa.</p>
</a>

        <a href="{{ route('kantin.laporan.index') }}" class="block bg-white rounded-lg shadow-lg p-6 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
    <div class="flex items-center justify-center mb-4">
        <div class="bg-amber-100 p-4 rounded-full">
            <i class="fas fa-chart-line text-3xl text-amber-600"></i>
        </div>
    </div>
    <h2 class="text-xl text-center font-bold text-gray-800">Laporan Harian</h2>
    <p class="text-gray-600 text-center mt-2">Lihat riwayat penjualan dan pendapatan harian Anda.</p>
</a>
@endsection