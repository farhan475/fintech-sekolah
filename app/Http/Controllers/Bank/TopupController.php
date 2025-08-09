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
    public function create(Request $request)
    {
        $siswa = null;
        if ($request->filled('nisn')) {
            $siswa = Siswa::with('user')->where('nisn', $request->nisn)->first();
        }
        return view('bank.topup.create', compact('siswa'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_siswa' => 'required|exists:siswas,id_siswa',
            'jumlah' => 'required|numeric|min:1000',
        ]);

        try {
            DB::transaction(function () use ($request) {
                $siswa = Siswa::with('user')->findOrFail($request->id_siswa);

                $siswa->saldo += $request->jumlah;
                $siswa->save();

                Topup::create([
                    'jumlah' => $request->jumlah,
                    'tanggal' => Carbon::today(),
                    'id_siswa' => $siswa->id_siswa,
                    'id_user_bank' => Auth::id(), 
                ]);

                Laporan::create([
                    'tanggal' => Carbon::today(),
                    'jenis_transaksi' => 'topup',
                    'jumlah' => $request->jumlah,
                    'id_user' => $siswa->id_user, 
                ]);
            });
        } catch (\Throwable $e) {
            return back()->with('error', 'Transaksi Gagal: ' . $e->getMessage())->withInput();
        }

        return redirect()->route('bank.topup.create')->with('success', 'Top-up saldo berhasil!');
    }
}