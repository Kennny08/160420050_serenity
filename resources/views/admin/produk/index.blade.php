@extends('layout.adminlayout')

@section('title', 'Admin || Daftar Produk')

@section('admincontent')
    <div class="page-title-box">

    </div>
    <!-- end page-title -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <h3 class="mt-0 header-title" id="grupAktif">Daftar Produk</h3>
                    <p class="sub-title">
                    </p>
                    <a class="btn btn-info waves-effect waves-light" href="{{ route('produks.create') }}"
                        id="btnTambahProduk">
                        Tambah Produk
                    </a>

                    <br>
                    <br>
                    @if (session('status'))
                        <div class="alert alert-success alert-dismissible" role="alert">
                            <button type="button" class="close text-success" data-dismiss="alert" aria-label="Close">
                                <span class="text-success" aria-hidden="true">&times;</span>
                            </button>
                            {{ session('status') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible" role="alert">
                            <button type="button" class="close text-danger" data-dismiss="alert" aria-label="Close">
                                <span class="text-danger" aria-hidden="true">&times;</span>
                            </button>
                            <p class="mb-0"><strong>Maaf, terjadi kesalahan!</strong></p>
                            @foreach ($errors->all() as $error)
                                <p class="mt-0 mb-1">- {{ $error }}</p>
                            @endforeach
                        </div>
                    @endif

                    <div class="form-group row">
                        <div class="col-md-6">
                            <h4>Produk Aktif</h4>
                        </div>
                        <div class="col-md-6 text-right">
                            <div class="btn-group btn-group-toggle border">
                                <a href="#grupAktif" class="btn btn-info waves-effect waves-light radioAktif">
                                    Produk Aktif
                                </a>
                                <a href="#grupNonaktif" class="btn waves-effect waves-light radioNonaktif">
                                    Produk Nonaktif
                                </a>
                            </div>
                        </div>
                    </div>

                    <table id="tabelDaftarProdukAktif"
                        class="table table-striped table-bordered dt-responsive wrap text-center"
                        style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                            <tr>
                                <th>Kode Produk</th>
                                <th>Nama</th>
                                <th>Harga Jual(Rp)</th>
                                <th>Harga Beli(Rp)</th>
                                <th>Stok</th>
                                <th>Minimum Stok</th>
                                <th>Status Jual</th>
                                <th>Merek</th>
                                <th>Kategori</th>
                                <th>Kondisi</th>
                                <th hidden>Tanggal Pembuatan</th>
                                <th hidden>Tanggal Edit Terakhir</th>
                                <th hidden>Deskripsi</th>
                                <th>Detail</th>
                                <th>Edit</th>
                                <th>Hapus</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($produksAktifMinimumStok as $p)
                                <tr id="tr_{{ $p->id }}">
                                    <td>{{ $p->kode_produk }}</td>
                                    <td>{{ $p->nama }}</td>
                                    @if ($p->harga_beli >= $p->harga_jual)
                                        <td class="text-danger font-weight-bold">
                                            {{ number_format($p->harga_jual, 2, ',', '.') }}</td>
                                        <td class="text-danger font-weight-bold">
                                            {{ number_format($p->harga_beli, 2, ',', '.') }}</td>
                                    @else
                                        <td>{{ number_format($p->harga_jual, 2, ',', '.') }}</td>
                                        <td>{{ number_format($p->harga_beli, 2, ',', '.') }}</td>
                                    @endif


                                    <td class="text-danger font-weight-bold">{{ $p->stok }}</td>
                                    <td class="text-danger font-weight-bold">{{ $p->minimum_stok }}</td>
                                    <td>{{ $p->status_jual }}</td>
                                    <td>{{ $p->merek->nama }}</td>
                                    <td>{{ $p->kategori->nama }}</td>
                                    <td class="text-left">
                                        <ul>
                                            @foreach ($p->kondisis as $kondisi)
                                                <li>{{ $kondisi->keterangan }}</li>
                                            @endforeach
                                        </ul>
                                    </td>
                                    <td hidden>{{ date('d-m-Y H:i:s', strtotime($p->created_at)) }}</td>
                                    <td hidden>{{ date('d-m-Y H:i:s', strtotime($p->updated_at)) }}</td>
                                    <td hidden>{{ $p->deskripsi }}</td>
                                    <td class="text-center"><button data-toggle="modal" data-target="#modalDetailProduk"
                                            deskripsi="{{ $p->deskripsi }}" namaProduk ="{{ $p->nama }}"
                                            createdAt="{{ date('d-m-Y H:i:s', strtotime($p->created_at)) }}"
                                            updatedAt="{{ date('d-m-Y H:i:s', strtotime($p->updated_at)) }}"
                                            class=" btn btn-warning waves-effect waves-light btnDetailProduk">Detail</button>
                                    </td>
                                    <td class="text-center"><a href="{{ route('produks.edit', $p->id) }}"
                                            class=" btn btn-info waves-effect waves-light">Edit</a></td>
                                    <td class="text-center"><button data-toggle="modal"
                                            data-target="#modalKonfirmasiDeleteProduk" idProduk = "{{ $p->id }}"
                                            namaProduk="{{ $p->nama }}"
                                            routeUrl = "{{ route('produks.destroy', $p->id) }}"
                                            class=" btn btn-danger waves-effect waves-light btnHapusProduk">Hapus</button>
                                    </td>
                                </tr>
                            @endforeach
                            @foreach ($produksAktifLebihMinimumStok as $p)
                                <tr id="tr_{{ $p->id }}">
                                    <td>{{ $p->kode_produk }}</td>
                                    <td>{{ $p->nama }}</td>
                                    @if ($p->harga_beli >= $p->harga_jual)
                                        <td class="text-danger font-weight-bold">
                                            {{ number_format($p->harga_jual, 2, ',', '.') }}</td>
                                        <td class="text-danger font-weight-bold">
                                            {{ number_format($p->harga_beli, 2, ',', '.') }}</td>
                                    @else
                                        <td>{{ number_format($p->harga_jual, 2, ',', '.') }}</td>
                                        <td>{{ number_format($p->harga_beli, 2, ',', '.') }}</td>
                                    @endif
                                    <td>{{ $p->stok }}</td>
                                    <td>{{ $p->minimum_stok }}</td>
                                    <td>{{ $p->status_jual }}</td>
                                    <td>{{ $p->merek->nama }}</td>
                                    <td>{{ $p->kategori->nama }}</td>
                                    <td class="text-left">
                                        <ul>
                                            @foreach ($p->kondisis as $kondisi)
                                                <li>{{ $kondisi->keterangan }}</li>
                                            @endforeach
                                        </ul>
                                    </td>
                                    <td hidden>{{ date('d-m-Y H:i:s', strtotime($p->created_at)) }}</td>
                                    <td hidden>{{ date('d-m-Y H:i:s', strtotime($p->updated_at)) }}</td>
                                    <td hidden>{{ $p->deskripsi }}</td>
                                    <td class="text-center"><button data-toggle="modal" data-target="#modalDetailProduk"
                                            deskripsi="{{ $p->deskripsi }}" namaProduk ="{{ $p->nama }}"
                                            createdAt="{{ date('d-m-Y H:i:s', strtotime($p->created_at)) }}"
                                            updatedAt="{{ date('d-m-Y H:i:s', strtotime($p->updated_at)) }}"
                                            class=" btn btn-warning waves-effect waves-light btnDetailProduk">Detail</button>
                                    </td>
                                    <td class="text-center"><a href="{{ route('produks.edit', $p->id) }}"
                                            class=" btn btn-info waves-effect waves-light">Edit</a></td>
                                    <td class="text-center"><button data-toggle="modal"
                                            data-target="#modalKonfirmasiDeleteProduk" idProduk = "{{ $p->id }}"
                                            namaProduk="{{ $p->nama }}"
                                            routeUrl = "{{ route('produks.destroy', $p->id) }}"
                                            class=" btn btn-danger waves-effect waves-light btnHapusProduk">Hapus</button>
                                    </td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>


                    <br>
                    <div class="form-group row" id="#dataProdukNonaktif">
                        <div class="col-md-6">
                            <h4>Produk Nonaktif</h4>
                        </div>
                        <div class="col-md-6 text-right" id="grupNonaktif">
                            <div class="btn-group btn-group-toggle border">
                                <a href="#grupAktif" class="btn btn-info waves-effect waves-light radioAktif">
                                    Produk Aktif
                                </a>
                                <a href="#grupNonaktif" class="btn waves-effect waves-light radioNonaktif">
                                    Produk Nonaktif
                                </a>
                            </div>
                        </div>
                    </div>

                    <table id="tabelDaftarProdukNonaktif"
                        class="table table-striped table-bordered dt-responsive wrap text-center"
                        style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                            <tr>
                                <th>Kode Produk</th>
                                <th>Nama</th>
                                <th>Harga Jual(Rp)</th>
                                <th>Harga Beli(Rp)</th>
                                <th>Stok</th>
                                <th>Minimum Stok</th>
                                <th>Status Jual</th>
                                <th>Merek</th>
                                <th>Kategori</th>
                                <th>Kondisi</th>
                                <th hidden>Tanggal Pembuatan</th>
                                <th hidden>Tanggal Edit Terakhir</th>
                                <th hidden>Deskripsi</th>
                                <th>Detail</th>
                                <th>Edit</th>
                                <th>Hapus</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($produksNonaktif as $p)
                                <tr id="tr_{{ $p->id }}">
                                    <td>{{ $p->kode_produk }}</td>
                                    <td>{{ $p->nama }}</td>
                                    @if ($p->harga_beli >= $p->harga_jual)
                                        <td class="text-danger font-weight-bold">
                                            {{ number_format($p->harga_jual, 2, ',', '.') }}</td>
                                        <td class="text-danger font-weight-bold">
                                            {{ number_format($p->harga_beli, 2, ',', '.') }}</td>
                                    @else
                                        <td>{{ number_format($p->harga_jual, 2, ',', '.') }}</td>
                                        <td>{{ number_format($p->harga_beli, 2, ',', '.') }}</td>
                                    @endif
                                    <td>{{ $p->stok }}</td>
                                    <td>{{ $p->minimum_stok }}</td>
                                    <td>{{ $p->status_jual }}</td>
                                    <td>{{ $p->merek->nama }}</td>
                                    <td>{{ $p->kategori->nama }}</td>
                                    <td class="text-left">
                                        <ul>
                                            @foreach ($p->kondisis as $kondisi)
                                                <li>{{ $kondisi->keterangan }}</li>
                                            @endforeach
                                        </ul>
                                    </td>
                                    <td hidden>{{ date('d-m-Y H:i:s', strtotime($p->created_at)) }}</td>
                                    <td hidden>{{ date('d-m-Y H:i:s', strtotime($p->updated_at)) }}</td>
                                    <td hidden>{{ $p->deskripsi }}</td>
                                    <td class="text-center"><button data-toggle="modal" data-target="#modalDetailProduk"
                                            deskripsi="{{ $p->deskripsi }}" namaProduk ="{{ $p->nama }}"
                                            createdAt="{{ date('d-m-Y H:i:s', strtotime($p->created_at)) }}"
                                            updatedAt="{{ date('d-m-Y H:i:s', strtotime($p->updated_at)) }}"
                                            class=" btn btn-warning waves-effect waves-light btnDetailProduk">Detail</button>
                                    </td>
                                    <td class="text-center"><a href="{{ route('produks.edit', $p->id) }}"
                                            class=" btn btn-info waves-effect waves-light">Edit</a></td>
                                    <td class="text-center"><button data-toggle="modal"
                                            data-target="#modalKonfirmasiDeleteProduk" idProduk = "{{ $p->id }}"
                                            namaProduk="{{ $p->nama }}"
                                            routeUrl = "{{ route('produks.destroy', $p->id) }}"
                                            class=" btn btn-danger waves-effect waves-light btnHapusProduk">Hapus</button>
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

    <div id="modalDetailProduk" class="modal fade bs-example-modal-center" tabindex="-1" role="dialog"
        aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title mt-0" id="modalNamaProduk">Detail Produk</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="contentDetailProduk">
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

    <div id="modalKonfirmasiDeleteProduk" class="modal fade bs-example-modal-center" tabindex="-1" role="dialog"
        aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <form id="formDeleteProduk" action="{{ route('produks.destroy', '1') }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="modal-header">
                        <h5 id="modalNamaProdukDelete" class="modal-title mt-0">Konfirmasi Penghapusan Produk</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div id="modalBodyHapusProduk" class="modal-body text-center">
                        <h6>Apakah Anda yakin untuk menghapus produk?</h6>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">Batal</button>
                        <button id="btnKonfirmasiHapusProduk" type="submit"
                            class="btn btn-info waves-effect waves-light btnKonfirmasiHapusProduk">Hapus</button>
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
            $('#tabelDaftarProdukAktif').DataTable({
                ordering: false,
                language: {
                    emptyTable: "Tidak terdapat data produk aktif!",
                    infoEmpty: "Tidak terdapat data produk aktif!",
                }
            });
            $('#tabelDaftarProdukNonaktif').DataTable({
                language: {
                    emptyTable: "Tidak terdapat data produk nonaktif!",
                    infoEmpty: "Tidak terdapat data produk nonaktif!",
                }
            });
        });

        $('.btnDetailProduk').on('click', function() {
            var deskripsiProduk = $(this).attr('deskripsi');
            var namaProduk = $(this).attr('namaProduk');
            var createdAt = $(this).attr('createdAt');
            var updatedAt = $(this).attr('updatedAt');
            $("#modalNamaProduk").text(" Detail Produk " + namaProduk);
            $("#contentDetailProduk").html(
                "<div class='form-group row text-center'><div class='form-group col-md-6'><h6>Tanggal Pembuatan</h6><p>" +
                createdAt + "</p></div><div class='form-group col-md-6'><h6>Tanggal Terakhir Diubah</h6><p>" +
                updatedAt +
                "</p></div></div><div class='form-group row text-center'><div class='form-group col-md-12'><h6>Deskripsi Produk</h6><p>" +
                deskripsiProduk + "</p></div></div>");

        });

        $('.radioAktif').on('click', function() {
            $(".radioAktif").addClass("btn-info");
            $(".radioNonaktif").removeClass("btn-info");
        });

        $('.radioNonaktif').on('click', function() {
            $(".radioNonaktif").addClass("btn-info");
            $(".radioAktif").removeClass("btn-info");
        });

        $('.btnHapusProduk').on('click', function() {

            var idProduk = $(this).attr("idProduk");
            var namaProduk = $(this).attr('namaProduk');
            var routeUrl = $(this).attr('routeUrl');
            $("#modalNamaProdukDelete").text("Konfirmasi Penghapusan Produk " + namaProduk);
            $("#modalBodyHapusProduk").html("<h6>Apakah Anda yakin untuk menghapus produk " + namaProduk +
                "?</h6>")
            $("#formDeleteProduk").attr("action", routeUrl);
        });
    </script>
@endsection
