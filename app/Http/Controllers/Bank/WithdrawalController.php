<?php

namespace App\Http\Controllers\Bank;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\Laporan;
use App\Models\Withdrawal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Throwable;

class WithdrawalController extends Controller
{
    /**
     * Menampilkan form untuk melakukan penarikan.
     */
    public function create(Request $request)
    {
        $siswa = null;
        if ($request->filled('nisn')) {
            $siswa = Siswa::with('user')->where('nisn', $request->nisn)->first();
        }
        return view('bank.withdrawal.create', compact('siswa'));
    }

    /**
     * Memproses dan menyimpan data penarikan.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_siswa' => 'required|exists:siswas,id_siswa',
            'jumlah' => 'required|numeric|min:1000',
        ]);

        try {
            DB::transaction(function () use ($request) {
                $siswa = Siswa::with('user')->findOrFail($request->id_siswa);

                // Validasi saldo
                if ($siswa->saldo < $request->jumlah) {
                    // throw new \Exception() akan ditangkap oleh blok catch
                    throw new \Exception("Saldo siswa tidak mencukupi.");
                }

                // 1. Kurangi saldo siswa
                $siswa->saldo -= $request->jumlah;
                $siswa->save();

                // 2. Catat di tabel 'withdrawals'
                Withdrawal::create([
                    'jumlah' => $request->jumlah,
                    'tanggal' => Carbon::today(),
                    'id_siswa' => $siswa->id_siswa,
                    'id_user_bank' => Auth::id(),
                ]);

                // 3. Catat di tabel 'laporans'
                Laporan::create([
                    'tanggal' => Carbon::today(),
                    'jenis_transaksi' => 'withdrawal',
                    'jumlah' => $request->jumlah,
                    'id_user' => $siswa->id_user,
                ]);
            });
        } catch (\Throwable $e) {
            return back()->with('error', 'Transaksi Gagal: ' . $e->getMessage())->withInput();
        }

        return redirect()->route('bank.withdrawal.create')->with('success', 'Penarikan tunai berhasil!');
    }
}