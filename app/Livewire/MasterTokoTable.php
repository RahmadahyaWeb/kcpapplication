<?php

namespace App\Livewire;

use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class MasterTokoTable extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $toko = '';

    public function search()
    {
        $this->resetPage();
    }

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
            ->where('master_toko.nama_toko', 'like', '%' . $this->toko . '%')
            ->orWhere('master_toko.kd_toko', 'like', '%' . $this->toko . '%')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('livewire.master-toko-table', compact('items'));
    }
}
