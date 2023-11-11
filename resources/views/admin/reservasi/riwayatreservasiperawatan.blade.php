@extends('layout.adminlayout')

@section('title', 'Admin || Daftar Riwayat Reservasi')

@section('admincontent')
    <div class="page-title-box">
    </div>
    <!-- end page-title -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <h3 class="mt-0 header-title">Daftar Riwayat Reservasi Perawatan</h3>
                    <p class="sub-title">
                    </p>
                    @if (session('status'))
                        <div class="alert alert-success alert-dismissible" role="alert">
                            <button type="button" class="close text-success" data-dismiss="alert" aria-label="Close">
                                <span class="text-success" aria-hidden="true">&times;</span>
                            </button>
                            {{ session('status') }}
                        </div>
                    @endif

                    <table id="tabelDaftarReservasi" class="table table-bordered dt-responsive wrap text-center"
                        style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                            <tr>
                                <th>Tanggal Reservasi</th>
                                <th>Jumlah Reservasi</th>
                                <th>Total Penjualan Perawatan(Rp)</th>
                                <th>Total Penjualan Produk(Rp)</th>
                                <th>Total Penjualan(Rp)</th>
                                <th>Detail</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($arrDaftarRiwayatReservasi as $r)
                                <tr>
                                    <td>{{ $r['tanggalreservasi'] }}</td>
                                    <td>{{ $r['jumlahreservasi'] }}</td>
                                    <td>{{ number_format($r['totalpenjualanperawatan'], 2, ',', '.') }}</td>
                                    <td>
                                        {{ number_format($r['totalpenjualanproduk'], 2, ',', '.') }}
                                    </td>
                                    <td>{{ number_format($r['totalpembayaran'], 2, ',', '.') }}</td>
                                    <td class="text-center"><button data-toggle="modal" data-target="#modalDetailRiwayat"
                                            tanggal ="{{ $r['tanggal'] }}" tanggalHari = "{{ $r['tanggalreservasi'] }}"
                                            class=" btn btn-info waves-effect waves-light btnDetailRiwayat">Detail</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
        <!-- end col -->
    </div>

    <div id="modalDetailRiwayat" class="modal fade bs-example-modal-center" tabindex="-1" role="dialog"
        aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
            <div class="modal-content ">
                <div class="modal-header">
                    <h5 class="modal-title mt-0" id="modalNamaRiwayat">Detail Riwayat Reservasi</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="contentDetailRiwayat">
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
            $('#tabelDaftarReservasi').DataTable({
                ordering: false
            });
        });

        $('.btnDetailRiwayat').on('click', function() {
            var tanggal = $(this).attr("tanggal");
            var tanggalHari = $(this).attr("tanggalHari");
            $('#contentDetailRiwayat').html(
                "<div class='text-center'><div class='spinner-border text-info' role='" +
                "status'><span class='sr-only'>Loading...</span></div></div>");

            $("#modalNamaRiwayat").text(" Detail Riwayat Reservasi " + tanggalHari);
            $.ajax({
                type: 'POST',
                url: '{{ route('admin.getdetailriwayatreservasiperawatan') }}',
                data: {
                    '_token': '<?php echo csrf_token(); ?>',
                    'tanggal': tanggal,
                },
                success: function(data) {
                    $('#contentDetailRiwayat').html(data.msg);

                    $('#tabelDetailDaftarReservasi').DataTable({
                        ordering: false
                    });
                }
            })
        });
    </script>
@endsection
