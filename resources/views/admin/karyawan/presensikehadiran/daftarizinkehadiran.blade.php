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
                    @if (session('status'))
                        <div class="alert alert-success">
                            <button type="button" class="close text-danger" data-dismiss="alert" aria-label="Close">
                                <span class="text-success" aria-hidden="true">&times;</span>
                            </button>
                            {{ session('status') }}
                        </div>
                    @endif


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

                                    <th>Tanggal Izin</th>
                                    <th hidden>Tanggal Izin</th>
                                    <th>Jumlah Karyawan Izin</th>
                                    <th>Dikonfirmasi</th>
                                    <th>Ditolak</th>
                                    <th>Belum Dikonfirmasi</th>
                                    <th>Detail</th>
                                </tr>
                            </thead>

                            <tbody id="bodyTableDaftarIzinHariIniKedepan">
                                @foreach ($daftarIzinPresensiHariIniKedepan as $p)
                                    <tr>
                                        <td>{{ $p['tanggalIzinHari'] }}</td>
                                        <td hidden>{{ $p['tanggalIzin'] }}</td>
                                        <td>{{ $p['jumlahKaryawan'] }}
                                        </td>
                                        <td>{{ $p['daftarIzin']->where('status', 'konfirmasi')->count() }}
                                        </td>
                                        <td>{{ $p['daftarIzin']->where('status', 'tolak')->count() }}
                                        </td>
                                        <td>
                                            @if ($p['daftarIzin']->where('status', 'belum')->count() > 0)
                                                <span
                                                    class="text-danger font-weight-bold">{{ $p['daftarIzin']->where('status', 'belum')->count() }}</span>
                                            @else
                                                0
                                            @endif

                                        </td>
                                        <td>
                                            <button data-toggle="modal" data-target="#modalDetailRiwayatIzin"
                                                tanggalIzin="{{ $p['tanggalIzin'] }}"
                                                tanggalizinHari="{{ $p['tanggalIzinHari'] }}"
                                                class=" btn btn-info waves-effect waves-light btnDetailRiwayatIzin">Detail
                                            </button>
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

                                    <th>Tanggal Izin</th>
                                    <th hidden>Tanggal Izin</th>
                                    <th>Jumlah Karyawan Izin</th>
                                    <th>Dikonfirmasi</th>
                                    <th>Ditolak</th>
                                    <th>Belum Dikonfirmasi</th>
                                    <th>Detail</th>
                                </tr>
                            </thead>

                            <tbody id="bodyTableDaftarIzinHariSebelumnya">
                                @foreach ($daftarIzinPresensiHariSebelumnya as $p)
                                    <tr>

                                        <td>{{ $p['tanggalIzinHari'] }}</td>
                                        <td hidden>{{ $p['tanggalIzin'] }}</td>
                                        <td>{{ $p['jumlahKaryawan'] }}
                                        </td>
                                        <td>{{ $p['daftarIzin']->where('status', 'konfirmasi')->count() }}
                                        </td>
                                        <td>{{ $p['daftarIzin']->where('status', 'tolak')->count() }}
                                        </td>
                                        <td>
                                            @if ($p['daftarIzin']->where('status', 'belum')->count() > 0)
                                                <span
                                                    class="text-danger font-weight-bold">{{ $p['daftarIzin']->where('status', 'belum')->count() }}</span>
                                            @else
                                                0
                                            @endif

                                        </td>
                                        <td>
                                            <button data-toggle="modal" data-target="#modalDetailRiwayatIzin"
                                                tanggalIzin="{{ $p['tanggalIzin'] }}"
                                                tanggalizinHari="{{ $p['tanggalIzinHari'] }}"
                                                class=" btn btn-info waves-effect waves-light btnDetailRiwayatIzin">Detail
                                            </button>

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

    <div id="modalFileTambahan" class="modal fade bs-example-modal-center" tabindex="-1" role="dialog"
        aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
            <div class="modal-content ">
                <div class="modal-header">
                    <h5 class="modal-title mt-0" id="modalNamaFileTambahan">Detail File Tambahan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="contentFileTambahan">
                    <div class="text-center">
                        <div class="spinner-border text-info" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer"> <button type="button" class="btn btn-danger waves-effect btnTutupFileTambahan"
                        data-dismiss="modal">Tutup</button>
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
                            "<span class='text-success font-weight-bolds'>Telah " +
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
                            "<span class='text-danger font-weight-bold'>Izin " +
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

        $('body').on('click', '.btnTutupFileTambahan', function() {
            $("#modalDetailRiwayatIzin").modal("show");
        });

        $('body').on('click', '.btnLihatFileTambahan', function() {
            var namaImage = $(this).attr("namaImage");
            $("#modalDetailRiwayatIzin").modal("hide");
            $("#contentFileTambahan").html("<img class='img-fluid' src='" + namaImage + "' alt='filetambahan'>");
        });
    </script>
@endsection
