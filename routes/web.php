<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DksController;
use App\Http\Controllers\MasterTokoController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::middleware(['auth'])->group(function () {
    // DASHBOARD
    Route::get('/', function () {
        return view('welcome');
    })->name('dashboard');

    // DKS-SCAN
    Route::get('dks-scan', [DksController::class, 'index'])->name('dks.scan');

    // MASTER TOKO
    Route::get('master-toko', [MasterTokoController::class, 'index'])->name('master-toko.index');

    // LOGOUT
    Route::get('logout', [AuthController::class, 'logout'])->name('logout');
});

Route::middleware(['guest'])->group(function () {
    Route::get('login', [AuthController::class, 'loginPage'])->name('login');
    Route::post('login', [AuthController::class, 'login'])->name('login');
});
