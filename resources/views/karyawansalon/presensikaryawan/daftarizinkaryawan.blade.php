@extends('layout.adminlayout')

@section('title', 'Admin || Daftar Riwayat Izin Kehadiran')

@section('admincontent')
    <div class="page-title-box">
    </div>
    <!-- end page-title -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body" id="grupAktif">

                    <h3 class="mt-0 header-title">Daftar Riwayat Izin Kehadiran</h3>
                    <p class="sub-title">
                    </p>
                    <button data-toggle="modal" data-target="#modalPilihTanggalPengajuanIzin"
                        class="btn btn-info btn-lg waves-effect waves-light" style="width: 200px">Ajukan Izin
                    </button>
                    <br>
                    @if (session('status'))
                        <br>
                        <div class="alert alert-success">
                            <button type="button" class="close text-danger" data-dismiss="alert" aria-label="Close">
                                <span class="text-success" aria-hidden="true">&times;</span>
                            </button>
                            {{ session('status') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <br>
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
                    <br>

                    <div class="form-group row">
                        <div class="col-md-6">
                            <h4>Daftar Izin Kehadiran Hari Ini dan Kedepannya</h4>
                        </div>
                        <div class="col-md-6 text-right">
                            <div class="btn-group btn-group-toggle border">
                                <a href="#grupAktif" class="btn btn-info waves-effect waves-light radioAktif">
                                    Izin Hari Ini Kedepannya
                                </a>
                                <a href="#grupNonaktif" class="btn waves-effect waves-light radioNonaktif">
                                    Riwayat Izin Kehadiran
                                </a>
                            </div>
                        </div>
                    </div>



                    <div class="table-responsive">
                        <table id="tabelDaftarRiwayatIzinKehadiranHariIniKedepan"
                            class="table table-bordered dt-responsive nowrap text-center w-100"
                            style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th>Nama Karyawan</th>
                                    <th>Waktu Pengajuan izin</th>
                                    <th>Tanggal Izin</th>
                                    <th>Status</th>
                                    <th>Dikonfirmasi Terakhir Pukul</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($daftarRiwayatIzinKaryawanHriIniKedepan as $p)
                                    <tr id="tr_{{ $p->id }}">
                                        <td>{{ $p->karyawan->nama }}</td>
                                        <td>
                                            {{ date('d-m-Y H:i', strtotime($p->created_at)) }}
                                        </td>
                                        <td>
                                            {{ date('d-m-Y', strtotime($p->tanggal_presensi)) }}
                                        </td>

                                        <td id="statusKonfirmasi_{{ $p->id }}">
                                            @if ($p->status == 'belum')
                                                <span style="font-size: 1em;padding: 0.5em 1em;"
                                                    class="badge badge-warning">Belum
                                                    Dikonfirmasi</span>
                                            @elseif($p->status == 'konfirmasi')
                                                <span style="font-size: 1em;padding: 0.5em 1em;"
                                                    class="badge badge-success">Telah
                                                    Dikonfirmasi</span>
                                            @elseif($p->status == 'tolak')
                                                <span style="font-size: 1em;padding: 0.5em 1em;"
                                                    class="badge badge-danger">Izin
                                                    Ditolak</span>
                                            @endif
                                        </td>

                                        <td class="align-middle" id="waktuKonfirmasi_{{ $p->id }}">
                                            @if ($p->status == 'belum')
                                                Menunggu Konfirmasi Admin
                                            @else
                                                {{ date('d-m-Y H:i', strtotime($p->updated_at)) }}
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <br>
                    <br>


                    <div class="form-group row">
                        <div class="col-md-6">
                            <h4>Daftar Riwayat Izin Kehadiran</h4>
                        </div>
                        <div class="col-md-6 text-right" id="grupNonaktif">
                            <div class="btn-group btn-group-toggle border">
                                <a href="#grupAktif" class="btn btn-info waves-effect waves-light radioAktif">
                                    Izin Hari Ini Kedepannya
                                </a>
                                <a href="#grupNonaktif" class="btn waves-effect waves-light radioNonaktif">
                                    Riwayat Izin Kehadiran
                                </a>
                            </div>
                        </div>
                    </div>


                    <div class="table-responsive">
                        <table id="tabelDaftarRiwayatIzinKehadiranSebelumnya"
                            class="table table-bordered dt-responsive nowrap text-center w-100"
                            style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th>Nama Karyawan</th>
                                    <th>Waktu Pengajuan izin</th>
                                    <th>Tanggal Izin</th>
                                    <th>Status</th>
                                    <th>Dikonfirmasi Terakhir Pukul</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($daftarRiwayatIzinKaryawanSebelumnya as $p)
                                    <tr id="tr_{{ $p->id }}">
                                        <td>{{ $p->karyawan->nama }}</td>
                                        <td>
                                            {{ date('d-m-Y H:i', strtotime($p->created_at)) }}
                                        </td>
                                        <td>
                                            {{ date('d-m-Y', strtotime($p->tanggal_presensi)) }}
                                        </td>

                                        <td id="statusKonfirmasi_{{ $p->id }}">
                                            @if ($p->status == 'belum')
                                                <span style="font-size: 1em;padding: 0.5em 1em;"
                                                    class="badge badge-warning">Belum
                                                    Dikonfirmasi</span>
                                            @elseif($p->status == 'konfirmasi')
                                                <span style="font-size: 1em;padding: 0.5em 1em;"
                                                    class="badge badge-success">Telah
                                                    Dikonfirmasi</span>
                                            @elseif($p->status == 'tolak')
                                                <span style="font-size: 1em;padding: 0.5em 1em;"
                                                    class="badge badge-danger">Izin
                                                    Ditolak</span>
                                            @endif
                                        </td>

                                        <td class="align-middle" id="waktuKonfirmasi_{{ $p->id }}">
                                            @if ($p->status == 'belum')
                                                Menunggu Konfirmasi Admin
                                            @else
                                                {{ date('d-m-Y H:i', strtotime($p->updated_at)) }}
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
        <!-- end col -->
    </div>

    <div id="modalDetailRiwayatIzin" class="modal fade bs-example-modal-center" tabindex="-1" role="dialog"
        aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl" style="max-width: 90%;">
            <div class="modal-content ">
                <div class="modal-header">
                    <h5 class="modal-title mt-0" id="modalNamaDetailRiwayatIzin">Detail Riwayat Presensi</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="contentDetailRiwayatIzin">
                    <div class="text-center">
                        <div class="spinner-border text-info" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>

                </div>
                <div class="modal-footer"> <button type="button" class="btn btn-danger waves-effect"
                        data-dismiss="modal">Tutup</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

    <div id="modalKonfirmasiIzin" class="modal fade bs-example-modal-center" tabindex="-1" role="dialog"
        aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content ">
                <div class="modal-header">
                    <h5 class="modal-title mt-0" id="modalNamaKonfirmasiIzin">Konfirmasi Izin</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="contentKonfirmasiIzin">
                    <div class="text-center">
                        <div class="spinner-border text-info" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="btnKonfirmasTutup" type="button" class="btn btn-danger waves-effect"
                        data-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-info waves-effect" data-dismiss="modal" idPresensi="0"
                        keterangan="" id="btnKonfirmasiSimpanIzin">Ya</button>
                </div>

            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

    <div id="modalPilihTanggalPengajuanIzin" class="modal fade bs-example-modal-center" tabindex="-1" role="dialog"
        aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content ">
                <form action="{{ route('karyawans.prosesizinkaryawansalon') }}" method="post">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title mt-0" id="modalNamaPilihTanggalPengajuanIzin">Pilih Tanggal Pembayaran</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" id="contentPilihTanggalPengajuanIzin">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <h6>Silahkan Pilih Tanggal Pengajuan izin</h6>
                            </div>
                            <div class="col-md-12">
                                <input type="date" class="form-control" name="tanggalIzin" id="tanggalPengajuanIzin"
                                    value="{{ date('Y-m-d') }}" aria-describedby="emailHelp" min="{{ date('Y-m-d') }}"
                                    placeholder="Silahkan Pilih Tanggal Pengajuan izin" required>
                                <small id="emailHelp" class="form-text text-muted">Pilih Tanggal Pengajuan Izin
                                    disini!</small>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">Tutup
                        </button>
                        <button type="submit" class="btn btn-info waves-effect">Konfirmasi
                        </button>
                    </div>
                </form>

            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>


@endsection

@section('javascript')
    <script>
        $(document).ready(function() {
            $('#tabelDaftarRiwayatIzinKehadiranHariIniKedepan').DataTable({
                order: [
                    [1, "asc"]
                ],
                language: {
                    emptyTable: "Tidak terdapat Daftar Izin Karyawan untuk hari ini dan kedepannya!",

                }
            });

            $('#tabelDaftarRiwayatIzinKehadiranSebelumnya').DataTable({
                order: [
                    [1, "desc"]
                ],
                language: {
                    emptyTable: "Tidak terdapat Daftar Riwayat Izin Kehadiran Karyawan!",

                }
            });
        });

        $('body').on('click', '.btnDetailRiwayatIzin', function() {
            var tanggalIzin = $(this).attr("tanggalIzin");
            var tanggalIzinHari = $(this).attr('tanggalIzinHari');

            $("#modalNamaDetailRiwayatIzin").text(" Detail Izin Kehadiran " + tanggalIzinHari);

            $.ajax({
                type: 'POST',
                url: '{{ route('admin.getdetailizinkehadiran') }}',
                data: {
                    '_token': '<?php echo csrf_token(); ?>',
                    'tanggalIzin': tanggalIzin,
                },
                success: function(data) {
                    $('#contentDetailRiwayatIzin').html(data.msg);
                    $('#tabelDaftarDetailRiwayatIzin').DataTable({});
                }
            })
        });


        $('body').on('click', '#btnKonfirmasTutup', function() {
            $("#modalDetailRiwayatIzin").modal("show");
        });

        $('body').on('click', '.btnKonfirmasiIzin', function() {
            var idPresensi = $(this).attr("idPresensi");
            var tanggalIzin = $(this).attr('tanggalIzin');
            var keteranganKonfirmasi = $(this).attr('keterangan');
            var namaKaryawan = $(this).attr('namaKaryawan');

            $("#modalNamaKonfirmasiIzin").text("Konfirmasi Izin untuk Karyawan " + namaKaryawan);

            if (keteranganKonfirmasi == "konfirmasi") {
                $("#contentKonfirmasiIzin").html(
                    "<h6>Apakah Anda yakin untuk memberikan izin kehadiran untuk karyawan <span class='text-danger'>" +
                    namaKaryawan + "</span> dengan waktu izin pada <span class='text-danger'>" + tanggalIzin +
                    "</span>?</h6>");
                $("#btnKonfirmasiSimpanIzin").attr("idPresensi", idPresensi);
                $("#btnKonfirmasiSimpanIzin").attr("keterangan", keteranganKonfirmasi);
            } else {
                $("#contentKonfirmasiIzin").html(
                    "<h6>Apakah Anda yakin untuk menolak pengajuan izin kehadiran untuk karyawan <span class='text-danger'>" +
                    namaKaryawan + "</span> dengan waktu izin pada <span class='text-danger'>" + tanggalIzin +
                    "</span>?</h6>");
                $("#btnKonfirmasiSimpanIzin").attr("idPresensi", idPresensi);
                $("#btnKonfirmasiSimpanIzin").attr("keterangan", keteranganKonfirmasi);
            }
            $("#modalDetailRiwayatIzin").modal("hide");
        });

        $('body').on('click', '#btnKonfirmasiSimpanIzin', function() {
            var idPresensi = $(this).attr("idPresensi");
            var keteranganKonfirmasi = $(this).attr('keterangan');

            $.ajax({
                type: 'POST',
                url: '{{ route('admin.updatestatusizin') }}',
                data: {
                    '_token': '<?php echo csrf_token(); ?>',
                    'idPresensi': idPresensi,
                    'keteranganKonfirmasi': keteranganKonfirmasi,
                },
                success: function(data) {
                    if (keteranganKonfirmasi == "konfirmasi") {
                        if (data.waktu == "hariinikedepan") {
                            $("#bodyTableDaftarIzinHariIniKedepan").html(data.msg);
                        } else {
                            $("#bodyTableDaftarIzinHariSebelumnya").html(data.msg);
                        }
                        $("#waktuKonfirmasi_" + idPresensi).html("<p>" + data.updated_at + "</p>");
                        $("#statusKonfirmasi_" + idPresensi).html(
                            "<span style='font-size: 1em;padding: 0.5em 1em;' class='badge badge-success'>Telah " +
                            "Dikonfirmasi </span>");
                        $("#modalDetailRiwayatIzin").modal("show");

                    } else {
                        if (data.waktu == "hariinikedepan") {
                            $("#bodyTableDaftarIzinHariIniKedepan").html(data.msg);
                        } else {
                            $("#bodyTableDaftarIzinHariSebelumnya").html(data.msg);
                        }
                        $("#waktuKonfirmasi_" + idPresensi).html("<p>" + data.updated_at + "</p>");
                        $("#statusKonfirmasi_" + idPresensi).html(
                            "<span style='font-size: 1em;padding: 0.5em 1em;' class='badge badge-danger'>Izin " +
                            "Ditolak </span>");
                        $("#modalDetailRiwayatIzin").modal("show");
                    }

                }
            })
        });


        $('.radioAktif').on('click', function() {
            $(".radioAktif").addClass("btn-info");
            $(".radioNonaktif").removeClass("btn-info");
        });

        $('.radioNonaktif').on('click', function() {
            $(".radioNonaktif").addClass("btn-info");
            $(".radioAktif").removeClass("btn-info");
        });
    </script>
@endsection
