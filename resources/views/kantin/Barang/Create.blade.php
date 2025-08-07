@extends('layouts.kantin')
@section('content')
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Tambah Barang Baru</h1>
    <div class="bg-white shadow-md rounded-lg p-6">
        <form action="{{ route('kantin.barang.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-4">
                <label for="nama_barang" class="block text-gray-700 mb-2">Nama Barang</label>
                <input type="text" name="nama_barang" id="nama_barang" class="w-full p-2 border rounded" required>
            </div>
            <div class="mb-4">
                <label for="harga" class="block text-gray-700 mb-2">Harga</label>
                <input type="number" name="harga" id="harga" class="w-full p-2 border rounded" required>
            </div>
            <div class="mb-4">
                <label for="stok" class="block text-gray-700 mb-2">Stok</label>
                <input type="number" name="stok" id="stok" class="w-full p-2 border rounded" required>
            </div>
            <div class="mb-4">
                <label for="gambar" class="block text-gray-700 font-semibold mb-2">Gambar Barang (Opsional)</label>
                <input type="file" name="gambar" id="gambar" class="w-full p-2 border rounded-lg file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                @error('gambar') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex justify-end">
                <a href="{{ route('kantin.barang.index') }}" class="bg-gray-500 text-white py-2 px-4 rounded mr-2">Batal</a>
                <button type="submit" class="bg-blue-600 text-white py-2 px-4 rounded">Simpan</button>
            </div>
        </form>
    </div>
@endsection