<?php

namespace App\Http\Controllers;

use App\Livewire\ReportDksTable;
use Illuminate\Http\Request;

class ReportDKSController extends Controller
{
    public function index()
    {
        return view('report-dks.index');
    }
}
