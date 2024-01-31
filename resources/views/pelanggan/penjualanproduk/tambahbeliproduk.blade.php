@extends('layout.pelangganlayout')

@section('title', 'Pelanggan || Penambahan Produk Pembelian')

@section('pelanggancontent')
    <style>
        .btnTambahClass {
            background-color: #273ED4;
        }

        .btnTambahClass:hover {
            background-color: #273ed4c3;
        }
    </style>
    <div class="page-title-box">

    </div>
    <!-- end page-title -->

    <div class="row" style="padding: 20px;">
        <div class="col-12">
            <div>
                <div class="card-body">

                    <h3 class="mt-0 header-title fw-bold">Pembelian Tambahan Produk</h3>
                    <h5 class="text-danger">Silahkan tambah produk dan konfirmasi melalui tombol "KERANJANG" jika Anda ingin membeli produk tambahan. <br> Jika tidak, silahkan tekan tombol "BATAL TAMBAH PRODUK" di bawah ini.</h5>
                    <p class="sub-title">
                    </p>

                    <div class="product-details-content quickview-content">
                        <div class="pro-details-quality" style="padding: 0px;margin: 0px;">
                            <div class="pro-details-cart ml-auto" style="width: 100%;display: flex; gap: 10px;">
                                <button class="add-cart " type="button" data-bs-toggle="modal" id="btnKeranjang"
                                    style="margin: 0px; background-color: #273ED4;">
                                    <span>
                                        <i class="fa fa-shopping-cart" style="font-size: 15px;" aria-hidden="true"></i>
                                        &nbsp; Keranjang
                                    </span>
                                </button>

                                <a class="add-cart " type="button"
                                    href="{{ route('reservasis.pelanggan.detailreservasi', $penjualan->reservasi->id) }}"
                                    style="margin: 0px; text-align: center;">
                                    <span>
                                        Batal Tambah Produk
                                    </span>
                                </a>
                            </div>
                        </div>
                    </div>
                    {{-- <div class="product-details-content quickview-content">
                        <div class="pro-details-quality" style="padding: 0px;margin: 0px;">
                            <div class="pro-details-cart ml-auto" style="width: 100%;">
                                
                            </div>
                        </div>
                    </div> --}}
                    <br>
                    @if (session('status'))
                        <div class="alert alert-success">{{ session('status') }}</div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible" role="alert">
                            <button type="button" class="close text-danger" data-bs-dismiss="alert" aria-label="Close">
                                <span class="text-danger" aria-hidden="true">&times;</span>
                            </button>
                            <p class="mb-0"><strong>Maaf, terjadi kesalahan!</strong></p>
                            @foreach ($errors->all() as $error)
                                <p class="mt-0 mb-1">- {{ $error }}</p>
                            @endforeach
                        </div>
                    @endif

                    <div class="billing-info-wrap">
                        <div class="billing-select">
                            <div class="table-responsive table_page">
                                <table id="tabelDaftarProduk"
                                    class="table border table-striped table-bordered dt-responsive wrap text-center"
                                    style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                    <thead class="">
                                        <tr>
                                            <th hidden class="align-middle">Kode Produk</th>
                                            <th class="align-middle">Nama</th>
                                            <th class="align-middle">Merek</th>
                                            <th class="align-middle">Harga(Rp)</th>
                                            <th class="align-middle">Stok</th>
                                            <th class="align-middle">Kategori</th>
                                            <th class="align-middle">Kondisi</th>
                                            <th class="align-middle">Deskripsi</th>
                                            <th class="align-middle">Tambah ke Keranjang</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($produks as $p)
                                            <tr id="tr_{{ $p->id }}" class="align-middle">
                                                <td hidden>{{ $p->kode_produk }}</td>
                                                <td>{{ $p->nama }}</td>
                                                <td>{{ $p->merek->nama }}</td>
                                                <td>{{ number_format($p->harga_jual, 2, ',', '.') }}</td>
                                                <td>{{ $p->stok }}</td>
                                                <td>{{ $p->kategori->nama }}</td>
                                                <td class="text-left">
                                                    <ul>
                                                        @foreach ($p->kondisis as $kondisi)
                                                            <li>{{ $kondisi->keterangan }}</li>
                                                        @endforeach
                                                    </ul>

                                                </td>

                                                <td>{{ $p->deskripsi }}</td>
                                                <td class='product-wishlist-cart'>
                                                    @if ($p->stok > 0)
                                                        <button id="btnTambahKeranjang_{{ $p->id }}"
                                                            style='width:100%;height:30px; border-radius:3px;font-weight: normal; padding-right: 10px; padding-left: 10px;'
                                                            class="btn btnTambahClass text-white waves-effect waves-light btnTambahKeranjang"
                                                            idProduk='{{ $p->id }}'
                                                            namaProduk='{{ $p->nama }}'
                                                            hargaProduk='{{ $p->harga_jual }}'
                                                            stokProduk='{{ $p->stok }}'
                                                            minimumStokProduk='{{ $p->minimum_stok }}'>Tambah</button>
                                                    @else
                                                        <button id="btnTambahKeranjang_{{ $p->id }}"
                                                            style='width:100%;height:30px; border-radius:3px;font-weight: normal; padding-right: 10px; padding-left: 10px;'
                                                            class="btn btn-danger waves-effect waves-light btnTambahKeranjang"
                                                            idProduk='{{ $p->id }}'
                                                            namaProduk='{{ $p->nama }}'
                                                            hargaProduk='{{ $p->harga_jual }}'
                                                            stokProduk='{{ $p->stok }}'
                                                            minimumStokProduk='{{ $p->minimum_stok }}'
                                                            disabled>Habis</button>
                                                    @endif
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
        </div>
        <!-- end col -->
    </div>

    <div id="modalKonfirmasiPenambahanProduk" class="modal fade bs-example-modal-center" tabindex="-1" role="dialog"
        aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title mt-0">Konfirmasi Pembelian Produk Tambahan</h4>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h5>Apakah Anda ingin menambah produk untuk dibeli?</h5>
                </div>
                <div class="modal-footer">

                    <div class="product-details-content quickview-content">
                        <div class="pro-details-quality" style="padding: 0px;margin: 0px;">
                            <div class="pro-details-cart ml-auto" style="width: 100%;">
                                <button type="button" class="add-cart text-white" data-bs-dismiss="modal"
                                    style="margin: 0px; width: 100%; background-color: #273ED4">Ya
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="product-details-content quickview-content">
                        <div class="pro-details-quality" style="padding: 0px;margin: 0px;">
                            <div class="pro-details-cart ml-auto" style="width: 100%;">
                                {{-- <button type="button" class="add-cart" data-bs-dismiss="modal"
                                    style="margin: 0px; width: 100%;">Ya
                                </button> --}}
                                <a type="button" style="margin: 0px; width: 100%;"
                                    href="{{ route('reservasis.pelanggan.detailreservasi', $penjualan->reservasi->id) }}"
                                    class="add-cart">Tidak
                                </a>
                            </div>
                        </div>
                    </div>



                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

    <div id="modalDetailProduk" class="modal fade bs-example-modal-center" tabindex="-1" role="dialog"
        aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 id="modalNamaProduk" class="modal-title mt-0">Nama Produk</h4>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div id="modalSettingKuantitasProduk" class="modal-body text-center">
                    <h5>Masukkan jumlah produk yang ingin Anda beli!</h5>
                    <div class="default-form-box" style="margin-bottom: 20px">
                        <div id="divSetNumberJumlahProduk"
                            class="form-group text-center row d-flex align-items-center justify-content-center">
                            <div class="col-md-1">

                            </div>
                            <div class="col-md-2 text-right">
                                <div class="product-details-content quickview-content">
                                    <div class=" pro-details-quality" style="padding: 0px;margin: 0px;">
                                        <div class="pro-details-cart ml-auto" style="width: 100%; ">
                                            <div style="padding-left: 5px;">
                                                <button type="button"
                                                    style="margin: 0px; width: 100%; font-size: 30px; background-color: #273ED4; "
                                                    class="add-cart btnMinJumlah">-</button>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <input class="text-center form-control" id="setNumberJumlahProduk" type="number"
                                    value="1" name="setNumberJumlahProduk" min="1" max="100" />
                            </div>
                            <div class="col-md-2 text-left">
                                <div class="product-details-content quickview-content" style="padding: 0px;margin: 0px;">
                                    <div class="pro-details-quality" style="padding: 0px;margin: 0px;">
                                        <div class="pro-details-cart ml-auto" style="width: 100%;">
                                            <button type="button" class="add-cart btnPlusJumlah"
                                                style="margin: 0px; width: 100%; font-size: 30px; background-color: #273ED4;">+</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-1">

                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <div class="product-details-content quickview-content">
                        <div class="pro-details-quality" style="padding: 0px;margin: 0px;">
                            <div class="pro-details-cart ml-auto" style="width: 100%;">
                                <button type="button" data-bs-dismiss="modal" class="btn close add-cart"
                                    style="margin: 0px; width: 100%; ">Batalkan</button>
                            </div>
                        </div>
                    </div>

                    <div class="product-details-content quickview-content">
                        <div class="pro-details-quality" style="padding: 0px;margin: 0px;">
                            <div class="pro-details-cart ml-auto" style="width: 100%;">
                                <button type="button" data-bs-dismiss="modal"
                                    class="btn close add-cart btnSimpanJumlahProduk" idButtonTambahKeranjang=''
                                    id="btnSimpanJumlahProduk"
                                    style="margin: 0px; width: 100%; background-color: #273ED4">Simpan</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

    <div id="modalKeranjang" class="modal fade bs-example-modal-center" tabindex="-1" role="dialog"
        aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl" style="max-width: 900px;">
            <div class="modal-content">
                <form action="{{ route('penjualans.pelanggan.konfirmasipenambahanproduk') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h4 id="modalNamaProduk" class="modal-title mt-0">Keranjang Produk Anda</h4>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div id="modalDetailKeranjang" class="modal-body text-center"
                        style="overflow-y: auto; max-height: 60vh;">
                        <input type="hidden" value="{{ $penjualan->id }}" name="idPenjualan">

                        <table id="tabelKeranjangProduk" class="table table-striped table-bordered dt-responsive wrap"
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
                                @if (count($penjualan->produks) > 0)
                                    @foreach ($penjualan->produks as $p)
                                        <tr id="barisKeranjang_{{ $p->id }}" class="align-middle">
                                            <td>
                                                {{ $p->nama }}
                                                @if (count($penjualan->pakets) > 0)
                                                    @foreach ($penjualan->pakets as $paket)
                                                        @if ($paket->produks->firstWhere('id', $p->id) != null)
                                                            <br>
                                                            <span class="fw-bold" style="color: #273ED4;">*
                                                                {{ $paket->nama }} -
                                                                ({{ $paket->produks->firstWhere('id', $p->id)->pivot->jumlah }})
                                                            </span>
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </td>
                                            <td>{{ number_format($p->harga_jual, 2, ',', '.') }}</td>
                                            <td id="kolomStokDiambil_{{ $p->id }}">{{ $p->pivot->kuantitas }}</td>
                                            <td id="kolomSubTotal_{{ $p->id }}">
                                                {{ number_format($p->harga_jual * $p->pivot->kuantitas, 2, ',', '.') }}
                                            </td>
                                            <td class="product-wishlist-cart">
                                                @php
                                                    $jumlahMinimalStokDiambil = 0;
                                                @endphp
                                                @if (count($penjualan->pakets) > 0)
                                                    @foreach ($penjualan->pakets as $paket)
                                                        @if ($paket->produks->firstWhere('id', $p->id) != null)
                                                            @php
                                                                $jumlahMinimalStokDiambil += 1;
                                                            @endphp
                                                        @endif
                                                    @endforeach
                                                @endif
                                                <button class='btn btn-danger btnHapusKeranjang'
                                                    style='width:100%;height:30px; border-radius:3px;font-weight: normal; padding-right: 10px; padding-left: 10px;'
                                                    id="btnHapusKeranjang_{{ $p->id }}"
                                                    idProduk="{{ $p->id }}"
                                                    stokDiambil="{{ $p->pivot->kuantitas }}"
                                                    subTotal="{{ $p->harga_jual * $p->pivot->kuantitas }}"
                                                    minStok = "{{ $jumlahMinimalStokDiambil }}">Hapus
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr id="trSilahkan">
                                        <td colspan="5">Silahkan tambahkan produk terlebih dahulu!</td>
                                    </tr>
                                @endif

                            </tbody>
                            <tfoot>
                                @php
                                    $totalSementara = 0;
                                @endphp
                                @if (count($penjualan->produks) > 0)
                                    @foreach ($penjualan->produks as $p)
                                        @php
                                            $totalSementara += $p->harga_jual * $p->pivot->kuantitas;
                                        @endphp
                                    @endforeach
                                    <tr>
                                        <td id="tabelTotalHarga" colspan="5" class="font-weight-bold">Total Harga :
                                            Rp. {{ number_format($totalSementara, 2, ',', '.') }}
                                        </td>
                                    </tr>
                                @else
                                    <tr>
                                        <td id="tabelTotalHarga" colspan="5" class="font-weight-bold">Total Harga :
                                            Rp. 0,00
                                        </td>
                                    </tr>
                                @endif

                            </tfoot>
                        </table>

                        @if (count($penjualan->produks) > 0)
                            @foreach ($penjualan->produks as $produk)
                                <input type="hidden" value="{{ $produk->id }}" id="produk_{{ $produk->id }}"
                                    name="arrayproduk[]">
                                <input type="hidden" value="{{ $produk->pivot->kuantitas }}"
                                    id="stokproduk_{{ $produk->id }}" name="arraystokproduk[]">
                            @endforeach
                        @endif


                    </div>
                    <div class="modal-footer">
                        <div class="product-details-content quickview-content">
                            <div class="pro-details-quality" style="padding: 0px;margin: 0px;">
                                <div class="pro-details-cart ml-auto" style="width: 100%;display: flex; gap: 10px;">
                                    <button class="add-cart " id="btnKonfirmasiKeranjang" type="submit"
                                        data-bs-toggle="modal" style="margin: 0px; background-color: #273ED4;">
                                        Konfirmasi
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

        </div>
        <!-- /.modal-dialog -->
    </div>

    <div id="modalPeringatanTidakBolehMin" class="modal fade bs-example-modal-center" tabindex="-1" role="dialog"
        aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 id="modalPeirngatanTidakBolehMin" class="modal-title mt-0">Terjadi Kesalahan</h4>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center d-flex align-items-center justify-content-center"
                    style="overflow-y: auto; height: 150px">
                    <h5 id="textModal" class="text-danger">Kuantitas produk yang ingin dibeli sebagai tambahan produk
                        minimal berjumlah 1!
                    </h5>
                </div>
                <div class="modal-footer">
                    <div class="product-details-content quickview-content">
                        <div class="pro-details-quality" style="padding: 0px;margin: 0px;">
                            <div class="pro-details-cart ml-auto" style="width: 100%;">
                                <button type="button" data-bs-dismiss="modal" class="btn close add-cart"
                                    id="btnPeringatanTidakBolehMin" style="margin: 0px; width: 100%; ">Tutup</button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

    <div id="modalMinimalStokProdukDiambil" class="modal fade bs-example-modal-center" tabindex="-1" role="dialog"
        aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 id="modalMinimalStokProdukDiambil" class="modal-title mt-0">Terjadi Kesalahan</h4>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center d-flex align-items-center justify-content-center"
                    style="overflow-y: auto; height: 150px">
                    <h5 id="textModalMinimalStokProdukDiambil" class="text-danger">Kuantitas produk yang ingin dibeli
                        sebagai tambahan produk
                        minimal berjumlah 1!
                    </h5>
                </div>
                <div class="modal-footer">
                    <div class="product-details-content quickview-content">
                        <div class="pro-details-quality" style="padding: 0px;margin: 0px;">
                            <div class="pro-details-cart ml-auto" style="width: 100%;">
                                <button type="button" data-bs-dismiss="modal" class="btn close add-cart"
                                    id="btnMinimalStokProdukDiambil" style="margin: 0px; width: 100%; ">Tutup</button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

    <div id="modalBerhasilTambahProduk" class="modal fade bs-example-modal-center" tabindex="-1" role="dialog"
        aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 id="modalJudulBerhasilTambahProduk" class="modal-title mt-0">Keterangan Penambahan Produk</h4>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center d-flex align-items-center justify-content-center"
                    style="overflow-y: auto; height: 150px">
                    <h5 id="textModalBerhasilTambahProduk" >Berhasil menambahkan produk ke Keranjang
                    </h5>
                </div>
                <div class="modal-footer">
                    <div class="product-details-content quickview-content">
                        <div class="pro-details-quality" style="padding: 0px;margin: 0px;">
                            <div class="pro-details-cart ml-auto" style="width: 100%;">
                                <button type="button" data-bs-dismiss="modal" class="btn close add-cart"
                                    id="btnMBerhasilTambahProduk" style="margin: 0px; width: 100%; ">Tutup</button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
@endsection

@section('javascript')
    <script>
        $(document).ready(function() {
            $('#tabelDaftarProduk').DataTable();
            if ($('#bodyTabelKeranjang').find("#trSilahkan").length > 0) {
                $('#modalKonfirmasiPenambahanProduk').modal('show');
            }


        });

        $('.btnTambahKeranjang').on('click', function() {
            var idProduk = $(this).attr('idProduk');
            var namaProduk = $(this).attr('namaProduk');
            var stokProduk = $(this).attr('stokProduk');
            //var minimumStokProduk = $(this).attr('minimumStokProduk');
            //var batasStok = stokProduk - minimumStokProduk;
            $("#modalNamaProduk").text(namaProduk);
            $("#setNumberJumlahProduk").attr('max', stokProduk);
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
            var max = parseInt($('#setNumberJumlahProduk').attr('max'));
            value = value + 1;
            if (value > max) {
                value = max;
            }
            $('#setNumberJumlahProduk').val(value);

        });

        $('.btnSimpanJumlahProduk').on('click', function() {
            var idButtonTambahKeranjang = $('#btnSimpanJumlahProduk').attr('idButtonTambahKeranjang');
            var stokSaatIni = parseInt($("#" + idButtonTambahKeranjang).attr('stokProduk'));
            var stokDiambil = parseInt($("#setNumberJumlahProduk").val());

            if (!isNaN(stokDiambil)) {
                if (stokDiambil <= 0) {
                    $("#setNumberJumlahProduk").val("1");
                    $('#textModal').text(
                        "Kuantitas produk yang ingin dibeli sebagai tambahan produk minimal berjumlah 1!");
                    $("#modalDetailProduk").modal('hide');
                    $("#modalPeringatanTidakBolehMin").modal("show");
                } else if (stokDiambil > stokSaatIni) {
                    $("#setNumberJumlahProduk").val(stokSaatIni);
                    $('#textModal').text(
                        "Kuantitas produk yang ingin dibeli sebagai tambahan produk maksimal berjumlah " +
                        stokSaatIni + "!");
                    $("#modalDetailProduk").modal('hide');
                    $("#modalPeringatanTidakBolehMin").modal("show");
                } else {
                    $("#" + idButtonTambahKeranjang).attr('stokProduk', stokSaatIni - stokDiambil);
                    if (stokSaatIni - stokDiambil == 0) {
                        $("#" + idButtonTambahKeranjang).attr('disabled', true);
                        $("#" + idButtonTambahKeranjang).text('Habis');
                        $("#" + idButtonTambahKeranjang).removeClass('btn-info');
                        $("#" + idButtonTambahKeranjang).addClass('btn-danger');
                    }

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
                        $("#kolomStokDiambil_" + idProduk).text(stokBaruDiKeranjang);
                        $("#kolomSubTotal_" + idProduk).text((hargaProduk * stokBaruDiKeranjang).toLocaleString(
                            'id-ID', {
                                style: 'currency',
                                currency: 'IDR'
                            }).replace('Rp', ''));
                        $("#btnHapusKeranjang_" + idProduk).attr('stokDiambil', stokBaruDiKeranjang);
                        $("#btnHapusKeranjang_" + idProduk).attr('subTotal', hargaProduk * stokBaruDiKeranjang);
                        $("#stokproduk_" + idProduk).val(stokBaruDiKeranjang);
                    } else {
                        var subTotal = parseInt(hargaProduk) * stokDiambil;
                        $("#bodyTabelKeranjang").append(
                            "<tr id='barisKeranjang_" + idProduk + "'>" +
                            "<td>" + namaProduk + "</td>" +
                            "<td>" + parseInt(hargaProduk).toLocaleString('id-ID', {
                                style: 'currency',
                                currency: 'IDR'
                            }).replace('Rp', '') + "</td>" +
                            "<td id='kolomStokDiambil_" + idProduk + "'>" + stokDiambil + "</td>" +
                            "<td id='kolomSubTotal_" + idProduk + "'>" + subTotal.toLocaleString('id-ID', {
                                style: 'currency',
                                currency: 'IDR'
                            }).replace('Rp', '') + "</td>" +
                            "<td class='product-product-wishlist-cart'>" +
                            "<button class='btn btn-danger btnHapusKeranjang' style='width:100%;height:30px; border-radius:3px;font-weight: normal; padding-right: 10px; padding-left: 10px;' id='btnHapusKeranjang_" +
                            idProduk +
                            "' idProduk='" + idProduk + "' stokDiambil='" + stokDiambil + "' subTotal='" +
                            subTotal +
                            "' minStok='0'>Hapus</button>" + "</td>" +
                            "</tr>");

                        $("#modalDetailKeranjang").append("<input type='hidden' value='" + idProduk +
                            "' id='produk_" +
                            idProduk + "' name='arrayproduk[]'><input type='hidden' value='" + stokDiambil +
                            "' id='stokproduk_" + idProduk + "' name='arraystokproduk[]'>");
                    }
                    $("#modalDetailProduk").modal('hide');
                    $("#textModalBerhasilTambahProduk").html("Berhasil menambahkan produk <span class='text-success'>" + namaProduk + "</span> berjumlah <span  class='text-success'>" + stokDiambil + "</span> ke dalam Keranjang!");
                    $("#modalBerhasilTambahProduk").modal('show');
                }
            } else {
                $("#setNumberJumlahProduk").val("1");
                $('#textModal').text("Mohon masukkan inputan kuantitas berupa angka!");
                $("#modalDetailProduk").modal('hide');
                $("#modalPeringatanTidakBolehMin").modal("show");
            }

        });

        $('#btnKeranjang').on('click', function() {

            var totalHarga = 0;
            $(".btnHapusKeranjang").each(function(index) {
                totalHarga += parseInt($(this).attr('subTotal'));
            });
            $("#tabelTotalHarga").text("Total harga : " + totalHarga.toLocaleString('id-ID', {
                style: 'currency',
                currency: 'IDR'
            }));

            // var totalHarga = 0;
            // $(".btnHapusKeranjang").each(function(index) {
            //     totalHarga += parseInt($(this).attr('subTotal'));
            // });
            // if (totalHarga == 0) {
            //     $("#btnKonfirmasiKeranjang").attr('hidden', true);
            // } else {
            //     $("#btnKonfirmasiKeranjang").attr('hidden', false);
            // }
            $("#modalKeranjang").modal('show');

        });

        $('body').on('click', '#btnMinimalStokProdukDiambil', function() {
            $("#modalKeranjang").modal('show');
        });

        $('body').on('click', '#btnPeringatanTidakBolehMin', function() {
            $("#modalDetailProduk").modal('show');
        });

        $('body').on('click', '.btnHapusKeranjang', function(e) {
            e.preventDefault();
            var idProduk = $(this).attr('idProduk');
            var stokDiambil = parseInt($(this).attr('stokDiambil'));
            var stokSaatIni = parseInt($('#btnTambahKeranjang_' + idProduk).attr('stokProduk'));
            var minimalStokDiambil = parseInt($(this).attr('minStok'));

            if (minimalStokDiambil > 0) {
                if (stokDiambil > minimalStokDiambil) {
                    $('#btnTambahKeranjang_' + idProduk).attr('stokProduk', (stokDiambil - minimalStokDiambil +
                        stokSaatIni));

                    if ($('#btnTambahKeranjang_' + idProduk).attr('stokProduk') >= 1) {
                        $('#btnTambahKeranjang_' + idProduk).removeClass('btn-danger');
                        $('#btnTambahKeranjang_' + idProduk).addClass('btnTambah');
                        $('#btnTambahKeranjang_' + idProduk).attr('disabled', false);
                        $('#btnTambahKeranjang_' + idProduk).text('Tambah');

                    }

                    var hargaPerProduk = parseInt($(this).attr("subTotal")) / stokDiambil;

                    $("#kolomStokDiambil_" + idProduk).text(minimalStokDiambil);
                    $("#kolomSubTotal_" + idProduk).text((hargaPerProduk * minimalStokDiambil).toLocaleString(
                        'id-ID', {
                            style: 'currency',
                            currency: 'IDR'
                        }).replace('Rp', ''));
                    $(this).attr('stokDiambil', minimalStokDiambil);
                    $(this).attr("subTotal", hargaPerProduk * minimalStokDiambil);

                    var totalHarga = 0;
                    $(".btnHapusKeranjang").each(function(index) {
                        totalHarga += parseInt($(this).attr('subTotal'));
                    });
                    $("#tabelTotalHarga").text("Total harga : " + totalHarga.toLocaleString('id-ID', {
                        style: 'currency',
                        currency: 'IDR'
                    }));
                    $("#stokproduk_" + idProduk).val(minimalStokDiambil);
                } else {

                    $("#modalKeranjang").modal('hide');
                    $('#textModalMinimalStokProdukDiambil').text("Minimal stok produk yang diambil adalah " +
                        minimalStokDiambil +
                        " yang berasal dari Paket yang Anda pilih!");
                    $("#modalMinimalStokProdukDiambil").modal("show");
                }
            } else {
                $('#btnTambahKeranjang_' + idProduk).attr('stokProduk', (stokDiambil +
                    stokSaatIni));

                if ($('#btnTambahKeranjang_' + idProduk).attr('stokProduk') >= 1) {
                    $('#btnTambahKeranjang_' + idProduk).removeClass('btn-danger');
                    $('#btnTambahKeranjang_' + idProduk).addClass('btn-info');
                    $('#btnTambahKeranjang_' + idProduk).attr('disabled', false);
                    $('#btnTambahKeranjang_' + idProduk).text('Tambah');

                }

                $(this).parent().parent().remove();

                if ($('#bodyTabelKeranjang').find("tr").length == 0) {
                    $('#bodyTabelKeranjang').html(
                        "<tr id='trSilahkan'><td colspan='5'>Silahkan tambahkan produk terlebih dahulu!</td></tr>"
                    );
                    $('#tabelTotalHarga').text("Total Harga : Rp. 0");
                    // $("#btnKonfirmasiKeranjang").attr('hidden', true);
                }

                var totalHarga = 0;
                $(".btnHapusKeranjang").each(function(index) {
                    totalHarga += parseInt($(this).attr('subTotal'));
                });
                $("#tabelTotalHarga").text("Total harga : Rp. " + totalHarga.toLocaleString('id-ID', {
                    style: 'currency',
                    currency: 'IDR'
                }));

                $("#produk_" + idProduk).remove();
                $("#stokproduk_" + idProduk).remove();

            }
        });
    </script>
@endsection
