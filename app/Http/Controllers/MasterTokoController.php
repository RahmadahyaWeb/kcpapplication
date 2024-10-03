<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MasterTokoController extends Controller
{
    public function guard()
    {
        if (Auth::user()->role != 'ADMIN') {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }
    }

    public function index()
    {
        $this->guard();

        $title = 'Hapus Data!';
        $text = "Apakah Anda yakin ingin hapus data?";
        confirmDelete($title, $text);

        return view('master_toko.index');
    }

    public function create()
    {
        $this->guard();

        return view('master_toko.create');
    }

    public function store(Request $request)
    {
        $this->guard();

        $validated = $request->validate([
            'kd_toko'   => 'required|unique:master_toko',
            'nama_toko' => 'required',
            'alamat'    => 'required',
            'status'    => 'required',
            'latitude'  => 'required',
            'longitude' => 'required'
        ], [
            'kd_toko.required'   => 'Kode toko harus diisi.',
            'kd_toko.unique'     => 'Kode toko sudah ada.',
            'nama_toko.required' => 'Nama toko harus diisi.',
            'alamat.required'    => 'Alamat harus diisi.',
            'status.required'    => 'Status harus diisi.',
            'latitude.required'  => 'Latitude harus diisi.',
            'longitude.required' => 'Longitude harus diisi.'
        ]);

        try {
            DB::table('master_toko')->insert($validated);

            return redirect()->route('master-toko.index')->with('success', 'Data Toko berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
        }
    }

    public function edit($kd_toko)
    {
        $item = DB::table('master_toko', $kd_toko)->where('kd_toko', $kd_toko)->first();

        return view('master_toko.edit', compact('item'));
    }

    public function update(Request $request, $kd_toko)
    {
        $this->guard();

        $item = DB::table('master_toko')
            ->select(['id'])
            ->where('kd_toko', $kd_toko)
            ->first();

        $request->validate([
            'kd_toko'   => 'required|unique:master_toko,kd_toko,' . $item->id,
            'nama_toko' => 'required',
            'alamat'    => 'required',
            'status'    => 'required',
            'latitude'  => 'required',
            'longitude' => 'required'
        ], [
            'kd_toko.required'   => 'Kode toko harus diisi.',
            'kd_toko.unique'     => 'Kode toko sudah ada.',
            'nama_toko.required' => 'Nama toko harus diisi.',
            'alamat.required'    => 'Alamat harus diisi.',
            'status.required'    => 'Status harus diisi.',
            'latitude.required'  => 'Latitude harus diisi.',
            'longitude.required' => 'Longitude harus diisi.'
        ]);

        try {
            DB::table('master_toko')->where('kd_toko', $kd_toko)->update([
                'kd_toko'   => $request->kd_toko,
                'nama_toko' => $request->nama_toko,
                'alamat'    => $request->alamat,
                'status'    => $request->status,
                'latitude'  => $request->latitude,
                'longitude' => $request->longitude,
            ]);

            return redirect()->route('master-toko.index')->with('success', 'Data Toko berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
        }
    }

    public function destroy($kd_toko)
    {
        $this->guard();

        try {
            DB::table('master_toko')->where('kd_toko', $kd_toko)->delete();
            return redirect()->route('master-toko.index')->with('success', 'Data Toko berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage());
        }
    }
}
