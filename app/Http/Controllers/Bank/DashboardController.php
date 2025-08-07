<?php

namespace App\Http\Controllers\Bank;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Untuk saat ini, kita hanya menampilkan view.
        // Nanti bisa ditambahkan data ringkasan jika perlu.
        return view('bank.dashboard');
    }
}
