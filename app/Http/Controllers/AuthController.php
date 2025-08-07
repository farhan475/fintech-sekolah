<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log; // Tambahkan ini untuk logging

class AuthController extends Controller
{
    // Menampilkan form login
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Menampilkan form registrasi
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    // Memproses registrasi (Hanya untuk Siswa)
    public function register(Request $request)
    {
        $rules = [
            'nama' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email', // Pastikan unique di tabel users
            'password' => 'required|string|min:8|confirmed',
            'nisn' => 'required|string|max:255|unique:siswas,nisn', // NISN selalu wajib dan unique di tabel siswas
            'kelas' => 'required|string|max:255', // Kelas selalu wajib
        ];

        $request->validate($rules);

        // Gunakan transaksi database untuk memastikan data konsisten
        DB::beginTransaction();
        try {
            // 1. Buat entri di tabel 'users' dengan role 'siswa'
            $user = User::create([
                'nama' => $request->nama,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'siswa', // Role di-hardcode menjadi 'siswa'
            ]);

            // 2. Buat entri di tabel 'siswas' dan hubungkan dengan user yang baru dibuat
            Siswa::create([
                'id_user' => $user->id_user, // Menggunakan primary key dari User yang baru dibuat
                'nisn' => $request->nisn,
                'kelas' => $request->kelas,
                'saldo' => 0.00, // Saldo awal siswa adalah 0
            ]);

            DB::commit(); // Commit transaksi jika berhasil

            // Langsung login user setelah registrasi berhasil
            Auth::login($user);

            // Redirect ke dashboard siswa
            return redirect()->intended('/siswa/dashboard')->with('success', 'Registrasi siswa berhasil! Selamat datang, ' . $user->nama);

        } catch (\Exception $e) {
            DB::rollBack(); // Rollback transaksi jika ada error
            // Log error untuk debugging lebih lanjut
            Log::error('Registration error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return redirect()->back()->withInput()->withErrors(['registration_error' => 'Gagal melakukan registrasi: ' . $e->getMessage() . '. Silakan coba lagi.']);
        }
    }

    // Memproses login
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, $request->remember)) {
            $request->session()->regenerate();

            // Redirect berdasarkan role
            switch (Auth::user()->role) {
                case 'administrator':
                    return redirect()->intended('/admin/dashboard');
                case 'bank':
                    return redirect()->intended('/bank/dashboard');
                case 'kantin':
                    return redirect()->intended('/kantin/dashboard');
                case 'siswa':
                    return redirect()->intended('/siswa/dashboard');
                default:
                    return redirect()->intended('/dashboard'); // Default
            }
        }

        throw ValidationException::withMessages([
            'email' => [trans('auth.failed')],
        ]);
    }
    
    // Memproses logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
