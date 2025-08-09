@extends('layouts.kantin')

@section('title', 'Laporan Harian Kantin')

@section('content')
<h1 class="text-3xl font-bold text-gray-800 mb-6">Laporan Penjualan Harian</h1>

<div class="bg-white shadow-md rounded-lg p-4 mb-6">
    <form action="{{ route('kantin.laporan.index') }}" method="GET" class="flex items-center space-x-4">
        <label for="tanggal" class="font-semibold text-gray-700">Pilih Tanggal Laporan:</label>
        <input type="date" id="tanggal" name="tanggal" value="{{ $selectedDate }}" class="border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            Tampilkan Laporan
        </button>
    </form>
</div>

<div class="mb-8">
    <h2 class="text-2xl font-semibold text-gray-800 mb-4">Ringkasan untuk Tanggal: {{ \Carbon\Carbon::parse($selectedDate)->format('d F Y') }}</h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-green-100 p-6 rounded-lg shadow">
            <p class="text-sm font-semibold text-green-800 uppercase">Total Pendapatan</p>
            <p class="text-2xl font-bold text-green-900 mt-1">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</p>
        </div>
        <div class="bg-sky-100 p-6 rounded-lg shadow">
            <p class="text-sm font-semibold text-sky-800 uppercase">Total Barang Terjual</p>
            <p class="text-2xl font-bold text-sky-900 mt-1">{{ $totalBarangTerjual }} unit</p>
        </div>
        <div class="bg-indigo-100 p-6 rounded-lg shadow">
            <p class="text-sm font-semibold text-indigo-800 uppercase">Jumlah Transaksi</p>
            <p class="text-2xl font-bold text-indigo-900 mt-1">{{ $jumlahTransaksi }}</p>
        </div>
    </div>
</div>

<div class="bg-white shadow-md rounded-lg overflow-x-auto">
    <table class="min-w-full leading-normal">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-5 py-3 border-b-2 text-left text-xs font-semibold uppercase">Waktu</th>
                <th class="px-5 py-3 border-b-2 text-left text-xs font-semibold uppercase">Nama Siswa</th>
                <th class="px-5 py-3 border-b-2 text-left text-xs font-semibold uppercase">Nama Barang</th>
                <th class="px-5 py-3 border-b-2 text-center text-xs font-semibold uppercase">Jml</th>
                <th class="px-5 py-3 border-b-2 text-right text-xs font-semibold uppercase">Total Harga</th>
            </tr>
        </thead>
        <tbody class="bg-white">
            @forelse ($transaksis as $transaksi)
                <tr>
                    <td class="px-5 py-4 border-b">{{ $transaksi->created_at->format('H:i:s') }}</td>
                    <td class="px-5 py-4 border-b">{{ $transaksi->siswa->user->nama ?? 'Siswa Dihapus' }}</td>
                    <td class="px-5 py-4 border-b">{{ $transaksi->barang->nama_barang ?? 'Barang Dihapus' }}</td>
                    <td class="px-5 py-4 border-b text-center">{{ $transaksi->jumlah_barang }}</td>
                    <td class="px-5 py-4 border-b text-right font-semibold">Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center py-10 text-gray-500">
                        Tidak ada transaksi penjualan pada tanggal yang dipilih.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection