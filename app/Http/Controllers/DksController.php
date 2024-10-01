<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DksController extends Controller
{
    public function index()
    {
        return view('dks.index');
    }
}
