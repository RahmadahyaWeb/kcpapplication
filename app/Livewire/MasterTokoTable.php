<?php

namespace App\Livewire;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

class MasterTokoTable extends Component
{
    public function render()
    {
        $items = DB::table('master_toko')
            ->select([
                'kd_toko',
                'nama_toko',
                'alamat',
                'latitude',
                'longitude'
            ])
            ->get();

        return view('livewire.master-toko-table', compact('items'));
    }
}
