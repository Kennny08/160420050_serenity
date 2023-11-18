<div class="table-responsive">
    <table id="tabelDetailDaftarRiwayatPengambilanProduk" class="table table-bordered dt-responsive wrap text-center w-100"
        style="border-collapse: collapse; border-spacing: 0; width: 100%;">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama Karyawan</th>
                <th>Nama Produk</th>
                <th>Kuantitas</th>
                <th>Keterangan</th>
                <th>Tanggal Pembuatan</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($daftarPengambilanProduk as $pp)
                <tr>

                    <td>{{ $pp->id }}</td>
                    <td>{{ $pp->karyawan->nama }}</td>
                    <td>{{ $pp->produk->nama }}</td>
                    <td>{{ $pp->kuantitas }}</td>
                    <td>{{ $pp->keterangan }}</td>
                    <td>{{ date('d-m-Y H:i:s', strtotime($pp->created_at)) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
