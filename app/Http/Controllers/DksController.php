<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DksController extends Controller
{
    public function guard()
    {
        // ADMIN,SALESMAN,HEAD-MARKETING,SUPERVISOR-AREA

        if (Auth::user()->role != 'ADMIN' || Auth::user()->role != 'SALESMAN' || Auth::user()->role != 'HEAD-MARKETING' || Auth::user()->role != 'SUPERVISOR-AREA') {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }
    }

    public function index($kd_toko = null)
    {
        if ($kd_toko) {
            // DECODE
            $kd_toko = base64_decode($kd_toko);

            $toko = DB::table('master_toko')
                ->select([
                    'kd_toko',
                    'nama_toko',
                    'latitude',
                    'longitude'
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

    public function store(Request $request, $kd_toko)
    {
        /**
         * VALIDASI LATITUDE DAN LONGITUDE
         * VALIDASI MAX 2X SCAN PER TOKO DALAM SATU HARI
         * VALIDASI JIKA BELUM CHECKIN DI TOKO TERSEBUT HARI INI MAKA TYPE = IN, 
         * VALIDASI JIKA SUDAH CHECKIN DI TOKO TERSEBUT HARI INI MAKA TYPE = OUT
         * VALIDASI JIKA KETERANGAN = 'IST/ist' MAKA TYPE = OUT
         */

        // DATA USER
        $latitude   = $request->latitude;
        $longitude  = $request->longitude;
        $keterangan = strtolower($request->keterangan);
        $user       = Auth::user()->username;

        // JARAK ANTARA USER DENGAN TOKO DALAM METER
        $distance = $request->distance;

        // VALIDASI JARAK ANTARA USER DENGAN TOKO
        if ($distance > 50) {
            return redirect()->back()->with('error', 'Anda berada di luar radius toko!');
        }

        // VALIDASI CHECK IN / CHECK OUT
        $type = '';

        $check = DB::table('trns_dks')
            ->where('kd_toko', $kd_toko)
            ->where('user_sales', $user)
            ->whereDate('tgl_kunjungan', '=', now()->toDateString())
            ->count();

        if ($check == 0) {
            $type = 'in';
        } else if ($check == 2) {
            return redirect()->back()->with('error', 'Anda sudah melakukan check out!');
        } else if ($keterangan == 'ist') {
            $type = 'out';
        } else {
            $type = 'out';
        }

        if ($latitude && $longitude) {
            DB::table('trns_dks')
                ->insert(
                    [
                        'tgl_kunjungan'     => now(),
                        'user_sales'        => $user,
                        'kd_toko'           => $kd_toko,
                        'waktu_kunjungan'   => now(),
                        'type'              => $type,
                        'latitude'          => $latitude,
                        'longitude'         => $longitude,
                        'keterangan'        => $keterangan,
                        'created_by'        => $user,
                        'created_at'        => now(),
                        'updated_at'        => now(),
                    ]
                );

            return redirect()->route('dks.scan')->with('success', "Berhasil melakukan check $type");
        } else {
            return redirect()->back()->with('error', 'Lokasi tidak ditemukan!');
        }
    }
}
