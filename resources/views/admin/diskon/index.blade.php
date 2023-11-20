@extends('layout.adminlayout')

@section('title', 'Admin || Daftar Diskon')

@section('admincontent')
    <div class="page-title-box">
    </div>
    <!-- end page-title -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <h3 class="mt-0 header-title" id="grupAktif">Daftar Diskon</h3>
                    <p class="sub-title">
                    </p>
                    <a class="btn btn-info waves-effect waves-light" href="{{ route('diskons.create') }}"
                        id="btnTambahDiskon">
                        Tambah Data Diskon
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
                            <h4>Diskon Aktif</h4>
                        </div>
                        <div class="col-md-6 text-right">
                            <div class="btn-group btn-group-toggle border">
                                <a href="#grupAktif" class="btn btn-info waves-effect waves-light radioAktif">
                                    Diskon Aktif
                                </a>
                                <a href="#grupNonaktif" class="btn waves-effect waves-light radioNonaktif">
                                    Diskon Nonaktif
                                </a>
                            </div>
                        </div>
                    </div>

                    <table id="tabelDaftarDiskonAktif"
                        class="table table-striped table-bordered dt-responsive wrap text-center"
                        style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                            <tr>
                                <th class="align-middle">Nama</th>
                                <th class="align-middle">Jumlah Potongan (%)</th>
                                <th class="align-middle">Minimal Transaksi</th>
                                <th class="align-middle">Maksimum Potongan</th>
                                <th class="align-middle">Tanggal Mulai</th>
                                <th class="align-middle">Tanggal Berakhir</th>
                                <th class="align-middle">Kode Diskon</th>
                                <th hidden class="align-middle">Tanggal Pembuatan</th>
                                <th hidden class="align-middle">Tanggal Terakhir Diedit</th>
                                <th class="align-middle">Detail</th>
                                <th class="align-middle">Edit</th>
                                <th class="align-middle">Hapus</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($diskonAktif as $d)
                                <tr id="tr_{{ $d->id }}">
                                    <td>{{ $d->nama }}</td>
                                    <td>{{ $d->jumlah_potongan }}</td>
                                    <td>{{ number_format($d->minimal_transaksi, 2, ',', '.') }}</td>
                                    <td>{{ number_format($d->maksimum_potongan, 2, ',', '.') }}</td>
                                    <td>{{ date('d-m-Y', strtotime($d->tanggal_mulai)) }}</td>
                                    <td>{{ date('d-m-Y', strtotime($d->tanggal_berakhir)) }}</td>
                                    <td>{{ $d->kode_diskon }}</td>
                                    <td hidden>{{ date('d-m-Y', strtotime($d->created_at)) }}</td>
                                    <td hidden>{{ date('d-m-Y', strtotime($d->updated_at)) }}</td>
                                    <td class="text-center"><button data-toggle="modal" data-target="#modalDetailDiskon"
                                            idDiskon="{{ $d->id }}" namaDiskon ="{{ $d->nama }}"
                                            class=" btn btn-warning waves-effect waves-light btnDetailDiskon">Detail</button>
                                    </td>
                                    <td class="text-center"><a href="{{ route('diskons.edit', $d->id) }}"
                                            class=" btn btn-info waves-effect waves-light">Edit</a></td>
                                    <td class="text-center"><button data-toggle="modal"
                                            data-target="#modalKonfirmasiDeleteDiskon" idDiskon = "{{ $d->id }}"
                                            namaDiskon="{{ $d->nama }}"
                                            routeUrl = "{{ route('diskons.destroy', $d->id) }}"
                                            class=" btn btn-danger waves-effect waves-light btnHapusDiskon">Hapus</button>
                                    </td>
                                </tr>
                            @endforeach

                        </tbody>
                        <tfoot id="grupNonaktif">

                        </tfoot>
                    </table>
                    <br>
                    <br>
                    <div class="form-group row">
                        <div class="col-md-6">
                            <h4>Diskon Nonaktif</h4>
                        </div>
                        <div class="col-md-6 text-right">
                            <div class="btn-group btn-group-toggle border">
                                <a href="#grupAktif" class="btn btn-info waves-effect waves-light radioAktif">
                                    Diskon Aktif
                                </a>
                                <a href="#grupNonaktif" class="btn waves-effect waves-light radioNonaktif">
                                    Diskon Nonaktif
                                </a>
                            </div>
                        </div>
                    </div>

                    <table id="tabelDaftarDiskonNonaktif"
                        class="table table-striped table-bordered dt-responsive wrap text-center"
                        style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                            <tr>
                                <th class="align-middle">Nama</th>
                                <th class="align-middle">Jumlah Potongan (%)</th>
                                <th class="align-middle">Minimal Transaksi</th>
                                <th class="align-middle">Maksimum Potongan</th>
                                <th class="align-middle">Tanggal Mulai</th>
                                <th class="align-middle">Tanggal Berakhir</th>
                                <th class="align-middle">Kode Diskon</th>
                                <th hidden class="align-middle">Tanggal Pembuatan</th>
                                <th hidden class="align-middle">Tanggal Terakhir Diedit</th>
                                <th class="align-middle">Detail</th>
                                <th class="align-middle">Edit</th>
                                <th class="align-middle">Hapus</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($diskonNonaktif as $d)
                                <tr id="tr_{{ $d->id }}">
                                    <td>{{ $d->nama }}</td>
                                    <td>{{ $d->jumlah_potongan }}</td>
                                    <td>{{ number_format($d->minimal_transaksi, 2, ',', '.') }}</td>
                                    <td>{{ number_format($d->maksimum_potongan, 2, ',', '.') }}</td>
                                    <td>{{ date('d-m-Y', strtotime($d->tanggal_mulai)) }}</td>
                                    <td>{{ date('d-m-Y', strtotime($d->tanggal_berakhir)) }}</td>
                                    <td>{{ $d->kode_diskon }}</td>
                                    <td hidden>{{ date('d-m-Y', strtotime($d->created_at)) }}</td>
                                    <td hidden>{{ date('d-m-Y', strtotime($d->updated_at)) }}</td>
                                    <td class="text-center"><button data-toggle="modal" data-target="#modalDetailDiskon"
                                            idDiskon="{{ $d->id }}" namaDiskon ="{{ $d->nama }}"
                                            class=" btn btn-warning waves-effect waves-light btnDetailDiskon">Detail</button>
                                    </td>
                                    <td class="text-center"><a href="{{ route('diskons.edit', $d->id) }}"
                                            class=" btn btn-info waves-effect waves-light">Edit</a></td>
                                    <td class="text-center"><button data-toggle="modal"
                                            data-target="#modalKonfirmasiDeleteDiskon" idDiskon = "{{ $d->id }}"
                                            namaDiskon="{{ $d->nama }}"
                                            routeUrl = "{{ route('diskons.destroy', $d->id) }}"
                                            class=" btn btn-danger waves-effect waves-light btnHapusDiskon">Hapus</button>
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

    <div id="modalDetailDiskon" class="modal fade bs-example-modal-center" tabindex="-1" role="dialog"
        aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" style="max-width: 600px;">
            <div class="modal-content ">
                <div class="modal-header">
                    <h5 class="modal-title mt-0" id="modalNamaDiskon">Detail Diskon</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="contentDetailDiskon">
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

    <div id="modalKonfirmasiDeleteDiskon" class="modal fade bs-example-modal-center" tabindex="-1" role="dialog"
        aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <form id="formDeleteDiskon" action="{{ route('diskons.destroy', '1') }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="modal-header">
                        <h5 id="modalNamaDiskonDelete" class="modal-title mt-0">Konfirmasi Penghapusan Diskon</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div id="modalBodyHapusDiskon" class="modal-body text-center">
                        <h6>Apakah Anda yakin untuk menghapus diskon?</h6>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">Batal</button>
                        <button id="btnKonfirmasiHapusDiskon" type="submit"
                            class="btn btn-info waves-effect waves-light btnKonfirmasiHapusDiskon">Hapus</button>
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
            $('#tabelDaftarDiskonAktif').DataTable({
                language: {
                    emptyTable: "Tidak terdapat data diskon aktif!",
                }
            });

            $('#tabelDaftarDiskonNonaktif').DataTable({
                language: {
                    emptyTable: "Tidak terdapat data diskon nonaktif!",
                }
            });

        });

        $('.radioAktif').on('click', function() {
            $(".radioAktif").addClass("btn-info");
            $(".radioNonaktif").removeClass("btn-info");
        });

        $('.radioNonaktif').on('click', function() {
            $(".radioNonaktif").addClass("btn-info");
            $(".radioAktif").removeClass("btn-info");
        });

        $('.btnDetailDiskon').on('click', function() {

            var idDiskon = $(this).attr("idDiskon");
            var nama = $(this).attr('namaDiskon');
            $("#modalNamaDiskon").text(" Detail Diskon " + nama);
            $.ajax({
                type: 'POST',
                url: '{{ route('admin.diskons.getdetaildiskons') }}',
                data: {
                    '_token': '<?php echo csrf_token(); ?>',
                    'idDiskon': idDiskon,
                },
                success: function(data) {
                    $('#contentDetailDiskon').html(data.msg);
                    $('#tabelDaftarDiskonProduk').DataTable({});
                }
            })

        });

        $('.btnHapusDiskon').on('click', function() {

            var idDiskon = $(this).attr("idDiskon");
            var namaDiskon = $(this).attr('namaDiskon');
            var routeUrl = $(this).attr('routeUrl');
            $("#modalNamaDiskonDelete").text("Konfirmasi Penghapusan Diskon " + namaDiskon);
            $("#modalBodyHapusDiskon").html("<h6>Apakah Anda yakin untuk menghapus diskon " + namaDiskon +
                "?</h6>")
            $("#formDeleteDiskon").attr("action", routeUrl);
        });
    </script>
@endsection
