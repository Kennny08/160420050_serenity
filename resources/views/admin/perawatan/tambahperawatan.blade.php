@extends('layout.adminlayout')

@section('title', 'Admin || Tambah Perawatan')

@section('admincontent')
    <div class="page-title-box">

    </div>
    <!-- end page-title -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <h3 class="mt-0 header-title">Tambah Perawatan</h3>
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

                    <form id="formStorePerawatan" method="POST" action="{{ route('perawatans.store') }}">
                        @csrf
                        <div class="form-group col-md-12">
                        </div>

                        <div class="form-group row">
                            <div class="col-md-12">
                                <label for="exampleInputEmail1"><strong>Nama Perawatan</strong></label>
                                <input type="text" class="form-control" name="namaPerawatan" id="txtNamaPerawatan"
                                    aria-describedby="emailHelp" placeholder="Silahkan masukkan nama perawatan" required
                                    value="{{ old('namaPerawatan') }}">
                                <small id="emailHelp" class="form-text text-muted">Masukkan nama perawatan disini!</small>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-4">
                                <label for="exampleInputEmail1"><strong>Kode Perawatan</strong></label>
                                <input type="text" class="form-control" name="kode_perawatan" id="txtKodePerawatan"
                                    aria-describedby="emailHelp" placeholder="Silahkan masukkan kode perawatan (awali dengan huruf 's')" required
                                    value="{{ old('kode_perawatan') }}">
                                <small id="emailHelp" class="form-text text-muted">Masukkan kode perawatan disini (awali dengan huruf "<span class="text-danger">s</span>")!</small>
                            </div>
                            <div class="col-md-4">
                                <label for="exampleInputEmail1"><strong>Harga Layanan Perawatan(Rp)</strong></label>
                                <input type="number" class="form-control" name="hargaPerawatan" id="numHargaPerawatan"
                                    min="1" aria-describedby="emailHelp"
                                    placeholder="Silahkan masukkan harga layanan perawatan" required
                                    value="{{ old('hargaPerawatan') }}">
                                <small id="emailHelp" class="form-text text-muted">Masukkan harga layanan perawatan
                                    disini!</small>
                            </div>
                            <div class="col-md-4">
                                <label for="exampleInputEmail1"><strong>Durasi(Menit)</strong></label>
                                <input type="number" class="form-control" name="durasi" id="numDurasi" min="1"
                                    aria-describedby="emailHelp" placeholder="Silahkan masukkan durasi perawatan" required
                                    value="{{ old('durasi') }}">
                                <small id="emailHelp" class="form-text text-muted">Masukkan durasi perawatan
                                    disini!</small>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-12">
                                <label for="exampleInputEmail1"><strong>Deskripsi Perawatan</strong></label>
                                <textarea aria-describedby="emailHelp" class="form-control" name="deskripsiPerawatan" id="" cols="30"
                                    rows="5" placeholder="Silahkan masukkan deskripsi perawatan" required>
{{ old('deskripsiPerawatan') }}
                                </textarea>
                                <small id="emailHelp" class="form-text text-muted">Masukkan deskripsi perawatan
                                    disini!</small>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-4">
                                <label for="exampleInputEmail1"><strong>Komisi Karyawan(%)</strong></label>
                                <input type="number" class="form-control" name="komisiKaryawan" id="numKomisiKaryawan"
                                    min="1" max="100" aria-describedby="emailHelp"
                                    placeholder="Silahkan masukkan komisi karyawan" required
                                    value="{{ old('komisiKaryawan') }}">
                                <small id="emailHelp" class="form-text text-muted">Masukkan komisi karyawan
                                    disini!</small>
                            </div>
                            <div class="col-md-4">
                                <label for="exampleInputEmail1"><strong>Status Keaktifan Perawatan</strong></label>
                                <br>
                                <div class="btn-group btn-group-toggle border w-100" data-toggle="buttons">
                                    @if (old('radioStatusPerawatan') == 'aktif')
                                        <label class="btn btn-info waves-effect waves-light" id="lblStatusPerawatanAktif">
                                            <input type="radio" value="aktif" name="radioStatusPerawatan"
                                                id="optionStatusPerawatanAktif" class="radioStatusPerawatan" checked>
                                            Aktif
                                        </label>
                                        <label class="btn waves-effect waves-light" id="lblStatusPerawatanNonaktif">
                                            <input type="radio" value="nonaktif" name="radioStatusPerawatan"
                                                id="optionStatusPerawatanNonaktif" class="radioStatusPerawatan">
                                            Nonaktif
                                        </label>
                                    @elseif(old('radioStatusPerawatan') == null)
                                        <label class="btn btn-info waves-effect waves-light" id="lblStatusPerawatanAktif">
                                            <input type="radio" value="aktif" name="radioStatusPerawatan"
                                                id="optionStatusPerawatanAktif" class="radioStatusPerawatan" checked>
                                            Aktif
                                        </label>
                                        <label class="btn waves-effect waves-light" id="lblStatusPerawatanNonaktif">
                                            <input type="radio" value="nonaktif" name="radioStatusPerawatan"
                                                id="optionStatusPerawatanNonaktif" class="radioStatusPerawatan">
                                            Nonaktif
                                        </label>
                                    @else
                                        <label class="btn waves-effect waves-light" id="lblStatusPerawatanAktif">
                                            <input type="radio" value="aktif" name="radioStatusPerawatan"
                                                id="optionStatusPerawatanAktif" class="radioStatusPerawatan"> Aktif
                                        </label>
                                        <label class="btn btn-info waves-effect waves-light"
                                            id="lblStatusPerawatanNonaktif">
                                            <input type="radio" value="nonaktif" name="radioStatusPerawatan"
                                                id="optionStatusPerawatanNonaktif" class="radioStatusPerawatan" checked>
                                            Nonaktif
                                        </label>
                                    @endif
                                </div>
                                <small id="emailHelp" class="form-text text-muted">Pilih Status keaktifan perawatan
                                    disini!</small>
                            </div>
                            <div class="col-md-4">
                                <label for="exampleInputEmail1"><strong>Status Komplemen Perawatan</strong></label>
                                <div class="btn-group btn-group-toggle border w-100" data-toggle="buttons">
                                    @if (old('radioStatusKomplemenPerawatan') == 'ya')
                                        <label class="btn btn-info waves-effect waves-light"
                                            id="lblStatusKomplemenPerawatanAktif">
                                            <input type="radio" name="radioStatusKomplemenPerawatan" value="ya"
                                                id="optionStatusJualPerawatanAktif" checked
                                                class="radioStatusKomplemenPerawatan">
                                            Aktif
                                        </label>
                                        <label class="btn waves-effect waves-light"
                                            id="lblStatusKomplemenPerawatanNonaktif">
                                            <input type="radio" name="radioStatusKomplemenPerawatan" value="tidak"
                                                id="optionStatusKomplemenPerawatanNonaktif"
                                                class="radioStatusKomplemenPerawatan">
                                            Nonaktif
                                        </label>
                                    @elseif(old('radioStatusJualProduk') == null)
                                        <label class="btn btn-info waves-effect waves-light"
                                            id="lblStatusKomplemenPerawatanAktif">
                                            <input type="radio" name="radioStatusKomplemenPerawatan" value="ya"
                                                id="optionStatusJualPerawatanAktif" checked
                                                class="radioStatusKomplemenPerawatan">
                                            Aktif
                                        </label>
                                        <label class="btn waves-effect waves-light"
                                            id="lblStatusKomplemenPerawatanNonaktif">
                                            <input type="radio" name="radioStatusKomplemenPerawatan" value="tidak"
                                                id="optionStatusKomplemenPerawatanNonaktif"
                                                class="radioStatusKomplemenPerawatan">
                                            Nonaktif
                                        </label>
                                    @else
                                        <label class="btn waves-effect waves-light" id="lblStatusKomplemenPerawatanAktif">
                                            <input type="radio" name="radioStatusKomplemenPerawatan" value="ya"
                                                id="optionStatusJualPerawatanAktif" class="radioStatusKomplemenPerawatan">
                                            Aktif
                                        </label>
                                        <label class="btn btn-info waves-effect waves-light"
                                            id="lblStatusKomplemenPerawatanNonaktif">
                                            <input type="radio" name="radioStatusKomplemenPerawatan" value="tidak"
                                                id="optionStatusKomplemenPerawatanNonaktif"
                                                class="radioStatusKomplemenPerawatan" checked>
                                            Nonaktif
                                        </label>
                                    @endif
                                </div>
                                <small id="emailHelp" class="form-text text-muted">Pilih status komplemen perawatan
                                    disini!</small>
                            </div>

                        </div>

                        <div class="form-group row" id="containerProduk">
                            <div class="col-md-12">
                                <label for="exampleInputEmail1"><strong>Daftar Produk yang Digunakan</strong></label>
                            </div>
                            <div class="col-md-12">
                                <div class="input-group" style="width: 100%">
                                    <select style="width: 70%" name="produk" id="selectProduk" class="form-control"
                                        aria-label="Default select example" required>
                                        <option value="null" selected disabled>Pilih Produk</option>
                                        @if (old('arrayprodukid') == null)
                                            @foreach ($produks as $produk)
                                                <option value="{{ $produk->id }}" nama="{{ $produk->nama }}"
                                                    hargajual = "{{ $produk->harga_jual }}">
                                                    {{ $produk->nama }}
                                                </option>
                                            @endforeach
                                        @else
                                            @foreach ($produks as $produk)
                                                @if (!in_array($produk->id, old('arrayprodukid')))
                                                    <option value="{{ $produk->id }}" nama="{{ $produk->nama }}"
                                                        hargajual = "{{ $produk->harga_jual }}">
                                                        {{ $produk->nama }}
                                                    </option>
                                                @endif
                                            @endforeach
                                        @endif
                                    </select>
                                    <button style="margin-left: 1%; width: 20%" type="button" id="btnTambahProduk"
                                        class="btn btn-info waves-effect waves-light">Tambah Produk</button>
                                </div>
                                <small id="emailHelp" class="form-text text-muted">Pilih Produk
                                    disini!</small>
                            </div>
                            @if (old('arrayprodukid') != null)
                                @foreach (old('arrayprodukid') as $produk)
                                    <input id='produk{{ $produk }}' type='hidden' class='classarrayprodukid'
                                        value='{{ $produk }}' name='arrayprodukid[]'>
                                @endforeach
                            @endif
                        </div>

                        <div class="form-group text-center">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th class="text-center">Nama Produk</th>
                                        <th class="text-center">Harga Produk(Rp)</th>
                                        <th class="text-center">Hapus</th>
                                    </tr>
                                </thead>
                                <tbody id="bodyListProduk">
                                    @if (old('arrayprodukid') == null)
                                        <tr id="trSilahkan">
                                            <td colspan="3">
                                                Silahkan pilih Produk!
                                            </td>
                                        </tr>
                                    @else
                                        @foreach (old('arrayprodukid') as $p)
                                            <tr>
                                                @foreach ($produks as $produk)
                                                    @if ($produk->id == $p)
                                                        <td>
                                                            {{ $produk->nama }}
                                                        </td>
                                                        <td>
                                                            {{ number_format($produk->harga_jual, 0, ',', '.') }}
                                                        </td>
                                                        <td>
                                                            <button type='button'
                                                                class='deleteProduk btn btn-danger waves-effect waves-light'
                                                                idProduk="{{ $produk->id }}"
                                                                nama="{{ $produk->nama }}"
                                                                hargajual="{{ $produk->harga_jual }}">Hapus</button>
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
                            <a id="btnBatalTambahPerawatan" href="{{ route('perawatans.index') }}"
                                class="btn btn-danger btn-lg waves-effect waves-light mr-3">Batal</a>
                            <button id="btnTambahPerawatan" type="submit"
                                class="btn btn-info btn-lg waves-effect waves-light text-right">Tambah</button>
                        </div>
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
    $('.radioStatusPerawatan').on('change', function() {
        var statusSaatIni = $(this).val();
        if (statusSaatIni == "nonaktif") {
            $("#lblStatusPerawatanAktif").removeClass("btn-info");
            $("#lblStatusPerawatanNonaktif").addClass("btn-info");
        } else {
            $("#lblStatusPerawatanAktif").addClass("btn-info");
            $("#lblStatusPerawatanNonaktif").removeClass("btn-info");
        }
    });


    $('.radioStatusKomplemenPerawatan').on('change', function() {
        var statusSaatIni = $(this).val();
        if (statusSaatIni == "tidak") {
            $("#lblStatusKomplemenPerawatanAktif").removeClass("btn-info");
            $("#lblStatusKomplemenPerawatanNonaktif").addClass("btn-info");
        } else {
            $("#lblStatusKomplemenPerawatanAktif").addClass("btn-info");
            $("#lblStatusKomplemenPerawatanNonaktif").removeClass("btn-info");
        }
    });

    $('body').on('click', '#btnTambahProduk', function() {
        var idProduk = $("#selectProduk").val();
        var namaProduk = $("#selectProduk option:selected").text();
        var hargaproduk = $("#selectProduk option:selected").attr('hargajual');


        if (idProduk != null) {
            $("#containerProduk").append("<input id='produk" + idProduk +
                "' type='hidden' class='classarrayprodukid' value='" +
                idProduk +
                "' name='arrayprodukid[]'>");
            $('#trSilahkan').remove();
            $("#bodyListProduk").append(
                "<tr><td>" + namaProduk + "</td>" +
                "<td>" + hargaproduk + "</td>" +
                "<td>" +
                "<button type='button' class='deleteProduk btn btn-danger waves-effect waves-light' idProduk='" +
                idProduk + "' nama='" + namaProduk + "' hargajual='" + hargaproduk + "'>Hapus</button>" +
                "</td>" +
                "</tr>");
            $("#selectProduk option:selected").remove();
            $("#selectProduk").val('null');
        }
    });

    $('body').on('click', '.deleteProduk', function() {
        var idProduk = $(this).attr('idProduk');
        var namaProduk = $(this).attr('nama');
        var hargaProduk = $(this).attr('hargajual');


        $("#selectProduk").append("<option value='" + idProduk + "' nama='" + namaProduk + "' hargajual='" +
            hargaProduk + "'>" + namaProduk + "</option>");
        $(this).parent().parent().remove();
        $("#produk" + idProduk).remove();

        var select = $("#selectProduk");
        $("#selectProduk option[value='null']").remove();
        var options = select.find("option");
        options.sort(function(a, b) {
            return a.text.localeCompare(b.text);
        });
        select.empty();
        select.append("<option value='null' selected disabled>Pilih Produk</option>")
        select.append(options);
        select.val("null");

        if ($('#bodyListProduk').find("tr").length == 0) {
            $('#bodyListProduk').html(
                "<tr id='trSilahkan'><td colspan='3'>Silahkan Pilih Produk</td></tr>");
        }
    });
</script>
@endsection
