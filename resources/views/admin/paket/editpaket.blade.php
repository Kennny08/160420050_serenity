@extends('layout.adminlayout')

@section('title', 'Admin || Edit Paket')

@section('admincontent')
    <div class="page-title-box">

    </div>
    <!-- end page-title -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <h3 class="mt-0 header-title">Edit Paket</h3>
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

                    <form id="formUpdatePaket" method="POST" action="{{ route('pakets.update', $paket->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="form-group col-md-12">
                        </div>

                        <div class="form-group row">
                            <div class="col-md-4">
                                <label for="exampleInputEmail1"><strong>Nama Paket</strong></label>
                                <input type="text" class="form-control" name="namaPaket" id="txtNamaPaket"
                                    aria-describedby="emailHelp" placeholder="Silahkan masukkan nama paket" required
                                    value="{{ $paket->nama }}">
                                <small id="emailHelp" class="form-text text-muted">Masukkan nama paket disini!</small>
                            </div>
                            <div class="col-md-4">
                                <label for="exampleInputEmail1"><strong>Kode Paket</strong></label>
                                <input type="text" class="form-control" name="kode_paket" id="txtKodePaket"
                                    aria-describedby="emailHelp" placeholder="Silahkan masukkan kode paket" required
                                    value="{{ $paket->kode_paket }}" readonly>
                                <small id="emailHelp" class="form-text text-muted">Masukkan jumlah kode disini!</small>
                            </div>
                            <div class="col-md-4">
                                <label for="exampleInputEmail1"><strong>Gambar Paket</strong></label>
                                <input type="file" class="form-control" name="gambarPaket" id="fileUpload"
                                    aria-describedby="emailHelp" value="{{ old('gambarPaket') }}" accept="image/*">
                                <small id="emailHelp" class="form-text text-muted">Upload file gambar paket
                                    disini!</small>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-4">
                                @if ($paket->diskon_id != null)
                                    <label for="exampleInputEmail1"><strong>Diskon Paket</strong></label>
                                    <input type="text" class="form-control" name="idDiskon" id="txtDiskonPaket"
                                        aria-describedby="emailHelp" placeholder="Silahkan masukkan kode paket" required
                                        value="{{ $paket->diskon->nama }}" readonly>
                                    <small id="emailHelp" class="form-text text-muted">Diskon yang Berlaku untuk Paket
                                        ini!</small>
                                @else
                                    <label for="exampleInputEmail1"><strong>Diskon Paket</strong></label>
                                    <input type="text" class="form-control" name="idDiskon" id="txtDiskonPaket"
                                        aria-describedby="emailHelp" placeholder="Silahkan masukkan kode paket" required
                                        value="Tidak Ada diskon yang ditetapkan untuk paket ini!" readonly>
                                    <small id="emailHelp" class="form-text text-muted">Diskon yang Berlaku untuk Paket
                                        ini!</small>
                                @endif

                            </div>
                            <div class="col-md-4">
                                <label for="exampleInputEmail1"><strong>Harga Paket(Rp)</strong></label>
                                <input type="number" class="form-control" name="hargaPaket" id="numHargaPaket"
                                    min="1" aria-describedby="emailHelp"
                                    placeholder="Silahkan masukkan harga layanan paket" required
                                    value="{{ $paket->harga }}">
                                <small id="emailHelp" class="form-text text-muted">Masukkan harga paket
                                    disini!</small>
                            </div>
                            <div class="col-md-4">
                                <label for="exampleInputEmail1"><strong>Status Keaktifan Paket</strong></label>
                                <br>
                                <div class="btn-group btn-group-toggle border w-100" data-toggle="buttons">
                                    @if ($paket->status == 'aktif')
                                        <label class="btn btn-info waves-effect waves-light" id="lblStatusPaketAktif">
                                            <input type="radio" value="aktif" name="radioStatusPaket"
                                                id="optionStatusPaketAktif" class="radioStatusPaket" checked>
                                            Aktif
                                        </label>
                                        <label class="btn waves-effect waves-light" id="lblStatusPaketNonaktif">
                                            <input type="radio" value="nonaktif" name="radioStatusPaket"
                                                id="optionStatusPaketNonaktif" class="radioStatusPaket">
                                            Nonaktif
                                        </label>
                                    @else
                                        <label class="btn waves-effect waves-light" id="lblStatusPaketAktif">
                                            <input type="radio" value="aktif" name="radioStatusPaket"
                                                id="optionStatusPaketAktif" class="radioStatusPaket"> Aktif
                                        </label>
                                        <label class="btn btn-info waves-effect waves-light" id="lblStatusPaketNonaktif">
                                            <input type="radio" value="nonaktif" name="radioStatusPaket"
                                                id="optionStatusPaketNonaktif" class="radioStatusPaket" checked>
                                            Nonaktif
                                        </label>
                                    @endif
                                </div>
                                <small id="emailHelp" class="form-text text-muted">Pilih Status keaktifan paket
                                    disini!</small>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-12">
                                <label for="exampleInputEmail1"><strong>Deskripsi Paket</strong></label>
                                <textarea aria-describedby="emailHelp" class="form-control" name="deskripsiPaket" id="" cols="30"
                                    rows="5" placeholder="Silahkan masukkan deskripsi paket" required>
{{ $paket->deskripsi }}
                                </textarea>
                                <small id="emailHelp" class="form-text text-muted">Masukkan deskripsi paket
                                    disini!</small>
                            </div>
                        </div>

                        {{-- <div class="form-group row" id="containerLayananPerawatan">
                            <div class="col-md-12">
                                <label for="exampleInputEmail1"><strong>Layanan Perawatan</strong></label>
                            </div>
                            <div class="col-md-12">
                                <div class="input-group" style="width: 100%">
                                    <select name="perawatan" id="perawatanSelect" class="form-control"
                                        aria-label="Default select example" required>
                                        <option value="null" selected disabled>Pilih Perawatan</option>

                                        @foreach ($perawatanAktif as $p)
                                            @if (!in_array($p->id, $arrPerawatanId))
                                                <option value="{{ $p->id }}" durasi="{{ $p->durasi }}"
                                                    harga="{{ $p->harga }}" deskripsi="{{ $p->deskripsi }}">
                                                    {{ $p->nama }}
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
                                    <button type="button" id="btnTambahLayanan" style="max-width: 150px"
                                        class="btn btn-info waves-effect waves-light ml-2">Tambah
                                        Layanan</button>
                                </div>

                                <small id="emailHelp" class="form-text text-muted">Pilih Layanan Perawatan disini!</small>
                            </div>

                            @if (count($paket->perawatans) != 0)
                                @foreach ($paket->perawatans as $aro)
                                    <input id="perawatan{{ $aro->id }}" type="hidden"
                                        class='classarrayperawatanid' value="{{ $aro->id }}"
                                        name="arrayperawatanid[]">
                                @endforeach
                            @endif
                        </div> --}}

                        <div class="form-group row text-center">
                            <div class="col-md-12 text-left">
                                <h5>Daftar Perawatan</h5>
                            </div>
                            <div class="form-group col-md-12 table-responsive">
                                <div>
                                    <table class="table table-bordered table-striped dt-responsive wrap">
                                        <thead>
                                            <tr>
                                                <th class="text-center">Nama Perawatan</th>
                                                <th class="text-center">Durasi (Menit)</th>
                                                <th class="text-center">Harga (Rp)</th>
                                                <th class="text-center">Deskripsi</th>
                                                {{-- <th class="text-center">Hapus</th> --}}
                                            </tr>
                                        </thead>
                                        <tbody id="bodyListPerawatan">

                                            @foreach ($arrPerawatanId as $idPerawatan)
                                                @php
                                                    $aro = $paket->perawatans->firstWhere('id', $idPerawatan);
                                                @endphp
                                                <tr>
                                                    <td> {{ $aro->nama }} </td>
                                                    <td>{{ $aro->durasi }} </td>
                                                    <td>{{ number_format($aro->harga, 2, ',', '.') }} </td>
                                                    <td>{{ $aro->deskripsi }}</td>
                                                    {{-- <td><button type='button'
                                                            class='deletePerawatan btn btn-danger waves-effect waves-light'
                                                            idPerawatan="{{ $aro->id }}"
                                                            namaPerawatan="{{ $aro->nama }}"
                                                            durasiPerawatan="{{ $aro->durasi }}"
                                                            hargaPerawatan="{{ $aro->harga }}"
                                                            deskripsiPerawatan="{{ $aro->deskripsi }}">Hapus</button>
                                                    </td> --}}
                                                </tr>
                                            @endforeach

                                            {{-- @foreach ($perawatanAktif as $aro)
                                                @if (in_array($aro->id, $arrPerawatanId))
                                                    
                                                @endif
                                            @endforeach --}}
                                        </tbody>
                                        <tfoot>
                                            @php
                                                $totalPerawatanSementara = 0;
                                            @endphp
                                            @foreach ($paket->perawatans as $perawatan)
                                                @php
                                                    $totalPerawatanSementara += $perawatan->harga;
                                                @endphp
                                            @endforeach
                                            <tr>
                                                <td id="tabelTotalHargaPerawatan" colspan="5" class="font-weight-bold"
                                                    total="{{ $totalPerawatanSementara }}">
                                                    Total Harga
                                                    :
                                                    Rp. {{ number_format($totalPerawatanSementara, 2, ',', '.') }}
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>

                        {{-- <div class="form-group row" id="containerProduk">
                            <div class="col-md-12">
                                <label for="exampleInputEmail1"><strong>Daftar Produk dan Kuantitas dalam
                                        Paket</strong></label>
                            </div>
                            <div class="col-md-12">
                                <div class="input-group" style="width: 100%">
                                    <select name="produk" id="selectProduk" class="form-control"
                                        aria-label="Default select example" required>
                                        <option value="null" selected disabled>Pilih Produk</option>
                                        @if (count($paket->produks) == 0)
                                            @foreach ($produkAktif as $produk)
                                                <option value="{{ $produk->id }}" nama="{{ $produk->nama }}"
                                                    hargajual = "{{ $produk->harga_jual }}">
                                                    {{ $produk->nama }}
                                                </option>
                                            @endforeach
                                        @else
                                            @foreach ($produkAktif as $produk)
                                                @if (!in_array($produk->id, $arrProdukId))
                                                    <option value="{{ $produk->id }}" nama="{{ $produk->nama }}"
                                                        hargajual = "{{ $produk->harga_jual }}">
                                                        {{ $produk->nama }}
                                                    </option>
                                                @endif
                                            @endforeach
                                        @endif
                                    </select>
                                    <input type="number" min="1" id="numKuantitasProduk" value="1"
                                        class="form-control ml-2" style="max-width: 280px">
                                    <button type="button" id="btnTambahProduk" style="width: 140px"
                                        class="btn btn-info waves-effect waves-light ml-2">Tambah
                                        Produk</button>
                                </div>
                                <small id="emailHelp" class="form-text text-muted">Pilih Produk
                                    disini!</small>
                            </div>
                            @if (count($paket->produks) != 0)
                                @foreach ($paket->produks as $produk)
                                    <input id='produk{{ $produk->id }}' type='hidden' class='classarrayprodukid'
                                        value='{{ $produk->id }}' name='arrayprodukid[]'>

                                    <input id='kuantitasproduk{{ $produk->id }}' type='hidden'
                                        class='classarrayprodukkuantitas' value='{{ $produk->pivot->jumlah }}'
                                        name='arrayprodukkuantitas[]'>
                                @endforeach

                            @endif
                        </div> --}}

                        <div class="form-group row text-center">
                            <div class="col-md-12 text-left">
                                <h5>Daftar Produk</h5>
                            </div>
                            <div class="form-group col-md-12 table-responsive">
                                <div>
                                    <table class="table table-bordered table-striped dt-responsive wrap">
                                        <thead>
                                            <tr>
                                                <th class="text-center">Nama Produk</th>
                                                <th class="text-center">Harga Produk(Rp)</th>
                                                <th class="text-center">Kuantitas</th>
                                                <th class="text-center">Subtotal(Rp)</th>
                                                {{-- <th class="text-center">Hapus</th> --}}
                                            </tr>
                                        </thead>
                                        <tbody id="bodyListProduk">
                                            @if (count($paket->produks) == 0)
                                                <tr id="trSilahkan">
                                                    <td colspan="4">
                                                        Silahkan pilih Produk!
                                                    </td>
                                                </tr>
                                            @else
                                                @foreach ($paket->produks as $p)
                                                    <tr>
                                                        <td>
                                                            {{ $p->nama }}
                                                        </td>
                                                        <td>
                                                            {{ number_format($p->harga_jual, 2, ',', '.') }}
                                                        </td>
                                                        <td>{{ $p->pivot->jumlah }}</td>
                                                        <td>
                                                            @php
                                                                $subtotal = $p->harga_jual * $p->pivot->jumlah;
                                                            @endphp
                                                            {{ number_format($subtotal, 2, ',', '.') }}
                                                        </td>
                                                        {{-- <td>
                                                    <button type='button'
                                                        class='deleteProduk btn btn-danger waves-effect waves-light'
                                                        idProduk="{{ $p->id }}" nama="{{ $p->nama }}"
                                                        hargajual="{{ $p->harga_jual }}"
                                                        subtotal="{{ $subtotal }}">Hapus</button>
                                                </td> --}}
                                                    </tr>
                                                @endforeach
                                            @endif
                                        </tbody>
                                        <tfoot>
                                            @php
                                                $totalProdukSementara = 0;
                                            @endphp
                                            @if (count($paket->produks) != 0)
                                                @foreach ($paket->produks as $produk)
                                                    @php
                                                        $totalProdukSementara += $produk->harga_jual * $produk->pivot->jumlah;
                                                    @endphp
                                                @endforeach
                                                <tr>
                                                    <td id="tabelTotalHargaProduk" colspan="5"
                                                        class="font-weight-bold" total="{{ $totalProdukSementara }}">
                                                        Total Harga
                                                        :
                                                        Rp. {{ number_format($totalProdukSementara, 2, ',', '.') }}
                                                    </td>
                                                </tr>
                                            @else
                                                <tr>
                                                    <td id="tabelTotalHargaProduk" colspan="5"
                                                        class="font-weight-bold" total="0">Total Harga
                                                        :
                                                        Rp. 0,00
                                                    </td>
                                                </tr>
                                            @endif
                                        </tfoot>
                                    </table>
                                </div>

                            </div>
                        </div>


                        <div class="form-group row text-right">
                            <div class="col-md-12">
                                <a id="btnBatalTambahPaket" href="{{ route('pakets.index') }}"
                                    class="btn btn-danger btn-lg waves-effect waves-light mr-3">Batal</a>
                                <button id="btnTambahPaket" type="submit"
                                    class="btn btn-info btn-lg waves-effect waves-light text-right">Edit</button>
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
        $('.radioStatusPaket').on('change', function() {
            var statusSaatIni = $(this).val();
            if (statusSaatIni == "nonaktif") {
                $("#lblStatusPaketAktif").removeClass("btn-info");
                $("#lblStatusPaketNonaktif").addClass("btn-info");
            } else {
                $("#lblStatusPaketAktif").addClass("btn-info");
                $("#lblStatusPaketNonaktif").removeClass("btn-info");
            }
        });


        $('body').on('click', '#btnTambahLayanan', function() {
            var perawatanid = $("#perawatanSelect").val();
            var namaperawatan = $("#perawatanSelect option:selected").text();
            var durasiperawatan = $("#perawatanSelect option:selected").attr('durasi');
            var hargaperawatan = parseInt($("#perawatanSelect option:selected").attr('harga'));
            var deskripsiperawatan = $("#perawatanSelect option:selected").attr('deskripsi');
            var tanggalReservasi = $("#tanggalReservasi").val();
            var totalHargaPerawatanSaatIni = parseInt($("#tabelTotalHargaPerawatan").attr("total"));


            if (perawatanid != null) {
                $("#containerLayananPerawatan").append("<input id='perawatan" + perawatanid +
                    "' type='hidden' class='classarrayperawatanid' value='" +
                    perawatanid +
                    "' name='arrayperawatanid[]'>");
                $('#trSilahkanPerawatan').remove();
                $("#bodyListPerawatan").append(
                    "<tr><td>" + namaperawatan + "</td>" +
                    "<td>" + durasiperawatan + "</td>" +
                    "<td>" + parseInt(hargaperawatan).toLocaleString('id-ID', {
                        style: 'currency',
                        currency: 'IDR'
                    }).toString().replace("Rp", "") + "</td>" +
                    "<td>" + deskripsiperawatan + "</td>" +
                    "<td>" +
                    "<button type='button' class='deletePerawatan btn btn-danger waves-effect waves-light' idPerawatan='" +
                    perawatanid +
                    "' namaPerawatan='" + namaperawatan + "' durasiPerawatan='" + durasiperawatan +
                    "' hargaPerawatan='" +
                    hargaperawatan + "' deskripsiPerawatan='" + deskripsiperawatan + "'>Hapus</button>" +
                    "</td>" +
                    "</tr>");
                $("#perawatanSelect option:selected").remove();
                $("#perawatanSelect").val('null');
                // $('#cardDetailPerawatan').html(
                //     "<div class='alert alert-info' role='alert'><h6><strong>Silahkan Pilih Perawatan terlebih dahulu!</strong></h6></div>"
                // );
                var totalHargaBaru = totalHargaPerawatanSaatIni + hargaperawatan;
                $("#tabelTotalHargaPerawatan").attr("total", totalHargaBaru);
                $("#tabelTotalHargaPerawatan").text("Total Harga : " + totalHargaBaru.toLocaleString('id-ID', {
                    style: 'currency',
                    currency: 'IDR'
                }).toString());

                $("#numHargaPaket").val(parseInt($("#tabelTotalHargaPerawatan").attr("total")) + parseInt($(
                    "#tabelTotalHargaProduk").attr("total")));
            }
        });

        // $('body').on('click', '.deletePerawatan', function() {
        //     var perawatanid = $(this).attr('idPerawatan');
        //     var namaPerawatan = $(this).attr('namaPerawatan');
        //     var durasiperawatan = $(this).attr('durasiPerawatan');
        //     var hargaperawatan = parseInt($(this).attr('hargaPerawatan'));
        //     var deskripsiperawatan = $(this).attr('deskripsiPerawatan');
        //     var tanggalReservasi = $("#tanggalReservasi").val();
        //     var totalHargaPerawatanSaatIni = parseInt($("#tabelTotalHargaPerawatan").attr("total"));

        //     var totalHargaBaru = totalHargaPerawatanSaatIni - hargaperawatan;
        //     $("#tabelTotalHargaPerawatan").attr("total", totalHargaBaru);
        //     $("#tabelTotalHargaPerawatan").text("Total Harga : " + totalHargaBaru.toLocaleString('id-ID', {
        //         style: 'currency',
        //         currency: 'IDR'
        //     }).toString());

        //     $("#perawatanSelect").append("<option value='" + perawatanid + "' durasi='" + durasiperawatan +
        //         "' harga='" + hargaperawatan + "' deskripsi='" + deskripsiperawatan + "'>" + namaPerawatan +
        //         "</option>");
        //     $(this).parent().parent().remove();
        //     $("#perawatan" + perawatanid).remove();

        //     var select = $("#perawatanSelect");
        //     $("#perawatanSelect option[value='null']").remove();
        //     var options = select.find("option");
        //     options.sort(function(a, b) {
        //         return a.text.localeCompare(b.text);
        //     });
        //     select.empty();
        //     select.append("<option value='null' selected disabled>Pilih Perawatan</option>")
        //     select.append(options);
        //     select.val("null");

        //     if ($('#bodyListPerawatan').find("tr").length == 0) {
        //         $('#bodyListPerawatan').html(
        //             "<tr id='trSilahkanPerawatan'><td colspan='5'>Silahkan Pilih Perawatan</td></tr>");
        //     }

        //     $("#numHargaPaket").val(parseInt($("#tabelTotalHargaPerawatan").attr("total")) + parseInt($(
        //         "#tabelTotalHargaProduk").attr("total")));
        // });

        // $('body').on('click', '#btnTambahProduk', function() {
        //     var idProduk = $("#selectProduk").val();
        //     var namaProduk = unescape($("#selectProduk option:selected").attr("nama"));
        //     var hargaproduk = parseInt($("#selectProduk option:selected").attr('hargajual'));
        //     var kuantitasProduk = parseInt($("#numKuantitasProduk").val());
        //     var totalHargaProdukSaatIni = parseInt($("#tabelTotalHargaProduk").attr("total"));

        //     if (kuantitasProduk <= 0 || (isNaN(kuantitasProduk))) {
        //         kuantitasProduk = 1;
        //     }

        //     if (idProduk != null) {
        //         $("#containerProduk").append("<input id='produk" + idProduk +
        //             "' type='hidden' class='classarrayprodukid' value='" +
        //             idProduk +
        //             "' name='arrayprodukid[]'>");
        //         $("#containerProduk").append("<input id='kuantitasproduk" + idProduk +
        //             "' type='hidden' class='classarrayprodukkuantitas' value='" +
        //             kuantitasProduk +
        //             "' name='arrayprodukkuantitas[]'>");
        //         $('#trSilahkan').remove();
        //         $("#bodyListProduk").append(
        //             "<tr><td>" + namaProduk + "</td>" +
        //             "<td>" + hargaproduk.toLocaleString('id-ID', {
        //                 style: 'currency',
        //                 currency: 'IDR'
        //             }).toString().replace("Rp", "") + "</td>" +
        //             "<td>" + kuantitasProduk + "</td>" +
        //             "<td>" + (hargaproduk * kuantitasProduk).toLocaleString('id-ID', {
        //                 style: 'currency',
        //                 currency: 'IDR'
        //             }).toString().replace("Rp", "") + "</td>" +
        //             "<td>" +
        //             "<button type='button' class='deleteProduk btn btn-danger waves-effect waves-light' idProduk='" +
        //             idProduk + "' nama='" + escape(namaProduk) + "' hargajual='" + hargaproduk +
        //             "' subtotal= '" + hargaproduk * kuantitasProduk + "'>Hapus</button>" +
        //             "</td>" +
        //             "</tr>");
        //         $("#selectProduk option:selected").remove();
        //         $("#selectProduk").val('null');
        //         $("#numKuantitasProduk").val("1");
        //         var totalHargaBaru = totalHargaProdukSaatIni + (hargaproduk * kuantitasProduk);
        //         $("#tabelTotalHargaProduk").attr("total", totalHargaBaru);
        //         $("#tabelTotalHargaProduk").text("Total Harga : " + totalHargaBaru.toLocaleString('id-ID', {
        //             style: 'currency',
        //             currency: 'IDR'
        //         }).toString());

        //         $("#numHargaPaket").val(parseInt($("#tabelTotalHargaPerawatan").attr("total")) + parseInt($(
        //             "#tabelTotalHargaProduk").attr("total")));
        //     }
        // });

        // $('body').on('click', '.deleteProduk', function() {
        //     var idProduk = $(this).attr('idProduk');
        //     var namaProduk = unescape($(this).attr('nama'));
        //     var hargaProduk = $(this).attr('hargajual');
        //     var subTotalProduk = parseInt($(this).attr('subtotal'));
        //     var totalHargaProdukSaatIni = parseInt($("#tabelTotalHargaProduk").attr("total"));

        //     var totalHargaBaru = totalHargaProdukSaatIni - subTotalProduk;
        //     $("#tabelTotalHargaProduk").attr("total", totalHargaBaru);
        //     $("#tabelTotalHargaProduk").text("Total Harga : " + totalHargaBaru.toLocaleString('id-ID', {
        //         style: 'currency',
        //         currency: 'IDR'
        //     }).toString());


        //     $("#selectProduk").append("<option value='" + idProduk + "' nama='" + escape(namaProduk) +
        //         "' hargajual='" +
        //         hargaProduk + "'>" + namaProduk + "</option>");
        //     $(this).parent().parent().remove();
        //     $("#produk" + idProduk).remove();
        //     $("#kuantitasproduk" + idProduk).remove();

        //     var select = $("#selectProduk");
        //     $("#selectProduk option[value='null']").remove();
        //     var options = select.find("option");
        //     options.sort(function(a, b) {
        //         return a.text.localeCompare(b.text);
        //     });
        //     select.empty();
        //     select.append("<option value='null' selected disabled>Pilih Produk</option>")
        //     select.append(options);
        //     select.val("null");

        //     if ($('#bodyListProduk').find("tr").length == 0) {
        //         $('#bodyListProduk').html(
        //             "<tr id='trSilahkan'><td colspan='5'>Silahkan Pilih Produk</td></tr>");
        //     }
        //     $("#numHargaPaket").val(parseInt($("#tabelTotalHargaPerawatan").attr("total")) + parseInt($(
        //         "#tabelTotalHargaProduk").attr("total")));
        // });
    </script>
@endsection
