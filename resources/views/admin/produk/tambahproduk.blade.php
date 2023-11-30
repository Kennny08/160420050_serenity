@extends('layout.adminlayout')

@section('title', 'Admin || Tambah Produk')

@section('admincontent')
    <div class="page-title-box">

    </div>
    <!-- end page-title -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <h3 class="mt-0 header-title">Tambah Produk</h3>
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

                    <form id="formStoreProduk" method="POST" action="{{ route('produks.store') }}">
                        @csrf
                        <div class="form-group col-md-12">
                        </div>

                        <div class="form-group row">
                            <div class="col-md-12">
                                <label for="exampleInputEmail1"><strong>Nama Produk</strong></label>
                                <input type="text" class="form-control" name="namaProduk" id="txtNamaProduk"
                                    aria-describedby="emailHelp" placeholder="Silahkan masukkan nama produk" required
                                    value="{{ old('namaProduk') }}">
                                <small id="emailHelp" class="form-text text-muted">Masukkan nama produk disini!</small>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-4">
                                <label for="exampleInputEmail1"><strong>Kode Produk</strong></label>
                                <input type="text" class="form-control" name="kode_produk" id="txtKodeProduk"
                                    aria-describedby="emailHelp" placeholder="Silahkan masukkan kode produk (awali dengan huruf 'p')" required
                                    value="{{ old('kode_produk') }}">
                                <small id="emailHelp" class="form-text text-muted">Masukkan kode produk disini (awali dengan huruf "<span class="text-danger">p</span>")!</small>
                            </div>
                            <div class="col-md-4">
                                <label for="exampleInputEmail1"><strong>Harga Jual Produk</strong></label>
                                <input type="number" class="form-control" name="hargaJual" id="numHargaJual" min="1"
                                    aria-describedby="emailHelp" placeholder="Silahkan masukkan harga jual produk" required
                                    value="{{ old('hargaJual') }}">
                                <small id="emailHelp" class="form-text text-muted">Masukkan harga jual produk
                                    disini!</small>
                            </div>
                            <div class="col-md-4">
                                <label for="exampleInputEmail1"><strong>Harga Beli Produk</strong></label>
                                <input type="number" class="form-control" name="hargaBeli" id="numHargaBeli" min="1"
                                    aria-describedby="emailHelp" placeholder="Silahkan masukkan harga beli produk" required
                                    value="{{ old('hargaBeli') }}">
                                <small id="emailHelp" class="form-text text-muted">Masukkan harga beli produk
                                    disini!</small>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-12">
                                <label for="exampleInputEmail1"><strong>Deskripsi Produk</strong></label>
                                <textarea aria-describedby="emailHelp" class="form-control" name="deskripsiProduk" id="" cols="30"
                                    rows="5" placeholder="Silahkan masukkan deskripsi produk" required>
{{ old('deskripsiProduk') }}
                                </textarea>
                                <small id="emailHelp" class="form-text text-muted">Masukkan deskripsi produk disini!</small>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6">
                                <label for="exampleInputEmail1"><strong>Stok Produk</strong></label>
                                <input type="number" class="form-control" name="stokProduk" id="numStok" min="1"
                                    aria-describedby="emailHelp" placeholder="Silahkan masukkan jumlah stok produk" required
                                    value="{{ old('stokProduk') }}">
                                <small id="emailHelp" class="form-text text-muted">Masukkan jumlah stok produk
                                    disini!</small>
                            </div>
                            <div class="col-md-6">
                                <label for="exampleInputEmail1"><strong>Minimum Stok Produk</strong></label>
                                <input type="number" class="form-control" name="minimumStok" id="numMinimumStok"
                                    min="1" aria-describedby="emailHelp"
                                    placeholder="Silahkan masukkan harga jual produk" required
                                    value="{{ old('minimumStok') }}">
                                <small id="emailHelp" class="form-text text-muted">Masukkan harga jual produk
                                    disini!</small>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-3">
                                <label for="exampleInputEmail1"><strong>Status Keaktifan Produk</strong></label>
                                <br>
                                <div class="btn-group btn-group-toggle border w-100" data-toggle="buttons">
                                    @if (old('radioStatusProduk') == 'aktif')
                                        <label class="btn btn-info waves-effect waves-light" id="lblStatusProdukAktif">
                                            <input type="radio" value="aktif" name="radioStatusProduk"
                                                id="optionStatusProdukAktif" class="radioStatusProduk" checked> Aktif
                                        </label>
                                        <label class="btn waves-effect waves-light" id="lblStatusProdukNonaktif">
                                            <input type="radio" value="nonaktif" name="radioStatusProduk"
                                                id="optionStatusProdukNonaktif" class="radioStatusProduk">
                                            Nonaktif
                                        </label>
                                    @elseif(old('radioStatusProduk') == null)
                                        <label class="btn btn-info waves-effect waves-light" id="lblStatusProdukAktif">
                                            <input type="radio" value="aktif" name="radioStatusProduk"
                                                id="optionStatusProdukAktif" class="radioStatusProduk" checked> Aktif
                                        </label>
                                        <label class="btn waves-effect waves-light" id="lblStatusProdukNonaktif">
                                            <input type="radio" value="nonaktif" name="radioStatusProduk"
                                                id="optionStatusProdukNonaktif" class="radioStatusProduk">
                                            Nonaktif
                                        </label>
                                    @else
                                        <label class="btn waves-effect waves-light" id="lblStatusProdukAktif">
                                            <input type="radio" value="aktif" name="radioStatusProduk"
                                                id="optionStatusProdukAktif" class="radioStatusProduk"> Aktif
                                        </label>
                                        <label class="btn btn-info waves-effect waves-light" id="lblStatusProdukNonaktif">
                                            <input type="radio" value="nonaktif" name="radioStatusProduk"
                                                id="optionStatusProdukNonaktif" class="radioStatusProduk" checked>
                                            Nonaktif
                                        </label>
                                    @endif




                                </div>
                                <small id="emailHelp" class="form-text text-muted">Pilih Status keaktifan produk
                                    disini!</small>
                            </div>
                            <div class="col-md-3">
                                <label for="exampleInputEmail1"><strong>Status Jual Produk</strong></label>
                                <div class="btn-group btn-group-toggle border w-100" data-toggle="buttons">
                                    @if (old('radioStatusJualProduk') == 'aktif')
                                        <label class="btn btn-info waves-effect waves-light"
                                            id="lblStatusJualProdukAktif">
                                            <input type="radio" name="radioStatusJualProduk" value="aktif"
                                                id="optionStatusJualProdukAktif" checked class="radioStatusJualProduk">
                                            Aktif
                                        </label>
                                        <label class="btn waves-effect waves-light" id="lblStatusJualProdukNonaktif">
                                            <input type="radio" name="radioStatusJualProduk" value="tidak"
                                                id="optionStatusJualProdukNonaktif" class="radioStatusJualProduk">
                                            Nonaktif
                                        </label>
                                    @elseif(old('radioStatusJualProduk') == null)
                                        <label class="btn btn-info waves-effect waves-light"
                                            id="lblStatusJualProdukAktif">
                                            <input type="radio" name="radioStatusJualProduk" value="aktif"
                                                id="optionStatusJualProdukAktif" checked class="radioStatusJualProduk">
                                            Aktif
                                        </label>
                                        <label class="btn waves-effect waves-light" id="lblStatusJualProdukNonaktif">
                                            <input type="radio" name="radioStatusJualProduk" value="tidak"
                                                id="optionStatusJualProdukNonaktif" class="radioStatusJualProduk">
                                            Nonaktif
                                        </label>
                                    @else
                                        <label class="btn waves-effect waves-light" id="lblStatusJualProdukAktif">
                                            <input type="radio" name="radioStatusJualProduk" value="aktif"
                                                id="optionStatusJualProdukAktif" class="radioStatusJualProduk"> Aktif
                                        </label>
                                        <label class="btn btn-info waves-effect waves-light"
                                            id="lblStatusJualProdukNonaktif">
                                            <input type="radio" name="radioStatusJualProduk" value="tidak"
                                                id="optionStatusJualProdukNonaktif" class="radioStatusJualProduk" checked>
                                            Nonaktif
                                        </label>
                                    @endif
                                </div>
                                <small id="emailHelp" class="form-text text-muted">Pilih status jual produk
                                    disini!</small>
                            </div>
                            <div class="col-md-3">
                                <label for="exampleInputEmail1"><strong>Kategori Produk</strong></label><br>
                                <select name="kategoriProduk" id="selectKategoriProduk" class="form-control"
                                    aria-label="Default select example" required>
                                    @foreach ($kategoris as $k)
                                        @if (old('kategoriProduk') == $k->id)
                                            <option value="{{ $k->id }}" selected>{{ $k->nama }}</option>
                                        @else
                                            <option value="{{ $k->id }}">{{ $k->nama }}</option>
                                        @endif
                                    @endforeach
                                </select>
                                <small id="emailHelp" class="form-text text-muted">Pilih kategori produk
                                    disini!</small>
                            </div>
                            <div class="col-md-3">
                                <label for="exampleInputEmail1"><strong>Merek Produk</strong></label><br>
                                <select name="merekProduk" id="selectMerekProduk" class="form-control"
                                    aria-label="Default select example" required>
                                    @foreach ($mereks as $m)
                                        @if (old('merekProduk') == $m->id)
                                            <option value="{{ $m->id }}" selected>{{ $m->nama }}</option>
                                        @else
                                            <option value="{{ $m->id }}">{{ $m->nama }}</option>
                                        @endif
                                    @endforeach
                                </select>
                                <small id="emailHelp" class="form-text text-muted">Pilih merek produk
                                    disini!</small>
                            </div>
                        </div>

                        <div class="form-group row" id="containerKeteranganKondisi">
                            <div class="col-md-12">
                                <label for="exampleInputEmail1"><strong>Kondisi Pelanggan Terhadap Produk</strong></label>
                            </div>
                            <div class="col-md-12">
                                <div class="input-group" style="width: 100%">
                                    <select style="width: 70%" name="kondisi" id="selectKondisi" class="form-control"
                                        aria-label="Default select example" required>
                                        <option value="null" selected disabled>Pilih Kondisi</option>
                                        @if (old('arraykondisiid') == null)
                                            @foreach ($kondisis as $kondisi)
                                                <option value="{{ $kondisi->id }}"
                                                    keterangan="{{ $kondisi->keterangan }}">
                                                    {{ $kondisi->keterangan }}
                                                </option>
                                            @endforeach
                                        @else
                                            @foreach ($kondisis as $kondisi)
                                                @if (!in_array($kondisi->id, old('arraykondisiid')))
                                                    <option value="{{ $kondisi->id }}"
                                                        keterangan="{{ $kondisi->keterangan }}">
                                                        {{ $kondisi->keterangan }}
                                                    </option>
                                                @endif
                                            @endforeach
                                        @endif


                                    </select>
                                    <button style="margin-left: 1%; width: 20%" type="button" id="btnTambahKondisi"
                                        class="btn btn-info waves-effect waves-light">Tambah Keterangan Kondisi</button>
                                </div>
                                <small id="emailHelp" class="form-text text-muted">Pilih Kondisi Pelanggan Terhdap Produk
                                    disini!</small>
                            </div>
                            @if (old('arraykondisiid') != null)
                                @foreach (old('arraykondisiid') as $kondisi)
                                    <input id='kondisi{{ $kondisi }}' type='hidden' class='classarraykondisiid'
                                        value='{{ $kondisi }}' name='arraykondisiid[]'>
                                @endforeach
                            @endif
                        </div>

                        <div class="form-group text-center">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th class="text-center">Keterangan Kondisi</th>
                                        <th class="text-center">Hapus</th>
                                    </tr>
                                </thead>
                                <tbody id="bodyListKeteranganKondisi">
                                    @if (old('arraykondisiid') == null)
                                        <tr id="trSilahkan">
                                            <td colspan="2">
                                                Silahkan pilih Keterangan Kondisi
                                            </td>
                                        </tr>
                                    @else
                                        @foreach (old('arraykondisiid') as $k)
                                            <tr>
                                                @foreach ($kondisis as $kondisi)
                                                    @if ($kondisi->id == $k)
                                                        <td>
                                                            {{ $kondisi->keterangan }}
                                                        </td>
                                                        <td>
                                                            <button type='button'
                                                                class='deleteKondisi btn btn-danger waves-effect waves-light'
                                                                idKondisi="{{ $kondisi->id }}"
                                                                keterangan="{{ $kondisi->keterangan }}">Hapus</button>
                                                        </td>
                                                    @break
                                                @endif
                                            @endforeach
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>

                    <div class="form-group row text-right">
                        <div class="col-md-12">
                            <a id="btnBatalTambahProduk" href="{{ route('produks.index') }}"
                                class="btn btn-danger btn-lg waves-effect waves-light mr-3">Batal</a>
                            <button id="btnTambahProduk" type="button"
                                class="btn btn-info btn-lg waves-effect waves-light text-right">Tambah</button>
                        </div>
                    </div>

                    <div id="modalPengecekanHargaJualBeli" class="modal fade bs-example-modal-center" tabindex="-1"
                        role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title mt-0">Konfirmasi Harga Jual dan Beli Produk</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body" id="bodyModalPengecekanHarga">
                                    <h6>Harga jual produk yang Anda masukkan lebih kecil dari harga beli produk. Apakh
                                        Anda yakin untuk mengkonfirmasi penyimpanan data produk?</h6>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger waves-effect"
                                        data-dismiss="modal">Tidak</button>
                                    <button type="submit" class="btn btn-info waves-effect waves-light">Ya</button>
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
@endsection

@section('javascript')
<script>
    $('.radioStatusProduk').on('change', function() {
        var statusSaatIni = $(this).val();
        if (statusSaatIni == "nonaktif") {
            $("#lblStatusProdukAktif").removeClass("btn-info");
            $("#lblStatusProdukNonaktif").addClass("btn-info");
        } else {
            $("#lblStatusProdukAktif").addClass("btn-info");
            $("#lblStatusProdukNonaktif").removeClass("btn-info");
        }
    });

    $('#btnTambahProduk').on('click', function() {
        var hargaJual = parseInt($("#numHargaJual").val());
        var hargaBeli = parseInt($("#numHargaBeli").val());
        var namaProduk = $("#txtNamaProduk").val();
        if (hargaJual < hargaBeli) {
            $("#bodyModalPengecekanHarga").html("<p>Harga jual produk " + namaProduk +
                " yang Anda masukkan(Rp. " + hargaJual + ") lebih kecil dari harga beli produk(Rp. " +
                hargaBeli + "). Apakah Anda yakin untuk mengkonfirmasi penyimpanan data produk?</p>");
            $("#modalPengecekanHargaJualBeli").modal("show");
        } else {
            $("#formStoreProduk").submit();
        }
    });

    $('.radioStatusJualProduk').on('change', function() {
        var statusSaatIni = $(this).val();
        if (statusSaatIni == "tidak") {
            $("#lblStatusJualProdukAktif").removeClass("btn-info");
            $("#lblStatusJualProdukNonaktif").addClass("btn-info");
        } else {
            $("#lblStatusJualProdukAktif").addClass("btn-info");
            $("#lblStatusJualProdukNonaktif").removeClass("btn-info");
        }
    });

    $('body').on('click', '#btnTambahKondisi', function() {
        var idKondisi = $("#selectKondisi").val();
        var keteranganKondisi = $("#selectKondisi option:selected").text();

        if (idKondisi != null) {
            $("#containerKeteranganKondisi").append("<input id='kondisi" + idKondisi +
                "' type='hidden' class='classarraykondisiid' value='" +
                idKondisi +
                "' name='arraykondisiid[]'>");
            $('#trSilahkan').remove();
            $("#bodyListKeteranganKondisi").append(
                "<tr><td>" + keteranganKondisi + "</td>" +
                "<td>" +
                "<button type='button' class='deleteKondisi btn btn-danger waves-effect waves-light' idKondisi='" +
                idKondisi +
                "' keterangan='" + keteranganKondisi + "' >Hapus</button>" +
                "</td>" +
                "</tr>");
            $("#selectKondisi option:selected").remove();
            $("#selectKondisi").val('null');
        }
    });

    $('body').on('click', '.deleteKondisi', function() {
        var idKondisi = $(this).attr('idKondisi');
        var keteranganKondisi = $(this).attr('keterangan');


        $("#selectKondisi").append("<option value='" + idKondisi + "'>" + keteranganKondisi +
            "</option>");
        $(this).parent().parent().remove();
        $("#kondisi" + idKondisi).remove();

        var select = $("#selectKondisi");
        $("#selectKondisi option[value='null']").remove();
        var options = select.find("option");
        options.sort(function(a, b) {
            return a.text.localeCompare(b.text);
        });
        select.empty();
        select.append("<option value='null' selected disabled>Pilih Kondisi</option>")
        select.append(options);
        select.val("null");

        if ($('#bodyListKeteranganKondisi').find("tr").length == 0) {
            $('#bodyListKeteranganKondisi').html(
                "<tr id='trSilahkan'><td colspan='2'>Silahkan Pilih Keterangan Kondisi</td></tr>");
        }
    });
</script>
@endsection
