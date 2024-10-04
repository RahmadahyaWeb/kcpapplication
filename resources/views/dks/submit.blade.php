@extends('layouts.app')

@section('title', 'DKS Scan')

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="row align-items-center">
                <div class="col">
                    <b>DKS</b>
                </div>
            </div>
            <hr>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12 mb-3">
                    <label for="kd_toko" class="form-label">Kode Toko</label>
                    <input type="text" class="form-control" value="{{ $toko->kd_toko }}" disabled>
                </div>
                <div class="col-12 mb-3">
                    <label for="nama_toko" class="form-label">Nama Toko</label>
                    <input type="text" class="form-control" value="{{ $toko->nama_toko }}" disabled>
                </div>

                <form action="{{ route('dks.store', $toko->kd_toko) }}" method="POST">
                    @csrf
                    <input type="hidden" name="latitude" id="latitude">
                    <input type="hidden" name="longitude" id="longitude">

                    <div class="col-12 mb-3">
                        <label for="keterangan" class="form-label">Keterangan</label>
                        <textarea name="keterangan" id="keterangan" class="form-control" placeholder="Keterangan"></textarea>
                    </div>

                    <div class="col-12 mb-3 d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        {{-- <script>
            document.addEventListener("DOMContentLoaded", function() {
                let latitude = '';
                let longitude = '';

                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(position => {
                        latitude = position.coords.latitude;
                        longitude = position.coords.longitude;
                        document.getElementById('latitude').value = latitude;
                        document.getElementById('longitude').value = longitude;
                    }, showError);
                } else {
                    alert("Geolokasi tidak didukung oleh browser ini.");
                }

                function showError(error) {
                    switch (error.code) {
                        case error.PERMISSION_DENIED:
                            alert("User menolak permintaan geolokasi.");
                            break;
                        case error.POSITION_UNAVAILABLE:
                            alert("Posisi tidak tersedia.");
                            break;
                        case error.TIMEOUT:
                            alert("Permintaan geolokasi waktu habis.");
                            break;
                        case error.UNKNOWN_ERROR:
                            alert("Terjadi kesalahan.");
                            break;
                    }
                }

                document.querySelector('form').addEventListener('submit', function(event) {
                    if (!latitude || !longitude) {
                        event.preventDefault();
                        alert("Lokasi tidak tersedia. Mohon izinkan akses geolokasi.");
                    }
                });
            });
        </script> --}}

        
    @endpush
@endsection
