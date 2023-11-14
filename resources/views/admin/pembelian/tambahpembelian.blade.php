@extends('layout.adminlayout')

@section('title', 'Admin || Tambah Pembelian Produk')

@section('admincontent')
    <div class="page-title-box">

    </div>
    <!-- end page-title -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <h3 class="mt-0 header-title" id="grupAktif">Tambah Pembelian Produk</h3>
                    <p class="sub-title">
                    </p>
                    <button class="btn btn-info waves-effect waves-light" data-toggle="modal" id="btnKeranjang"><i
                            class="mdi mdi-basket"></i> &nbsp;
                        Detail Pembelian Produk</button>
                    <a class="btn btn-primary waves-effect waves-light" href="{{ route('pembelians.index') }}">
                        Daftar Pembelian</a>
                    {{-- @if ($penjualan->reservasi != null)
                        <a class="btn btn-primary waves-effect waves-light"
                            href="{{ route('reservasi.admin.detailreservasi', $penjualan->reservasi->id) }}">
                            Batal Tambah Produk</a>
                    @else
                        
                    @endif --}}

                    <br>
                    <br>
                    @if (session('status'))
                        <div class="alert alert-success">{{ session('status') }}</div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible" role="alert">
                            <button type="button" class="close text-danger" data-dismiss="alert" aria-label="Close">
                                <span class="text-danger" aria-hidden="true">&times;</span>
                            </button>
                            <p class="mb-0"><strong>Maaf, terjadi kesalahan!</strong></p>
                            @foreach ($errors->all() as $error)
                                <p class="mt-0 mb-1">- {{ $error }}</p>
                            @endforeach
                        </div>
                    @endif

                    @if (count($produksKurangDariMinimumStok) != 0)
                        <div class="form-group row">
                            <div class="col-md-6">
                                <h4>Produk Minimum Stok</h4>
                            </div>
                            <div class="col-md-6 text-right">
                                <div class="btn-group btn-group-toggle border">
                                    <a href="#grupAktif" class="btn btn-info waves-effect waves-light radioAktif">
                                        <span
                                            class="badge badge-danger badge-pill float-right ml-2">{{ count($produksKurangDariMinimumStok) }}</span><span>Produk
                                            Minimum Stok</span>
                                    </a>
                                    <a href="#grupNonaktif" class="btn waves-effect waves-light radioNonaktif">
                                        Daftar Produk
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="form-group col-md-12">
                                <table id="tabelDaftarProdukMinimumStok"
                                    class="table table-striped table-bordered dt-responsive wrap text-center"
                                    style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                    <thead class="">
                                        <tr>
                                            <th class="align-middle">Kode Produk</th>
                                            <th class="align-middle">Nama</th>
                                            <th class="align-middle">Merek</th>
                                            <th class="align-middle">Harga Beli(Rp)</th>
                                            <th class="align-middle">Harga Jual(Rp)</th>
                                            <th class="align-middle">Stok</th>
                                            <th class="align-middle">Minimum Stok</th>
                                            <th class="align-middle">Kategori</th>
                                            <th class="align-middle">Kondisi</th>
                                            <th class="align-middle">Deskripsi</th>
                                            <th class="align-middle">Tambah ke Keranjang</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($produksKurangDariMinimumStok as $p)
                                            <tr id="tr_{{ $p->id }}">
                                                <td>{{ $p->kode_produk }}</td>
                                                <td>{{ $p->nama }}</td>
                                                <td>{{ $p->merek->nama }}</td>
                                                <td>{{ $p->harga_beli }}</td>
                                                <td>{{ $p->harga_jual }}</td>
                                                <td class="text-danger">{{ $p->stok }}</td>
                                                <td class="text-danger">{{ $p->minimum_stok }}</td>
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
                                                    <button id="btnTambahKeranjang_{{ $p->id }}"
                                                        class="btn btn-info waves-effect waves-light btnTambahKeranjang"
                                                        idProduk='{{ $p->id }}' namaProduk='{{ $p->nama }}'
                                                        hargaProduk='{{ $p->harga_beli }}'
                                                        stokProduk='{{ $p->stok }}'
                                                        minimumStokProduk='{{ $p->minimum_stok }}'>Tambah</button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot id="grupNonaktif">

                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        <br>
                        <div class="form-group row">
                            <div class="col-md-6">
                                <h4>Daftar Produk</h4>
                            </div>
                            <div class="col-md-6 text-right">
                                <div class="btn-group btn-group-toggle border">
                                    <a href="#grupAktif" class="btn btn-info waves-effect waves-light radioAktif">
                                        <span
                                            class="badge badge-danger badge-pill float-right ml-2">{{ count($produksKurangDariMinimumStok) }}</span><span>Produk
                                            Minimum Stok</span>
                                    </a>
                                    <a href="#grupNonaktif" class="btn waves-effect waves-light radioNonaktif">
                                        Daftar Produk
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if (count($produksKurangDariMinimumStok) == 0)
                        <div class="form-group row">
                            <div class="col-md-6">
                                <h4>Daftar Produk</h4>
                            </div>
                        </div>
                    @endif

                    <div class="form-group row">
                        <div class="form-group col-md-12">
                            <table id="tabelDaftarProduk"
                                class="table table-striped table-bordered dt-responsive wrap text-center"
                                style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead class="">
                                    <tr>
                                        <th class="align-middle">Kode Produk</th>
                                        <th class="align-middle">Nama</th>
                                        <th class="align-middle">Merek</th>
                                        <th class="align-middle">Harga Beli(Rp)</th>
                                        <th class="align-middle">Harga Jual(Rp)</th>
                                        <th class="align-middle">Stok</th>
                                        <th class="align-middle">Minimum Stok</th>
                                        <th class="align-middle">Kategori</th>
                                        <th class="align-middle">Kondisi</th>
                                        <th class="align-middle">Deskripsi</th>
                                        <th class="align-middle">Tambah ke Keranjang</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($produks as $p)
                                        <tr id="tr_{{ $p->id }}">
                                            <td>{{ $p->kode_produk }}</td>
                                            <td>{{ $p->nama }}</td>
                                            <td>{{ $p->merek->nama }}</td>
                                            <td>{{ $p->harga_beli }}</td>
                                            <td>{{ $p->harga_jual }}</td>
                                            <td>{{ $p->stok }}</td>
                                            <td>{{ $p->minimum_stok }}</td>
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
                                                <button id="btnTambahKeranjang_{{ $p->id }}"
                                                    class="btn btn-info waves-effect waves-light btnTambahKeranjang"
                                                    idProduk='{{ $p->id }}' namaProduk='{{ $p->nama }}'
                                                    hargaProduk='{{ $p->harga_beli }}' stokProduk='{{ $p->stok }}'
                                                    minimumStokProduk='{{ $p->minimum_stok }}'>Tambah</button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end col -->
    </div>

    {{-- <div id="modalKonfirmasiPenambahanProduk" class="modal fade bs-example-modal-center" tabindex="-1" role="dialog"
        aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title mt-0">Konfirmasi Pembelian Produk Tambahan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h6>Apakah Anda ingin menambah produk untuk dibeli?</h6>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-info waves-effect" data-dismiss="modal">Ya</button>
                    @if ($penjualan->reservasi != null)
                        <a type="button"
                            href="{{ route('reservasi.admin.detailreservasi', $penjualan->reservasi->id) }}"
                            class="btn btn-secondary waves-effect waves-light">Tidak</a>
                    @else
                    @endif
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div> --}}

    <div id="modalDetailProduk" class="modal fade bs-example-modal-center" tabindex="-1" role="dialog"
        aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 id="modalNamaProduk" class="modal-title mt-0">Nama Produk</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div id="modalSettingKuantitasProduk" class="modal-body text-center">
                    <h6>Masukkan jumlah produk yang ingin Anda beli!</h6>
                    <div id="divSetNumberJumlahProduk" class="form-group text-center row">
                        <div class="col-md-3 text-right">
                            <button class="btn btn-info waves-effect waves-light btnMinJumlah">
                                -
                            </button>
                        </div>
                        <div class="col-md-6">
                            <input class="text-center form-control" id="setNumberJumlahProduk" type="number"
                                value="1" name="setNumberJumlahProduk" min="1" />
                        </div>
                        <div class="col-md-3 text-left">
                            <button class="btn btn-info waves-effect waves-light btnPlusJumlah">
                                +
                            </button>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">Batalkan</button>
                    <button id="btnSimpanJumlahProduk" type="button" idButtonTambahKeranjang=''
                        class="btn btn-info waves-effect waves-light btnSimpanJumlahProduk">Simpan</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

    <div id="modalPeringatanTidakBolehMin" class="modal fade bs-example-modal-center" tabindex="-1" role="dialog"
        aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 id="modalPeirngatanTidakBolehMin" class="modal-title mt-0">Terjadi Kesalahan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center d-flex align-items-center justify-content-center"
                    style="overflow-y: auto; height: 150px">
                    <h5 id="textModal" class="text-danger">Kuantitas produk yang ingin dibeli minimal 1!</h5>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">Tutup</button>

                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

    <div id="modalKeranjang" class="modal fade bs-example-modal-center" tabindex="-1" role="dialog"
        aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
            <div class="modal-content">
                <form id="formSubmitPembelian" action="{{ route('pembelians.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 id="modalNamaProduk" class="modal-title mt-0">Detail Pembelian Produk</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div id="modalDetailKeranjang" class="modal-body" style="overflow-y: auto; max-height: 500px">

                        <div class="form-group pl-3 pr-3">
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <label for="exampleInputEmail1"><strong>Detail Produk</strong></label>
                                    <table id="tabelKeranjangProduk"
                                        class="table table-striped table-bordered dt-responsive wrap text-center"
                                        style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                        <thead>
                                            <tr>
                                                <th>Nama</th>
                                                <th>Harga(Rp)</th>
                                                <th>Jumlah</th>
                                                <th>Sub Total(Rp)</th>
                                                <th>Hapus</th>
                                            </tr>
                                        </thead>
                                        <tbody id="bodyTabelKeranjang">
                                            <tr id="trSilahkan">
                                                <td colspan="5">Silahkan tambahkan produk terlebih dahulu!</td>
                                            </tr>

                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td id="tabelTotalHarga" colspan="5"
                                                    class="font-weight-bold h5 text-right"><span
                                                        class="font-weight-normal">Total Harga : </span><span
                                                        class="text-danger">Rp. 0,00</span>
                                                </td>
                                            </tr>

                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <label for="exampleInputEmail1"><strong>Nomor Nota</strong></label>
                                    <input type="text" class="form-control txtNomorNotaPembelian" name="nomorNota"
                                        id="txtNomorNotaPembelian" aria-describedby="emailHelp"
                                        placeholder="Silahkan masukkan nomor nota pembelian" required
                                        value="{{ old('nomorNota') }}">
                                    <small id="emailHelp" class="form-text text-muted">Masukkan nomor nota pembelian
                                        disini!</small>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-6">
                                    <label for="exampleInputEmail1"><strong>Supplier</strong></label><br>
                                    <select name="supplierPembelian" id="selectSupplier" class="form-control"
                                        aria-label="Default select example" required>
                                        @foreach ($suppliers as $s)
                                            @if (old('supplierPembelian') == $s->id)
                                                <option value="{{ $s->id }}" selected>{{ $s->nama }}</option>
                                            @else
                                                <option value="{{ $s->id }}">{{ $s->nama }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    <small id="emailHelp" class="form-text text-muted">Pilih supplier produk
                                        disini!
                                    </small>
                                </div>
                                <div class="col-md-6">
                                    <label for="exampleInputEmail1"><strong>Nama Karyawan</strong></label><br>
                                    <select name="namaKaryawan" id="selectNamaKaryawan" class="form-control"
                                        aria-label="Default select example" required>
                                        @foreach ($karyawansAdmin as $k)
                                            @if (old('namaKaryawan') == $k->id)
                                                <option value="{{ $k->id }}" selected>{{ $k->nama }}</option>
                                            @else
                                                <option value="{{ $k->id }}">{{ $k->nama }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    <small id="emailHelp" class="form-text text-muted">Pilih nama karyawan yang
                                        bertanggung
                                        jawab
                                        disini!
                                    </small>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-6">
                                    <label for="exampleInputEmail1"><strong>Tanggal Pembelian</strong></label>
                                    <input type="date" class="form-control" name="tanggalPembelian"
                                        id="tanggalPembelian" aria-describedby="emailHelp"
                                        placeholder="Silahkan pilih tanggal pembelian produk"
                                        value="{{ old('tanggalPembelian') }}" required>
                                    <small id="emailHelp" class="form-text text-muted">Pilih tanggal pembelian produk
                                        disini!</small>
                                </div>
                                <div class="col-md-6">
                                    <label for="exampleInputEmail1"><strong>Tanggal Pembayaran</strong></label>
                                    <input type="date" class="form-control" name="tanggalPembayaran"
                                        id="tanggalPembayaran" aria-describedby="emailHelp"
                                        placeholder="Silahkan pilih tanggal pebayaran pembelian"
                                        value="{{ old('tanggalPembayaran') }}">
                                    <small id="emailHelp" class="form-text text-muted">Pilih tanggal pembayaran pembelian
                                        disini!</small>
                                </div>
                            </div>
                            <br>


                        </div>

                        {{-- @if (count($penjualan->produks) > 0)
                            @foreach ($penjualan->produks as $produk)
                                <input type="hidden" value="{{ $produk->id }}" id="produk_{{ $produk->id }}"
                                    name="arrayproduk[]">
                                <input type="hidden" value="{{ $produk->pivot->kuantitas }}"
                                    id="stokproduk_{{ $produk->id }}" name="arraystokproduk[]">
                            @endforeach
                        @endif --}}


                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-warning waves-effect" data-dismiss="modal">Pilih
                            Produk</button>
                        <div id="divBtnKonfirmasi">
                            <button id="btnKonfirmasiKeranjang" type="submit" idKeranjang='' disabled
                                title="Pastikan telah memilih produk yang akan dibeli terlebih dahulu!"
                                data-toggle='tooltip' data-placement='top'
                                class="btn btn-info waves-effect waves-light btnKonfirmasiKeranjang">Konfirmasi</button>
                        </div>

                    </div>
                </form>
            </div>

        </div>
        <!-- /.modal-dialog -->
    </div>
@endsection

@section('javascript')
    <script>
        $(document).ready(function() {
            $('#tabelDaftarProduk').DataTable();
            $('#tabelDaftarProdukMinimumStok').DataTable();
            // if ($('#bodyTabelKeranjang').find("#trSilahkan").length > 0) {
            //     $('#modalKonfirmasiPenambahanProduk').modal('show');
            // }

            $('#modalKeranjang').modal('show');
        });

        $('.radioAktif').on('click', function() {
            $(".radioAktif").addClass("btn-info");
            $(".radioNonaktif").removeClass("btn-info");
        });

        $('.radioNonaktif').on('click', function() {
            $(".radioNonaktif").addClass("btn-info");
            $(".radioAktif").removeClass("btn-info");
        });

        $('.btnTambahKeranjang').on('click', function() {
            var idProduk = $(this).attr('idProduk');
            var namaProduk = $(this).attr('namaProduk');
            // var stokProduk = $(this).attr('stokProduk');
            //var minimumStokProduk = $(this).attr('minimumStokProduk');
            //var batasStok = stokProduk - minimumStokProduk;
            $("#modalNamaProduk").text(namaProduk);
            $('#setNumberJumlahProduk').val(1);
            $('#btnSimpanJumlahProduk').attr('idButtonTambahKeranjang', "btnTambahKeranjang_" + idProduk)
            $("#modalDetailProduk").modal('show');

        });

        $('.btnMinJumlah').on('click', function() {
            var value = parseInt($('#setNumberJumlahProduk').val());
            value = value - 1;
            if (value < 1) {
                value = 1;
            }
            $('#setNumberJumlahProduk').val(value);

        });

        $('.btnPlusJumlah').on('click', function() {
            var value = parseInt($('#setNumberJumlahProduk').val());
            value = value + 1;
            $('#setNumberJumlahProduk').val(value);

        });

        $('body').on('keydown', 'input', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
            }
        });

        $('body').on('change', '.numHargaProduk', function() {
            var numUpDownharga = $(this);
            var idProduk = numUpDownharga.attr('idProduk');
            var value = parseInt(numUpDownharga.val());
            if (value < 1) {
                numUpDownharga.val("1");
                value = 1;
            }
            var newSubTotal = value * parseInt($("#kolomStokDiambil_" + idProduk).text());
            $("#kolomSubTotal_" + idProduk).text(parseInt(newSubTotal).toLocaleString('id-ID', {
                style: 'currency',
                currency: 'IDR'
            }).replace('Rp', ''));
            $("#btnHapusKeranjang_" + idProduk).attr("subTotal", newSubTotal);

            var totalHarga = 0;
            $(".btnHapusKeranjang").each(function(index) {
                totalHarga += parseInt($(this).attr('subTotal'));
            });
            $("#tabelTotalHarga").html(
                "<span class='font-weight-normal'>Total harga : </span> <span class='text-danger'>" + totalHarga
                .toLocaleString('id-ID', {
                    style: 'currency',
                    currency: 'IDR'
                }) + "</span>");
        });


        $('.btnSimpanJumlahProduk').on('click', function() {
            var idButtonTambahKeranjang = $('#btnSimpanJumlahProduk').attr('idButtonTambahKeranjang');
            var stokSaatIni = parseInt($("#" + idButtonTambahKeranjang).attr('stokProduk'));
            var stokDiambil = parseInt($("#setNumberJumlahProduk").val());

            if (!isNaN(stokDiambil)) {
                if (stokDiambil <= 0) {
                    $("#setNumberJumlahProduk").val("1");
                    $('#textModal').text("Kuantitas produk yang ingin dibeli minimal berjumlah 1!");
                    $("#modalPeringatanTidakBolehMin").modal("show");
                } else {
                    //Tambahkan ke modal keranjang
                    $("#trSilahkan").remove();
                    var namaProduk = $("#" + idButtonTambahKeranjang).attr('namaProduk');
                    var hargaProduk = $("#" + idButtonTambahKeranjang).attr('hargaProduk');
                    var idProduk = $("#" + idButtonTambahKeranjang).attr('idProduk');

                    var check = false;
                    $("#bodyTabelKeranjang tr").each(function(index) {
                        if ($(this).attr('id') == 'barisKeranjang_' + idProduk) {
                            check = true;
                        }
                    });

                    if (check == true) {
                        var stokSaatIni = parseInt($("#kolomStokDiambil_" + idProduk).text());
                        var stokBaruDiKeranjang = stokSaatIni + stokDiambil;
                        var hargaSaatIni = parseInt($("#kolomHargaProduk_" + idProduk).val());
                        $("#kolomStokDiambil_" + idProduk).text(stokBaruDiKeranjang);
                        $("#kolomSubTotal_" + idProduk).text(parseInt(hargaSaatIni * stokBaruDiKeranjang)
                            .toLocaleString(
                                'id-ID', {
                                    style: 'currency',
                                    currency: 'IDR'
                                }).replace('Rp', ''));
                        $("#btnHapusKeranjang_" + idProduk).attr('subTotal', hargaSaatIni * stokBaruDiKeranjang);
                        $("#stokproduk_" + idProduk).val(stokBaruDiKeranjang);
                    } else {
                        var subTotal = parseInt(hargaProduk) * stokDiambil;
                        $("#bodyTabelKeranjang").append(
                            "<tr id='barisKeranjang_" + idProduk + "'>" +
                            "<td>" + namaProduk + "</td>" +
                            "<td><input type='number' min='1' value='" + hargaProduk +
                            "' name='arrayhargaproduk[]' id='kolomHargaProduk_" + idProduk +
                            "' class='form-control numHargaProduk' idProduk='" + idProduk + "'></td>" +
                            "<td id='kolomStokDiambil_" + idProduk + "'>" + stokDiambil + "</td>" +
                            "<td id='kolomSubTotal_" + idProduk + "'>" + subTotal.toLocaleString(
                                'id-ID', {
                                    style: 'currency',
                                    currency: 'IDR'
                                }).replace('Rp', '') + "</td>" +
                            "<td>" + "<button class='btn btn-danger btnHapusKeranjang' id='btnHapusKeranjang_" +
                            idProduk +
                            "' idProduk='" + idProduk + "' subTotal='" + subTotal +
                            "'>Hapus</button>" + "</td>" +
                            "</tr>");

                        $("#modalDetailKeranjang").append("<input type='hidden' value='" + idProduk +
                            "' id='produk_" +
                            idProduk + "' name='arrayproduk[]'><input type='hidden' value='" + stokDiambil +
                            "' id='stokproduk_" + idProduk + "' name='arraystokproduk[]'>");
                    }
                    $("#modalDetailProduk").modal('hide');
                }
            } else {
                $("#setNumberJumlahProduk").val("1");
                $('#textModal').text("Mohon masukkan inputan kuantitas berupa angka!");
                $("#modalPeringatanTidakBolehMin").modal("show");
            }


        });

        $('#btnKeranjang').on('click', function() {

            var totalHarga = 0;
            $(".btnHapusKeranjang").each(function(index) {
                totalHarga += parseInt($(this).attr('subTotal'));
            });
            $("#tabelTotalHarga").html(
                "<span class='font-weight-normal'>Total harga : </span> <span class='text-danger'>" + totalHarga
                .toLocaleString('id-ID', {
                    style: 'currency',
                    currency: 'IDR'
                }) + "</span>");

            var totalHarga = 0;
            $(".btnHapusKeranjang").each(function(index) {
                totalHarga += parseInt($(this).attr('subTotal'));
            });
            if (totalHarga == 0) {
                $("#divBtnKonfirmasi").html(
                    "<button id='btnKonfirmasiKeranjang' type='submit' idKeranjang='' disabled title = 'Pastikan telah memilih produk yang akan dibeli terlebih dahulu!' data-toggle='tooltip' data-placement='top' class = 'btn btn-info waves-effect waves-light btnKonfirmasiKeranjang' > Konfirmasi </button>"
                );
            } else {
                $("#divBtnKonfirmasi").html(
                    "<button id='btnKonfirmasiKeranjang' type='submit' idKeranjang='' class = 'btn btn-info waves-effect waves-light btnKonfirmasiKeranjang' > Konfirmasi </button>"
                );
            }
            $("#modalKeranjang").modal('show');
        });

        $('body').on('click', '.btnHapusKeranjang', function() {
            var idProduk = $(this).attr('idProduk');
            var stokDiambil = parseInt($(this).attr('stokDiambil'));
            var stokSaatIni = parseInt($('#btnTambahKeranjang_' + idProduk).attr('stokProduk'));

            $(this).parent().parent().remove();

            if ($('#bodyTabelKeranjang').find("tr").length == 0) {
                $('#bodyTabelKeranjang').html(
                    "<tr id='trSilahkan'><td colspan='5'>Silahkan tambahkan produk terlebih dahulu!</td></tr>"
                );
                $("#divBtnKonfirmasi").html(
                    "<button id='btnKonfirmasiKeranjang' type='submit' idKeranjang='' disabled title = 'Pastikan telah memilih produk yang akan dibeli terlebih dahulu!' data-toggle='tooltip' data-placement='top' class = 'btn btn-info waves-effect waves-light btnKonfirmasiKeranjang' > Konfirmasi </button>"
                );
                // $("#tabelTotalHarga").html(
                //     "<span class='font-weight-normal'>Total harga : </span> <span class='text-danger'>Rp. 0</span>"
                // );
                // $("#btnKonfirmasiKeranjang").attr('hidden', true);
            } else {
                $("#divBtnKonfirmasi").html(
                    "<button id='btnKonfirmasiKeranjang' type='submit' idKeranjang='' class = 'btn btn-info waves-effect waves-light btnKonfirmasiKeranjang'> Konfirmasi </button>"
                );
            }

            var totalHarga = 0;
            $(".btnHapusKeranjang").each(function(index) {
                totalHarga += parseInt($(this).attr('subTotal'));
            });
            $("#tabelTotalHarga").html(
                "<span class='font-weight-normal'>Total harga : </span> <span class='text-danger'>" +
                totalHarga
                .toLocaleString('id-ID', {
                    style: 'currency',
                    currency: 'IDR'
                }) + "</span>");

            $("#produk_" + idProduk).remove();
            $("#stokproduk_" + idProduk).remove();

        });
    </script>
@endsection
