@php
    $days = [
        'Sunday' => 'Minggu',
        'Monday' => 'Senin',
        'Tuesday' => 'Selasa',
        'Wednesday' => 'Rabu',
        'Thursday' => 'Kamis',
        'Friday' => 'Jumat',
        'Saturday' => 'Sabtu',
    ];
@endphp

<div>
    <div class="table-responsive">
        <table class="table table-hover table-bordered table-sm">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Tgl. Kunjungan</th>
                    <th>Toko</th>
                    <th>Check In</th>
                    <th>Check Out</th>
                    <th>Lama Kunjungan</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @if ($items->isEmpty())
                    <tr>
                        <td colspan="7" class="text-center">No data</td>
                    </tr>
                @else
                    @foreach ($items as $item)
                        @php
                            $carbonDate = \Carbon\Carbon::parse($item->tgl_kunjungan);
                            $formattedDate = $days[$carbonDate->format('l')] . ', ' . $carbonDate->format('d-m-Y');
                        @endphp
                        <tr>
                            <td>{{ $item->user_sales }}</td>
                            <td>{{ $formattedDate }}</td>
                            <td>{{ $item->nama_toko }}</td>
                            <td>{{ date('H:i:s', strtotime($item->waktu_cek_in)) }}</td>
                            <td>{{ date('H:i:s', strtotime($item->waktu_cek_out)) }}</td>
                            <td>{{ $item->lama_kunjungan }} menit</td>
                            <td>{{ $item->keterangan }}</td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
</div>
