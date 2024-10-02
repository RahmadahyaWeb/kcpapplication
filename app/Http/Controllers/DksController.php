<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DksController extends Controller
{
    public function index($kd_toko = null)
    {

        if ($kd_toko) {
            $toko = DB::table('master_toko')
                ->select([
                    'kd_toko',
                    'nama_toko',
                ])
                ->where('kd_toko', $kd_toko)
                ->first();

            if ($toko) {
                return view('dks.submit', compact('toko'));
            } else {
                return redirect()->route('dks.scan')->with('error', "Toko dengan kode $kd_toko tidak ditemukan.");
            }
        } else {
            return view('dks.index');
        }
    }
}
