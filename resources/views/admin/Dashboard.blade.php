@extends('layouts.admin')

@section('content')
<h1 class="text-3xl font-bold text-gray-800 mb-6">Dashboard Admin</h1>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow-md p-6 flex items-center justify-between">
        <div>
            <p class="text-gray-500 text-sm uppercase font-bold">Total Pengguna</p>
            <p class="text-3xl font-bold text-gray-900 mt-1">{{ $totalPengguna }}</p>
        </div>
        <i class="fas fa-users text-4xl text-blue-500"></i>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6 flex items-center justify-between">
        <div>
            <p class="text-gray-500 text-sm uppercase font-bold">Saldo Terkumpul</p>
            <p class="text-3xl font-bold text-gray-900 mt-1">Rp {{ number_format($totalSaldo, 0, ',', '.') }}</p>
        </div>
        <i class="fas fa-wallet text-4xl text-green-500"></i>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6 flex items-center justify-between">
        <div>
            <p class="text-gray-500 text-sm uppercase font-bold">Transaksi Hari Ini</p>
            <p class="text-3xl font-bold text-gray-900 mt-1">{{ $transaksiHariIni }}</p>
        </div>
        <i class="fas fa-exchange-alt text-4xl text-yellow-500"></i>
    </div>
</div>

<div class="bg-white rounded-lg shadow-md p-6">
    <h2 class="text-2xl font-semibold text-gray-800 mb-4">Aktivitas Terbaru</h2>
    <ul class="list-disc list-inside text-gray-700 space-y-3">
        @forelse ($aktivitasTerbaru as $aktivitas)
            <li>
                <strong>{{ $aktivitas->user->nama ?? 'Pengguna' }}</strong> 
                
                @if($aktivitas->jenis == 'top-up')
                    melakukan top-up sebesar <strong>Rp {{ number_format($aktivitas->jumlah, 0, ',', '.') }}</strong>
                @elseif($aktivitas->jenis == 'pembelian')
                    melakukan pembelian sebesar <strong>Rp {{ number_format($aktivitas->jumlah, 0, ',', '.') }}</strong>
                @elseif($aktivitas->jenis == 'penarikan')
                    menarik tunai sebesar <strong>Rp {{ number_format($aktivitas->jumlah, 0, ',', '.') }}</strong>
                @else
                    melakukan transaksi sebesar <strong>Rp {{ number_format($aktivitas->jumlah, 0, ',', '.') }}</strong>
                @endif
                
                <span class="text-gray-500 text-sm">({{ $aktivitas->created_at->diffForHumans() }})</span>
            </li>
        @empty
            <li class="list-none">Tidak ada aktivitas terbaru.</li>
        @endforelse
    </ul>
</div>
@endsection
