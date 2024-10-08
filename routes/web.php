<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DksController;
use App\Http\Controllers\MasterTokoController;
use App\Http\Controllers\ReportDKSController;
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
    Route::get('dks-scan/{kd_toko?}', [DksController::class, 'index'])->name('dks.scan');
    Route::post('dks-scan/store/{kd_toko}', [DksController::class, 'store'])->name('dks.store');

    // REPORT DKS
    Route::get('report/dks', [ReportDKSController::class, 'index'])->name('report.dks');

    // MASTER TOKO
    Route::get('master-toko', [MasterTokoController::class, 'index'])->name('master-toko.index');
    Route::get('master-toko/create', [MasterTokoController::class, 'create'])->name('master-toko.create');
    Route::post('master-toko/store', [MasterTokoController::class, 'store'])->name('master-toko.store');
    Route::get('master-toko/edit/{kd_toko}', [MasterTokoController::class, 'edit'])->name('master-toko.edit');
    Route::put('master-toko/update/{kd_toko}', [MasterTokoController::class, 'update'])->name('master-toko.update');
    Route::delete('master-toko/destroy/{kd_toko}', [MasterTokoController::class, 'destroy'])->name('master-toko.destroy');

    // LOGOUT
    Route::get('logout', [AuthController::class, 'logout'])->name('logout');
});

Route::middleware(['guest'])->group(function () {
    Route::get('login', [AuthController::class, 'loginPage'])->name('login');
    Route::post('login', [AuthController::class, 'login'])->name('login');
});
