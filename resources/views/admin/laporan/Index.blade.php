@extends('layouts.admin')

@section('content')
<h1 class="text-3xl font-bold text-gray-800 mb-6">Laporan Harian</h1>

<div class="bg-white shadow-md rounded-lg p-4 mb-6">
    <form action="{{ route('admin.laporan.index') }}" method="GET" class="flex items-center space-x-4">
        <label for="tanggal" class="font-semibold text-gray-700">Pilih Tanggal Laporan:</label>
        <input type="date" id="tanggal" name="tanggal" value="{{ $selectedDate }}" class="border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            Tampilkan Laporan
        </button>
    </form>
</div>

<div class="mb-8">
    <h2 class="text-2xl font-semibold text-gray-800 mb-4">Ringkasan untuk Tanggal: {{ \Carbon\Carbon::parse($selectedDate)->format('d F Y') }}</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-green-100 p-6 rounded-lg shadow">
            <p class="text-sm font-semibold text-green-800 uppercase">Total Top-up</p>
            <p class="text-2xl font-bold text-green-900 mt-1">Rp {{ number_format($totalTopup, 2, ',', '.') }}</p>
        </div>
        <div class="bg-red-100 p-6 rounded-lg shadow">
            <p class="text-sm font-semibold text-red-800 uppercase">Total Penarikan</p>
            <p class="text-2xl font-bold text-red-900 mt-1">Rp {{ number_format($totalWithdrawal, 2, ',', '.') }}</p>
        </div>
        <div class="bg-yellow-100 p-6 rounded-lg shadow">
            <p class="text-sm font-semibold text-yellow-800 uppercase">Total Belanja</p>
            <p class="text-2xl font-bold text-yellow-900 mt-1">Rp {{ number_format($totalTransaksi, 2, ',', '.') }}</p>
        </div>
        <div class="bg-indigo-100 p-6 rounded-lg shadow">
            <p class="text-sm font-semibold text-indigo-800 uppercase">Jumlah Transaksi</p>
            <p class="text-2xl font-bold text-indigo-900 mt-1">{{ $jumlahTotalTransaksi }}</p>
        </div>
    </div>
</div>

<div class="bg-white shadow-md rounded-lg overflow-x-auto">
    <table class="min-w-full leading-normal">
        <thead>
            <tr>
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Waktu</th>
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nama Pengguna</th>
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Jenis Transaksi</th>
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($laporans as $laporan)
                <tr>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">{{ $laporan->created_at->format('H:i:s') }}</td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">{{ $laporan->user->nama ?? 'Pengguna Dihapus' }}</td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">{{ ucfirst($laporan->jenis_transaksi) }}</td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-right">Rp {{ number_format($laporan->jumlah, 2, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-center">
                        Tidak ada transaksi pada tanggal yang dipilih.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection