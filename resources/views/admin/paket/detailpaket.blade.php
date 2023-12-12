<div class="form-group row text-center">
    <div class="form-group col-md-12">
        <img src="{{ asset('assets_admin/images/paket/') }}/{{ $paket->gambar }}" alt="gambarPaket" style="max-height: 500px;" class="img-fluid">
    </div>
</div>
<div class="form-group row text-center">
    <div class="form-group col-md-6">
        <h6>Tanggal Pembuatan</h6>
        <p>{{ date('d-m-Y H:i:s', strtotime($paket->created_at)) }}</p>
    </div>
    <div class="form-group col-md-6">
        <h6>Tanggal Terakhir Diubah</h6>
        <p>{{ date('d-m-Y H:i:s', strtotime($paket->updated_at)) }}</p>
    </div>
</div>

<div class="form-group row text-center">
    <div class="form-group col-md-6">
        <h6>Jumlah Direservasi</h6>
        <p>{{ $paket->jmlh_reservasi }}</p>
    </div>
    <div class="form-group col-md-6">
        <h6>Jumlah Tanpa Reservasi</h6>
        <p>{{ $paket->jmlh_tanpa_reservasi }}</p>
    </div>
</div>

<div class="form-group text-center">
    <div class="form-group col-md-12">
        <h6>Daftar Perawatan dalam Paket </h6>
        <table id="tabelDaftarPaketPerawatan" class="table dt-responsive table-bordered table-striped text-center"
            style="border-collapse: collapse; border-spacing: 0; width: 100%;">
            <thead>
                <tr>
                    <th>Nama Perawatan</th>
                    <th>Durasi(Menit)</th>
                </tr>
            </thead>
            <tbody id="bodyListPerawatanProduk">
                @foreach ($paket->perawatans()->withPivot("urutan")->orderBy("urutan")->get() as $p)
                    <tr>
                        <td>
                            {{ $p->nama }}
                        </td>
                        <td>
                            {{ $p->durasi }}
                        </td>
                    </tr>
                @endforeach

            </tbody>
        </table>
    </div>
</div>
<br>
<div class="form-group text-center">
    <div class="form-group col-md-12">
        <h6>Daftar Produk dalam Paket </h6>
        <table id="tabelDaftarPaketProduk" class="table dt-responsive table-bordered table-striped text-center"
            style="border-collapse: collapse; border-spacing: 0; width: 100%;">
            <thead>
                <tr>
                    <th>Nama Produk</th>
                    <th>Jumlah</th>
                </tr>
            </thead>
            <tbody id="bodyListPerawatanProduk">
                @foreach ($paket->produks as $p)
                    <tr>
                        <td>
                            {{ $p->nama }}
                        </td>
                        <td>
                            {{ $p->pivot->jumlah }}
                        </td>
                    </tr>
                @endforeach

            </tbody>
        </table>
    </div>
</div>
