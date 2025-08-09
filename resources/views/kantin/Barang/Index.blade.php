@extends('layouts.kantin')
@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Manajemen Barang</h1>
        <a href="{{ route('kantin.barang.create') }}" class="bg-blue-600 text-white font-bold py-2 px-4 rounded hover:bg-blue-700">+ Tambah Barang</a>
    </div>

    @if (session('success'))
        <div class="bg-green-100 text-green-700 p-4 rounded mb-4" role="alert">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white shadow-md rounded-lg overflow-x-auto">
        <table class="min-w-full leading-normal">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold uppercase">Gambar</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold uppercase">Nama Barang</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold uppercase">Harga</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold uppercase">Stok</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 text-center text-xs font-semibold uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($barangs as $barang)
                <tr>
                    <td class="px-5 py-4 border-b border-gray-200 bg-white text-sm">
                        @if($barang->gambar)
                            <img src="{{ asset('storage/' . $barang->gambar) }}" alt="{{ $barang->nama_barang }}" class="w-16 h-16 object-cover rounded">
                        @else
                            <div class="w-16 h-16 bg-gray-200 flex items-center justify-center rounded text-gray-400">
                                <i class="fas fa-image fa-2x"></i>
                            </div>
                        @endif
                    </td>
                    <td class="px-5 py-4 border-b border-gray-200 bg-white text-sm">
                        {{ $barang->nama_barang }}
                    </td>
                    
                    
                    <td class="px-5 py-4 border-b border-gray-200 bg-white text-sm">
                        Rp {{ number_format($barang->harga, 0, ',', '.') }}
                    </td>

                    <td class="px-5 py-4 border-b border-gray-200 bg-white text-sm">
                        {{ $barang->stok }}
                    </td>

                    <td class="px-5 py-4 border-b border-gray-200 bg-white text-sm text-center">
                        <a href="{{ route('kantin.barang.edit', $barang) }}" class="text-yellow-600 hover:text-yellow-900 font-semibold mr-3">Edit</a>
                        <form action="{{ route('kantin.barang.destroy', $barang) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus barang ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900 font-semibold">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-10 text-gray-500">
                        Anda belum menambahkan barang dagangan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="mt-6">
        {{ $barangs->links() }}
    </div>
@endsection