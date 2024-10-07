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
            var userCircle;

            var map = L.map('map', {
                center: [tokoLatitude, tokoLongitude],
                zoom: 13
            });

            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                zoom: 13,
            }).addTo(map);

            storeCircle = L.circle([tokoLatitude, tokoLongitude], {
                color: 'red',
                fillColor: 'red',
                fillOpacity: 0.2,
                radius: radiusToko
            }).addTo(map);


            setInterval(function() {
                map.locate({
                    setView: true,
                    zoom: 13,

                    enableHighAccuracy: true,
                });
            }, 5000);

            function onLocationFound(e) {
                if (userMarker) {
                    map.removeLayer(userMarker);
                }

                if (userCircle) {
                    map.removeLayer(userCircle);
                }

                userMarker = L.marker(e.latlng).addTo(map)
                    .bindPopup("{{ Auth::user()->username }}").openPopup();

                userCircle = L.circle(e.latlng, 5).addTo(map);

            }

            function onLocationError(e) {
                alert(e.message);
            }

            map.on('locationfound', onLocationFound);

            map.on('locationerror', onLocationError);
        </script>
    @endpush
@endsection
