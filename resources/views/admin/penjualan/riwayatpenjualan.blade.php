@extends('layout.adminlayout')

@section('title', 'Admin || Daftar Riwayat Penjualan')

@section('admincontent')
    <div class="page-title-box">
    </div>
    <!-- end page-title -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <h3 class="mt-0 header-title">Daftar Riwayat Penjualan</h3>
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
                                <th>Tanggal Penjualan</th>
                                <th>Jumlah Penjualan</th>
                                <th>Total Penjualan Perawatan(Rp)</th>
                                <th>Total Penjualan Produk(Rp)</th>
                                <th>Total Penjualan Paket(Rp)</th>
                                <th>Total Potongan Diskon(Rp)</th>
                                <th>Total Penjualan(Rp)</th>
                                <th>Detail</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($arrDaftarRiwayatPenjualan as $r)
                                <tr>
                                    <td>{{ $r['tanggalpenjualan'] }}</td>
                                    <td>{{ $r['jumlahpenjualan'] }}</td>
                                    <td>{{ number_format($r['totalpenjualanperawatan'], 2, ',', '.') }}</td>
                                    <td>
                                        {{ number_format($r['totalpenjualanproduk'], 2, ',', '.') }}
                                    </td>
                                    <td>
                                        {{ number_format($r['totalpenjualanpaket'], 2, ',', '.') }}
                                    </td>
                                    <td>
                                        {{ number_format($r['totalpotongandiskon'], 2, ',', '.') }}
                                    </td>
                                    <td>{{ number_format($r['totalpembayaran'], 2, ',', '.') }}</td>
                                    <td class="text-center"><button data-toggle="modal" data-target="#modalDetailRiwayat"
                                            tanggal ="{{ $r['tanggal'] }}" tanggalHari = "{{ $r['tanggalpenjualan'] }}"
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
                    <h5 class="modal-title mt-0" id="modalNamaRiwayat">Detail Riwayat Penjualan</h5>
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
                ordering: false,
                language: {
                    emptyTable: "Tidak terdapat data diskon riwayat penjualan!",
                }
            });
        });

        $('.btnDetailRiwayat').on('click', function() {
            var tanggal = $(this).attr("tanggal");
            var tanggalHari = $(this).attr("tanggalHari");
            $('#contentDetailRiwayat').html(
                "<div class='text-center'><div class='spinner-border text-info' role='" +
                "status'><span class='sr-only'>Loading...</span></div></div>");

            $("#modalNamaRiwayat").text(" Detail Riwayat Penjualan " + tanggalHari);
            $.ajax({
                type: 'POST',
                url: '{{ route('penjualans.admin.getdetailriwayatpenjualan') }}',
                data: {
                    '_token': '<?php echo csrf_token(); ?>',
                    'tanggal': tanggal,
                },
                success: function(data) {
                    $('#contentDetailRiwayat').html(data.msg);

                    $('#tabelDetailDaftarPenjualan').DataTable({
                        order: [
                            [1, "asc"]
                        ],
                        language: {
                            emptyTable: "Tidak terdapat data untuk detail penjualan!",
                        }
                    });
                }
            })
        });
    </script>
@endsection
