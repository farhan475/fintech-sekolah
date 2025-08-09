@extends('layouts.kantin')
@section('content')
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Edit Barang: {{ $barang->nama_barang }}</h1>
    <div class="bg-white shadow-md rounded-lg p-6">
        <form action="{{ route('kantin.barang.update', $barang->id_barang) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label for="nama_barang" class="block text-gray-700 font-semibold mb-2">Nama Barang</label>
                <input type="text" name="nama_barang" id="nama_barang" value="{{ old('nama_barang', $barang->nama_barang) }}" class="w-full p-2 border border-gray-300 rounded" required>
            </div>
            <div class="mb-4">
                <label for="harga" class="block text-gray-700 font-semibold mb-2">Harga</label>
                <input type="number" name="harga" id="harga" value="{{ old('harga', $barang->harga) }}" class="w-full p-2 border border-gray-300 rounded" required>
            </div>
            <div class="mb-4">
                <label for="stok" class="block text-gray-700 font-semibold mb-2">Stok</label>
                <input type="number" name="stok" id="stok" value="{{ old('stok', $barang->stok) }}" class="w-full p-2 border border-gray-300 rounded" required>
            </div>
             <div class="mb-4">
                <label for="gambar" class="block text-gray-700 font-semibold mb-2">Ganti Gambar (Kosongkan jika tidak diubah)</label>
                @if($barang->gambar)
                    <img src="{{ asset('storage/' . $barang->gambar) }}" alt="{{ $barang->nama_barang }}" class="w-32 h-32 object-cover rounded-md mb-2 border">
                @endif
                <input type="file" name="gambar" id="gambar" class="w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none file:mr-4 file:py-2 file:px-4 file:rounded-l-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                @error('gambar') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div class="flex justify-end mt-6">
                <a href="{{ route('kantin.barang.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded mr-2 transition-colors">Batal</a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition-colors">Update Barang</button>
            </div>
        </form>
    </div>
@endsection
