@extends('layout.adminlayout')

@section('title', 'Admin || Buka Presensi')

@section('admincontent')
    <div class="page-title-box">
    </div>
    <!-- end page-title -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <h3 class="mt-0 header-title">Buka Presensi</h3>
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



                    <form method="POST" action="{{ route('presensikehadirans.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group row">
                            <div class="form-group col-md-6">
                                <h3>Tanggal Presensi : {{ $tanggalHariIniTeks }}</h3>
                            </div>
                            <div class="form-group col-md-6 text-right">
                                <button class="btn btn-info waves-effect waves-light mt-2" type="submit">
                                    Konfirmasi Presensi</button>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="form-group col-md-12">
                                <div>
                                    <table id="tabelDaftarPresensiKaryawan"
                                        class="table table-bordered dt-responsive nowrap text-center table-striped w-100"
                                        style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                        <thead>
                                            <tr>
                                                <th>Nama Karyawan</th>
                                                <th>Tanggal Presensi</th>
                                                <th>Keterangan</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @foreach ($karyawans as $k)
                                                <tr id="tr_{{ $k->id }}">
                                                    <td>{{ $k->nama }}</td>
                                                    <td>{{ $tanggalHariIniTeks }}</td>

                                                    @if (!in_array($k->id, $idKaryawansIzin))
                                                        <input type="hidden" value="{{ $k->id }}"
                                                            name="daftarNamaKaryawan[]">
                                                        <td>
                                                            <div class="col-md-12">
                                                                <select name="keteranganPresensi[]"
                                                                    id="keteranganPresensiSelect" class="form-control"
                                                                    aria-label="Default select example" required>
                                                                    <option class="text-danger" selected disabled
                                                                        value="null">Pilih Keterangan
                                                                        Presensi</option>
                                                                    <option value="hadir">HADIR</option>
                                                                    <option value="sakit">SAKIT</option>
                                                                    <option value="absen">ABSEN</option>
                                                                    <option value="izin">IZIN</option>
                                                                </select>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="col-md-12">
                                                                <select name="statusPresensi[]"
                                                                    id="keteranganPresensiSelect" class="form-control"
                                                                    aria-label="Default select example" required>
                                                                    <option class="text-danger" selected disabled
                                                                        value="null">Pilih Status Presensi</option>
                                                                    <option value="konfirmasi">Konfirmasi</option>
                                                                    <option value="belum">Belum Konfirmasi</option>
                                                                </select>
                                                            </div>
                                                        </td>
                                                    @else
                                                        @php
                                                            $presensiIzinKaryawanTerpilih = $daftarIzinPresensiHariIni->firstWhere('karyawan_id', $k->id);
                                                        @endphp

                                                        @if ($presensiIzinKaryawanTerpilih->status == 'konfirmasi')
                                                            <td style="font-size: 1.3em"
                                                                class="font-weight-bold text-warning">
                                                                IZIN
                                                            </td>
                                                            <td>
                                                                <span style="font-size: 1em;padding: 0.5em 1em;"
                                                                    class="badge badge-success">Telah Dikonfirmasi</span>
                                                            </td>
                                                        @elseif($presensiIzinKaryawanTerpilih->status == 'tolak')
                                                            <input type="hidden" value="{{ $k->id }}"
                                                                name="daftarNamaKaryawan[]">
                                                            <td>
                                                                <div class="col-md-12">
                                                                    <select name="keteranganPresensi[]"
                                                                        id="keteranganPresensiSelect" class="form-control"
                                                                        aria-label="Default select example" required>
                                                                        <option class="text-danger" selected disabled
                                                                            value="null">Pilih Keterangan
                                                                            Presensi (izin ditolak)</option>
                                                                        <option value="hadir">HADIR</option>
                                                                        <option value="sakit">SAKIT</option>
                                                                        <option value="absen">ABSEN</option>
                                                                    </select>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="col-md-12">
                                                                    <select name="statusPresensi[]"
                                                                        id="keteranganPresensiSelect" class="form-control"
                                                                        aria-label="Default select example" required>
                                                                        <option class="text-danger" selected disabled
                                                                            value="null">Pilih Status Presensi</option>
                                                                        <option value="konfirmasi">Konfirmasi</option>
                                                                        <option value="belum">Belum Konfirmasi</option>
                                                                    </select>
                                                                </div>
                                                            </td>
                                                        @else
                                                            <input type="hidden" value="{{ $k->id }}"
                                                                name="daftarNamaKaryawan[]">
                                                            <td class="font-weight-bold text-warning">
                                                                IZIN
                                                            </td>
                                                            <td>
                                                                <div class="col-md-12">
                                                                    <select name="statusPresensi[]"
                                                                        id="keteranganPresensiSelect" class="form-control"
                                                                        aria-label="Default select example" required>
                                                                        <option class="text-danger" selected disabled
                                                                            value="null">Pilih Status Presensi</option>
                                                                        <option value="konfirmasi">Konfirmasi</option>
                                                                        <option value="belum">Belum Konfirmasi</option>
                                                                    </select>
                                                                </div>
                                                            </td>
                                                        @endif
                                                    @endif
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </form>


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

    {{-- <div id="modalKonfirmasiDeleteKaryawan" class="modal fade bs-example-modal-center" tabindex="-1" role="dialog"
        aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <form id="formDeleteKaryawan" action="{{ route('karyawans.destroy', '1') }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="modal-header">
                        <h5 id="modalNamaKaryawanDelete" class="modal-title mt-0">Konfirmasi Penghapusan Karyawan</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div id="modalBodyHapusKaryawan" class="modal-body text-center">
                        <h6>Apakah Anda yakin untuk menghapus karyawan?</h6>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">Batal</button>
                        <button id="btnKonfirmasiHapusKaryawan" type="submit"
                            class="btn btn-info waves-effect waves-light btnKonfirmasiHapusKaryawan">Hapus</button>
                    </div>
                </form>
            </div>

        </div>
        <!-- /.modal-dialog -->
    </div> --}}
@endsection

@section('javascript')
    <script>
        $(document).ready(function() {
            // $('#tabelDaftarPresensiKaryawan').DataTable({

            // });

        });

        // $('.btnDetailKaryawan').on('click', function() {
        //     var idKaryawan = $(this).attr("idKaryawan");
        //     var nama = $(this).attr('nama');

        //     $("#modalNamaKaryawan").text(" Detail Karyawan " + nama);
        //     $.ajax({
        //         type: 'POST',
        //         url: '{{ route('admin.getdetailkaryawan') }}',
        //         data: {
        //             '_token': '<?php echo csrf_token(); ?>',
        //             'idKaryawan': idKaryawan,
        //         },
        //         success: function(data) {
        //             $('#contentDetailKaryawan').html(data.msg);
        //             $('#tabelDaftarKaryawanPerawatan').DataTable({});
        //         }
        //     })
        // });
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
