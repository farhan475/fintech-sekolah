@extends('layouts.kantin')

@section('title', 'Transaksi Penjualan')

@section('content')
<h1 class="text-3xl font-bold text-gray-800 mb-6">Transaksi Penjualan</h1>

@if (session('success')) <div class="bg-green-100 text-green-700 p-4 rounded mb-4">{{ session('success') }}</div> @endif
@if (session('error')) <div class="bg-red-100 text-red-700 p-4 rounded mb-4">{{ session('error') }}</div> @endif
@if ($errors->any())
    <div class="bg-red-100 text-red-700 p-4 rounded mb-4">
        <p class="font-bold">Terjadi Kesalahan Validasi:</p>
        <ul class="list-disc list-inside mt-1">
            @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
        </ul>
    </div>
@endif

<div class="bg-white shadow-md rounded-lg p-6 mb-6">
    <form action="{{ route('kantin.transaksi.create') }}" method="GET">
        <label for="nisn" class="block font-medium text-gray-700 mb-2">Cari Siswa (NISN):</label>
        <div class="flex"><input type="text" name="nisn" class="w-full p-2 border rounded-l-lg" value="{{ request('nisn') }}" required><button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-r-lg">Cari</button></div>
    </form>
</div>

@if(isset($siswa) && $siswa)
    <form action="{{ route('kantin.transaksi.store') }}" method="POST">
        @csrf
        <input type="hidden" name="id_siswa" value="{{ $siswa->id_siswa }}">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 bg-white shadow-md rounded-lg p-6">
                <h2 class="text-xl font-semibold mb-4">Pilih Barang</h2>
                <div class="space-y-4 max-h-96 overflow-y-auto">
                    @forelse($barangs as $barang)
                    <div class="flex items-center justify-between p-3 border rounded-lg">
                        <div>
                            <p class="font-bold">{{ $barang->nama_barang }}</p>
                            <p class="text-sm text-gray-600">Stok: {{ $barang->stok }} | Harga: Rp <span id="harga-{{$barang->id_barang}}">{{ number_format($barang->harga, 0, ',', '.') }}</span></p>
                        </div>
                        <div>
                            <input type="number" data-name-jumlah="items[{{ $barang->id_barang }}][jumlah]" data-name-id="items[{{ $barang->id_barang }}][id_barang]" min="0" max="{{$barang->stok}}" data-id-barang="{{$barang->id_barang}}" data-harga="{{$barang->harga}}" placeholder="0" class="w-24 p-2 border rounded-lg text-center item-jumlah">
                            <input type="hidden" value="{{ $barang->id_barang }}">
                        </div>
                    </div>
                    @empty
                    <p>Tidak ada barang yang tersedia.</p>
                    @endforelse
                </div>
            </div>
            <div class="bg-white shadow-md rounded-lg p-6" id="summary-section">
                <h2 class="text-xl font-semibold mb-4">Ringkasan</h2>
                <div class="space-y-2 text-gray-700">
                    <p><strong>Nama Siswa:</strong> {{ $siswa->user->nama }}</p>
                    <p><strong>Saldo Awal:</strong> <span id="saldo-awal" data-saldo="{{$siswa->saldo}}">Rp {{ number_format($siswa->saldo, 0, ',', '.') }}</span></p>
                    <hr>
                    <div class="flex justify-between items-center text-2xl font-bold"><span>Total:</span><span id="total-belanja">Rp 0</span></div>
                    <div class="flex justify-between items-center text-lg font-semibold mt-1"><span>Sisa Saldo:</span><span id="sisa-saldo" class="text-green-600">Rp {{ number_format($siswa->saldo, 0, ',', '.') }}</span></div>
                </div>
                <button type="submit" id="btn-proses" class="w-full bg-green-600 text-white font-bold py-3 rounded-lg mt-6 hover:bg-green-700">Proses Transaksi</button>
            </div>
        </div>
    </form>
@endif
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const itemInputs = document.querySelectorAll('.item-jumlah');
    const totalBelanjaEl = document.getElementById('total-belanja');
    const saldoAwalEl = document.getElementById('saldo-awal');
    const sisaSaldoEl = document.getElementById('sisa-saldo');
    const btnProses = document.getElementById('btn-proses');
    const summarySection = document.getElementById('summary-section');
    const form = btnProses ? btnProses.closest('form') : null;

    if (!saldoAwalEl || !form) return;

    const saldoAwal = parseFloat(saldoAwalEl.dataset.saldo);

    function handleTransaction() {
        form.querySelectorAll('input[type="hidden"][name^="items"]').forEach(el => el.remove());

        let total = 0;
        let hasItems = false;
        itemInputs.forEach(input => {
            const jumlah = parseInt(input.value) || 0;
            const harga = parseFloat(input.dataset.harga);

            if (jumlah > 0) {
                hasItems = true;
                total += jumlah * harga;
                const hiddenJumlah = document.createElement('input');
                hiddenJumlah.type = 'hidden';
                hiddenJumlah.name = input.dataset.nameJumlah;
                hiddenJumlah.value = jumlah;
                form.appendChild(hiddenJumlah);

                const hiddenId = document.createElement('input');
                hiddenId.type = 'hidden';
                hiddenId.name = input.dataset.nameId;
                hiddenId.value = input.dataset.idBarang;
                form.appendChild(hiddenId);
            }
        });

        totalBelanjaEl.textContent = `Rp ${total.toLocaleString('id-ID')}`;
        const sisaSaldo = saldoAwal - total;
        sisaSaldoEl.textContent = `Rp ${sisaSaldo.toLocaleString('id-ID')}`;

        if (sisaSaldo < 0 || !hasItems) {
            sisaSaldoEl.classList.remove('text-green-600');
            sisaSaldoEl.classList.add('text-red-600');
            btnProses.disabled = true;
            btnProses.classList.add('opacity-50', 'cursor-not-allowed');
        } else {
            sisaSaldoEl.classList.remove('text-red-600');
            sisaSaldoEl.classList.add('text-green-600');
            btnProses.disabled = false;
            btnProses.classList.remove('opacity-50', 'cursor-not-allowed');
        }
    }

    itemInputs.forEach(input => {
        input.addEventListener('input', handleTransaction);
    });
    
    form.addEventListener('submit', handleTransaction);

    handleTransaction();
});
</script>
@endpush