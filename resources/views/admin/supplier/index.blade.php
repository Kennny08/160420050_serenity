@extends('layout.adminlayout')

@section('title', 'Admin || Daftar Supplier')

@section('admincontent')
    <div class="page-title-box">
    </div>
    <!-- end page-title -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <h3 class="mt-0 header-title">Daftar Supplier</h3>
                    <p class="sub-title">
                    </p>
                    {{-- <a class="btn btn-primary" data-toggle="modal" href="{{ route('categories.create') }}">Add Category</a> --}}
                    <a class="btn btn-info waves-effect waves-light" href={{ route('suppliers.create') }}>Tambah
                        Supplier</a><br>
                    <br>
                    @if (session('status'))
                        <div class="alert alert-success">
                            <button type="button" class="close text-danger" data-dismiss="alert" aria-label="Close">
                                <span class="text-success" aria-hidden="true">&times;</span>
                            </button>
                            {{ session('status') }}
                        </div>
                    @endif

                    <div>
                        <table id="tabelDaftarKaryawan" class="table table-bordered dt-responsive nowrap text-center w-100"
                            style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nama</th>
                                    <th>Alamat</th>
                                    <th>Nomor Telepon</th>
                                    <th>Email</th>
                                    <th>Tanggal Pembuatan</th>
                                    <th>Tanggal Edit Terakhir</th>
                                    <th>Detail</th>
                                    <th>Edit</th>
                                    <th>Hapus</th>
                                </tr>
                            </thead>

                            <tbody>
                                @if (count($suppliers) != 0)
                                    @foreach ($suppliers as $s)
                                        <tr id="tr_{{ $s->id }}">
                                            <td>{{ $s->id }}</td>
                                            <td>{{ $s->nama }}</td>
                                            <td>{{ $s->alamat }}</td>
                                            <td>{{ $s->nomor_telepon }}</td>
                                            <td>{{ $s->email }}</td>
                                            <td>{{ date('d-m-Y H:i:s', strtotime($s->created_at)) }}</td>
                                            <td>{{ date('d-m-Y H:i:s', strtotime($s->updated_at)) }}</td>
                                            <td class="text-center"><button data-toggle="modal"
                                                    data-target="#modalDetailKaryawan" nama ="{{ $s->nama }}"
                                                    idSupplier = "{{ $s->id }}"
                                                    class=" btn btn-warning waves-effect waves-light btnDetailKaryawan">Detail</button>
                                            </td>
                                            <td class="text-center"><a href="{{ route('suppliers.edit', $s->id) }}"
                                                    class=" btn btn-info waves-effect waves-light">Edit</a></td>
                                            <td class="text-center"><button data-toggle="modal"
                                                    data-target="#modalKonfirmasiDeleteSupplier"
                                                    idSupplier = "{{ $s->id }}" namaSupplier="{{ $s->nama }}"
                                                    routeUrl = "{{ route('supplier.destroy', $s->id) }}"
                                                    class=" btn btn-danger waves-effect waves-light btnHapusSupplier">Hapus</button>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr class="text-center">
                                        <td colspan="10">
                                            Tidak ada daftar Supplier!
                                        </td>
                                    </tr>
                                @endif

                            </tbody>
                        </table>
                    </div>


                </div>
            </div>
        </div>
        <!-- end col -->
    </div>

    <div id="modalDetailSupplier" class="modal fade bs-example-modal-center" tabindex="-1" role="dialog"
        aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" style="max-width: 600px;">
            <div class="modal-content ">
                <div class="modal-header">
                    <h5 class="modal-title mt-0" id="modalNamaSupplier">Detail Supplier</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="contentDetailSupplier">
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

    <div id="modalKonfirmasiDeleteSupplier" class="modal fade bs-example-modal-center" tabindex="-1" role="dialog"
        aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <form id="formDeleteSupplier" action="{{ route('karyawans.destroy', '1') }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="modal-header">
                        <h5 id="modalNamaSupplierDelete" class="modal-title mt-0">Konfirmasi Penghapusan Supplier</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div id="modalBodyHapusSupplier" class="modal-body text-center">
                        <h6>Apakah Anda yakin untuk menghapus supplier?</h6>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">Batal</button>
                        <button id="btnKonfirmasiHapusSupplier" type="submit"
                            class="btn btn-info waves-effect waves-light btnKonfirmasiHapusSupplier">Hapus</button>
                    </div>
                </form>
            </div>

        </div>
        <!-- /.modal-dialog -->
    </div>
@endsection

@section('javascript')
    <script>
        $(document).ready(function() {
            $('#tabelDaftarKaryawan').DataTable({

            });

        });

        $('.btnDetailSupplier').on('click', function() {
            var idSupplier = $(this).attr("idSupplier");
            var nama = $(this).attr('nama');

            $("#modalNamaSupplier").text(" Detail Supplier " + nama);
            $.ajax({
                type: 'POST',
                url: '{{ route('admin.getdetailsupplier') }}',
                data: {
                    '_token': '<?php echo csrf_token(); ?>',
                    'idSupplier': idSupplier,
                },
                success: function(data) {
                    $('#contentDetailSupplier').html(data.msg);
                    $('#tabelDaftarSupplierProduk').DataTable({});
                }
            })
        });
        $('.btnHapusSupplier').on('click', function() {

            var idSupplier = $(this).attr("idSupplier");
            var namaSupplier = $(this).attr('namaSupplier');
            var routeUrl = $(this).attr('routeUrl');
            $("#modalNamaSupplierDelete").text("Konfirmasi Penghapusan Supplier " + namaSupplier);
            $("#modalBodyHapusSupplier").html("<h6>Apakah Anda yakin untuk menghapus supplier " + namaSupplier +
                "?</h6>")
            $("#formDeleteSupplier").attr("action", routeUrl);
        });
    </script>
@endsection
