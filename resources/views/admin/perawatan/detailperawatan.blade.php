<div class="form-group row text-center">
    <div class="form-group col-md-6">
        <h6>Tanggal Pembuatan</h6>
        <p>{{ date('d-m-Y H:i:s', strtotime($perawatan->created_at)) }}</p>
    </div>
    <div class="form-group col-md-6">
        <h6>Tanggal Terakhir Diubah</h6>
        <p>{{ date('d-m-Y H:i:s', strtotime($perawatan->updated_at)) }}</p>
    </div>
</div>

<div class="form-group row text-center">
    <div class="form-group col-md-6">
        <h6>Jumlah Direservasi</h6>
        <p>{{ $perawatan->jmlh_reservasi }}</p>
    </div>
    <div class="form-group col-md-6">
        <h6>Jumlah Tanpa Reservasi</h6>
        <p>{{ $perawatan->jmlh_tanpa_reservasi }}</p>
    </div>
</div>

<div class="form-group text-center">
    <div class="form-group col-md-12">
        <h6>Deskripsi Perawatan</h6>
        <p>{{ $perawatan->deskripsi }}</p>
    </div>
</div>
<br>

<div class="form-group text-center">
    <div class="form-group col-md-12">
        <h6>Produk yang digunakan :</h6>
        <table id="tabelDaftarPerawatanProduk" class="table table-bordered table-striped text-center"
            style="border-collapse: collapse; border-spacing: 0; width: 100%;">
            <thead>
                <tr>
                    <th>Nama Produk</th>
                </tr>
            </thead>
            <tbody id="bodyListPerawatanProduk">
                @if (count($perawatan->produks) == 0)
                    <tr>
                        <td>
                            Tidak ada produk yang digunakan!
                        </td>
                    </tr>
                @else
                    @foreach ($perawatan->produks as $p)
                        <tr>
                            <td>
                                {{ $p->nama }}
                            </td>
                        </tr>
                    @endforeach
                @endif

            </tbody>
        </table>
    </div>

</div>
