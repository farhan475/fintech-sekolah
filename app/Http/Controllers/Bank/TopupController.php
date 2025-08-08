<?php

namespace App\Http\Controllers\Bank;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\Laporan;
use App\Models\Topup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Throwable;

class TopupController extends Controller
{
    /**
     * Menampilkan form untuk melakukan top-up.
     */
    public function create(Request $request)
    {
        $siswa = null;
        if ($request->filled('nisn')) {
            // Eager load relasi 'user' untuk menampilkan nama di view
            $siswa = Siswa::with('user')->where('nisn', $request->nisn)->first();
            // dd($siswa);
        }
        return view('bank.topup.create', compact('siswa'));
    }

    /**
     * Memproses dan menyimpan data top-up.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_siswa' => 'required|exists:siswas,id_siswa',
            'jumlah' => 'required|numeric|min:1000',
        ]);

        try {
            DB::transaction(function () use ($request) {
                // PENTING: Cari siswa dan pastikan relasi user-nya ada.
                $siswa = Siswa::with('user')->findOrFail($request->id_siswa);

                // 1. Update saldo siswa
                $siswa->saldo += $request->jumlah;
                $siswa->save();

                // 2. Catat di tabel 'topups'
                Topup::create([
                    'jumlah' => $request->jumlah,
                    'tanggal' => Carbon::today(),
                    'id_siswa' => $siswa->id_siswa,
                    'id_user_bank' => Auth::id(), // ID petugas bank
                ]);

                // 3. Catat di tabel 'laporans'
                Laporan::create([
                    'tanggal' => Carbon::today(),
                    'jenis_transaksi' => 'topup',
                    'jumlah' => $request->jumlah,
                    'id_user' => $siswa->id_user, // ID milik siswa
                ]);
            });
        } catch (\Throwable $e) {
            // Jika terjadi error apa pun, tampilkan pesannya
            return back()->with('error', 'Transaksi Gagal: ' . $e->getMessage())->withInput();
        }

        return redirect()->route('bank.topup.create')->with('success', 'Top-up saldo berhasil!');
    }
}