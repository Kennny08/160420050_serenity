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
                                    <th>Keterangan</th>
                                    <th>Waktu Pengajuan izin</th>
                                    <th>Tanggal Izin</th>
                                    <th>Deskripsi Izin</th>
                                    <th>File Tambahan</th>
                                    <th>Status</th>
                                    <th>Dikonfirmasi Terakhir Pukul</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($daftarRiwayatIzinKaryawanHriIniKedepan as $p)
                                    <tr id="tr_{{ $p->id }}">

                                        @if ($p->keterangan == 'izin')
                                            <td class="text-warning">IZIN</td>
                                        @elseif($p->keterangan == 'sakit')
                                            <td class="text-info">SAKIT</td>
                                        @endif

                                        <td>
                                            {{ date('d-m-Y H:i', strtotime($p->created_at)) }}
                                        </td>
                                        <td>
                                            {{ date('d-m-Y', strtotime($p->tanggal_presensi)) }}
                                        </td>

                                        <td>{{ $p->deskripsi }}</td>

                                        <td>
                                            @if ($p->file_tambahan != null)
                                                <button data-toggle="modal" data-target="#modalFileTambahan"
                                                    class="btn btn-info waves-effect waves-light btnLihatFileTambahan"
                                                    namaImage="{{ asset('assets_admin/images/izin_sakit_karyawan/') }}/{{ $p->file_tambahan }}">Lihat
                                                </button>
                                            @else
                                                Tidak ada File Tambahan
                                            @endif
                                        </td>


                                        <td id="statusKonfirmasi_{{ $p->id }}">
                                            @if ($p->status == 'belum')
                                                <span class="text-warning font-weight-bold">Belum
                                                    Dikonfirmasi</span>
                                            @elseif($p->status == 'konfirmasi')
                                                <span class="text-success font-weight-bold">Telah
                                                    Dikonfirmasi</span>
                                            @elseif($p->status == 'tolak')
                                                <span class="text-danger font-weight-bold">Izin Ditolak</span>
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
                                    <th>Keterangan</th>
                                    <th>Waktu Pengajuan izin</th>
                                    <th>Tanggal Izin</th>
                                    <th>Deskripsi Izin</th>
                                    <th>File Tambahan</th>
                                    <th>Status</th>
                                    <th>Dikonfirmasi Terakhir Pukul</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($daftarRiwayatIzinKaryawanSebelumnya as $p)
                                    <tr id="tr_{{ $p->id }}">

                                        @if ($p->keterangan == 'izin')
                                            <td class="text-warning">IZIN</td>
                                        @elseif($p->keterangan == 'sakit')
                                            <td class="text-info">SAKIT</td>
                                        @endif

                                        <td>
                                            {{ date('d-m-Y H:i', strtotime($p->created_at)) }}
                                        </td>
                                        <td>
                                            {{ date('d-m-Y', strtotime($p->tanggal_presensi)) }}
                                        </td>

                                        <td>{{ $p->deskripsi }}</td>

                                        <td>
                                            @if ($p->file_tambahan != null)
                                                <button data-toggle="modal" data-target="#modalFileTambahan"
                                                    class="btn btn-info waves-effect waves-light btnLihatFileTambahan"
                                                    namaImage="{{ asset('assets_admin/images/izin_sakit_karyawan/') }}/{{ $p->file_tambahan }}">Lihat
                                                </button>
                                            @else
                                                Tidak ada File Tambahan
                                            @endif
                                        </td>


                                        <td id="statusKonfirmasi_{{ $p->id }}">
                                            @if ($p->status == 'belum')
                                                <span class="text-warning font-weight-bold">Belum
                                                    Dikonfirmasi</span>
                                            @elseif($p->status == 'konfirmasi')
                                                <span class="text-success font-weight-bold">Telah
                                                    Dikonfirmasi</span>
                                            @elseif($p->status == 'tolak')
                                                <span class="text-danger font-weight-bold">Izin Ditolak</span>
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

    <div id="modalFileTambahan" class="modal fade bs-example-modal-center" tabindex="-1" role="dialog"
        aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" >
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
                <div class="modal-footer"> <button type="button" class="btn btn-danger waves-effect"
                        data-dismiss="modal">Tutup</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>


    <div id="modalPilihTanggalPengajuanIzin" class="modal fade bs-example-modal-center" tabindex="-1" role="dialog"
        aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
            <div class="modal-content ">
                <form action="{{ route('karyawans.prosesizinkaryawansalon') }}" method="post"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title mt-0" id="modalNamaPilihTanggalPengajuanIzin">Formulir Pengajuan Izin</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" id="contentPilihTanggalPengajuanIzin">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <h6>Tanggal Pengajuan Izin</h6>
                            </div>
                            <div class="col-md-6">
                                <h6>Keterangan Izin</h6>
                            </div>
                            <div class="col-md-6">
                                <input type="date" class="form-control" name="tanggalIzin" id="tanggalPengajuanIzin"
                                    aria-describedby="emailHelp" min="{{ date('Y-m-d') }}"
                                    value="{{ old('tanggalIzin', date('Y-m-d')) }}"
                                    placeholder="Silahkan Pilih Tanggal Pengajuan izin" required>
                                <small id="emailHelp" class="form-text text-muted">Pilih Tanggal Pengajuan Izin
                                    disini!</small>
                            </div>
                            <div class="col-md-6">
                                @if (old('radioKeteranganPresensi') != null)
                                    @if (old('radioKeteranganPresensi') == 'izin')
                                        <div class="btn-group btn-group-toggle border w-100" data-toggle="buttons">
                                            <label class="btn btn-warning waves-effect waves-light"
                                                id="lblKeteranganIzin">
                                                <input type="radio" value="izin" name="radioKeteranganPresensi"
                                                    id="optionKeteranganIzin" class="radioKeteranganPresensi" checked>
                                                Izin
                                            </label>
                                            <label class="btn waves-effect waves-light" id="lblKeteranganSakit">
                                                <input type="radio" value="sakit" name="radioKeteranganPresensi"
                                                    id="optionKeteranganSakit" class="radioKeteranganPresensi">
                                                Sakit
                                            </label>
                                        </div>
                                    @else
                                        <div class="btn-group btn-group-toggle border w-100" data-toggle="buttons">
                                            <label class="btn waves-effect waves-light" id="lblKeteranganIzin">
                                                <input type="radio" value="izin" name="radioKeteranganPresensi"
                                                    id="optionKeteranganIzin" class="radioKeteranganPresensi">
                                                Izin
                                            </label>
                                            <label class="btn btn-danger waves-effect waves-light"
                                                id="lblKeteranganSakit">
                                                <input type="radio" value="sakit" name="radioKeteranganPresensi"
                                                    id="optionKeteranganSakit" class="radioKeteranganPresensi" checked>
                                                Sakit
                                            </label>
                                        </div>
                                    @endif
                                @else
                                    <div class="btn-group btn-group-toggle border w-100" data-toggle="buttons">
                                        <label class="btn btn-warning waves-effect waves-light" id="lblKeteranganIzin">
                                            <input type="radio" value="izin" name="radioKeteranganPresensi"
                                                id="optionKeteranganIzin" class="radioKeteranganPresensi" checked>
                                            Izin
                                        </label>
                                        <label class="btn waves-effect waves-light" id="lblKeteranganSakit">
                                            <input type="radio" value="sakit" name="radioKeteranganPresensi"
                                                id="optionKeteranganSakit" class="radioKeteranganPresensi">
                                            Sakit
                                        </label>
                                    </div>
                                @endif

                                <small id="emailHelp" class="form-text text-muted">Pilih Keterangan Pengajuan Izin
                                    disini!</small>
                            </div>
                            <div class="col-md-12">
                                <h6>Deskripsi Keterangan Izin</h6>
                            </div>
                            <div class="col-md-12">
                                <textarea aria-describedby="emailHelp" class="form-control" name="deskripsiIzin" id="" cols="30"
                                    rows="4" placeholder="Silahkan masukkan alasan Anda izin" required>
@if (old('deskripsiIzin') != null)
{{ old('deskripsiIzin') }}
@endif
                                </textarea>
                                <small id="emailHelp" class="form-text text-muted">Masukan alasan Pengajuan Izin
                                    disini!</small>
                            </div>
                            <div class="col-md-12">
                                <h6>File Tambahan <span class="text-danger">*Contoh: Surat Dokter</span></h6>
                            </div>
                            <div class="col-md-12">
                                <div class="fallback">
                                    <input type="file" class="form-control" name="fileTambahan" id="fileUpload"
                                        aria-describedby="emailHelp" value="{{ old('fileTambahan') }}" accept="image/*">
                                </div>

                                <small id="emailHelp" class="form-text text-muted">Upload file tambahan Pengajuan Izin
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

        $('body').on('click', '.btnLihatFileTambahan', function() {
            var namaImage = $(this).attr("namaImage");
            
            $("#contentFileTambahan").html("<img class='img-fluid' src='" + namaImage + "' alt='filetambahan'>");
        });



        $('.radioAktif').on('click', function() {
            $(".radioAktif").addClass("btn-info");
            $(".radioNonaktif").removeClass("btn-info");
        });

        $('.radioNonaktif').on('click', function() {
            $(".radioNonaktif").addClass("btn-info");
            $(".radioAktif").removeClass("btn-info");
        });

        $('body').on('change', '.radioKeteranganPresensi', function() {
            var statusSaatIni = $(this).val();
            if (statusSaatIni == "sakit") {
                $("#lblKeteranganIzin").removeClass("btn-warning");
                $("#lblKeteranganSakit").addClass("btn-danger");
            } else {
                $("#lblKeteranganIzin").addClass("btn-warning");
                $("#lblKeteranganSakit").removeClass("btn-danger");
            }
        });
    </script>
@endsection
