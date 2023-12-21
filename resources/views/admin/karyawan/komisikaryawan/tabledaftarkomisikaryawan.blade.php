@foreach ($komisiPerKaryawan as $komisiKaryawan)
    <tr>
        <td>{{ $komisiKaryawan['karyawan']->nama }}</td>
        <td>
            @if ($komisiKaryawan['jumlah_pelayanan'] == 0)
                @if ($komisiKaryawan['karyawan']->jenis_karyawan == 'admin')
                    Tidak ada Penjualan(Karyawan Admin)
                @else
                    Belum ada Penjualan yang Selesai
                @endif
            @else
                {{ date('d-m-Y', strtotime($komisiKaryawan['tanggal_awal'])) }}
            @endif

        </td>
        <td>
            @if ($komisiKaryawan['jumlah_pelayanan'] == 0)
                @if ($komisiKaryawan['karyawan']->jenis_karyawan == 'admin')
                    Tidak ada Penjualan(Karyawan Admin)
                @else
                    Belum ada Penjualan yang Selesai
                @endif
            @else
                {{ date('d-m-Y', strtotime($komisiKaryawan['tanggal_akhir'])) }}
            @endif

        </td>
        <td>{{ $komisiKaryawan['jumlah_pelayanan'] }}</td>
        <td>{{ number_format($komisiKaryawan['total_komisi'], 2, ',', '.') }}</td>
        <td>
            @if ($komisiKaryawan['jumlah_pelayanan'] != 0)
                <button data-toggle="modal" data-target="#modalDetailKomisiKaryawan"
                    idKaryawan ="{{ $komisiKaryawan['karyawan']->id }}"
                    namaKaryawan = "{{ $komisiKaryawan['karyawan']->nama }}"
                    class=" btn btn-info waves-effect waves-light btnDetailKomisiKaryawan">Detail
                </button>
            @else
                <button disabled class=" btn btn-danger waves-effect waves-light btnDetailKomisiKaryawan">Detail
                </button>
            @endif

        </td>
    </tr>
@endforeach
