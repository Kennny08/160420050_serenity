@extends('layout.adminlayout')

@section('title', 'Admin || Daftar Kategori')

@section('admincontent')
    <div class="page-title-box">
    </div>
    <!-- end page-title -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <h3 class="mt-0 header-title">Daftar Kategori Produk</h3>
                    <p class="sub-title">
                    </p>
                    {{-- <a class="btn btn-primary" data-toggle="modal" href="{{ route('categories.create') }}">Add Category</a> --}}
                    <a class="btn btn-info waves-effect waves-light" href={{ route('kategoris.create') }}>Tambah
                        Kategori</a><br>
                    <br>
                    @if (session('status'))
                        <div class="alert alert-success">
                            <button type="button" class="close text-danger" data-dismiss="alert" aria-label="Close">
                                <span class="text-success" aria-hidden="true">&times;</span>
                            </button>
                            {{ session('status') }}
                        </div>
                    @endif

                    <table id="tabelDaftarKategori" class="table table-bordered dt-responsive nowrap text-center"
                        style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama Kategori</th>
                                <th>Tanggal Pembuatan</th>
                                <th>Tanggal Edit Terakhir</th>
                                <th>Daftar Produk</th>
                                <th>Edit</th>
                                <th>Hapus</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($kategoris as $k)
                                <tr id="tr_{{ $k->id }}">
                                    <td>{{ $k->id }}</td>
                                    <td>{{ $k->nama }}</td>
                                    <td>{{ date('d-m-Y H:i:s', strtotime($k->created_at)) }}</td>
                                    <td>{{ date('d-m-Y H:i:s', strtotime($k->updated_at)) }}</td>
                                    <td class="text-center"><button idKategori="{{ $k->id }}"
                                            namaKategori="{{ $k->nama }}" data-toggle="modal"
                                            data-target="#modalDaftarProdukKategori"
                                            class=" btn btn-warning waves-effect waves-light btnDaftarProduk">Tampilkan</button>
                                    </td>
                                    <td class="text-center"><a href="{{ route('kategoris.edit', $k->id) }}"
                                            class=" btn btn-info waves-effect waves-light">Edit</a>
                                    </td>
                                    <td class="text-center"><button data-toggle="modal"
                                            data-target="#modalKonfirmasiDeleteKategori"
                                            class=" btn btn-danger waves-effect waves-light btnHapusKategori"
                                            idKategori = "{{ $k->id }}" namaKategori="{{ $k->nama }}"
                                            routeUrl = "{{ route('kategoris.destroy', $k->id) }}">Hapus</button>
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

    <div id="modalDaftarProdukKategori" class="modal fade bs-example-modal-center" tabindex="-1" role="dialog"
        aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl" style="max-width: 90%">
            <div class="modal-content ">
                <div class="modal-header">
                    <h5 class="modal-title mt-0" id="modalNamaDaftarProdukKategori">Daftar Produk</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="contentDaftarProdukKategori">
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

    <div id="modalKonfirmasiDeleteKategori" class="modal fade bs-example-modal-center" tabindex="-1" role="dialog"
        aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <form id="formDeleteKategori" action="{{ route('kategoris.destroy', '1') }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="modal-header">
                        <h5 id="modalNamaKategori" class="modal-title mt-0">Konfirmasi Penghapusan Kategori</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div id="modalBodyHapusKategori" class="modal-body text-center">
                        <h6>Apakah Anda yakin untuk menghapus kategori?</h6>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">Batal</button>
                        <button id="btnKonfirmasiHapusKategori" type="submit"
                            class="btn btn-info waves-effect waves-light btnKonfirmasiHapusKategori">Hapus</button>
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
            $('#tabelDaftarKategori').DataTable({
                language: {
                    emptyTable: "Tidak terdapat Data Daftar Kategori",
                    infoEmpty: "Tidak terdapat Data Daftar Kategori",
                },
            });
        });

        $("body").on("click", ".btnDaftarProduk", function() {
            var idKategori = $(this).attr("idKategori");
            var namaKategori = $(this).attr("namaKategori");

            $('#contentDaftarProdukKategori').html(
                "<div class='text-center'>" +
                "<div class='spinner-border text-info' role='status'>" +
                "<span class='sr-only'>Loading...</span>" +
                "</div></div>");

            $("#modalNamaDaftarProdukKategori").text("Daftar Produk untuk Kategori " + namaKategori);
            $.ajax({
                type: 'POST',
                url: '{{ route('admin.kategoris.getdaftarprodukkategori') }}',
                data: {
                    '_token': '<?php echo csrf_token(); ?>',
                    'idKategori': idKategori,
                },
                success: function(data) {
                    $('#contentDaftarProdukKategori').html(data.msg);
                    $('#tabelDaftarProdukKategori').DataTable({
                        language: {
                            emptyTable: "Tidak terdapat Daftar Produk dengan Kategori " + namaKategori,
                            infoEmpty: "Tidak terdapat Daftar Produk dengan Kategori " + namaKategori,
                        },
                    });
                }
            })
        });

        $('.btnHapusKategori').on('click', function() {

            var idKategori = $(this).attr("idKategori");
            var namaKategori = $(this).attr('namaKategori');
            var routeUrl = $(this).attr('routeUrl');
            $("#modalNamaKategori").text("Konfirmasi Penghapusan Kategori " + namaKategori);
            $("#modalBodyHapusKategori").html("<h6>Apakah Anda yakin untuk menghapus kategori " + namaKategori +
                "?</h6>")
            $("#formDeleteKategori").attr("action", routeUrl);
        });
    </script>
@endsection
