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
         * VALIDASI LOKASI USER DAN LOKASI TOKO
         * VALIDASI MAX 3X SCAN PER TOKO DALAM SATU HARI
         * VALIDASI JIKA BELUM CHECKIN DI TOKO TERSEBUT HARI INI MAKA TYPE = IN, 
         * VALIDASI JIKA SUDAH CHECKIN DI TOKO TERSEBUT HARI INI MAKA TYPE = OUT
         * VALIDASI JIKA KETERANGAN = 'IST/ist' MAKA TYPE = OUT
         */

        //  LOKASI USER
        $latitude   = $request->latitude;
        $longitude  = $request->longitude;

        // LOKASI TOKO
        $lokasi_toko = DB::table('master_toko')
            ->select(['nama_toko', 'latitude', 'longitude'])
            ->where('kd_toko', $kd_toko)
            ->first();

        // JARAK DALAM METER
        $distance = $this->getDistanceBetweenPoints($latitude, $longitude, $lokasi_toko->latitude, $lokasi_toko->longitude);

        if ($distance > 50) {
            echo 'tidak boleh absen';
        } else {
            echo 'boleh absen';
        }

        exit;

        if ($latitude && $longitude) {
            DB::table('trns_dks')
                ->insert(
                    [
                        'tgl_kunjungan'     => now(),
                        'user_sales'        => Auth::user()->username,
                        'kd_toko'           => $kd_toko,
                        'waktu_kunjungan'   => now(),
                        'latitude'          => $latitude,
                        'longitude'         => $longitude,
                        'keterangan'        => $request->keterangan,
                        'created_by'        => Auth::user()->username,
                        'created_at'        => now(),
                        'updated_at'        => now(),
                    ]
                );
        } else {
            return redirect()->back()->with('error', 'Lokasi tidak ditemukan');
        }
    }

    public function getDistanceBetweenPoints($lat1, $lon1, $lat2, $lon2)
    {
        $theta = $lon1 - $lon2;
        $miles = (sin(deg2rad($lat1)) * sin(deg2rad($lat2))) + (cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta)));
        $miles = acos($miles);
        $miles = rad2deg($miles);
        $miles = $miles * 60 * 1.1515;
        $feet = $miles * 5280;
        $yards = $feet / 3;
        $kilometers = $miles * 1.609344;
        $meters = $kilometers * 1000;

        return $meters;
    }
}
