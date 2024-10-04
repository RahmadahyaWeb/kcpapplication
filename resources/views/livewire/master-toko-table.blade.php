<div>
    @php
        function generate_google_maps_link($latitude, $longitude)
        {
            return "https://www.google.com/maps/search/?api=1&query={$latitude},{$longitude}";
        }
    @endphp

    <div class="col-md-6 mb-3">
        <form wire:submit="search">
            <label class="form-label">Nama Toko</label>
            <input id="nama_toko" type="text" class="form-control" wire:model.live="nama_toko" placeholder="Cari berdasarkan nama toko">
        </form>
    </div>

    <div class="table-responsive">
        <table class="table table-hover table-bordered table-sm">
            <thead>
                <tr>
                    <th>Kode Toko</th>
                    <th>Nama Toko</th>
                    <th style="width: 30%">Alamat</th>
                    <th>Map</th>
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
                                    target="_blank" class="btn btn-sm btn-success">
                                    <i class='bx bxs-map'></i>
                                </a>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex justify-content-start gap-2">
                                <div class="d-grid ">
                                    <a href="{{ route('master-toko.edit', $item->kd_toko) }}"
                                        class="btn btn-sm btn-warning text-white">
                                        <i class='bx bxs-edit'></i>
                                        <div class="ms-1">
                                            Edit
                                        </div>
                                    </a>
                                </div>

                                <div class="d-grid">
                                    <a href="{{ route('master-toko.destroy', $item->kd_toko) }}"
                                        class="btn btn-sm btn-danger text-white" data-confirm-delete="true">
                                        <i class='bx bxs-trash-alt'></i>
                                        <div class="ms-1">
                                            Hapus
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>


    <div class="mt-6">
        {{ $items->links() }}
    </div>
</div>
