<table id="tabelDaftarProdukMerek" class="table table-striped table-bordered dt-responsive wrap text-center"
    style="border-collapse: collapse; border-spacing: 0; width: 100%;">
    <thead>
        <tr>
            <th>Kode Produk</th>
            <th>Nama</th>
            <th>Harga Beli(Rp)</th>
            <th>harga Jual (RP)</th>
            <th>Kategori</th>
            <th>Kondisi</th>
            <th>Deskripsi</th>
        </tr>
    </thead>

    <tbody>
        @foreach ($daftarProdukMerek as $p)
            <tr id="tr_{{ $p->id }}">
                <td>{{ $p->kode_produk }}</td>
                <td>{{ $p->nama }}</td>
                <td>{{ number_format($p->harga_beli, 2, ',', '.') }}</td>
                <td>{{ number_format($p->harga_jual, 2, ',', '.') }}</td>
                <td>{{ $p->kategori->nama }}</td>
                <td class="text-left">
                    <ul>
                        @foreach ($p->kondisis as $kondisi)
                            <li>{{ $kondisi->keterangan }}</li>
                        @endforeach
                    </ul>
                </td>

                <td>{{ $p->deskripsi }}</td>

            </tr>
        @endforeach

    </tbody>
</table>
