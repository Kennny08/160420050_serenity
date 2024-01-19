@extends('layout.adminlayout')

@section('title', 'Admin || Daftar Pelanggan')

@section('admincontent')
    <div class="page-title-box">
    </div>
    <!-- end page-title -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <h3 class="mt-0 header-title">Daftar Pelanggan</h3>
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

                    <div>
                        <table id="tabelDaftarSupplier" class="table table-bordered table-striped dt-responsive wrap text-center w-100"
                            style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th class="align-middle">ID</th>
                                    <th class="align-middle">Nama</th>
                                    <th class="align-middle">Tanggal Lahir</th>
                                    <th class="align-middle">Alamat</th>
                                    <th class="align-middle">Nomor Telepon</th>
                                    <th class="align-middle">Gender</th>
                                    <th class="align-middle">Email</th>
                                    <th class="align-middle">Total Penjualan (Rp)</th>
                                    <th class="align-middle">Tanggal Registrasi</th>
                                    <th class="align-middle">Tanggal Perubahan Terakhir</th>
                                    {{-- <th class="align-middle">Detail</th> --}}
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($pelanggans as $p)
                                    <tr class="align-middle" id="tr_{{ $p->id }}">
                                        <td>{{ $p->id }}</td>
                                        <td>{{ $p->nama }}</td>
                                        <td>{{ date('d-m-Y', strtotime($p->tanggal_lahir)) }}</td>
                                        <td>{{ $p->alamat }}</td>
                                        <td>{{ $p->nomor_telepon }}</td>
                                        <td>{{ $p->gender }}</td>
                                        <td>{{ $p->user->email }}</td>
                                        <td>{{ number_format($penjualanSelesai->where('pelanggan_id', $p->id)->sum('total_pembayaran'), 2, ',', '.') }}
                                        </td>
                                        <td>{{ date('d-m-Y H:i', strtotime($p->created_at)) }}</td>
                                        <td>{{ date('d-m-Y H:i', strtotime($p->updated_at)) }}</td>
                                        {{-- <td class="text-center"><button data-toggle="modal"
                                                    data-target="#modalDetailPembelianSupplier" nama ="{{ $s->nama }}"
                                                    idSupplier = "{{ $s->id }}"
                                                    class=" btn btn-warning waves-effect waves-light btnDetailPembelianSupplier">Detail</button>
                                            </td> --}}
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

    <div id="modalDetailPembelianSupplier" class="modal fade bs-example-modal-center" tabindex="-1" role="dialog"
        aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
            <div class="modal-content ">
                <div class="modal-header">
                    <h5 class="modal-title mt-0" id="modalNamaDetailPembelianSupplier">Detail Supplier</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="contentDetailPembelianSupplier">
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
            $('#tabelDaftarSupplier').DataTable({
                language: {
                    emptyTable: "Tidak terdapat data pelanggan!",
                }
            });

        });

        $('.btnDetailPembelianSupplier').on('click', function() {
            var idSupplier = $(this).attr("idSupplier");
            var nama = $(this).attr('nama');

            $("#modalNamaDetailPembelianSupplier").text(" Detail Pembelian Stok Produk pada Supplier " + nama);
            $.ajax({
                type: 'POST',
                url: '{{ route('admin.suppliers.getdetailpembeliansupplier') }}',
                data: {
                    '_token': '<?php echo csrf_token(); ?>',
                    'idSupplier': idSupplier,
                },
                success: function(data) {
                    $('#contentDetailPembelianSupplier').html(data.msg);
                    $('#tabelDaftarPembelianDariSupplier').DataTable({});
                }
            })
        });
        $('.btnHapusSupplier').on('click', function() {

            var idSupplier = $(this).attr("idSupplier");
            var namaSupplier = $(this).attr('namaSupplier');
            var routeUrl = $(this).attr('routeUrl');
            $("#modalNamaSupplierDelete").text("Konfirmasi Penghapusan Supplier " + namaSupplier);
            $("#modalBodyHapusSupplier").html(
                "<h6>Apakah Anda yakin untuk menghapus supplier <span class='text-danger'>" + namaSupplier +
                "</span>?</h6>")
            $("#formDeleteSupplier").attr("action", routeUrl);
        });
    </script>
@endsection
