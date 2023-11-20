@extends('layout.adminlayout')

@section('title', 'Admin || Penambahan Produk Pembelian')

@section('admincontent')
    <div class="page-title-box">

    </div>
    <!-- end page-title -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <h3 class="mt-0 header-title">Pembelian Tambahan Produk</h3>
                    <p class="sub-title">
                    </p>
                    <button class="btn btn-info waves-effect waves-light" data-toggle="modal" id="btnKeranjang"><i
                            class="mdi mdi-basket"></i> &nbsp;
                        Keranjang</button>
                    @if ($penjualan->reservasi != null)
                        <a class="btn btn-primary waves-effect waves-light"
                            href="{{ route('reservasi.admin.detailreservasi', $penjualan->reservasi->id) }}">
                            Batal Tambah Produk</a>
                    @else
                        {{-- <button class="btn btn-primary waves-effect waves-light" data-toggle="modal" id="btnKonfirmasi">
                            Batal Tambah Produk</button> --}}
                    @endif

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

                    <table id="tabelDaftarProduk" class="table table-striped table-bordered dt-responsive wrap text-center"
                        style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead class="">
                            <tr>
                                <th class="align-middle">Kode Produk</th>
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
                                <tr id="tr_{{ $p->id }}">
                                    <td>{{ $p->kode_produk }}</td>
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
                                    <td class="text-center">
                                        @if ($p->stok > 0)
                                            <button id="btnTambahKeranjang_{{ $p->id }}"
                                                class="btn btn-info waves-effect waves-light btnTambahKeranjang"
                                                idProduk='{{ $p->id }}' namaProduk='{{ $p->nama }}'
                                                hargaProduk='{{ $p->harga_jual }}' stokProduk='{{ $p->stok }}'
                                                minimumStokProduk='{{ $p->minimum_stok }}'>Tambah</button>
                                        @else
                                            <button id="btnTambahKeranjang_{{ $p->id }}"
                                                class="btn btn-danger waves-effect waves-light btnTambahKeranjang"
                                                idProduk='{{ $p->id }}' namaProduk='{{ $p->nama }}'
                                                hargaProduk='{{ $p->harga_jual }}' stokProduk='{{ $p->stok }}'
                                                minimumStokProduk='{{ $p->minimum_stok }}' disabled>Habis</button>
                                        @endif

                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

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
                        <a type="button" href="{{ route('reservasi.admin.detailreservasi', $penjualan->reservasi->id) }}"
                            class="btn btn-secondary waves-effect waves-light">Tidak</a>
                    @else
                    @endif
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
                                value="1" name="setNumberJumlahProduk" min="1" max="100" />
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

    <div id="modalKeranjang" class="modal fade bs-example-modal-center" tabindex="-1" role="dialog"
        aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
            <div class="modal-content">
                <form action="{{ route('reservasi.admin.konfirmasipenambahanproduk') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 id="modalNamaProduk" class="modal-title mt-0">Keranjang Produk Anda</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div id="modalDetailKeranjang" class="modal-body text-center">
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
                                        <tr id="barisKeranjang_{{ $p->id }}">
                                            <td>{{ $p->nama }}</td>
                                            <td>{{ number_format($p->harga_jual, 2, ',', '.') }}</td>
                                            <td id="kolomStokDiambil_{{ $p->id }}">{{ $p->pivot->kuantitas }}</td>
                                            <td id="kolomSubTotal_{{ $p->id }}">
                                                {{ number_format($p->harga_jual * $p->pivot->kuantitas, 2, ',', '.') }}
                                            </td>
                                            <td>
                                                <button class='btn btn-danger btnHapusKeranjang'
                                                    id="btnHapusKeranjang_{{ $p->id }}"
                                                    idProduk="{{ $p->id }}"
                                                    stokDiambil="{{ $p->pivot->kuantitas }}"
                                                    subTotal="{{ $p->harga_jual * $p->pivot->kuantitas }}">Hapus
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
                                            Rp. {{ $totalSementara }}
                                        </td>
                                    </tr>
                                @else
                                    <tr>
                                        <td id="tabelTotalHarga" colspan="5" class="font-weight-bold">Total Harga :
                                            Rp. 0
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
                        <button id="btnKonfirmasiKeranjang" type="submit" idKeranjang=''
                            class="btn btn-info waves-effect waves-light btnKonfirmasiKeranjang">Konfirmasi</button>
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
                    <h5 id="modalPeirngatanTidakBolehMin" class="modal-title mt-0">Terjadi Kesalahan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
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
                    <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">Tutup</button>

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
                    $("#modalPeringatanTidakBolehMin").modal("show");
                } else if (stokDiambil > stokSaatIni) {
                    $("#setNumberJumlahProduk").val(stokSaatIni);
                    $('#textModal').text(
                        "Kuantitas produk yang ingin dibeli sebagai tambahan produk maksimal berjumlah " +
                        stokSaatIni + "!");
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
                        $("#kolomSubTotal_" + idProduk).text(hargaProduk * stokBaruDiKeranjang);
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
                            "<td>" + "<button class='btn btn-danger btnHapusKeranjang' id='btnHapusKeranjang_" +
                            idProduk +
                            "' idProduk='" + idProduk + "' stokDiambil='" + stokDiambil + "' subTotal='" +
                            subTotal +
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

        $('body').on('click', '.btnHapusKeranjang', function() {
            var idProduk = $(this).attr('idProduk');
            var stokDiambil = parseInt($(this).attr('stokDiambil'));
            var stokSaatIni = parseInt($('#btnTambahKeranjang_' + idProduk).attr('stokProduk'));

            $('#btnTambahKeranjang_' + idProduk).attr('stokProduk', (stokDiambil + stokSaatIni));

            if ($('#btnTambahKeranjang_' + idProduk).attr('stokProduk') >= 1) {
                $('#btnTambahKeranjang_' + idProduk).removeClass('btn-danger');
                $('#btnTambahKeranjang_' + idProduk).addClass('btn-info');
                $('#btnTambahKeranjang_' + idProduk).attr('disabled', false);
                $('#btnTambahKeranjang_' + idProduk).text('Tambah');

            }

            $(this).parent().parent().remove();

            if ($('#bodyTabelKeranjang').find("tr").length == 0) {
                $('#bodyTabelKeranjang').html(
                    "<tr id='trSilahkan'><td colspan='5'>Silahkan tambahkan produk terlebih dahulu!</td></tr>");
                $('#tabelTotalHarga').text("Total Harga : Rp. 0");
                // $("#btnKonfirmasiKeranjang").attr('hidden', true);
            }

            var totalHarga = 0;
            $(".btnHapusKeranjang").each(function(index) {
                totalHarga += parseInt($(this).attr('subTotal'));
            });
            $("#tabelTotalHarga").text("Total harga : Rp. " + totalHarga);

            $("#produk_" + idProduk).remove();
            $("#stokproduk_" + idProduk).remove();

        });
    </script>
@endsection
