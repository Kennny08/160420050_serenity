@extends('layout.adminlayout')

@section('title', 'Admin || Daftar Merek Produk')

@section('admincontent')
    <div class="page-title-box">
    </div>
    <!-- end page-title -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <h3 class="mt-0 header-title">Daftar Merek Produk</h3>
                    <p class="sub-title">
                    </p>
                    @if (Auth::user()->role == 'admin' || Auth::user()->karyawan->jenis_karyawan == 'admin')
                        <a class="btn btn-info waves-effect waves-light" href={{ route('mereks.create') }}>Tambah
                            Merek</a>
                            <br>
                    @endif

                    <br>
                    @if (session('status'))
                        <div class="alert alert-success">
                            <button type="button" class="close text-danger" data-dismiss="alert" aria-label="Close">
                                <span class="text-success" aria-hidden="true">&times;</span>
                            </button>
                            {{ session('status') }}
                        </div>
                    @endif

                    <table id="tabelDaftarMerek" class="table table-bordered dt-responsive nowrap text-center"
                        style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama Merek</th>
                                <th>Tanggal Pembuatan</th>
                                <th>Tanggal Edit Terakhir</th>
                                <th>Daftar Produk</th>
                                @if (Auth::user()->role == 'admin' || Auth::user()->karyawan->jenis_karyawan == 'admin')
                                    <th>Edit</th>
                                    <th>Hapus</th>
                                @endif

                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($mereks as $m)
                                <tr id="tr_{{ $m->id }}">
                                    <td>{{ $m->id }}</td>
                                    <td>{{ $m->nama }}</td>
                                    <td>{{ date('d-m-Y H:i:s', strtotime($m->created_at)) }}</td>
                                    <td>{{ date('d-m-Y H:i:s', strtotime($m->updated_at)) }}</td>
                                    <td class="text-center"><button idMerek="{{ $m->id }}"
                                            namaMerek="{{ $m->nama }}" data-toggle="modal"
                                            data-target="#modalDaftarProdukMerek"
                                            class=" btn btn-warning waves-effect waves-light btnDaftarProduk">Tampilkan</button>
                                    </td>
                                    @if (Auth::user()->role == 'admin' || Auth::user()->karyawan->jenis_karyawan == 'admin')
                                        <td class="text-center"><a href="{{ route('mereks.edit', $m->id) }}"
                                                class=" btn btn-info waves-effect waves-light">Edit</a></td>
                                        <td class="text-center"><button data-toggle="modal"
                                                data-target="#modalKonfirmasiDeleteMerek"
                                                class=" btn btn-danger waves-effect waves-light btnHapusMerek"
                                                idMerek = "{{ $m->id }}" namaMerek="{{ $m->nama }}"
                                                routeUrl = "{{ route('mereks.destroy', $m->id) }}">Hapus</button></td>
                                    @endif

                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
        <!-- end col -->
    </div>
    <div id="modalKonfirmasiDeleteMerek" class="modal fade bs-example-modal-center" tabindex="-1" role="dialog"
        aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <form id="formDeleteMerek" action="{{ route('mereks.destroy', '1') }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="modal-header">
                        <h5 id="modalNamaMerek" class="modal-title mt-0">Konfirmasi Penghapusan Merek</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div id="modalBodyHapusMerek" class="modal-body text-center">
                        <h6>Apakah Anda yakin untuk menghapus merek?</h6>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">Batal</button>
                        <button id="btnKonfirmasiHapusMerek" type="submit"
                            class="btn btn-info waves-effect waves-light btnKonfirmasiHapusMerek">Hapus</button>
                    </div>
                </form>
            </div>

        </div>
        <!-- /.modal-dialog -->
    </div>

    <div id="modalDaftarProdukMerek" class="modal fade bs-example-modal-center" tabindex="-1" role="dialog"
        aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl" style="max-width: 90%">
            <div class="modal-content ">
                <div class="modal-header">
                    <h5 class="modal-title mt-0" id="modalNamaDaftarProdukMerek">Daftar Produk</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="contentDaftarProdukMerek">
                    <div class="text-center">
                        <div class="spinner-border text-info" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer"> <button type="button" class="btn btn-danger waves-effect"
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
            $('#tabelDaftarMerek').DataTable({

            });
        });

        $("body").on("click", ".btnDaftarProduk", function() {
            var idMerek = $(this).attr("idMerek");
            var namaMerek = $(this).attr("namaMerek");

            $('#contentDaftarProdukMerek').html(
                "<div class='text-center'>" +
                "<div class='spinner-border text-info' role='status'>" +
                "<span class='sr-only'>Loading...</span>" +
                "</div></div>");

            $("#modalNamaDaftarProdukMerek").text("Daftar Produk untuk Merek " + namaMerek);
            $.ajax({
                type: 'POST',
                url: '{{ route('admin.mereks.getdaftarprodukmerek') }}',
                data: {
                    '_token': '<?php echo csrf_token(); ?>',
                    'idMerek': idMerek,
                },
                success: function(data) {
                    $('#contentDaftarProdukMerek').html(data.msg);
                    $('#tabelDaftarProdukMerek').DataTable({
                        language: {
                            emptyTable: "Tidak terdapat Daftar Produk dengan Merek " +
                                namaMerek,
                            infoEmpty: "Tidak terdapat Daftar Produk dengan Merek " +
                                namaMerek,
                        },
                    });
                }
            })
        });

        $('.btnHapusMerek').on('click', function() {

            var idMerek = $(this).attr("idMerek");
            var namaMerek = $(this).attr('namaMerek');
            var routeUrl = $(this).attr('routeUrl');
            $("#modalNamaMerek").text("Konfirmasi Penghapusan Merek " + namaMerek);
            $("#modalBodyHapusMerek").html(
                "<h6>Apakah Anda yakin untuk menghapus merek <span class='text-danger'>" + namaMerek +
                "</span>?</h6>")
            $("#formDeleteMerek").attr("action", routeUrl);
        });
    </script>
@endsection
