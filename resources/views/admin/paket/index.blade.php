@extends('layout.adminlayout')

@section('title', 'Admin || Daftar Paket')

@section('admincontent')
    <div class="page-title-box">
    </div>
    <!-- end page-title -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <h3 class="mt-0 header-title" id="grupAktif">Daftar Paket</h3>
                    <p class="sub-title">
                    </p>
                    @if (Auth::user()->role == 'admin' || Auth::user()->karyawan->jenis_karyawan == 'admin')
                        <a class="btn btn-info waves-effect waves-light" href="{{ route('pakets.create') }}"
                            id="btnTambahPaket">
                            Tambah Paket
                        </a>
                        <br>
                    @endif

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
                            <h4>Paket Aktif</h4>
                        </div>
                        <div class="col-md-6 text-right">
                            <div class="btn-group btn-group-toggle border">
                                <a href="#grupAktif" class="btn btn-info waves-effect waves-light radioAktif">
                                    Paket Aktif
                                </a>
                                <a href="#grupNonaktif" class="btn waves-effect waves-light radioNonaktif">
                                    Paket Nonaktif
                                </a>
                            </div>
                        </div>
                    </div>

                    <table id="tabelDaftarPaketAktif"
                        class="table table-striped table-bordered dt-responsive wrap text-center"
                        style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                            <tr>
                                <th class="align-middle">Kode Paket</th>
                                <th class="align-middle">Nama</th>
                                <th class="align-middle">Harga(Rp)</th>
                                <th class="align-middle">Total Durasi(Menit)</th>
                                <th class="align-middle">Deskripsi</th>
                                <th hidden>Tanggal Pembuatan</th>
                                <th hidden>Tanggal Terakhir Diedit</th>
                                <th class="align-middle">Detail</th>
                                @if (Auth::user()->role == 'admin' || Auth::user()->karyawan->jenis_karyawan == 'admin')
                                    <th class="align-middle">Edit</th>
                                    <th class="align-middle">Hapus</th>
                                @endif

                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($paketsAktif as $pa)
                                <tr id="tr_{{ $pa->id }}">
                                    <td>{{ $pa->kode_paket }}</td>
                                    <td>{{ $pa->nama }}</td>
                                    <td>{{ number_format($pa->harga, 2, ',', '.') }}</td>
                                    @php
                                        $totalDurasiPerawatanNonKomplemen = $pa->perawatans->where("status_komplemen", "tidak")->sum("durasi");
                                        $totalDurasiPerawatanKomplemen = $pa->perawatans->where("status_komplemen", "ya")->max("durasi");
                                    @endphp
                                    <td>{{ $totalDurasiPerawatanNonKomplemen + $totalDurasiPerawatanKomplemen }}</td>
                                    <td>{{ $pa->deskripsi }}</td>
                                    <td hidden>{{ date('d-m-Y', strtotime($pa->created_at)) }}</td>
                                    <td hidden>{{ date('d-m-Y', strtotime($pa->updated_at)) }}</td>
                                    <td class="text-center"><button data-toggle="modal" data-target="#modalDetailPaket"
                                            idPaket="{{ $pa->id }}" namaPaket ="{{ $pa->nama }}"
                                            class=" btn btn-warning waves-effect waves-light btnDetailPaket">Detail</button>
                                    </td>
                                    @if (Auth::user()->role == 'admin' || Auth::user()->karyawan->jenis_karyawan == 'admin')
                                        <td class="text-center"><a href="{{ route('pakets.edit', $pa->id) }}"
                                                class=" btn btn-info waves-effect waves-light">Edit</a></td>
                                        <td class="text-center"><button data-toggle="modal"
                                                data-target="#modalKonfirmasiDeletePaket" idPaket = "{{ $pa->id }}"
                                                namaPaket="{{ $pa->nama }}"
                                                routeUrl = "{{ route('pakets.destroy', $pa->id) }}"
                                                class=" btn btn-danger waves-effect waves-light btnHapusPaket">Hapus</button>
                                        </td>
                                    @endif

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
                            <h4>Paket Nonaktif</h4>
                        </div>
                        <div class="col-md-6 text-right">
                            <div class="btn-group btn-group-toggle border">
                                <a href="#grupAktif" class="btn btn-info waves-effect waves-light radioAktif">
                                    Paket Aktif
                                </a>
                                <a href="#grupNonaktif" class="btn waves-effect waves-light radioNonaktif">
                                    Paket Nonaktif
                                </a>
                            </div>
                        </div>
                    </div>

                    <table id="tabelDaftarPaketNonaktif"
                        class="table table-striped table-bordered dt-responsive wrap text-center"
                        style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                            <tr>
                                <th class="align-middle">Kode Paket</th>
                                <th class="align-middle">Nama</th>
                                <th class="align-middle">Harga(Rp)</th>
                                <th class="align-middle">Total Durasi(Menit)</th>
                                <th class="align-middle">Deskripsi</th>
                                <th hidden>Tanggal Pembuatan</th>
                                <th hidden>Tanggal Terakhir Diedit</th>
                                <th class="align-middle">Detail</th>
                                @if (Auth::user()->role == 'admin' || Auth::user()->karyawan->jenis_karyawan == 'admin')
                                    <th class="align-middle">Edit</th>
                                    <th class="align-middle">Hapus</th>
                                @endif

                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($paketsNonaktif as $pn)
                                <tr id="tr_{{ $pn->id }}">
                                    <td>{{ $pn->kode_paket }}</td>
                                    <td>{{ $pn->nama }}</td>
                                    <td>{{ number_format($pn->harga, 2, ',', '.') }}</td>
                                    <td>{{ $pn->perawatans->sum('durasi') }}</td>
                                    <td>{{ $pn->deskripsi }}</td>
                                    <td hidden>{{ date('d-m-Y', strtotime($pn->created_at)) }}</td>
                                    <td hidden>{{ date('d-m-Y', strtotime($pn->updated_at)) }}</td>
                                    <td class="text-center"><button data-toggle="modal" data-target="#modalDetailPaket"
                                            idPaket="{{ $pn->id }}" namaPaket ="{{ $pn->nama }}"
                                            class=" btn btn-warning waves-effect waves-light btnDetailPaket">Detail</button>
                                    </td>
                                    @if (Auth::user()->role == 'admin' || Auth::user()->karyawan->jenis_karyawan == 'admin')
                                        <td class="text-center"><a href="{{ route('pakets.edit', $pn->id) }}"
                                                class=" btn btn-info waves-effect waves-light">Edit</a></td>
                                        <td class="text-center"><button data-toggle="modal"
                                                data-target="#modalKonfirmasiDeletePaket" idPaket = "{{ $pn->id }}"
                                                namaPaket="{{ $pn->nama }}"
                                                routeUrl = "{{ route('pakets.destroy', $pn->id) }}"
                                                class=" btn btn-danger waves-effect waves-light btnHapusPaket">Hapus</button>
                                        </td>
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

    <div id="modalDetailPaket" class="modal fade bs-example-modal-center" tabindex="-1" role="dialog"
        aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" style="max-width: 600px;">
            <div class="modal-content ">
                <div class="modal-header">
                    <h5 class="modal-title mt-0" id="modalNamaPaket">Detail Paket</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="contentDetailPaket">
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

    <div id="modalKonfirmasiDeletePaket" class="modal fade bs-example-modal-center" tabindex="-1" role="dialog"
        aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <form id="formDeletePaket" action="{{ route('pakets.destroy', '1') }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="modal-header">
                        <h5 id="modalNamaPaketDelete" class="modal-title mt-0">Konfirmasi Penghapusan Paket</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div id="modalBodyHapusPaket" class="modal-body text-center">
                        <h6>Apakah Anda yakin untuk menghapus paket?</h6>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">Batal</button>
                        <button id="btnKonfirmasiHapusPaket" type="submit"
                            class="btn btn-info waves-effect waves-light btnKonfirmasiHapusPaket">Hapus</button>
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
            $('#tabelDaftarPaketAktif').DataTable({
                language: {
                    emptyTable: "Tidak terdapat data paket aktif!",
                }
            });

            $('#tabelDaftarPaketNonaktif').DataTable({
                language: {
                    emptyTable: "Tidak terdapat data paket nonaktif!",
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

        $('body').on('click', ".btnDetailPaket", function() {

            var idPaket = $(this).attr("idPaket");
            var nama = $(this).attr('namaPaket');

            $("#modalNamaPaket").text(" Detail Paket " + nama);
            $.ajax({
                type: 'POST',
                url: '{{ route('admin.pakets.getdetailpaket') }}',
                data: {
                    '_token': '<?php echo csrf_token(); ?>',
                    'idPaket': idPaket,
                },
                success: function(data) {
                    $('#contentDetailPaket').html(data.msg);
                    $('#tabelDaftarPaketProduk').DataTable({});
                    $('#tabelDaftarPaketPerawatan').DataTable({
                        ordering: false,
                    });
                }
            })

        });

        $('.btnHapusPaket').on('click', function() {

            var idPaket = $(this).attr("idPaket");
            var namaPaket = $(this).attr('namaPaket');
            var routeUrl = $(this).attr('routeUrl');
            $("#modalNamaPaketDelete").text("Konfirmasi Penghapusan Paket " + namaPaket);
            $("#modalBodyHapusPaket").html(
                "<h6>Apakah Anda yakin untuk menghapus paket <span class='text-danger'>" + namaPaket +
                "</span>?</h6>")
            $("#formDeletePaket").attr("action", routeUrl);
        });
    </script>
@endsection
