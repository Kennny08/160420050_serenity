<div>
    <table id="tabelDaftarDetailKomisiKaryawan" class="table table-bordered dt-responsive nowrap text-center w-100"
        style="border-collapse: collapse; border-spacing: 0; width: 100%;">
        <thead>
            <tr>
                <th>Nomor Nota</th>
                <th hidden>tanggalHidden</th>
                <th>Tanggal Perawatan</th>
                <th>Nama Perawatan</th>
                <th>Persentase Komisi (%)</th>
                <th>Harga pada Nota Perawatan (Rp)</th>
                <th>Total Komisi (Rp)</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($daftarPenjualan as $p)
                <tr>
                    <td>{{ $p->penjualan->nomor_nota }}</td>
                    <td hidden>{{ $p->penjualan->tanggal_penjualan }}</td>
                    <td>{{ date('d-m-Y', strtotime($p->penjualan->tanggal_penjualan)) }}</td>
                    <td>
                        {{ $p->perawatan->nama }}
                    </td>

                    <td>
                        {{ $p->perawatan->komisi }}
                    </td>

                    <td>
                        {{ number_format($p->harga, 2, ',', '.') }}
                    </td>

                    <td>
                        {{ number_format(($p->harga * $p->perawatan->komisi) / 100, 2, ',', '.') }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
