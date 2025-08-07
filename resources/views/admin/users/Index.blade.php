@extends('layouts.admin')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-3xl font-bold text-gray-800">Manajemen Pengguna</h1>
    {{-- Menggunakan route() helper untuk URL yang lebih aman dan fleksibel --}}
    <a href="{{ route('admin.users.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline flex items-center">
        <i class="fas fa-plus mr-2"></i> Tambah Pengguna Baru
    </a>
</div>

{{-- Notifikasi diaktifkan kembali untuk menampilkan pesan feedback --}}
@if (session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
@endif
@if (session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
        <span class="block sm:inline">{{ session('error') }}</span>
    </div>
@endif

<div class="bg-white shadow-md rounded-lg overflow-x-auto">
    <table class="min-w-full leading-normal">
        <thead>
            <tr>
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                    ID
                </th>
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                    Nama
                </th>
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                    Email
                </th>
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                    Role
                </th>
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                    Aksi
                </th>
            </tr>
        </thead>
        <tbody>
            {{-- Menggunakan @forelse untuk loop data. Ini lebih baik dari @foreach karena ada @empty --}}
            @forelse ($users as $user)
                <tr>
                    {{-- Mengambil data dari objek $user secara dinamis --}}
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">{{ $user->id_user }}</td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">{{ $user->nama }}</td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">{{ $user->email }}</td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">{{ ucfirst($user->role) }}</td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                        {{-- Link Edit dinamis, mengambil id_user dari objek $user --}}
                        <a href="{{ route('admin.users.edit', $user->id_user) }}" class="text-yellow-600 hover:text-yellow-900 mr-3">Edit</a>
                        
                        {{-- Form Hapus dinamis, mengambil id_user dari objek $user --}}
                        <form action="{{ route('admin.users.destroy', $user->id_user) }}" method="POST" class="inline-block">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?')">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                {{-- Bagian ini akan tampil jika variabel $users kosong --}}
                <tr>
                    <td colspan="5" class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-center">
                        Tidak ada data pengguna yang ditemukan.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Menampilkan link untuk pagination jika data lebih dari 10 --}}
<div class="mt-4">
    {{ $users->links() }}
</div>
@endsection