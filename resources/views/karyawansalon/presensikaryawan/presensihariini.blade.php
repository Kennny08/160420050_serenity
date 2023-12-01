@extends('layout.adminlayout')

@section('title', 'Admin || Presensi Karyawan')

@section('admincontent')
    <div class="page-title-box">
    </div>
    <!-- end page-title -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <h3 class="mt-0 header-title">Daftar Presensi Kehadiran Karyawan</h3>
                    <p class="sub-title">
                    </p>
                    {{-- @if ($presensiKaryawan == null)
                        <a class="btn btn-info waves-effect waves-light" href={{ route('presensikehadirans.create') }}>Buka
                            Presensi
                            Karyawan</a>
                    @endif --}}
                    <br>
                    @if (session('status'))
                        <div class="alert alert-success">
                            <button type="button" class="close text-danger" data-dismiss="alert" aria-label="Close">
                                <span class="text-success" aria-hidden="true">&times;</span>
                            </button>
                            {{ session('status') }}
                        </div>
                        <br>
                    @endif



                    @if ($objectPertamaYangtanpaIzin != null)
                        <div class="form-group row">
                            <div class="col-md-8">
                                <h5>Waktu Buka Presensi :
                                    {{ date('H:i', strtotime($objectPertamaYangtanpaIzin->created_at)) }}
                                </h5>
                                @if (
                                    $presensiKaryawan != null &&
                                        ($presensiKaryawan->keterangan != 'izin' || $presensiKaryawan->keterangan != 'sakit') &&
                                        date('H:i:s', strtotime($presensiKaryawan->created_at)) ==
                                            date('H:i:s', strtotime($presensiKaryawan->tanggal_presensi)))
                                    <h6 class="text-danger">Silahkan lakukan presensi dengan memastikan kehadiran Anda pada
                                        kolom Keterangan dan Silahkan lakukan Konfirmasi Presensi!
                                    </h6>
                                @endif
                            </div>
                            @if (date('H:i:s', strtotime($presensiKaryawan->created_at)) ==
                                    date('H:i:s', strtotime($presensiKaryawan->tanggal_presensi)) &&
                                    ($presensiKaryawan->keterangan != 'izin' || $presensiKaryawan->keterangan != 'sakit'))
                                <div class="col-md-4 text-right">
                                    <button class="btn btn-info waves-effect waves-light mt-3 btn-lg" style="width: 250px;"
                                        id="btnModalKonfirmasiPresensi" data-toggle="modal"
                                        data-target="#modalKonfirmasiPresensi" keterangan="hadir"
                                        tanggalHariIni="{{ date('d-m-Y') }}" type="submit">
                                        Konfirmasi Presensi</button>
                                </div>
                            @endif

                        </div>
                    @else
                        <div class="form-group row">
                            <div class="col-md-12">
                                <h5>Waktu Buka Presensi : <span class="text-danger">-</span></h5>
                            </div>
                        </div>
                    @endif

                    @if (
                        $presensiKaryawan == null ||
                            count($presensisHariIni) != $jumlahKaryawan ||
                            (count($presensisHariIni) != $jumlahKaryawan &&
                                ($presensiKaryawan->keterangan == 'izin' || $presensiKaryawan->keterangan == 'sakit')))
                        <div class="col-xl-12">
                            <div class="card text-white bg-danger text-center">
                                <div class="card-body">
                                    <blockquote class="card-bodyquote mb-0">
                                        <h3>Belum ada presensi untuk hari ini pada tanggal
                                            {{ date('d-m-Y') }}</h3>
                                        <footer class=" text-white font-12">
                                            <h5>Silahkan menunggu Admin Salon membuka presensi terlebih dahulu!</h5>
                                        </footer>
                                    </blockquote>
                                </div>
                            </div>
                        </div>
                    @else
                        <div>
                            <table id="tabelDaftarPresensiKaryawan"
                                class="table table-bordered dt-responsive nowrap text-center w-100"
                                style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead>
                                    <tr>
                                        <th>Tanggal Presensi</th>
                                        @if ($presensiKaryawan->keterangan == 'izin' || $presensiKaryawan->keterangan == 'sakit')
                                            <th>Waktu Pengajuan Izin</th>
                                        @else
                                            <th>Waktu Buka Presensi</th>
                                            <th>Waktu Karyawan Presensi</th>
                                        @endif
                                        <th>Keterangan</th>
                                        <th>Status</th>
                                        <th>Dikonfirmasi Terakhir Pukul</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <tr id="tr_{{ $presensiKaryawan->id }}">
                                        <td>{{ date('d-m-Y', strtotime($presensiKaryawan->tanggal_presensi)) }}</td>

                                        @if ($presensiKaryawan->keterangan == 'izin' || $presensiKaryawan->keterangan == 'sakit')
                                            <td>
                                                {{ date('d-m-Y H:i:s', strtotime($presensiKaryawan->created_at)) }}
                                            </td>
                                        @else
                                            <td>
                                                {{ date('H:i', strtotime($presensiKaryawan->created_at)) }}
                                            </td>
                                            @if (date('H:i:s', strtotime($presensiKaryawan->created_at)) ==
                                                    date('H:i:s', strtotime($presensiKaryawan->tanggal_presensi)))
                                                <td>
                                                    Menunggu Karyawan Presensi
                                                </td>
                                            @else
                                                <td>
                                                    {{ date('H:i', strtotime($presensiKaryawan->tanggal_presensi)) }}
                                                </td>
                                            @endif
                                        @endif

                                        @if (date('H:i:s', strtotime($presensiKaryawan->created_at)) ==
                                                date('H:i:s', strtotime($presensiKaryawan->tanggal_presensi)))
                                            <td>
                                                <form method="POST" id="formKonfirmasiPresensi"
                                                    action="{{ route('karyawans.prosespresensihariinikaryawansalon') }}"
                                                    enctype="multipart/form-data">
                                                    @csrf
                                                    <div class="btn-group btn-group-toggle border w-100"
                                                        data-toggle="buttons">
                                                        <input type="hidden" name="idPresensiKaryawan"
                                                            value="{{ $presensiKaryawan->id }}">

                                                        <label class="btn btn-primary waves-effect waves-light"
                                                            id="lblKeteranganHadir">
                                                            <input type="radio" value="hadir"
                                                                name="radioKeteranganPresensi" id="optionKeteranganHadir"
                                                                class="radioKeteranganPresensi" checked>
                                                            Hadir
                                                        </label>
                                                    </div>

                                                </form>

                                            </td>
                                        @else
                                            @if ($presensiKaryawan->keterangan == 'hadir')
                                                <td class="text-success">HADIR</td>
                                            @elseif($presensiKaryawan->keterangan == 'absen')
                                                <td class="text-danger">ABSEN</td>
                                            @elseif($presensiKaryawan->keterangan == 'izin')
                                                <td class="text-warning">IZIN</td>
                                            @elseif($presensiKaryawan->keterangan == 'sakit')
                                                <td class="text-info">SAKIT</td>
                                            @endif
                                        @endif


                                        @if ($presensiKaryawan->keterangan == 'izin' || $presensiKaryawan->keterangan == 'sakit')
                                            @if ($presensiKaryawan->status == 'belum')
                                                <td><span class="text-warning font-weight-bold">Belum
                                                        Dikonfirmasi</span></td>
                                            @elseif($presensiKaryawan->status == 'konfirmasi')
                                                <td><span class="text-success font-weight-bold">Telah
                                                        Dikonfirmasi</span></td>
                                            @elseif($presensiKaryawan->status == 'tolak')
                                                <td><span class="text-danger font-weight-bold">Izin Ditolak</span></td>
                                            @endif
                                        @else
                                            @if ($presensiKaryawan->status == 'belum')
                                                <td><span class="text-warning font-weight-bold">Belum
                                                        Dikonfirmasi</span></td>
                                            @elseif($presensiKaryawan->status == 'konfirmasi')
                                                <td><span class="text-success font-weight-bold">Telah
                                                        Dikonfirmasi</span></td>
                                            @endif
                                        @endif

                                        <td>
                                            @if ($presensiKaryawan->status == 'belum')
                                                Menunggu Konfirmasi
                                            @else
                                                {{ date('H:i', strtotime($presensiKaryawan->updated_at)) }}
                                            @endif
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    @endif

                </div>
            </div>
        </div>
        <!-- end col -->
    </div>

    <div id="modalKonfirmasiPresensi" class="modal fade bs-example-modal-center" tabindex="-1" role="dialog"
        aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content ">

                <div class="modal-header">
                    <h5 class="modal-title mt-0" id="modalKonfirmasiPresensi">Konfirmasi Presensi</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="text-center" id="contentKonfirmasiPresensi">

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">Tutup</button>
                    <button class="btn btn-info waves-effect" id="btnSubmitKonfirmasi" type="button">Konfirmasi</button>
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

        });

        $('body').on('click', '#btnModalKonfirmasiPresensi', function() {
            var keterangan = $(this).attr("keterangan");
            var tanggal = $(this).attr("tanggalHariIni");

            if (keterangan == "hadir") {
                $("#contentKonfirmasiPresensi").html(
                    "<h6>Apa Anda yakin untuk mengkonfirmasi presensi </h6><h6>pada tanggal <span class='text-danger'>" +
                    tanggal + "</span> dengan keterangan <span class='text-primary'>" + keterangan +
                    "</span>?</h6>");
            } else {
                $("#contentKonfirmasiPresensi").html(
                    "<h6>Apa Anda yakin untuk mengkonfirmasi presensi </h6><h6>pada tanggal <span class='text-danger'>" +
                    tanggal + "</span> dengan keterangan <span class='text-danger'>" + keterangan +
                    "</span>?</h6>");
            }

        });

        $('body').on('click', '#btnSubmitKonfirmasi', function() {
            $("#formKonfirmasiPresensi").submit();

        });

        $('body').on('change', '.radioKeteranganPresensi', function() {
            var statusSaatIni = $(this).val();
            if (statusSaatIni == "sakit") {
                $("#lblKeteranganHadir").removeClass("btn-primary");
                $("#lblKeteranganSakit").addClass("btn-danger");
                $("#btnModalKonfirmasiPresensi").attr("keterangan", "sakit");
            } else {
                $("#lblKeteranganHadir").addClass("btn-primary");
                $("#lblKeteranganSakit").removeClass("btn-danger");
                $("#btnModalKonfirmasiPresensi").attr("keterangan", "hadir");
            }
        });
    </script>
@endsection
