<table id="tabelDetailPembelian" class="table table-striped table-bordered dt-responsive wrap text-center"
    style="border-collapse: collapse; border-spacing: 0; width: 100%;">
    <thead>
        <tr>
            <th>Kode Produk</th>
            <th>Nama</th>
            <th>Merek</th>
            <th>Harga Beli(Rp)</th>
            <th>Stok Dibeli</th>
            <th>Kategori</th>
            <th>Kondisi</th>
            <th>Deskripsi</th>
            <th>Subtotal(Rp)</th>
        </tr>
    </thead>

    <tbody>
        @foreach ($detailPembelianProduk as $p)
            <tr id="tr_{{ $p->id }}">
                <td>{{ $p->kode_produk }}</td>
                <td>{{ $p->nama }}</td>
                <td>{{ $p->merek->nama }}</td>
                <td>{{ number_format($p->pivot->harga, 2, ',', '.') }}</td>
                <td>{{ $p->pivot->kuantitas }}</td>
                <td>{{ $p->kategori->nama }}</td>
                <td class="text-left">
                    <ul>
                        @foreach ($p->kondisis as $kondisi)
                            <li>{{ $kondisi->keterangan }}</li>
                        @endforeach
                    </ul>
                </td>

                <td>{{ $p->deskripsi }}</td>
                <td class="text-center">
                    {{ number_format($p->pivot->kuantitas * $p->pivot->harga, 2, ',', '.') }}
                </td>
            </tr>
        @endforeach

    </tbody>
</table>
