@extends('layout.adminlayout')

@section('title', 'Admin || Tambah Riwayat Pengambilan Produk')

@section('admincontent')
    <div class="page-title-box">

    </div>
    <!-- end page-title -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <h3 class="mt-0 header-title">Tambah Riwayat Pengambilan Produk</h3>
                    <p class="sub-title">
                    </p>
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

                    <form id="formRiwayatPengambilanProduk" method="POST"
                        action="{{ route('riwayatpengambilanproduks.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="form-group row">
                            <div class="form-group col-md-12">
                                <h3>Tanggal Pengambilan Produk : {{ $tanggalHariIni }}</h3>
                            </div>
                        </div>


                        <div class="form-group row">
                            <div class="col-md-12">
                                <label for="exampleInputEmail1"><strong>Nama Karyawan</strong></label>
                            </div>
                            <div class="col-md-12">
                                <div class="input-group" style="width: 100%">
                                    <select name="karyawan" id="karyawanSelect" class="form-control"
                                        aria-label="Default select example" required>
                                        <option value="null" selected disabled>Pilih Karyawan</option>
                                        @if (old('karyawan'))
                                            @foreach ($karyawanPekerjaSalon as $k)
                                                @if (old('karyawan') == $k->id)
                                                    <option selected value="{{ $k->id }}">
                                                        {{ $k->id }} - {{ $k->nama }}
                                                    </option>
                                                @else
                                                    <option value="{{ $k->id }}">
                                                        {{ $k->id }} - {{ $k->nama }}
                                                    </option>
                                                @endif
                                            @endforeach
                                        @else
                                            @foreach ($karyawanPekerjaSalon as $k)
                                                <option value="{{ $k->id }}">
                                                    {{ $k->id }} - {{ $k->nama }}
                                                </option>
                                            @endforeach
                                        @endif

                                    </select>
                                </div>
                                <small id="emailHelp" class="form-text text-muted">Pilih karyawan yang mengambil produk
                                    disini!</small>
                            </div>
                        </div>

                        <div class="form-group row">

                            <div class="col-md-6">
                                <label for="exampleInputEmail1"><strong>Produk Diambil</strong></label>
                                <div class="input-group" style="width: 100%">
                                    <select name="produk" id="produkSelect" class="form-control"
                                        aria-label="Default select example" required>
                                        <option value="null" selected disabled>Pilih Produk</option>
                                        @if (old('produk'))
                                            @foreach ($produks as $p)
                                                @if (old('produk') == $p->id)
                                                    <option selected value="{{ $p->id }}"
                                                        stokTersedia="{{ $p->stok }}">
                                                        {{ $p->kode_produk }} - {{ $p->nama }}
                                                    </option>
                                                @else
                                                    <option value="{{ $p->id }}"
                                                        stokTersedia="{{ $p->stok }}">
                                                        {{ $p->kode_produk }} - {{ $p->nama }}
                                                    </option>
                                                @endif
                                            @endforeach
                                        @else
                                            @foreach ($produks as $p)
                                                <option value="{{ $p->id }}" stokTersedia="{{ $p->stok }}">
                                                    {{ $p->kode_produk }} - {{ $p->nama }}
                                                </option>
                                            @endforeach
                                        @endif

                                    </select>
                                </div>
                                <small id="emailHelp" class="form-text text-muted">Pilih produk yang diambil disini!</small>
                            </div>
                            <div class="col-md-6">
                                @if (old('kuantitas') != null)
                                    @foreach ($produks as $p)
                                        @if ($p->id == old('produk'))
                                            <label for="exampleInputEmail1"><strong id="titleJumlahKuantitas">Jumlah
                                                    Kuantitas - Stok Tersedia (<span
                                                        class="text-danger">{{ $p->stok }}</span>)</strong></label>
                                        @break
                                    @endif
                                @endforeach
                            @else
                                <label for="exampleInputEmail1"><strong id="titleJumlahKuantitas">Jumlah
                                        Kuantitas</strong></label>
                            @endif

                            <input type="number" class="form-control" name="kuantitas" id="numKuantitasProduk"
                                min="1" max="100" aria-describedby="emailHelp"
                                placeholder="Silahkan masukkan kuantitas produk" required
                                value="{{ old('kuantitas', '1') }}">
                            <small id="emailHelp" class="form-text text-muted">Masukkan kuantitas produk yang diambil
                                disini!</small>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="exampleInputEmail1"><strong>Keterangan Pengambilan Produk</strong></label>
                            <textarea aria-describedby="emailHelp" class="form-control" name="keterangan" id="textAreaKeterangan" cols="30"
                                rows="5" placeholder="Silahkan masukkan keterangan pengambilan produk" required>
{{ old('keterangan', '') }}
                                </textarea>
                            <small id="emailHelp" class="form-text text-muted">Masukkan keterangan pengambilan produk
                                disini!</small>
                        </div>
                    </div>
                    <div class="form-group text-right">
                        <button id="btnKonfirmasiPerawatan" type="button"
                            class="btn btn-primary btn-lg waves-effect waves-light">Konfirmasi</button>
                    </div>

                    <div id="modalKonfirmasiPengambilanProduk" class="modal fade bs-example-modal-center" tabindex="-1"
                        role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 id="modalNamaKonfirmasiPengambilanProduk" class="modal-title mt-0">Terjadi
                                        Kesalahan
                                    </h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body text-center d-flex align-items-center justify-content-center"
                                    style="overflow-y: auto; height: 150px" id="bodyModalKonfirmasiPengambilanProduk">
                                    <h6>
                                    </h6>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger waves-effect"
                                        data-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-info waves-effect">Ya</button>


                                </div>
                            </div>
                            <!-- /.modal-content -->
                        </div>
                        <!-- /.modal-dialog -->
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- end col -->
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
                <h5 id="textModal" class="text-danger">Kuantitas produk yang diambil
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
    $('body').on('click', '#btnKonfirmasiPerawatan', function() {
        var valueProduk = $("#produkSelect").val();
        var valueKaryawan = $("#karyawanSelect").val();
        var valueKuantitas = parseInt($("#numKuantitasProduk").val());
        var valKeterangan = $("#textAreaKeterangan").val();


        if (valueProduk == null || valueKaryawan == null || valueKuantitas == 0 || valKeterangan.trim() == "") {
            var pesan = "";
            if (valueKaryawan == null) {
                pesan = pesan + "* Mohon pilih karyawan yang mengambil produk!<br>";
            }
            if (valueProduk == null) {
                pesan = pesan + "* Mohon pilih produk yang mau diambil!<br>";
            }

            if (valueKuantitas == 0) {
                pesan = pesan + "* Kuantitas produk yang mau diambil sudah habis";
            }

            if (valKeterangan.trim() == "") {
                pesan = pesan + "* Mohon isi keterangan pengambilan produk";
            }
            $('#textModal').html(pesan);
            $("#modalPeringatanTidakBolehMin").modal("show");
        } else {
            $("#formRiwayatPengambilanProduk").submit();
        }
    });

    $('body').on('change', '#produkSelect', function() {
        var stokTersedia = $("#produkSelect option:selected").attr("stokTersedia");

        $("#titleJumlahKuantitas").html(
            "Jumlah Kuantitas - Stok Tersedia(<span class='text-danger font-weight-bold'>" + stokTersedia +
            "</span>)");
        $("#numKuantitasProduk").attr("max", stokTersedia);
        if (stokTersedia >= 1) {
            $("#numKuantitasProduk").attr("disabled", false);
            $("#numKuantitasProduk").val("1");
        } else {
            $("#numKuantitasProduk").attr("disabled", true);
            $("#numKuantitasProduk").val("0");
        }

    });

    $('body').on('change', '#numKuantitasProduk', function() {
        var value = parseInt($("#numKuantitasProduk").val());
        var stokTersedia = parseInt($("#produkSelect option:selected").attr("stokTersedia"));

        if (!isNaN(value)) {
            if (value <= 0) {
                $("#numKuantitasProduk").val("1");
                $('#textModal').text(
                    "Kuantitas produk yang diambil minimal berjumlah 1!");
                $("#modalPeringatanTidakBolehMin").modal("show");
            }
            if (value > stokTersedia) {
                $("#numKuantitasProduk").val(stokTersedia);
                $('#textModal').text(
                    "Kuantitas produk yang ingin diambil maksimal berjumlah " +
                    stokTersedia + "!");
                $("#modalPeringatanTidakBolehMin").modal("show");
            }
        } else {
            $("#numKuantitasProduk").val("1");
            $('#textModal').text("Mohon masukkan inputan kuantitas berupa angka!");
            $("#modalPeringatanTidakBolehMin").modal("show");
        }

    });
</script>
@endsection
