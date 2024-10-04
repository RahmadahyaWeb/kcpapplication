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

                <form action="{{ route('dks.store', $toko->kd_toko) }}" method="POST" onclick="return validateForm(event)">
                    @csrf
                    <input type="hidden" name="latitude" id="latitude">
                    <input type="hidden" name="longitude" id="longitude">
                    <input type="hidden" name="distance" id="distance">

                    <div class="col-12 mb-3">
                        <label for="status" class="form-label">Status</label>
                        <input type="text" class="form-control" id="status" name="status"
                            value="Lokasi tidak terdeteksi." disabled>
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
            var userMarker;

            var map = L.map('map').setView([51.505, -0.09], 13);

            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 18,
            }).addTo(map);

            // Function to get user location
            function getLocation() {
                if (navigator.geolocation) {
                    navigator.geolocation.watchPosition(position => {
                        updateMap(position.coords.latitude, position.coords.longitude);
                    }, showError);
                } else {
                    alert("Geolokasi tidak didukung oleh browser ini.");
                }
            }

            // Function to update the map with user location
            function updateMap(latitude, longitude) {
                if (userMarker) {
                    map.removeLayer(userMarker);
                }

                // Circle for the store's location
                let circleStore = L.circle([tokoLatitude, tokoLongitude], {
                    color: 'red',
                    fillColor: '#f03',
                    fillOpacity: 0.5,
                    radius: radiusToko
                }).addTo(map);

                // Marker for user's location
                let userMarker = L.marker([latitude, longitude]).addTo(map)
                    .bindPopup(`{{ Auth::user()->username }}`)
                    .openPopup();

                // Circle for user's location
                let userCircle = L.circle([latitude, longitude], {
                    color: 'blue',
                    fillColor: '#30f',
                    fillOpacity: 0.5,
                    radius: 10
                }).addTo(map);

                map.fitBounds(userCircle.getBounds());

                document.getElementById('latitude').value = latitude;
                document.getElementById('longitude').value = longitude;

                // Calculate distance
                var userLocation = L.latLng(latitude, longitude);
                var storeLocation = L.latLng(tokoLatitude, tokoLongitude);
                var distance = userLocation.distanceTo(storeLocation);

                document.getElementById('distance').value = distance;

                if (distance <= radiusToko) {
                    document.getElementById('status').value = 'Berada di dalam radius toko';
                } else {
                    document.getElementById('status').value = 'Berada di luar radius toko';
                }
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

            function validateForm(event) {
                getLocation();

                distance = document.getElementById('distance').value;
                latitude = document.getElementById('latitude').value;
                longitude = document.getElementById('longitude').value;

                if (!distance || !latitude || !longitude) {
                    alert("Lokasi tidak terdeteksi.");
                    event.preventDefault();
                    return false;
                }

                return true;

            }

            getLocation();
        </script>
    @endpush
@endsection
