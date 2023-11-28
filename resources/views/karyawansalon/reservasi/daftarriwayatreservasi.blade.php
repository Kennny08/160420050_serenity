@extends('layout.adminlayout')

@section('title', 'Karyawan || Daftar Riwayat Reservasi')

@section('admincontent')
    <div class="page-title-box">
    </div>
    <!-- end page-title -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <h3 class="mt-0 header-title" id="grupAktif">Daftar Riwayat Reservasi Perawatan</h3>
                    <p class="sub-title">
                    </p>
                    <br>
                    @if (session('status'))
                        <div class="alert alert-success alert-dismissible" role="alert">
                            <button type="button" class="close text-success" data-dismiss="alert" aria-label="Close">
                                <span class="text-success" aria-hidden="true">&times;</span>
                            </button>
                            {{ session('status') }}
                        </div>
                    @endif


                    <div class="table-responsive">
                        <div>
                            <table id="tabelDaftarRiwayatReservasi"
                                class="tabelDaftarReservasi table table-bordered dt-responsive nowrap text-center"
                                style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead>
                                    <tr>
                                        <th>Hari</th>
                                        <th>Tanggal Reservasi</th>
                                        <th>Total Reservasi</th>
                                        <th>Total Pelayanan</th>
                                        <th>Detail</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($riwayatReservasi as $r)
                                        <tr>
                                            <td>{{ $r['hari_reservasi'] }}</td>
                                            <td>{{ date('d-m-Y', strtotime($r['tanggal_reservasi'])) }}</td>
                                            <td>{{ $r['total_reservasi'] }}</td>
                                            <td>{{ $r['total_pelayanan'] }}</td>
                                            <td class="text-center"><button data-toggle = "modal"
                                                    data-target = "#modalDetailReservasiPerTanggal"
                                                    tanggalReservasi = "{{ date('Y-m-d', strtotime($r['tanggal_reservasi'])) }}"
                                                    hariTanggalReservasi = "{{ $r['hari_reservasi'] }}, {{ date('d-m-Y', strtotime($r['tanggal_reservasi'])) }}"
                                                    class=" btn btn-info waves-effect waves-light btnDetailReservasiPerTanggal">Detail</button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <!-- end col -->
    </div>
    <div id="modalDetailReservasiPerTanggal" class="modal fade bs-example-modal-center" tabindex="-1" role="dialog"
        aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl" style="max-width: 90%;">
            <div class="modal-content ">
                <div class="modal-header">
                    <h5 class="modal-title mt-0" id="modalNamaDetailReservasiPerTanggal">Detail Riwayat Presensi</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="contentDetailReservasiPerTanggal">
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

            $("#tabelDaftarRiwayatReservasi").DataTable({
                order: [
                    [1, "desc"],
                ],
                language: {
                    emptyTable: "Tidak terdapat reservasi perawatan untuk hari yang akan datang!",
                }
            });

        });

        $('body').on('click', '.btnDetailReservasiPerTanggal', function() {
            var tanggalReservasi = $(this).attr("tanggalReservasi");
            var hariTanggalReservasi = $(this).attr("hariTanggalReservasi");

            $("#modalNamaDetailReservasiPerTanggal").text(" Daftar Reservasi untuk " + hariTanggalReservasi);

            $('#contentDetailReservasiPerTanggal').html("<div class='text-center'>" +
                "<div class='spinner-border text-info' role='status'>" +
                "<span class='sr-only'>Loading...</span>" +
                "</div></div>");

            $.ajax({
                type: 'POST',
                url: '{{ route('karyawans.detailreservasiperhari') }}',
                data: {
                    '_token': '<?php echo csrf_token(); ?>',
                    'tanggalReservasi': tanggalReservasi,
                },
                success: function(data) {
                    $('#contentDetailReservasiPerTanggal').html(data.msg);
                    $('#tabelDetailReservasiPerTanggal').DataTable({
                        order: [
                            [6, "asc"],
                        ],

                    });
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
