@extends('layout.adminlayout')

@section('title', 'Admin || Buat Reservasi')

@section('admincontent')
    <div class="page-title-box">

    </div>
    <!-- end page-title -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <h3 class="mt-0 header-title">Buat Jadwal Reservasi (ADMIN)</h3>
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

                    <form id="formPilihPerawatan" method="POST" action="{{ route('reservasi.admin.pilihkaryawan') }}"
                        enctype="multipart/form-data">
                        @csrf

                        <div class="form-group row">
                            <div class="form-group col-md-6">
                                <h3>Tanggal Pembuatan : {{ $tanggalHariIni }}</h3>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="exampleInputEmail1"><strong>Nama Pelanggan</strong></label><br>
                                <select name="idPelanggan" id="userPelanggan" class="form-control"
                                    aria-label="Default select example" required>
                                    <option value="null" selected disabled>Pilih Pelanggan</option>
                                    @if (session('daftarPelanggans'))
                                        @foreach (session('daftarPelanggans') as $dp)
                                            @if ($dp->id == session('idPelanggan'))
                                                <option selected value="{{ $dp->id }}">{{ $dp->nama }}</option>
                                            @else
                                                <option value="{{ $dp->id }}">{{ $dp->nama }}</option>
                                            @endif
                                        @endforeach
                                    @else
                                        @foreach ($daftarPelanggans as $pelanggan)
                                            <option value="{{ $pelanggan->id }}">{{ $pelanggan->nama }}</option>
                                        @endforeach
                                    @endif

                                </select>
                                <small id="emailHelp" class="form-text text-muted">Pilih Nama Pelanggan
                                    disini!</small>
                            </div>


                        </div>



                        <div class="form-group row">
                            <div class="col-md-6">
                                <label for="exampleInputEmail1"><strong>Tanggal Reservasi</strong></label>
                                @if (session('tanggalReservasi'))
                                    <input type="date" class="form-control" name="tanggalReservasi" id="tanggalReservasi"
                                        min="{{ $tanggalPertamaDalamMinggu }}" max="{{ $tanggalTerakhirDalamMinggu }}"
                                        aria-describedby="emailHelp" placeholder="Silahkan Pilih Tanggal Reservasi"
                                        value="{{ session('tanggalReservasi') }}" required>
                                    <small id="emailHelp" class="form-text text-muted">Pilih Tanggal Reservasi Anda
                                        disini!</small>
                                @else
                                    <input type="date" class="form-control" name="tanggalReservasi" id="tanggalReservasi"
                                        min="{{ $tanggalPertamaDalamMinggu }}" max="{{ $tanggalTerakhirDalamMinggu }}"
                                        aria-describedby="emailHelp" placeholder="Silahkan Pilih Tanggal Reservasi"
                                        required>
                                    <small id="emailHelp" class="form-text text-muted">Pilih Tanggal Reservasi Anda
                                        disini!</small>
                                @endif

                            </div>

                            <div class="col-md-6">
                                <label for="exampleInputEmail1"><strong>Jam Reservasi</strong></label><br>
                                <select name="slotJam" id="slotJamSelect" class="form-control"
                                    aria-label="Default select example" required>
                                    @if (session('daftarSlotJam'))
                                        @foreach (session('daftarSlotJam') as $sj)
                                            @if ($sj->id == session('idSlotJam'))
                                                <option selected value="{{ $sj->id }}">{{ $sj->jam }}</option>
                                            @else
                                                @if ($sj->status == 'aktif')
                                                    <option value="{{ $sj->id }}">{{ $sj->jam }}</option>
                                                @else
                                                    <option class="text-danger" disabled value="{{ $sj->id }}">
                                                        {{ $sj->jam }} - Tutup</option>
                                                @endif
                                            @endif
                                        @endforeach
                                    @else
                                        @if (count($slotJams) == 0)
                                            <option selected disabled value="null">Tidak ada Slot Jam Tersedia</option>
                                        @else
                                            <option selected disabled value="null">Pilih Slot Jam</option>
                                            @foreach ($slotJams as $sj)
                                                @if ($sj->status == 'aktif')
                                                    <option value="{{ $sj->id }}">{{ $sj->jam }}</option>
                                                @else
                                                    <option class="text-danger" disabled value="{{ $sj->id }}">
                                                        {{ $sj->jam }} - Tutup</option>
                                                @endif
                                            @endforeach
                                        @endif

                                    @endif

                                </select>
                                <small id="emailHelp" class="form-text text-muted">Pilih Jam Reservasi produk
                                    disini!</small>
                            </div>
                        </div>


                        <div class="form-group row" id="containerLayananPerawatan">
                            <div class="col-md-12">
                                <label for="exampleInputEmail1"><strong>Layanan Perawatan</strong></label>
                            </div>
                            <div class="col-md-12">
                                <div class="input-group" style="width: 100%">
                                    <select name="perawatan" id="perawatanSelect" class="form-control"
                                        aria-label="Default select example" required>
                                        <option value="null" selected disabled>Pilih Perawatan</option>
                                        @if (session('arrPerawatan'))
                                            @foreach ($perawatans as $p)
                                                @if (!in_array($p->id, session('arrPerawatan')))
                                                    <option value="{{ $p->id }}" durasi="{{ $p->durasi }}"
                                                        harga="{{ $p->harga }}" deskripsi="{{ $p->deskripsi }}">
                                                        {{ $p->nama }}
                                                    </option>
                                                @endif
                                            @endforeach
                                        @else
                                            @foreach ($perawatans as $p)
                                                <option value="{{ $p->id }}" durasi="{{ $p->durasi }}"
                                                    harga="{{ $p->harga }}" deskripsi="{{ $p->deskripsi }}">
                                                    {{ $p->nama }}
                                                </option>
                                            @endforeach
                                        @endif

                                    </select>
                                    <button type="button" id="btnTambahLayanan" style="width: 150px"
                                        class="btn btn-info waves-effect waves-light ml-2">Tambah Layanan</button>
                                </div>

                                <small id="emailHelp" class="form-text text-muted">Pilih Layanan Perawatan disini!</small>
                            </div>

                            @if (session('arrPerawatanObject'))
                                @foreach (session('arrPerawatanObject') as $aro)
                                    <input id="perawatan{{ $aro->id }}" type="hidden"
                                        class='classarrayperawatanid' value="{{ $aro->id }}"
                                        name="arrayperawatanid[]">
                                @endforeach
                            @endif
                        </div>

                        <div class="form-group row">
                            <div class="col-md-12">
                                <div class="shadow card">
                                    <div class="card-body text-center" id="cardDetailPerawatan">
                                        <div class="align-middle alert alert-info" role="alert">
                                            <h6><strong>Silahkan Pilih Perawatan terlebih dahulu!</strong></h6>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            {{-- <div class="card"
                                style="border: 1px solid #ccc;
                    border-radius: 5px;
                    padding: 15px;
                    margin-top: 5px;
                    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                                <div class="card-body text-center" id="cardDetailPerawatan">
                                    <h5 class="card-header"><strong>Silahkan Pilih Perawatan terlebih dahulu!</strong></h5>

                                </div>
                            </div> --}}
                        </div>

                        <div class="form-group row text-center">
                            <div class="form-group col-md-12 table-responsive">
                                <div>
                                    <table class="table table-bordered table-striped dt-responsive wrap">
                                        <thead>
                                            <tr>
                                                <th class="text-center">Nama Perawatan</th>
                                                <th class="text-center">Durasi (Menit)</th>
                                                <th class="text-center">Harga (Rp)</th>
                                                <th class="text-center">Deskripsi</th>
                                                <th class="text-center">Hapus</th>
                                            </tr>
                                        </thead>
                                        <tbody id="bodyListPerawatan">
                                            @if (session('arrPerawatanObject'))
                                                @foreach (session('arrPerawatanObject') as $aro)
                                                    <tr>
                                                        <td> {{ $aro->nama }} </td>
                                                        <td>{{ $aro->durasi }} </td>
                                                        <td>{{ $aro->harga }} </td>
                                                        <td>{{ $aro->deskripsi }}</td>
                                                        <td><button type='button'
                                                                class='deletePerawatan btn btn-danger waves-effect waves-light'
                                                                idPerawatan="{{ $aro->id }}"
                                                                namaPerawatan="{{ $aro->nama }}"
                                                                durasiPerawatan="{{ $aro->durasi }}"
                                                                hargaPerawatan="{{ $aro->harga }}"
                                                                deskripsiPerawatan="{{ $aro->deskripsi }}">Hapus</button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr id="trSilahkan">
                                                    <td colspan="5">
                                                        Silahkan pilih Layanan Perawatan
                                                    </td>
                                                </tr>
                                            @endif

                                        </tbody>
                                    </table>
                                </div>

                            </div>

                        </div>
                        <div class="form-group text-right">
                            <button id="btnKonfirmasiPerawatan" type="button" data-toggle = "modal"
                                data-target="#modalKonfirmasiPerawatan"
                                class="btn btn-primary btn-lg waves-effect waves-light">Konfirmasi Perawatan</button>
                        </div>

                        <div id="modalKonfirmasiPerawatan" class="modal fade bs-example-modal-center" tabindex="-1"
                            role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title mt-0">Konfirmasi Pemilihan Karyawan</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body text-center" id="bodyModalKonfirmasiPerawatan">
                                        <h6>Apakah Anda ingin memilih karyawan yang akan melayani perawatan Anda?</h6>
                                        <input id="inputPilihKaryawan" type="hidden" name="keteranganPilihKaryawan"
                                            value="tidak">
                                    </div>
                                    <div class="modal-footer">
                                        <button id="btnTidakPilihKaryawan" type="button"
                                            class="btn btn-primary waves-effect">Tidak</button>
                                        <button id="btnPilihKaryawan" type="button"
                                            class="btn btn-info waves-effect waves-light">Ya</button>
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
        var jamReservasi = '09.00';
        var jamTutup = '20:00';

        $('#tanggalReservasi').on('change', function() {
            var tanggal = $(this).val();

            $.ajax({
                type: 'POST',
                url: '{{ route('reservasi.admin.getslotjamaktif') }}',
                data: {
                    '_token': '<?php echo csrf_token(); ?>',
                    'tanggal': tanggal,
                },
                success: function(data) {
                    if (data.status == "ok") {
                        $('#slotJamSelect').html(data.msg);
                    } else {
                        $('#slotJamSelect').html(data.msg);
                    }
                }
            })
        });

        $('#slotJamSelect').on('change', function() {
            // jamReservasi = $("#slotJamSelect option:selected").text();
            // $(".deletePerawatan").each(function(index) {
            //     var perawatanid = $(this).attr('idPerawatan');
            //     var namaPerawatan = $(this).attr('namaPerawatan');
            //     var durasiperawatan = $(this).attr('durasiPerawatan');
            //     var hargaperawatan = $(this).attr('hargaPerawatan');
            //     var deskripsiperawatan = $(this).attr('deskripsiPerawatan');

            //     $("#perawatanSelect").append("<option value='" + perawatanid + "' durasi='" +
            //         durasiperawatan +
            //         "' harga='" + hargaperawatan + "' deskripsi='" + deskripsiperawatan + "'>" +
            //         namaPerawatan +
            //         "</option>");

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
            // });
            // $('#bodyListPerawatan').html(
            //     "<tr id='trSilahkan'><td colspan='5'>Silahkan Pilih Perawatan</td></tr>");

            // $(".classarrayperawatanid").remove();


        });

        $('#perawatanSelect').on('change', function() {
            var idPerawatan = $(this).val();
            $.ajax({
                type: 'POST',
                url: '{{ route('reservasi.admin.getdetailperawatan') }}',
                data: {
                    '_token': '<?php echo csrf_token(); ?>',
                    'idPerawatan': idPerawatan,
                },
                success: function(data) {
                    $('#cardDetailPerawatan').html(data.msg);
                }
            })
        });

        $('body').on('click', '#btnTambahLayanan', function() {
            var perawatanid = $("#perawatanSelect").val();
            var namaperawatan = $("#perawatanSelect option:selected").text();
            var durasiperawatan = $("#perawatanSelect option:selected").attr('durasi');
            var hargaperawatan = $("#perawatanSelect option:selected").attr('harga');
            var deskripsiperawatan = $("#perawatanSelect option:selected").attr('deskripsi');
            var tanggalReservasi = $("#tanggalReservasi").val();

            if (perawatanid != null) {
                $("#containerLayananPerawatan").append("<input id='perawatan" + perawatanid +
                    "' type='hidden' class='classarrayperawatanid' value='" +
                    perawatanid +
                    "' name='arrayperawatanid[]'>");
                $('#trSilahkan').remove();
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
                    hargaperawatan + "' deskripsiPerawatan='" + deskripsiperawatan + "' >Hapus</button>" +
                    "</td>" +
                    "</tr>");
                $("#perawatanSelect option:selected").remove();
                $("#perawatanSelect").val('null');
                $('#cardDetailPerawatan').html(
                    "<div class='alert alert-info' role='alert'><h6><strong>Silahkan Pilih Perawatan terlebih dahulu!</strong></h6></div>"
                );

                // //Pengecekan agar perawatan yang ditambah tidak melebihi jam tutup
                // //Jam Reservasi
                // var waktuAwal = jamReservasi.split(".");
                // var jamAwal = parseInt(waktuAwal[0]);
                // var menitAwal = parseInt(waktuAwal[1]);
                // var waktuAwal = jamAwal + ":" + menitAwal;
                // var tanggalAwal = new Date(tanggalReservasi + " " + waktuAwal);


                // tanggalAwal.setMinutes(tanggalAwal.getMinutes() + parseInt(durasiperawatan));
                // var jamAkhir = tanggalAwal.getHours();
                // var menitAkhir = tanggalAwal.getMinutes();
                // jamAkhir = (jamAkhir < 10 ? "0" : "") + jamAkhir;
                // menitAkhir = (menitAkhir < 10 ? "0" : "") + menitAkhir;

                // var timestampAwal = Date.parse(tanggalReservasi + " " + jamAkhir + ":" + menitAkhir);

                // //Jam TUTUP
                // var tanggalTutup = new Date(tanggalReservasi + " " + jamTutup);
                // var timestampTutup = Date.parse(tanggalTutup);

                // if (timestampAwal <= timestampTutup) {
                //     jamReservasi = jamAkhir + "." + menitAkhir;


                //     // alert(jamReservasi);
                // } else {
                //     alert("Perawatan yang dipilih sudah melebih jam tutup!");
                // }
            }
        });

        $('body').on('click', '.deletePerawatan', function() {
            var perawatanid = $(this).attr('idPerawatan');
            var namaPerawatan = $(this).attr('namaPerawatan');
            var durasiperawatan = $(this).attr('durasiPerawatan');
            var hargaperawatan = $(this).attr('hargaPerawatan');
            var deskripsiperawatan = $(this).attr('deskripsiPerawatan');
            var tanggalReservasi = $("#tanggalReservasi").val();

            $("#perawatanSelect").append("<option value='" + perawatanid + "' durasi='" + durasiperawatan +
                "' harga='" + hargaperawatan + "' deskripsi='" + deskripsiperawatan + "'>" + namaPerawatan +
                "</option>");
            $(this).parent().parent().remove();
            $("#perawatan" + perawatanid).remove();

            var select = $("#perawatanSelect");
            $("#perawatanSelect option[value='null']").remove();
            var options = select.find("option");
            options.sort(function(a, b) {
                return a.text.localeCompare(b.text);
            });
            select.empty();
            select.append("<option value='null' selected disabled>Pilih Perawatan</option>")
            select.append(options);
            select.val("null");

            //Pengurangan variabel jamReservasi di JQuery
            // var waktuAwal = jamReservasi.split(".");
            // var jamAwal = parseInt(waktuAwal[0]);
            // var menitAwal = parseInt(waktuAwal[1]);
            // var waktuAwal = jamAwal + ":" + menitAwal;
            // var tanggalAwal = new Date(tanggalReservasi + " " + waktuAwal);


            // tanggalAwal.setMinutes(tanggalAwal.getMinutes() - parseInt(durasiperawatan));
            // var jamAkhir = tanggalAwal.getHours();
            // var menitAkhir = tanggalAwal.getMinutes();
            // jamAkhir = (jamAkhir < 10 ? "0" : "") + jamAkhir;
            // menitAkhir = (menitAkhir < 10 ? "0" : "") + menitAkhir;
            // jamReservasi = jamAkhir + "." + menitAkhir;
            // alert(jamReservasi);
            //------------------------------------------------------------------

            if ($('#bodyListPerawatan').find("tr").length == 0) {
                $('#bodyListPerawatan').html(
                    "<tr id='trSilahkan'><td colspan='5'>Silahkan Pilih Perawatan</td></tr>");
            }
        });

        $('#btnKonfirmasiPerawatan').on('click', function() {
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

        $('body').on('click', '#btnPilihKaryawan', function() {

            $("#inputPilihKaryawan").val("ya");
            $("#modalKonfirmasiPerawatan").modal("hide");
            $("#formPilihPerawatan").submit();
        });

        $('body').on('click', '#btnTidakPilihKaryawan', function() {

            $("#inputPilihKaryawan").val("tidak");
            $("#modalKonfirmasiPerawatan").modal("hide");
            $("#formPilihPerawatan").submit();
        });
    </script>
@endsection
