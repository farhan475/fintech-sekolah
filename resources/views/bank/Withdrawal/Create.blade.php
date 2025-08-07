@extends('layouts.bank') {{-- Menggunakan layout khusus bank --}}

@section('content')
<h1 class="text-3xl font-bold text-gray-800 mb-6">Form Penarikan Tunai</h1>

{{-- Notifikasi untuk sukses atau error --}}
@if (session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
        <p>{{ session('success') }}</p>
    </div>
@endif
@if ($errors->any())
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
        <ul class="list-disc list-inside">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<!-- Form Pencarian Siswa -->
<div class="bg-white shadow-md rounded-lg p-6 mb-6">
    <form action="{{ route('bank.withdrawal.create') }}" method="GET">
        <label for="nisn" class="block text-sm font-medium text-gray-700 mb-2">Cari Siswa Berdasarkan NISN:</label>
        <div class="flex">
            <input type="text" id="nisn" name="nisn" class="w-full px-4 py-2 border border-gray-300 rounded-l-lg focus:ring-blue-500 focus:border-blue-500" placeholder="Masukkan NISN..." value="{{ request('nisn') }}" required>
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white font-semibold rounded-r-lg hover:bg-blue-700">
                <i class="fas fa-search"></i> Cari
            </button>
        </div>
    </form>
</div>

<!-- Hasil Pencarian & Form Tarik Tunai (Tampil setelah pencarian) -->
@if(request()->has('nisn'))
    <div class="bg-white shadow-md rounded-lg p-6">
        @if($siswa)
            <h2 class="text-2xl font-semibold mb-4">Detail Siswa</h2>
            <div class="mb-4 text-gray-700 space-y-1">
                <p><strong>Nama:</strong> {{ $siswa->user->nama ?? 'N/A' }}</p> {{-- Mengambil nama dari relasi user --}}
                <p><strong>NISN:</strong> {{ $siswa->nisn }}</p>
                <p><strong>Kelas:</strong> {{ $siswa->kelas }}</p>
                <p><strong>Saldo Saat Ini:</strong> <span class="font-bold text-lg text-green-600">Rp {{ number_format($siswa->saldo, 2, ',', '.') }}</span></p>
            </div>
            <hr class="my-4">
            
            <form action="{{ route('bank.withdrawal.store') }}" method="POST">
                @csrf
                <input type="hidden" name="id_siswa" value="{{ $siswa->id_siswa }}">
                
                <div class="mb-4">
                    <label for="jumlah" class="block text-sm font-medium text-gray-700 mb-2">Jumlah Penarikan:</label>
                    <input type="number" id="jumlah" name="jumlah" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" placeholder="e.g. 20000" required>
                    @error('jumlah') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                
                <button type="submit" class="w-full bg-red-600 text-white font-bold py-2 px-4 rounded-lg hover:bg-red-700 transition">
                    Proses Penarikan
                </button>
            </form>
        @else
            <p class="text-center text-red-500 font-semibold">Siswa dengan NISN "{{ request('nisn') }}" tidak ditemukan.</p>
        @endif
    </div>
@endif
@endsection