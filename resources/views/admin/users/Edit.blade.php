@extends('layouts.admin')

@section('content')
<h1 class="text-3xl font-bold text-gray-800 mb-6">Edit Pengguna: {{ $user->nama }}</h1>

<div class="bg-white shadow-md rounded-lg p-6">
    
    @if (session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
            <p>{{ session('success') }}</p>
        </div>
    @endif
    @if ($errors->any())
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
            <strong>Whoops! Terjadi kesalahan.</strong>
            <ul class="mt-2 list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    
    <form action="{{ route('admin.users.update', $user->id_user) }}" method="POST">
        @csrf
        @method('PUT') 

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
            <div>
                <label for="nama" class="block text-gray-700 text-sm font-bold mb-2">Nama:</label>
                <input type="text" id="nama" name="nama" value="{{ old('nama', $user->nama) }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                @error('nama') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email:</label>
                <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
            <div>
                <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Password (Kosongkan jika tidak ingin mengubah):</label>
                <input type="password" id="password" name="password" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="password_confirmation" class="block text-gray-700 text-sm font-bold mb-2">Konfirmasi Password:</label>
                <input type="password" id="password_confirmation" name="password_confirmation" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
        </div>

        <div class="mb-4">
            <label for="role" class="block text-gray-700 text-sm font-bold mb-2">Role:</label>
            <select id="role" name="role" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" onchange="toggleSiswaFields()" required>
                <option value="bank" {{ old('role', $user->role) == 'bank' ? 'selected' : '' }}>Bank</option>
                <option value="kantin" {{ old('role', $user->role) == 'kantin' ? 'selected' : '' }}>Kantin</option>
                <option value="siswa" {{ old('role', $user->role) == 'siswa' ? 'selected' : '' }}>Siswa</option>
                <option value="administrator" {{ old('role', $user->role) == 'administrator' ? 'selected' : '' }}>Administrator</option>
            </select>
             @error('role') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
        
        <div id="siswa-fields" class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4 {{ old('role', $user->role) == 'siswa' ? '' : 'hidden' }}">
            <div>
                <label for="nisn" class="block text-gray-700 text-sm font-bold mb-2">NISN (Wajib untuk Siswa):</label>
                <input type="text" id="nisn" name="nisn" value="{{ old('nisn', $user->siswa->nisn ?? '') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                @error('nisn') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="kelas" class="block text-gray-700 text-sm font-bold mb-2">Kelas (Wajib untuk Siswa):</label>
                <input type="text" id="kelas" name="kelas" value="{{ old('kelas', $user->siswa->kelas ?? '') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                @error('kelas') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="flex items-center justify-between mt-6">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                Update Pengguna
            </button>
            <a href="{{ route('admin.users.index') }}" class="inline-block align-baseline font-bold text-sm text-blue-600 hover:text-blue-800">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    function toggleSiswaFields() {
        const roleSelect = document.getElementById('role');
        const siswaFields = document.getElementById('siswa-fields');
        const nisnInput = document.getElementById('nisn');
        const kelasInput = document.getElementById('kelas');

        if (roleSelect.value === 'siswa') {
            siswaFields.classList.remove('hidden');
            nisnInput.required = true;
            kelasInput.required = true;
        } else {
            siswaFields.classList.add('hidden');
            nisnInput.required = false;
            kelasInput.required = false;
        }
    }
    document.addEventListener('DOMContentLoaded', toggleSiswaFields);
</script>
@endpush