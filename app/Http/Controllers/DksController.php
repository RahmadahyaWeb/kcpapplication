<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DksController extends Controller
{
    public function index($kd_toko = null)
    {

        if ($kd_toko) {
            echo $kd_toko;
        } else {
            return view('dks.index');
        }
    }
}
