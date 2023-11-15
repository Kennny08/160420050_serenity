@foreach ($daftarIzinPresensiHariIniKedepan as $p)
    <tr>
        <td>{{ $p['tanggalIzinHari'] }}</td>
        <td hidden>{{ $p['tanggalIzin'] }}</td>
        <td>{{ $p['jumlahKaryawan'] }}
        </td>
        <td>{{ $p['daftarIzin']->where('status', 'konfirmasi')->count() }}
        </td>
        <td>{{ $p['daftarIzin']->where('status', 'tolak')->count() }}
        </td>
        <td>
            @if ($p['daftarIzin']->where('status', 'belum')->count() > 0)
                <span
                    class="text-danger font-weight-bold">{{ $p['daftarIzin']->where('status', 'belum')->count() }}</span>
            @else
                0
            @endif

        </td>
        <td>
            <button data-toggle="modal" data-target="#modalDetailRiwayatIzin" tanggalIzin="{{ $p['tanggalIzin'] }}"
                tanggalizinHari="{{ $p['tanggalIzinHari'] }}"
                class=" btn btn-info waves-effect waves-light btnDetailRiwayatIzin">Detail
            </button>
        </td>
    </tr>
@endforeach
