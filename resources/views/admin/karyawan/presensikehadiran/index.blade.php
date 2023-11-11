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
                    @else
                        <a class="btn btn-info waves-effect waves-light" href={{ route('presensikehadirans.edit') }}>Edit
                            Presensi
                            Karyawan</a>
                    @endif
                    @if ($jumlahIzinKehadiran > 0)
                        <button class="btn btn-primary waves-effect waves-light" data-toggle="modal" id="btnDaftarIzin">
                            <span
                                class="badge badge-danger badge-pill float-right ml-2">{{ $jumlahIzinKehadiran }}</span><span>Produk
                                Daftar Izin Karyawan</span>
                        </button>
                    @endif
                    <br>
                    <br>
                    @if (session('status'))
                        <div class="alert alert-success">
                            <button type="button" class="close text-danger" data-dismiss="alert" aria-label="Close">
                                <span class="text-success" aria-hidden="true">&times;</span>
                            </button>
                            {{ session('status') }}
                        </div>
                    @endif

                    @if (count($presensisHariIni) == 0)
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
                    @else
                        <div>
                            <table id="tabelDaftarPresensiKaryawan"
                                class="table table-bordered dt-responsive nowrap text-center w-100"
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
                                    @foreach ($presensisHariIni as $p)
                                        <tr id="tr_{{ $p->id }}">
                                            <td>{{ $p->karyawan->nama }}</td>
                                            <td>{{ $p->tanggal_presensi }}</td>

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
                                                    <td><span class="badge badge-warning">Belum Dikonfirmasi</span></td>
                                                @elseif($p->status == 'konfirmasi')
                                                    <td><span class="badge badge-success">Telah Dikonfirmasi</span></td>
                                                @elseif($p->status == 'tolak')
                                                    <td><span class="badge badge-danger">Izin Ditolak</span></td>
                                                @endif
                                            @else
                                                @if ($p->status == 'belum')
                                                    <td><span class="badge badge-warning">Belum Dikonfirmasi</span></td>
                                                @elseif($p->status == 'konfirmasi')
                                                    <td><span class="badge badge-success">Telah Dikonfirmasi</span></td>
                                                @endif
                                            @endif
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
            $('#tabelDaftarPresensiKaryawan').DataTable({

            });

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