@extends('layout.adminlayout')

@section('title', 'Admin || Daftar Riwayat Pengambilan Produk')

@section('admincontent')
    <div class="page-title-box">
    </div>
    <!-- end page-title -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <h3 class="mt-0 header-title">Daftar Riwayat Pengambilan Produk</h3>
                    <p class="sub-title">
                    </p>
                    <a class="btn btn-info waves-effect waves-light" href="{{ route('riwayatpengambilanproduks.create') }}">
                        Tambah Data Pengambilan Produk
                    </a>
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
                        <table id="tabelDaftarRiwayatPengambilanProduk"
                            class="table table-bordered dt-responsive nowrap text-center w-100"
                            style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>

                                    <th>Tanggal Pengambilan Produk</th>
                                    <th>Total Riwayat</th>
                                    <th>Total Produk</th>
                                    <th>Total Karyawan</th>
                                    <th>Detail</th>

                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($arrPengambilanProdukPerTanggal as $rpp)
                                    <tr>

                                        <td>{{ $rpp['tanggal_pengambilan_teks'] }}</td>
                                        <td>{{ $rpp['totalriwayat'] }}</td>
                                        <td>{{ $rpp['totalproduk'] }}</td>
                                        <td>{{ $rpp['jumlahkaryawan'] }}</td>
                                        <td>
                                            <button data-toggle="modal" data-target="#modalDetailRiwayatPengambilanProduk"
                                                tanggalRiwayat="{{ date('Y-m-d', strtotime($rpp['tanggal_pengambilan'])) }}"
                                                tanggalRiwayatTeks="{{ $rpp['tanggal_pengambilan_teks'] }}"
                                                class=" btn btn-info waves-effect waves-light btnDetailRiwayatPengambilanProduk">Detail
                                            </button>
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

    <div id="modalDetailRiwayatPengambilanProduk" class="modal fade bs-example-modal-center" tabindex="-1" role="dialog"
        aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
            <div class="modal-content ">
                <div class="modal-header">
                    <h5 class="modal-title mt-0" id="modalNamaDetailRiwayatPengambilanProduk">Detail Riwayat Pengambilan Produk</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="contentDetailRiwayatPengambilanProduk">
                    <div class="text-center">
                        <div class="spinner-border text-info" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger waves-effect"
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
            $('#tabelDaftarRiwayatPengambilanProduk').DataTable({
                order: [
                    [0, "desc"]
                ],
                language: {
                    emptyTable: "Tidak terdapat Daftar Riwayat Pengambilan Produk!",
                }
            });
        });

        $('.btnDetailRiwayatPengambilanProduk').on('click', function() {
            var tanggalRiwayat = $(this).attr("tanggalRiwayat");
            var tanggalRiwayatTeks = $(this).attr("tanggalRiwayatTeks");
            $("#modalNamaDetailRiwayatPengambilanProduk").text("Detail Riwayat Pengambilan Produk pada " +
                tanggalRiwayatTeks);
            $.ajax({
                type: 'POST',
                url: '{{ route('admin.riwayatpengambilanproduks.getdetailriwayatpengambilanproduk') }}',
                data: {
                    '_token': '<?php echo csrf_token(); ?>',
                    'tanggalRiwayat': tanggalRiwayat,
                },
                success: function(data) {
                    $('#contentDetailRiwayatPengambilanProduk').html(data.msg);
                    $('#tabelDetailDaftarRiwayatPengambilanProduk').DataTable({});
                }
            })
        });
    </script>
@endsection
