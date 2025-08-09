<?php

namespace App\Http\Controllers\Kantin;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BarangController extends Controller
{
    public function index()
    {
        $barangs = Barang::where('id_user_kantin', Auth::id())
            ->latest()
            ->paginate(10);
        return view('kantin.barang.index', compact('barangs'));
    }

    public function create()
    {
        return view('kantin.barang.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $data = $request->only(['nama_barang', 'harga', 'stok']);
        $data['id_user_kantin'] = Auth::id();

        if ($request->hasFile('gambar')) {
            $path = $request->file('gambar')->store('barangs', 'public');
            $data['gambar'] = $path;
        }

        Barang::create($data);

        return redirect()->route('kantin.barang.index')->with('success', 'Barang baru berhasil ditambahkan.');
    }


    public function edit(Barang $barang)
    {
        if ($barang->id_user_kantin !== Auth::id()) {
            abort(403, 'AKSI TIDAK DIIZINKAN');
        }
        return view('kantin.barang.edit', compact('barang'));
    }

    public function update(Request $request, Barang $barang)
    {
        if ($barang->id_user_kantin !== Auth::id()) abort(403);

        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $data = $request->only(['nama_barang', 'harga', 'stok']);

        if ($request->hasFile('gambar')) {
            if ($barang->gambar) {
                Storage::disk('public')->delete($barang->gambar);
            }
            $path = $request->file('gambar')->store('barangs', 'public');
            $data['gambar'] = $path;
        }

        $barang->update($data);

        return redirect()->route('kantin.barang.index')->with('success', 'Data barang berhasil diperbarui.');
    }


    public function destroy(Barang $barang)
    {
        if ($barang->id_user_kantin !== Auth::id()) abort(403);

        if ($barang->gambar) {
            Storage::disk('public')->delete($barang->gambar);
        }

        $barang->delete();

        return redirect()->route('kantin.barang.index')->with('success', 'Barang berhasil dihapus.');
    }
}
