<table id="tabelDetailDiskonProduk" class="table table-striped table-bordered dt-responsive wrap text-center"
    style="border-collapse: collapse; border-spacing: 0; width: 100%;">
    <thead>
        <tr>
            <th class="align-middle">Nomor Nota</th>
            <th class="align-middle">Nama Pelanggan</th>
            <th class="align-middle">Total Harga (Rp)</th>
            <th class="align-middle">Jumlah Potongan (Rp)</th>
            <th class="align-middle">Total Pembayaran (Rp)</th>
        </tr>
    </thead>

    <tbody>
        @foreach ($penjualanDiskons as $rd)
            <tr >
                <td>{{ $rd["penjualan"]->nomor_nota }}</td>
                <td>{{ $rd["penjualan"]->pelanggan->nama }}</td>
                <td>{{ number_format( $rd["totalHarga"], 2, ",", ".") }}</td>
                <td>{{ number_format( $rd["totalPotongan"], 2, ",", ".") }}</td>
                <td>{{ number_format( $rd["totalPembayaran"], 2, ",", ".") }}</td>
                
            </tr>
        @endforeach

    </tbody>
    <tfoot id="grupNonaktif">

    </tfoot>
</table>
