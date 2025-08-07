<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
    Route::get('/register', [AuthController::class, 'register']);

    // Tambahkan rute API lain yang butuh otentikasi
    // Contoh:
    // Route::apiResource('siswa', Api\SiswaController::class)->middleware('role:administrator');
    // Route::post('topup', [Api\FintechController::class, 'topup'])->middleware('role:bank');
    // ...
});
