<div class="form-group row text-center">
    <div class="form-group col-md-6">
        <h6>Tanggal Pembuatan</h6>
        <p>{{ date('d-m-Y H:i:s', strtotime($supplier->created_at)) }}</p>
    </div>
    <div class="form-group col-md-6">
        <h6>Tanggal Terakhir Diubah</h6>
        <p>{{ date('d-m-Y H:i:s', strtotime($supplier->updated_at)) }}</p>
    </div>
</div>

<div class="form-group row text-center">
    <div class="form-group col-md-6">
        <h6>Jumlah Pembelian Berlangsung</h6>
        <p>{{ $pembelianDariSupplier->where('tanggal_bayar', null)->count() }}</p>
    </div>
    <div class="form-group col-md-6">
        <h6>Jumlah Riwayat Pembelian</h6>
        <p>{{ $pembelianDariSupplier->where('tanggal_bayar', '!=', null)->count() }}</p>
    </div>
</div>

<br>

<div class="form-group row text-center">
    <div class="col-md-12">
        <h6>Daftar Pembelian Stok Produk :</h6>
        <table id="tabelDaftarPembelianDariSupplier"
            class="table table-striped table-bordered dt-responsive wrap text-center"
            style="border-collapse: collapse; border-spacing: 0; width: 100%;">
            <thead>
                <tr>
                    <th class="align-middle">Nomor Nota</th>
                    <th class="align-middle">Nama Supplier</th>
                    <th class="align-middle">Tanggal Pembelian</th>
                    <th class="align-middle">Tanggal Pembayaran</th>
                    <th class="align-middle">Total Pembayaran</th>
                    <th class="align-middle">Karyawan Penerima</th>
                    <th hidden class="align-middle">Tanggal Pembuatan</th>
                    <th hidden class="align-middle">Tanggal Edit Terakhir</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($pembelianDariSupplier as $p)
                    <tr id="tr_{{ $p->id }}">
                        <td>{{ $p->nomor_nota }}</td>
                        <td>{{ $p->supplier->nama }}</td>
                        <td>{{ date('d-m-Y', strtotime($p->tanggal_beli)) }}</td>
                        <td>
                            @if ($p->tanggal_bayar == null)
                                Belum ada Tanggal Bayar
                            @else
                                {{ date('d-m-Y', strtotime($p->tanggal_bayar)) }}
                            @endif

                        </td>
                        <td>{{ number_format($p->total, 2, ',', '.') }}</td>
                        <td>{{ $p->karyawan->nama }}</td>
                        <td hidden>{{ date('d-m-Y', strtotime($p->created_at)) }}</td>
                        <td hidden>{{ date('d-m-Y', strtotime($p->updated_at)) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
