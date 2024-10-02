<div>
    @php
        function generate_google_maps_link($latitude, $longitude)
        {
            return "https://www.google.com/maps/search/?api=1&query={$latitude},{$longitude}";
        }
    @endphp

    <div class="table-responsive">
        <table class="table table-hover table-bordered table-sm">
            <thead>
                <tr>
                    <th>Kode Toko</th>
                    <th>Nama Toko</th>
                    <th style="width: 30%">Alamat</th>
                    <th>Map</th>
                    <th>QR</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($items as $item)
                    <tr>
                        <td class="text-uppercase">{{ $item->kd_toko }}</td>
                        <td>{{ $item->nama_toko }}</td>
                        <td>{{ $item->alamat }}</td>
                        <td>
                            @if ($item->latitude && $item->longitude)
                                <a href="{{ generate_google_maps_link($item->latitude, $item->longitude) }}"
                                    target="_blank">
                                    Lihat Map
                                </a>
                            @endif
                        </td>
                        <td>
                            <a href="https://api.qrserver.com/v1/create-qr-code/?data={{ $item->kd_toko }}" target="_blank">
                                Download
                            </a>
                        </td>
                        <td>
                            <a href="">Edit</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
