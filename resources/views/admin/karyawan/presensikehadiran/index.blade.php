@extends('layout.adminlayout')

@section('title', 'Admin || Daftar Presensi Kehadiran Karyawan')

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
                    {{-- <a class="btn btn-primary" data-toggle="modal" href="{{ route('categories.create') }}">Add Category</a> --}}
                    @if (count($presensisHariIni) == 0)
                        <a class="btn btn-info waves-effect waves-light" href={{ route('presensikehadirans.create') }}>Buka
                            Presensi
                            Karyawan</a>
                    @elseif(count($presensisHariIni) == $jumlahKaryawan)
                        <a class="btn btn-info waves-effect waves-light"
                            href={{ route('admin.presensikehadirans.editpresensi', $tanggalHariIni) }}>Edit
                            Presensi
                            Karyawan</a>
                    @else
                        @if (count($idKaryawanUnikIzin) != $jumlahKaryawan)
                            <a class="btn btn-info waves-effect waves-light"
                                href={{ route('presensikehadirans.create') }}>Buka
                                Presensi
                                Karyawan</a>
                        @else
                            <a class="btn btn-info waves-effect waves-light"
                                href={{ route('admin.presensikehadirans.editpresensi', $tanggalHariIni) }}>Edit
                                Presensi
                                Karyawan</a>
                        @endif

                    @endif
                    <br>
                    <br>
                    @if ($objectPertamaYangtanpaIzin != null)
                        <div class="form-group row">
                            <div class="col-md-6">
                                <h5>Waktu Buka Presensi :
                                    {{ date('H:i', strtotime($objectPertamaYangtanpaIzin->created_at)) }}</h5>
                            </div>
                            @if (count($presensisHariIni) == $jumlahKaryawan && $presensisHariIni->where('status', 'belum')->count() > 0)
                                <div class="col-md-6 text-right">

                                    <button class="btn btn-info waves-effect waves-light ml-3" data-toggle="modal"
                                        data-target="#modalKonfirmasiCheckPresensi" id="btnKonfirmasiCheck" disabled>
                                        Konfirmasi Check Presensi</button><br>
                                    <div class="mt-2 d-flex align-items-center justify-content-end">
                                        <input type="checkbox" id="checkBoxKonfirmasiAll"
                                            style="width: 1.8em; height: 1.8em;" class="mr-1"><span
                                            class="font-weight-normal ml-1 h6" style="margin-top: 8px;">Check Semua</span>
                                    </div>

                                </div>
                            @endif
                        </div>
                    @endif
                    {{-- @if ($jumlahIzinKehadiran > 0)
                        <button class="btn btn-primary waves-effect waves-light" data-toggle="modal" id="btnDaftarIzin">
                            <span
                                class="badge badge-danger badge-pill float-right ml-2">{{ $jumlahIzinKehadiran }}</span><span>
                                Daftar Izin Karyawan</span>
                        </button>
                    @endif --}}
                    @if (session('status'))
                        <div class="alert alert-success">
                            <button type="button" class="close text-danger" data-dismiss="alert" aria-label="Close">
                                <span class="text-success" aria-hidden="true">&times;</span>
                            </button>
                            {{ session('status') }}
                        </div>
                    @endif

                    @if (count($presensisHariIni) != $jumlahKaryawan)
                        <div class="col-xl-12">
                            <div class="card text-white bg-danger text-center">
                                <div class="card-body">
                                    <blockquote class="card-bodyquote mb-0">
                                        <h3>Belum ada presensi untuk hari ini pada tanggal
                                            {{ date('d-m-Y', strtotime($tanggalHariIni)) }}</h3>
                                        <footer class=" text-white font-12">
                                            <h5>Silahkan buka presensi terlebih dahulu!</h5>
                                        </footer>
                                    </blockquote>
                                </div>
                            </div>
                        </div>
                        @if (count($presensiIzinKehadiranHariIni) > 0)
                            <div class="form-group row">
                                <div class="form-group col-md-12">
                                    <h4>Daftar Presensi Izin Hari Ini </h4>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="table-responsive">
                                    <div class="form-group col-md-12">
                                        <table id="tabelDaftarPresensiKaryawanIzin"
                                            class="table table-bordered dt-responsive nowrap text-center w-100"
                                            style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                            <thead>
                                                <tr>
                                                    <th>Nama Karyawan</th>
                                                    <th>Tanggal Pengajuan Izin</th>
                                                    <th>Waktu Buka Presensi</th>
                                                    <th>Waktu Karyawan Izin</th>
                                                    <th>Keterangan</th>
                                                    <th>Status</th>
                                                    <th>Dikonfirmasi Terakhir Pukul</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                @foreach ($presensiIzinKehadiranHariIni as $p)
                                                    <tr id="tr_{{ $p->id }}">
                                                        <td>{{ $p->karyawan->nama }}</td>
                                                        <td>{{ date('d-m-Y', strtotime($p->tanggal_presensi)) }}</td>
                                                        @if (count($presensisHariIni) != $jumlahKaryawan)
                                                            <td>Presensi Belum Dibuka</td>
                                                        @else
                                                            <td>{{ date('H:i', strtotime($p->tanggal_presensi)) }}</td>
                                                        @endif

                                                        <td>
                                                            {{ date('d-m-Y', strtotime($p->tanggal_presensi)) }}
                                                        </td>

                                                        @if ($p->keterangan == 'hadir')
                                                            <td class="text-success">HADIR</td>
                                                        @elseif($p->keterangan == 'absen')
                                                            <td class="text-danger">ABSEN</td>
                                                        @elseif($p->keterangan == 'izin')
                                                            <td class="text-warning">IZIN</td>
                                                        @elseif($p->keterangan == 'sakit')
                                                            <td class="text-info">SAKIT</td>
                                                        @endif

                                                        @if ($p->keterangan == 'izin' || $p->keterangan == 'sakit')
                                                            @if ($p->status == 'belum')
                                                                <td><span class="text-warning font-weight-bold">Belum
                                                                        Dikonfirmasi</span></td>
                                                            @elseif($p->status == 'konfirmasi')
                                                                <td><span class="text-success font-weight-bold">Telah
                                                                        Dikonfirmasi</span></td>
                                                            @elseif($p->status == 'tolak')
                                                                <td><span class="text-danger font-weight-bold">Izin
                                                                        Ditolak</span></td>
                                                            @endif
                                                        @else
                                                            @if ($p->status == 'belum')
                                                                <td><span class="text-warning font-weight-bold">Belum
                                                                        Dikonfirmasi</span></td>
                                                            @elseif($p->status == 'konfirmasi')
                                                                <td><span class="text-success font-weight-bold">Telah
                                                                        Dikonfirmasi</span></td>
                                                            @endif
                                                        @endif

                                                        <td>
                                                            @if ($p->status == 'belum')
                                                                Menunggu Konfirmasi
                                                            @else
                                                                {{ date('H:i', strtotime($p->updated_at)) }}
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                            </div>
                        @endif
                    @else
                        <form id="formKonfirmasiCheckPresensi"
                            action="{{ route('admin.presensikehadirans.konfirmasicheckpresensi') }}" method="POST">
                            @csrf
                            <div class="table-responsive">

                                <table id="tabelDaftarPresensiKaryawan"
                                    class="table table-bordered table-striped dt-responsive nowrap text-center w-100"
                                    style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>Check Konfirmasi</th>
                                            <th>Nama Karyawan</th>
                                            <th>Tanggal Presensi</th>
                                            <th>Waktu Pembuatan</th>
                                            <th>Waktu Karyawan Presensi</th>
                                            <th>Keterangan</th>
                                            <th>Status</th>
                                            <th>Dikonfirmasi Terakhir Pukul</th>
                                        </tr>
                                    </thead>

                                    <tbody>

                                        @foreach ($presensisHariIni as $p)
                                            <tr id="tr_{{ $p->id }}">
                                                <td class="text-center align-middle">
                                                    @if ($p->status == 'belum')
                                                        {{-- <input type="hidden" id="hiddenIdPresensi" name="idPresensi[]"
                                                            value="{{ $p->id }}" class="hiddenIdPresensi"
                                                            namaKaryawan = "{{ $p->karyawan->nama }}"
                                                            keteranganIzin = "{{ $p->keterangan }}"> --}}
                                                        <input type="checkbox" name="checkKonfirmasi[]"
                                                            id="checkBoxKonfirmasi_{{ $p->id }}"
                                                            class="form-control checkBoxKonfirmasiPresensi"
                                                            value="{{ $p->id }}"
                                                            namaKaryawan = "{{ $p->karyawan->nama }}"
                                                            keteranganPresensi = "{{ $p->keterangan }}">
                                                    @else
                                                        <strong>Dikonfirmasi</strong>
                                                    @endif
                                                </td>
                                                <td>{{ $p->karyawan->nama }}</td>
                                                <td>{{ date('d-m-Y', strtotime($p->tanggal_presensi)) }}</td>

                                                <td>
                                                    @if ($p->keterangan == 'izin' || $p->keterangan == 'sakit')
                                                        {{ date('d-m-Y H:i:s', strtotime($p->created_at)) }}
                                                    @else
                                                        {{ date('H:i', strtotime($p->created_at)) }}
                                                    @endif

                                                </td>
                                                <td>
                                                    @if (date('H:i:s', strtotime($p->created_at)) == date('H:i:s', strtotime($p->tanggal_presensi)))
                                                        @if ($p->status == 'konfirmasi')
                                                            {{ date('H:i', strtotime($p->updated_at)) }} <br> <span
                                                                class="text-danger">*oleh Admin</span>
                                                        @else
                                                            Menunggu Karyawan Presensi
                                                        @endif
                                                    @else
                                                        @if ($p->keterangan == 'izin' || $p->keterangan == 'sakit')
                                                            {{ date('d-m-Y H:i:s', strtotime($p->created_at)) }}
                                                        @else
                                                            @if ($p->status == 'konfirmasi')
                                                                {{ date('H:i', strtotime($p->tanggal_presensi)) }}
                                                            @else
                                                                Menunggu Karyawan Presensi
                                                            @endif
                                                        @endif
                                                    @endif
                                                </td>

                                                @if ($p->keterangan == 'hadir')
                                                    <td class="text-success">HADIR</td>
                                                @elseif($p->keterangan == 'absen')
                                                    <td class="text-danger">ABSEN</td>
                                                @elseif($p->keterangan == 'izin')
                                                    <td class="text-warning">IZIN</td>
                                                @elseif($p->keterangan == 'sakit')
                                                    <td class="text-info">SAKIT</td>
                                                @endif

                                                @if ($p->keterangan == 'izin' || $p->keterangan == 'sakit')
                                                    @if ($p->status == 'belum')
                                                        <td><span class="text-warning font-weight-bold">Belum
                                                                Dikonfirmasi</span></td>
                                                    @elseif($p->status == 'konfirmasi')
                                                        <td><span class="text-success font-weight-bold">Telah
                                                                Dikonfirmasi</span></td>
                                                    @elseif($p->status == 'tolak')
                                                        <td><span class="text-danger font-weight-bold">Izin Ditolak</span>
                                                        </td>
                                                    @endif
                                                @else
                                                    @if ($p->status == 'belum')
                                                        <td><span class="text-warning font-weight-bold">Belum
                                                                Dikonfirmasi</span></td>
                                                    @elseif($p->status == 'konfirmasi')
                                                        <td><span class="text-success font-weight-bold">Telah
                                                                Dikonfirmasi</span></td>
                                                    @endif
                                                @endif

                                                <td>
                                                    @if ($p->status == 'belum')
                                                        Menunggu Konfirmasi
                                                    @else
                                                        {{ date('H:i', strtotime($p->updated_at)) }}
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                            </div>
                        </form>
                    @endif

                </div>
            </div>
        </div>
        <!-- end col -->
    </div>

    <div id="modalKonfirmasiCheckPresensi" class="modal fade bs-example-modal-center" tabindex="-1" role="dialog"
        aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" style="max-width: 600px;">
            <div class="modal-content ">
                <div class="modal-header">
                    <h5 class="modal-title mt-0" id="modalKonfirmasiCheckPresensi">Konfirmasi Check Presensi</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body overflow-auto">
                    <div class=" m-3" id="contentKonfirmasiCheckPresensi">
                        <div class="spinner-border text-info" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">Batal</button>
                    <button type="button" id="btnSubmitFormKonfirmasiCheckPresensi"
                        class="btn btn-info waves-effect">Konfirmasi</button>
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
            $('#tabelDaftarPresensiKaryawan').DataTable({
                order: [
                    [1, "asc"],
                ]
            });

            $('#tabelDaftarPresensiKaryawanIzin').DataTable({

            });
        });

        $("body").on("change", ".checkBoxKonfirmasiPresensi", function() {
            var count = 0;
            $(".checkBoxKonfirmasiPresensi").each(function(index) {
                if ($(this).prop("checked") == true) {
                    count += 1;
                }
            });

            if (count > 0) {
                $("#btnKonfirmasiCheck").prop("disabled", false);
            } else {
                $("#btnKonfirmasiCheck").prop("disabled", true);
            }
        });

        $("body").on("click", "#btnKonfirmasiCheck", function() {
            $pesanKonfirmasi = "<h6>Berikut daftar karyawan dan keterangan yang akan dikonfirmasi:<br><br><ol >";
            $(".checkBoxKonfirmasiPresensi").each(function(index) {
                if ($(this).prop("checked") == true) {
                    $pesanKonfirmasi += "<span class='text-danger'><li class='mb-2'> " + $(this).attr(
                            "namaKaryawan") +
                        " - " + $(this).attr(
                            "keteranganPresensi") +
                        "</li></span>";
                }
            });
            $pesanKonfirmasi += "</ol>Apa Anda yakin data yang akan dikonfirmasi sudah benar?</h6>";
            $("#contentKonfirmasiCheckPresensi").html($pesanKonfirmasi);
        });

        $("body").on("click", "#btnSubmitFormKonfirmasiCheckPresensi", function() {
            $("#formKonfirmasiCheckPresensi").submit();
        });



        $("body").on("change", "#checkBoxKonfirmasiAll", function() {
            if ($("#checkBoxKonfirmasiAll").prop("checked") == true) {
                $(".checkBoxKonfirmasiPresensi").each(function(index) {
                    $(this).prop("checked", true);
                    $("#btnKonfirmasiCheck").prop("disabled", false);
                });
            } else {
                $(".checkBoxKonfirmasiPresensi").each(function(index) {
                    $(this).prop("checked", false);
                    $("#btnKonfirmasiCheck").prop("disabled", true);
                });
            }

        });
    </script>
@endsection
