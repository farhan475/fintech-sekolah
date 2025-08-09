@extends('layouts.bank')

@section('title', 'Laporan Transaksi Bank')

@section('content')
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Laporan Transaksi</h1>

    <div class="bg-white shadow-md rounded-lg p-4 mb-6">
        <form action="{{ route('bank.laporan.index') }}" method="GET" class="flex items-center space-x-4">
            <label for="tanggal" class="font-semibold text-gray-700">Pilih Tanggal Laporan:</label>
            <input type="date" id="tanggal" name="tanggal" value="{{ $selectedDate }}" class="border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Tampilkan
            </button>
        </form>
    </div>

    <div class="mb-8">
        <h2 class="text-2xl font-semibold text-gray-800 mb-4">Ringkasan untuk Tanggal: {{ \Carbon\Carbon::parse($selectedDate)->format('d F Y') }}</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-green-100 p-6 rounded-lg shadow">
                <p class="text-sm font-semibold text-green-800 uppercase">Total Dana Masuk (Top-up)</p>
                <p class="text-2xl font-bold text-green-900 mt-1">Rp {{ number_format($totalTopup, 0, ',', '.') }}</p>
            </div>
            <div class="bg-red-100 p-6 rounded-lg shadow">
                <p class="text-sm font-semibold text-red-800 uppercase">Total Dana Keluar (Penarikan)</p>
                <p class="text-2xl font-bold text-red-900 mt-1">Rp {{ number_format($totalWithdrawal, 0, ',', '.') }}</p>
            </div>
            <div class="bg-indigo-100 p-6 rounded-lg shadow">
                <p class="text-sm font-semibold text-indigo-800 uppercase">Total Transaksi</p>
                <p class="text-2xl font-bold text-indigo-900 mt-1">{{ $jumlahTransaksi }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-x-auto">
        <table class="min-w-full leading-normal">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-5 py-3 border-b-2 text-left text-xs font-semibold uppercase">Nama Siswa</th>
                    <th class="px-5 py-3 border-b-2 text-left text-xs font-semibold uppercase">Jenis Transaksi</th>
                    <th class="px-5 py-3 border-b-2 text-right text-xs font-semibold uppercase">Jumlah</th>
                </tr>
            </thead>
            <tbody class="bg-white">
                @forelse ($laporanGabungan as $item)
                    <tr>
                        <td class="px-5 py-4 border-b border-gray-200">{{ $item->nama_siswa }}</td>
                        <td class="px-5 py-4 border-b border-gray-200">
                            @if($item->status == 'masuk')
                                <span class="px-2 py-1 font-semibold leading-tight text-green-700 bg-green-100 rounded-full text-sm">
                                    {{ $item->jenis }}
                                </span>
                            @else
                                <span class="px-2 py-1 font-semibold leading-tight text-red-700 bg-red-100 rounded-full text-sm">
                                    {{ $item->jenis }}
                                </span>
                            @endif
                        </td>
                        <td class="px-5 py-4 border-b border-gray-200 text-right font-semibold">
                            @if($item->status == 'masuk')
                                <span class="text-green-600">+ Rp {{ number_format($item->jumlah, 0, ',', '.') }}</span>
                            @else
                                <span class="text-red-600">- Rp {{ number_format($item->jumlah, 0, ',', '.') }}</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center py-10 text-gray-500">
                            Tidak ada transaksi pada tanggal yang dipilih.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection