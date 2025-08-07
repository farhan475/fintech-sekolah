<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use Illuminate\Http\Request;

class SiswaController extends Controller
{
    public function index()
    {
        // Hanya admin yang bisa melihat semua siswa
        if (auth()->user()->role !== 'administrator') {
            return response()->json(['message' => 'Forbidden'], 403);
        }
        $siswas = Siswa::with('user')->get();
        return response()->json($siswas);
    }

    public function show(Siswa $siswa)
    {
        // Siswa bisa melihat datanya sendiri
        if (auth()->user()->role === 'siswa' && auth()->user()->id_user !== $siswa->id_user) {
            return response()->json(['message' => 'Forbidden'], 403);
        }
        // Admin, bank, kantin bisa melihat data siswa
        return response()->json($siswa->load('user'));
    }

    // Tambahkan store, update, destroy jika diperlukan via API (dengan otorisasi ketat)
}