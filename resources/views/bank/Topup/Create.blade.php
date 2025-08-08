@extends('layouts.bank')

@section('title', 'Top-up Saldo')

@section('content')
<h1 class="text-3xl font-bold text-gray-800 mb-6">Form Top-up Saldo Siswa</h1>

{{-- Notifikasi untuk sukses atau error --}}
@if (session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
        <p>{{ session('success') }}</p>
    </div>
@endif
{{-- Tampilkan juga error validasi --}}
@if ($errors->any())
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
        <p class="font-bold">Terjadi Kesalahan Validasi</p>
        <ul class="list-disc list-inside mt-2">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<!-- Form Pencarian Siswa -->
<div class="bg-white shadow-md rounded-lg p-6 mb-6">
    <form action="{{ route('bank.topup.create') }}" method="GET">
        <label for="nisn" class="block text-sm font-medium text-gray-700 mb-2">Cari Siswa Berdasarkan NISN:</label>
        <div class="flex">
            <input type="text" id="nisn" name="nisn" class="w-full px-4 py-2 border border-gray-300 rounded-l-lg" placeholder="Masukkan NISN..." value="{{ request('nisn') }}" required>
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white font-semibold rounded-r-lg hover:bg-blue-700">
                <i class="fas fa-search"></i> Cari
            </button>
        </div>
    </form>
</div>

{{-- ========================================================================= --}}
{{-- BAGIAN INI HANYA AKAN TAMPIL JIKA SISWA DITEMUKAN SETELAH PENCARIAN --}}
{{-- ========================================================================= --}}
@if(isset($siswa) && $siswa)
    <div class="bg-white shadow-md rounded-lg p-6">
        <h2 class="text-2xl font-semibold mb-4">Detail Siswa</h2>
        <div class="mb-4 text-gray-700 space-y-1">
            <p><strong>Nama:</strong> {{ $siswa->user->nama ?? 'N/A' }}</p>
            <p><strong>NISN:</strong> {{ $siswa->nisn }}</p>
            <p><strong>Kelas:</strong> {{ $siswa->kelas }}</p>
            <p><strong>Saldo Saat Ini:</strong> <span class="font-bold text-lg text-green-600">Rp {{ number_format($siswa->saldo, 2, ',', '.') }}</span></p>
        </div>
        <hr class="my-4">
        
        {{-- Form Top-up yang sesungguhnya --}}
        <form action="{{ route('bank.topup.store') }}" method="POST">
            @csrf
            
            {{-- PERBAIKAN PENTING: Pastikan input ini ada di dalam form yang benar --}}
            <input type="hidden" name="id_siswa" value="{{ $siswa->id_siswa}}">

            {{-- Input untuk jumlah top-up --}}
            
            <div class="mb-4">
                <label for="jumlah" class="block text-sm font-medium text-gray-700 mb-2">Jumlah Top-up:</label>
                <input type="number" id="jumlah" name="jumlah" class="w-full px-4 py-2 border border-gray-300 rounded-lg" placeholder="e.g. 50000" required>
                @error('jumlah') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            
            <button type="submit" class="w-full bg-green-600 text-white font-bold py-2 px-4 rounded-lg hover:bg-green-700">
                Proses Top-up
            </button>
        </form>
    </div>
{{-- Bagian ini tampil jika ada NISN di URL tapi siswa tidak ditemukan --}}
@elseif(request()->has('nisn'))
    <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4" role="alert">
        <p>Siswa dengan NISN "{{ request('nisn') }}" tidak ditemukan.</p>
    </div>
@endif
@endsection