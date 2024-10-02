<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MasterTokoController extends Controller
{
    public function index()
    {
        return view('master_toko.index');
    }
}
