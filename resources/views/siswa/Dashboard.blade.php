@extends('layouts.siswa')

@section('title', 'Dashboard Siswa')

@section('content')
    <!-- Kartu Saldo Utama -->
    <div class="bg-blue-600 text-white rounded-xl shadow-lg p-8 mb-8">
        <p class="text-lg text-blue-200">Saldo Anda Saat Ini</p>
        <p class="text-5xl font-bold tracking-tight mt-1">Rp {{ number_format($siswa->saldo, 0, ',', '.') }}</p>
        <div class="mt-4 text-blue-200">
            <p><strong>Nama:</strong> {{ $siswa->user->nama }}</p>
            <p><strong>NISN:</strong> {{ $siswa->nisn }} | <strong>Kelas:</strong> {{ $siswa->kelas }}</p>
        </div>
    </div>

    {{-- Notifikasi untuk pembelian, ditempatkan di atas agar mudah terlihat --}}
    @if (session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded mb-6" role="alert">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded mb-6" role="alert">
            {{ session('error') }}
        </div>
    @endif

    <!-- Riwayat Transaksi -->
    <div class="mb-12">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">Riwayat Transaksi Terakhir</h2>
        <div class="bg-white shadow-md rounded-lg">
            <ul class="divide-y divide-gray-200">
                @forelse ($riwayat as $item)
                    <li class="p-4 flex items-center justify-between hover:bg-gray-50">
                        <div class="flex items-center">
                            {{-- Ikon --}}
                            @if($item->jenis == 'Top-up')
                                <div class="bg-green-100 p-3 rounded-full mr-4"><i class="fas fa-arrow-up text-green-600"></i></div>
                            @elseif($item->jenis == 'Penarikan')
                                <div class="bg-blue-100 p-3 rounded-full mr-4"><i class="fas fa-arrow-down text-blue-600"></i></div>
                            @else {{-- Pembelian --}}
                                <div class="bg-red-100 p-3 rounded-full mr-4"><i class="fas fa-shopping-cart text-red-600"></i></div>
                            @endif
                            
                            {{-- Deskripsi --}}
                            <div>
                                <p class="font-semibold text-gray-900">{{ $item->jenis }}</p>
                                <p class="text-sm text-gray-500">{{ $item->deskripsi }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            {{-- Jumlah --}}
                            @if($item->jenis == 'Top-up')
                                <p class="font-bold text-green-600">+ Rp {{ number_format($item->jumlah, 0, ',', '.') }}</p>
                            @elseif($item->jenis == 'Penarikan')
                                <p class="font-bold text-blue-600">- Rp {{ number_format($item->jumlah, 0, ',', '.') }}</p>
                            @else {{-- Pembelian --}}
                                <p class="font-bold text-red-600">- Rp {{ number_format($item->total_harga, 0, ',', '.') }}</p>
                            @endif
                            {{-- Tanggal --}}
                            <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($item->tanggal_sort ?? $item->created_at)->format('d M Y') }}</p>
                        </div>
                    </li>
                @empty
                    <li class="p-6 text-center text-gray-500">
                        Belum ada riwayat transaksi.
                    </li>
                @endforelse
            </ul>
        </div>
    </div> {{-- Penutup untuk blok Riwayat Transaksi --}}


    {{-- ========================================================================= --}}
    {{-- PERBAIKAN: Blok Jajan di Kantin sekarang berada di luar blok sebelumnya --}}
    {{-- ========================================================================= --}}

    <!-- Daftar Barang Kantin -->
    <div>
        <h2 class="text-2xl font-bold text-gray-800 mb-4">Jajan di Kantin</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @forelse ($barangs as $barang)
                <div class="bg-white rounded-lg shadow-md overflow-hidden flex flex-col transition-transform duration-300 hover:-translate-y-1">
                    {{-- Gambar Barang --}}
                    @if($barang->gambar)
                    <img src="{{ asset('storage/' . $barang->gambar) }}" alt="{{ $barang->nama_barang }}" class="w-full h-40 object-cover">
                    @else
                    <div class="w-full h-40 bg-gray-200 flex items-center justify-center text-gray-400"><i class="fas fa-image fa-3x"></i></div>
                    @endif

                    {{-- Detail Barang --}}
                    <div class="p-4 flex-grow flex flex-col">
                        <h3 class="font-bold text-lg text-gray-800">{{ $barang->nama_barang }}</h3>
                        <p class="text-sm text-gray-500">Kantin: {{ $barang->kantin->nama ?? 'N/A' }}</p>
                        <p class="text-sm text-gray-500">Stok: {{ $barang->stok }}</p>
                        <div class="mt-2 flex-grow"></div> {{-- Spacer --}}
                        <div class="flex justify-between items-center mt-4">
                            <p class="font-bold text-blue-600 text-lg">Rp {{ number_format($barang->harga, 0, ',', '.') }}</p>
                            
                            {{-- Form Tombol Beli --}}
                            <form action="{{ route('siswa.transaksi.store', $barang->id_barang) }}" method="POST" onsubmit="return confirm('Yakin ingin membeli {{ $barang->nama_barang }}?')">
                                @csrf
                                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-1 px-3 rounded-lg text-sm">
                                    Beli
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <p class="col-span-full text-center text-gray-500 py-10">Saat ini tidak ada barang yang dijual di kantin.</p>
            @endforelse
        </div>
    </div>
@endsection