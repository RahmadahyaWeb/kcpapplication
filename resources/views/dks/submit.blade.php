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
            <div class="row mb-3">
                <div class="col-12 mb-3">
                    <label for="kd_toko" class="form-label">Kode Toko</label>
                    <input type="text" id="kd_toko" class="form-control" value="{{ $toko->kd_toko }}" disabled>
                </div>
                <div class="col-12 mb-3">
                    <label for="nama_toko" class="form-label">Nama Toko</label>
                    <input type="text" id="nama_toko" class="form-control" value="{{ $toko->nama_toko }}" disabled>
                </div>

                <div class="col my-3">
                    <div id="map"></div>
                </div>

                <form action="{{ route('dks.store', $toko->kd_toko) }}" method="POST">
                    @csrf
                    <input type="hidden" name="latitude" id="latitude">
                    <input type="hidden" name="longitude" id="longitude">

                    <div class="col-12 mb-3">
                        <label for="status" class="form-label">Status</label>
                        <input type="text" class="form-control" id="status" name="status">
                    </div>

                    <div class="col-12 mb-3">
                        <label for="keterangan" class="form-label">Keterangan</label>
                        <textarea name="keterangan" id="keterangan" class="form-control" placeholder="Keterangan"></textarea>
                    </div>

                    <div class="col my-3">
                        <small>NB: Pastikan Anda berada di dalam radius Toko ketika melakukan check in / check out.</small>
                    </div>

                    <div class="col-12 mb-3 d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            var tokoLatitude = {{ $toko->latitude }};
            var tokoLongitude = {{ $toko->longitude }};
            var radiusToko = 50;

            var map = L.map('map').setView([51.505, -0.09], 13);

            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 18,
            }).addTo(map);

            if (navigator.geolocation) {
                navigator.geolocation.watchPosition(position => {
                    latitude = position.coords.latitude;
                    longitude = position.coords.longitude;
                    accuracy = position.coords.accuracy;

                    let circleStore = L.circle([tokoLatitude, tokoLongitude], {
                        color: 'red',
                        fillColor: '#f03',
                        fillOpacity: 0.5,
                        radius: 50
                    }).addTo(map);

                    // Menambahkan marker untuk lokasi pengguna
                    let userMarker = L.marker([latitude, longitude]).addTo(map)
                        .bindPopup(`{{ Auth::user()->username }}`)
                        .openPopup();

                    // Menambahkan circle untuk lokasi pengguna
                    let userCircle = L.circle([latitude, longitude], {
                        color: 'blue',
                        fillColor: '#30f',
                        fillOpacity: 0.5,
                        radius: 10
                    }).addTo(map);

                    map.fitBounds(userCircle.getBounds())

                    document.getElementById('latitude').value = latitude;
                    document.getElementById('longitude').value = longitude;


                    // MENGHITUNG JARAK
                    var userLocation = L.latLng(latitude, longitude);
                    var storeLocation = L.latLng(tokoLatitude, tokoLongitude);
                    var distance = userLocation.distanceTo(storeLocation);

                    if (distance <= radiusToko) {
                        document.getElementById('status').value = 'Berada di dalam radius';
                    } else {
                        document.getElementById('status').value = 'Berada di luar radius';
                    }
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
        </script>
    @endpush
@endsection
