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

            var map = L.map('map').fitWorld();

            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
            }).addTo(map);

            map.locate({
                setView: true,
                maxZoom: 16
            });

            function onLocationFound(e) {
                L.marker(e.latlng).addTo(map)
                    .bindPopup("{{ Auth::user()->username }}").openPopup();

                L.circle(e.latlng, radius).addTo(map);
            }

            function onLocationError(e) {
                alert(e.message);
            }

            map.on('locationfound', onLocationFound);

            map.on('locationerror', onLocationError);

            // function handlePermission() {
            //     navigator.permissions.query({
            //         name: "geolocation"
            //     }).then((result) => {
            //         if (result.state === "granted") {
            //             report(result.state);
            //             navigator.geolocation.getCurrentPosition(showPosition);
            //         } else if (result.state === "prompt") {
            //             report(result.state);
            //             geoBtn.style.display = "none";
            //             navigator.geolocation.getCurrentPosition(
            //                 revealPosition,
            //                 positionDenied,
            //                 geoSettings
            //             );
            //         } else if (result.state === "denied") {
            //             report(result.state);
            //         }

            //         result.addEventListener("change", () => {
            //             report(result.state);
            //         });
            //     });
            // }

            // function report(state) {
            //     if (state === "granted") {
            //         console.log(`Permission granted`);
            //     } else if (state === "denied") {
            //         console.log(`Permission denied`);
            //     } else {
            //         console.log(`Permission prompt`);
            //     }
            // }

            // function showPosition(position) {
            //     console.log(position);
            // }

            // function positionDenied(error) {
            //     console.error(`Error getting position: ${error.message}`);
            // }

            // handlePermission();
        </script>
    @endpush
@endsection
