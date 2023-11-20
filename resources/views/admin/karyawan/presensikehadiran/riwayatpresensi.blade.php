@extends('layout.adminlayout')

@section('title', 'Admin || Daftar Riwayat Presensi Karyawan')

@section('admincontent')
    <div class="page-title-box">
    </div>
    <!-- end page-title -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <h3 class="mt-0 header-title">Daftar Riwayat Presensi Karyawan</h3>
                    <p class="sub-title">
                    </p>
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

                    <div class="table-responsive">
                        <table id="tabelDaftarRiwayatPresensiKaryawan"
                            class="table table-bordered dt-responsive nowrap text-center w-100"
                            style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    
                                    <th>Tanggal Presensi</th>
                                    <th hidden>Tanggal</th>
                                    <th>Hadir</th>
                                    <th>Sakit</th>
                                    <th>Izin</th>
                                    <th>Absen</th>
                                    <th>Belum Konfirmasi</th>
                                    <th>Detail</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($daftarRiwayatPresensi as $p)
                                    <tr>
                                        
                                        <td>{{ $p['tanggalPresensiDenganHari'] }}</td>
                                        <td hidden>{{ $p['tanggalPresensi'] }}</td>
                                        <td>{{ $p['daftarPresensi']->where('keterangan', 'hadir')->where('status', 'konfirmasi')->count() }}
                                        </td>
                                        <td>{{ $p['daftarPresensi']->where('keterangan', 'sakit')->where('status', 'konfirmasi')->count() }}
                                        </td>
                                        <td>{{ $p['daftarPresensi']->where('keterangan', 'izin')->where('status', 'konfirmasi')->count() }}
                                        </td>
                                        <td>{{ $p['daftarPresensi']->where('keterangan', 'absen')->where('status', 'konfirmasi')->count() }}
                                        </td>
                                        <td>{{ $p['daftarPresensi']->where('status', 'belum')->count() }}</td>
                                        <td>
                                            @if ($p['objectPresensiPertamaTanpaIzin'] != null)
                                                <button data-toggle="modal" data-target="#modalDetailRiwayatPresensi"
                                                    waktuBukaPresensi = "{{ date('H:i', strtotime($p['objectPresensiPertamaTanpaIzin']->created_at)) }}"
                                                    tanggalPresensi="{{ $p['tanggalPresensi'] }}"
                                                    tanggalPresensiHari="{{ $p['tanggalPresensiDenganHari'] }}"
                                                    urlEdit = "{{ route('admin.presensikehadirans.editpresensi', $p['tanggalPresensi']) }}"
                                                    class=" btn btn-info waves-effect waves-light btnDetailRiwayatPresensi">Detail</button>
                                            @else
                                                <button data-toggle="modal" data-target="#modalDetailRiwayatPresensi"
                                                    waktuBukaPresensi = "" tanggalPresensi="{{ $p['tanggalPresensi'] }}"
                                                    tanggalPresensiHari="{{ $p['tanggalPresensiDenganHari'] }}"
                                                    urlEdit = "{{ route('admin.presensikehadirans.editpresensi', $p['tanggalPresensi']) }}"
                                                    class=" btn btn-info waves-effect waves-light btnDetailRiwayatPresensi">Detail</button>
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

    <div id="modalDetailRiwayatPresensi" class="modal fade bs-example-modal-center" tabindex="-1" role="dialog"
        aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl" style="max-width: 90%;">
            <div class="modal-content ">
                <div class="modal-header">
                    <h5 class="modal-title mt-0" id="modalNamaDetailRiwayatPresensi">Detail Riwayat Presensi</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="contentDetailRiwayatPresensi">
                    <div class="text-center">
                        <div class="spinner-border text-info" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <a id="btnEditRiwayatPresensi" style="width: 10%;" class="btn btn-info waves-effect mr-3"
                        href="#">Edit Presensi</a>
                    <button type="button" style="width: 10%;" class="btn btn-danger waves-effect"
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
            $('#tabelDaftarRiwayatPresensiKaryawan').DataTable({
                "order": [
                    [1, "desc"]
                ]
            });
        });

        $('.btnDetailRiwayatPresensi').on('click', function() {
            var tanggalPresensi = $(this).attr("tanggalPresensi");
            var tanggalPresensiHari = $(this).attr('tanggalPresensiHari');
            var urlEdit = $(this).attr('urlEdit');
            var waktuBuka = $(this).attr('waktuBukaPresensi');

            if (waktuBuka == "") {
                $("#modalNamaDetailRiwayatPresensi").text(" Detail Riwayat Presensi " + tanggalPresensiHari);
            } else {
                $("#modalNamaDetailRiwayatPresensi").text(" Detail Riwayat Presensi " + tanggalPresensiHari +
                    " - Buka pukul " + waktuBuka);
            }


            $("#btnEditRiwayatPresensi").attr("href", urlEdit);

            $.ajax({
                type: 'POST',
                url: '{{ route('admin.getdetailriwayatpresensi') }}',
                data: {
                    '_token': '<?php echo csrf_token(); ?>',
                    'tanggalPresensi': tanggalPresensi,
                },
                success: function(data) {
                    $('#contentDetailRiwayatPresensi').html(data.msg);
                    $('#tabelDaftarDetailRiwayatPresensi').DataTable({
                        

                    });
                }
            })
        });
    </script>
@endsection
