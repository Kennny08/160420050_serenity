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
                            <div class="col-md-12">
                                <h5>Waktu Buka Presensi :
                                    {{ date('H:i', strtotime($objectPertamaYangtanpaIzin->created_at)) }}</h5>
                            </div>
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
                                                    <td>{{ date('Y-m-d', strtotime($p->tanggal_presensi)) }}</td>
                                                    @if (count($presensisHariIni) != $jumlahKaryawan)
                                                        <td>Presensi Belum Dibuka</td>
                                                    @else
                                                        <td>{{ date('H:i', strtotime($p->tanggal_presensi)) }}</td>
                                                    @endif

                                                    <td>
                                                        {{ date('H:i', strtotime($p->tanggal_presensi)) }}
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

                                                    @if ($p->keterangan == 'izin')
                                                        @if ($p->status == 'belum')
                                                            <td><span style="font-size: 1em;padding: 0.5em 1em;"
                                                                    class="badge badge-warning">Belum
                                                                    Dikonfirmasi</span>
                                                            </td>
                                                        @elseif($p->status == 'konfirmasi')
                                                            <td><span style="font-size: 1em;padding: 0.5em 1em;"
                                                                    class="badge badge-success">Telah
                                                                    Dikonfirmasi</span>
                                                            </td>
                                                        @elseif($p->status == 'tolak')
                                                            <td><span style="font-size: 1em;padding: 0.5em 1em;"
                                                                    class="badge badge-danger">Izin Ditolak</span></td>
                                                        @endif
                                                    @else
                                                        @if ($p->status == 'belum')
                                                            <td><span style="font-size: 1em;padding: 0.5em 1em;"
                                                                    class="badge badge-warning">Belum
                                                                    Dikonfirmasi</span>
                                                            </td>
                                                        @elseif($p->status == 'konfirmasi')
                                                            <td><span style="font-size: 1em;padding: 0.5em 1em;"
                                                                    class="badge badge-success">Telah
                                                                    Dikonfirmasi</span>
                                                            </td>
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
                        @endif
                    @else
                        <div>
                            <table id="tabelDaftarPresensiKaryawan"
                                class="table table-bordered dt-responsive nowrap text-center w-100"
                                style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead>
                                    <tr>
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
                                            <td>{{ $p->karyawan->nama }}</td>
                                            <td>{{ date('d-m-Y', strtotime($p->tanggal_presensi)) }}</td>

                                            <td>
                                                @if ($p->keterangan == 'izin')
                                                    {{ date('d-m-Y H:i:s', strtotime($p->created_at)) }}
                                                @else
                                                    {{ date('H:i', strtotime($p->created_at)) }}
                                                @endif

                                            </td>
                                            <td>
                                                @if (date('H:i:s', strtotime($p->created_at)) == date('H:i:s', strtotime($p->tanggal_presensi)))
                                                    Menunggu Karyawan Presensi
                                                @else
                                                    @if ($p->keterangan == 'izin')
                                                        {{ date('d-m-Y H:i:s', strtotime($p->created_at)) }}
                                                    @else
                                                        {{ date('H:i', strtotime($p->tanggal_presensi)) }}
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

                                            @if ($p->keterangan == 'izin')
                                                @if ($p->status == 'belum')
                                                    <td><span style="font-size: 1em;padding: 0.5em 1em;"
                                                            class="badge badge-warning">Belum Dikonfirmasi</span></td>
                                                @elseif($p->status == 'konfirmasi')
                                                    <td><span style="font-size: 1em;padding: 0.5em 1em;"
                                                            class="badge badge-success">Telah Dikonfirmasi</span></td>
                                                @elseif($p->status == 'tolak')
                                                    <td><span style="font-size: 1em;padding: 0.5em 1em;"
                                                            class="badge badge-danger">Izin Ditolak</span></td>
                                                @endif
                                            @else
                                                @if ($p->status == 'belum')
                                                    <td><span style="font-size: 1em;padding: 0.5em 1em;"
                                                            class="badge badge-warning">Belum Dikonfirmasi</span></td>
                                                @elseif($p->status == 'konfirmasi')
                                                    <td><span style="font-size: 1em;padding: 0.5em 1em;"
                                                            class="badge badge-success">Telah Dikonfirmasi</span></td>
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
                    @endif

                </div>
            </div>
        </div>
        <!-- end col -->
    </div>

    <div id="modalDetailKaryawan" class="modal fade bs-example-modal-center" tabindex="-1" role="dialog"
        aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" style="max-width: 600px;">
            <div class="modal-content ">
                <div class="modal-header">
                    <h5 class="modal-title mt-0" id="modalNamaKaryawan">Detail Karyawan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="contentDetailKaryawan">
                    <div class="text-center">
                        <div class="spinner-border text-info" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>

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
            $('#tabelDaftarPresensiKaryawan').DataTable({

            });

            $('#tabelDaftarPresensiKaryawanIzin').DataTable({

            });
        });


        $('.btnHapusKaryawan').on('click', function() {

            var idKaryawan = $(this).attr("idKaryawan");
            var namaKaryawan = $(this).attr('namaKaryawan');
            var routeUrl = $(this).attr('routeUrl');
            $("#modalNamaKaryawanDelete").text("Konfirmasi Penghapusan Karyawan " + namaKaryawan);
            $("#modalBodyHapusKaryawan").html("<h6>Apakah Anda yakin untuk menghapus perawatan " + namaKaryawan +
                "?</h6>")
            $("#formDeleteKaryawan").attr("action", routeUrl);
        });
    </script>
@endsection
