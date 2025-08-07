@extends('layouts.kantin')

@section('title', 'Manajemen Barang')

@section('content')
    {{-- Header Halaman --}}
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Manajemen Barang</h1>
        <a href="{{ route('kantin.barang.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition-colors duration-300">
            + Tambah Barang
        </a>
    </div>

    {{-- Notifikasi Sukses --}}
    @if (session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded mb-6" role="alert">
            <p>{{ session('success') }}</p>
        </div>
    @endif

    {{-- Kontainer Tabel --}}
    <div class="bg-white shadow-md rounded-lg overflow-x-auto">
        <table class="min-w-full leading-normal">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Gambar</th>
                    <th scope="col" class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nama Barang</th>
                    <th scope="col" class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Harga</th>
                    <th scope="col" class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Stok</th>
                    <th scope="col" class="px-5 py-3 border-b-2 border-gray-200 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white">
                @forelse ($barangs as $barang)
                    <tr>
                        <td class="px-5 py-4 border-b border-gray-200 text-sm">
                            @if($barang->gambar)
                                <img src="{{ asset('storage/' . $barang->gambar) }}" alt="{{ $barang->nama_barang }}" class="w-16 h-16 object-cover rounded-md border">
                            @else
                                <div class="w-16 h-16 bg-gray-200 flex items-center justify-center rounded-md text-gray-400">
                                    <i class="fas fa-image fa-2x"></i>
                                </div>
                            @endif
                        </td>
                        <td class="px-5 py-4 border-b border-gray-200 text-sm">
                            <p class="text-gray-900 whitespace-no-wrap">{{ $barang->nama_barang }}</p>
                        </td>
                        <td class="px-5 py-4 border-b border-gray-200 text-sm">
                            <p class="text-gray-900 whitespace-no-wrap">Rp {{ number_format($barang->harga, 0, ',', '.') }}</p>
                        </td>
                        <td class="px-5 py-4 border-b border-gray-200 text-sm">
                            <p class="text-gray-900 whitespace-no-wrap">{{ $barang->stok }}</p>
                        </td>
                        <td class="px-5 py-4 border-b border-gray-200 text-sm text-center">
                            {{-- ======================================================= --}}
                            {{-- INI ADALAH BAGIAN YANG PALING PENTING UNTUK DIPERIKSA --}}
                            {{-- Pastikan $barang->id_barang diteruskan sebagai parameter --}}
                            {{-- ======================================================= --}}
                            <a href="{{ route('kantin.barang.edit', $barang->id_barang) }}" class="text-indigo-600 hover:text-indigo-900 font-semibold mr-4">Edit</a>
                            
                            <form action="{{ route('kantin.barang.destroy', $barang->id_barang) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus barang ini? Data tidak bisa dikembalikan.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 font-semibold">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-10">
                            <p class="text-gray-500">Anda belum menambahkan barang dagangan.</p>
                            <a href="{{ route('kantin.barang.create') }}" class="mt-2 inline-block text-blue-600 hover:underline">Tambah Barang Sekarang</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    {{-- Link Paginasi --}}
    <div class="mt-6">
        {{ $barangs->links() }}
    </div>
@endsection