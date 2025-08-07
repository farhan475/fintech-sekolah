<?php
namespace App\Http\Controllers\Bank;

use App\Http\Controllers\Controller;
use App\Models\Topup;
use App\Models\Withdrawal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $selectedDate = $request->input('tanggal', Carbon::today()->toDateString());
        $bankUserId = Auth::id();

        // Ambil data dari masing-masing tabel
        $topups = Topup::with('siswa.user')
            ->where('id_user_bank', $bankUserId)
            ->where('tanggal', $selectedDate)
            ->get();

        $withdrawals = Withdrawal::with('siswa.user')
            ->where('id_user_bank', $bankUserId)
            ->where('tanggal', $selectedDate)
            ->get();
        
        return view('bank.laporan.index', compact('topups', 'withdrawals', 'selectedDate'));
    }
}