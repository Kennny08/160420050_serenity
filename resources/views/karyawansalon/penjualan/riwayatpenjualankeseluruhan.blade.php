@extends('layout.adminlayout')

@section('title', 'Karyawan || Daftar Riwayat Penjualan Keseluruhan')

@section('admincontent')
    <div class="page-title-box">
    </div>
    <!-- end page-title -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <h3 class="mt-0 header-title" id="grupAktif">Daftar Riwayat Penjualan Keseluruhan</h3>
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
                            <table id="tabelDaftarRiwayatPenjualan"
                                class="tabelDaftarPenjualan table table-bordered dt-responsive nowrap text-center"
                                style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead>
                                    <tr>
                                        <th>Hari</th>
                                        <th hidden>tanggalHidden</th>
                                        <th>Tanggal Penjualan</th>
                                        <th>Total Penjualan</th>
                                        <th>Total Pelayanan</th>
                                        <th>Detail</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($riwayatPenjualan as $r)
                                        <tr>
                                            <td>{{ $r['hari_penjualan'] }}</td>
                                            <td hidden>{{ date('Y-m-d', strtotime($r['tanggal_penjualan'])) }}</td>
                                            <td>{{ date('d-m-Y', strtotime($r['tanggal_penjualan'])) }}</td>
                                            <td>{{ $r['total_penjualan'] }}</td>
                                            <td>{{ $r['total_pelayanan'] }}</td>
                                            <td class="text-center"><button data-toggle = "modal"
                                                    data-target = "#modalDetailPenjualanPerTanggal"
                                                    tanggalPenjualan = "{{ date('Y-m-d', strtotime($r['tanggal_penjualan'])) }}"
                                                    hariTanggalPenjualan = "{{ $r['hari_penjualan'] }}, {{ date('d-m-Y', strtotime($r['tanggal_penjualan'])) }}"
                                                    class=" btn btn-info waves-effect waves-light btnDetailPenjualanPerTanggal">Detail</button>
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
    <div id="modalDetailPenjualanPerTanggal" class="modal fade bs-example-modal-center" tabindex="-1" role="dialog"
        aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl" style="max-width: 90%;">
            <div class="modal-content ">
                <div class="modal-header">
                    <h5 class="modal-title mt-0" id="modalNamaDetailPenjualanPerTanggal">Detail Riwayat Penjualan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="contentDetailPenjualanPerTanggal">
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

            $("#tabelDaftarRiwayatPenjualan").DataTable({
                order: [
                    [1, "desc"],
                ],
                language: {
                    emptyTable: "Tidak terdapat penjualan perawatan untuk hari yang akan datang!",
                }
            });

        });

        $('body').on('click', '.btnDetailPenjualanPerTanggal', function() {
            var tanggalPenjualan = $(this).attr("tanggalPenjualan");
            var hariTanggalPenjualan = $(this).attr("hariTanggalPenjualan");

            $("#modalNamaDetailPenjualanPerTanggal").text(" Daftar Penjualan Keseluruhan untuk " + hariTanggalPenjualan);

            $('#contentDetailPenjualanPerTanggal').html("<div class='text-center'>" +
                "<div class='spinner-border text-info' role='status'>" +
                "<span class='sr-only'>Loading...</span>" +
                "</div></div>");

            $.ajax({
                type: 'POST',
                url: '{{ route('penjualans.karyawan.detailpenjualankeseluruhan') }}',
                data: {
                    '_token': '<?php echo csrf_token(); ?>',
                    'tanggalPenjualan': tanggalPenjualan,
                },
                success: function(data) {
                    $('#contentDetailPenjualanPerTanggal').html(data.msg);
                    $('#tabelDetailDaftarPenjualan').DataTable({
                        order: [
                            [6, "asc"],
                        ],
                        language: {
                    emptyTable: "Tidak terdapat penjualan keseluruhan untuk tanggal yang dipilih!",
                }

                    });
                }
            })
        });
    </script>
@endsection
