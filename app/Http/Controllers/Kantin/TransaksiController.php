<?php

namespace App\Http\Controllers\Kantin;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\Barang;
use App\Models\Laporan;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Throwable;

class TransaksiController extends Controller
{
    /**
     * Menampilkan halaman utama untuk membuat transaksi.
     */
    public function create(Request $request)
    {
        $siswa = null;
        if ($request->filled('nisn')) {
            $siswa = Siswa::with('user')->where('nisn', $request->nisn)->first();
        }

        $barangs = Barang::where('id_user_kantin', Auth::id())
                         ->where('stok', '>', 0)
                         ->get();

        return view('kantin.transaksi.create', compact('siswa', 'barangs'));
    }

    /**
     * Memproses dan menyimpan transaksi penjualan.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_siswa' => 'required|exists:siswas,id_siswa',
            // Pastikan 'items' ada dan merupakan array
            'items' => 'present|nullable|array',
            'items.*.id_barang' => 'required|exists:barangs,id_barang',
            'items.*.jumlah' => 'required|integer|min:1',
        ]);

        // Filter item yang jumlahnya 0 atau tidak diisi
        $items = array_filter($validated['items'] ?? [], function ($item) {
            return !empty($item['jumlah']) && $item['jumlah'] > 0;
        });

        if (empty($items)) {
            return back()->with('error', 'Tidak ada barang yang dipilih untuk dibeli.')->withInput();
        }

        try {
            DB::transaction(function () use ($items, $validated) {
                $siswa = Siswa::with('user')->findOrFail($validated['id_siswa']);
                $idKantin = Auth::id();
                $totalBelanja = 0;

                foreach ($items as $itemData) {
                    // Kunci barang untuk mencegah race condition (mengunci baris di database)
                    $barang = Barang::where('id_barang', $itemData['id_barang'])->lockForUpdate()->first();
                    $jumlahBeli = $itemData['jumlah'];
                    
                    if ($barang->id_user_kantin !== $idKantin) {
                        throw new \Exception("Produk '{$barang->nama_barang}' tidak valid.");
                    }
                    if ($barang->stok < $jumlahBeli) {
                        throw new \Exception("Stok untuk '{$barang->nama_barang}' tidak mencukupi (tersisa {$barang->stok}).");
                    }

                    $subtotal = $barang->harga * $jumlahBeli;
                    $totalBelanja += $subtotal;

                    // Kurangi stok barang
                    $barang->stok -= $jumlahBeli;
                    $barang->save();

                    // Catat di tabel 'transaksis'
                    Transaksi::create([
                        'jumlah_barang' => $jumlahBeli,
                        'total_harga' => $subtotal,
                        'tanggal' => Carbon::today(),
                        'id_siswa' => $siswa->id_siswa,
                        'id_barang' => $barang->id_barang,
                        'id_user_kantin' => $idKantin,
                    ]);
                }

                if ($siswa->saldo < $totalBelanja) {
                    throw new \Exception("Saldo siswa tidak mencukupi. Total belanja: Rp " . number_format($totalBelanja));
                }

                // Kurangi saldo siswa
                $siswa->saldo -= $totalBelanja;
                $siswa->save();

                // Catat di tabel 'laporans'
                Laporan::create([
                    'tanggal' => Carbon::today(),
                    'jenis_transaksi' => 'transaksi',
                    'jumlah' => $totalBelanja,
                    'id_user' => $siswa->id_user,
                ]);
            });
        } catch (\Throwable $e) {
            // Jika ada error apa pun, tampilkan pesannya dengan jelas
            return back()->with('error', 'Transaksi Gagal: ' . $e->getMessage())->withInput();
        }

        return redirect()->route('kantin.transaksi.create')->with('success', 'Transaksi berhasil!');
    }
}