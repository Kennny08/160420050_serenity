@extends('layout.adminlayout')

@section('title', 'Admin || Daftar Perawatan')

@section('admincontent')
    <div class="page-title-box">
    </div>
    <!-- end page-title -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <h3 class="mt-0 header-title" id="grupAktif">Daftar Perawatan</h3>
                    <p class="sub-title">
                    </p>
                    @if (Auth::user()->role == 'admin' || Auth::user()->karyawan->jenis_karyawan == 'admin')
                        <a class="btn btn-info waves-effect waves-light" href="{{ route('perawatans.create') }}"
                            id="btnTambahPerawatan">
                            Tambah Perawatan
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
                            <h4>Perawatan Aktif</h4>
                        </div>
                        <div class="col-md-6 text-right">
                            <div class="btn-group btn-group-toggle border">
                                <a href="#grupAktif" class="btn btn-info waves-effect waves-light radioAktif">
                                    Perawatan Aktif
                                </a>
                                <a href="#grupNonaktif" class="btn waves-effect waves-light radioNonaktif">
                                    Perawatan Nonaktif
                                </a>
                            </div>
                        </div>
                    </div>

                    <table id="tabelDaftarPerawatanAktif"
                        class="table table-striped table-bordered dt-responsive wrap text-center"
                        style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                            <tr>
                                <th class="align-middle">Kode Perawatan</th>
                                <th class="align-middle">Nama</th>
                                <th class="align-middle">Harga(Rp)</th>
                                <th class="align-middle">Durasi (menit)</th>
                                <th class="align-middle">Persentase Komisi(%)</th>
                                <th class="align-middle">Status Dikerjakan Bersama</th>
                                <th hidden class="align-middle">Deskripsi</th>
                                <th hidden class="align-middle">Tanggal Pembuatan</th>
                                <th hidden class="align-middle">Tanggal Edit Terakhir</th>
                                <th class="align-middle">Detail</th>
                                @if (Auth::user()->role == 'admin' || Auth::user()->karyawan->jenis_karyawan == 'admin')
                                    <th class="align-middle">Edit</th>
                                    <th class="align-middle">Hapus</th>
                                @endif
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($perawatansAktif as $pa)
                                <tr id="tr_{{ $pa->id }}">
                                    <td>{{ $pa->kode_perawatan }}</td>
                                    <td>{{ $pa->nama }}</td>
                                    <td>{{ number_format($pa->harga, 2, ',', '.') }}</td>
                                    <td>{{ $pa->durasi }}</td>
                                    <td>{{ $pa->komisi }}</td>
                                    <td>{{ $pa->status_komplemen }}</td>
                                    <td hidden>{{ $pa->deskripsi }}</td>
                                    <td hidden>{{ date('d-m-Y', strtotime($pa->created_at)) }}</td>
                                    <td hidden>{{ date('d-m-Y', strtotime($pa->updated_at)) }}</td>
                                    <td class="text-center"><button data-toggle="modal" data-target="#modalDetailPerawatan"
                                            idPerawatan="{{ $pa->id }}" namaPerawatan ="{{ $pa->nama }}"
                                            class=" btn btn-warning waves-effect waves-light btnDetailPerawatan">Detail</button>
                                    </td>
                                    @if (Auth::user()->role == 'admin' || Auth::user()->karyawan->jenis_karyawan == 'admin')
                                        <td class="text-center"><a href="{{ route('perawatans.edit', $pa->id) }}"
                                                class=" btn btn-info waves-effect waves-light">Edit</a></td>
                                        <td class="text-center"><button data-toggle="modal"
                                                data-target="#modalKonfirmasiDeletePerawatan"
                                                idPerawatan = "{{ $pa->id }}" namaPerawatan="{{ $pa->nama }}"
                                                routeUrl = "{{ route('perawatans.destroy', $pa->id) }}"
                                                class=" btn btn-danger waves-effect waves-light btnHapusPerawatan">Hapus</button>
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
                            <h4>Perawatan Nonaktif</h4>
                        </div>
                        <div class="col-md-6 text-right">
                            <div class="btn-group btn-group-toggle border">
                                <a href="#grupAktif" class="btn btn-info waves-effect waves-light radioAktif">
                                    Perawatan Aktif
                                </a>
                                <a href="#grupNonaktif" class="btn waves-effect waves-light radioNonaktif">
                                    Perawatan Nonaktif
                                </a>
                            </div>
                        </div>
                    </div>

                    <table id="tabelDaftarPerawatanNonaktif"
                        class="table table-striped table-bordered dt-responsive wrap text-center"
                        style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                            <tr>
                                <th class="align-middle">Kode Perawatan</th>
                                <th class="align-middle">Nama</th>
                                <th class="align-middle">Harga(Rp)</th>
                                <th class="align-middle">Durasi (menit)</th>
                                <th class="align-middle">Persentase Komisi(%)</th>
                                <th class="align-middle">Status Dikerjakan Bersama</th>
                                <th hidden class="align-middle">Deskripsi</th>
                                <th hidden class="align-middle">Tanggal Pembuatan</th>
                                <th hidden class="align-middle">Tanggal Edit Terakhir</th>
                                <th class="align-middle">Detail</th>
                                @if (Auth::user()->role == 'admin' || Auth::user()->karyawan->jenis_karyawan == 'admin')
                                    <th class="align-middle">Edit</th>
                                    <th class="align-middle">Hapus</th>
                                @endif
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($perawatansNonaktif as $pn)
                                <tr id="tr_{{ $pn->id }}">
                                    <td>{{ $pn->kode_perawatan }}</td>
                                    <td>{{ $pn->nama }}</td>
                                    <td>{{ number_format($pn->harga, 2, ',', '.') }}</td>
                                    <td>{{ $pn->durasi }}</td>
                                    <td>{{ $pn->komisi }}</td>
                                    <td>{{ $pn->status_komplemen }}</td>
                                    <td hidden>{{ $pn->deskripsi }}</td>
                                    <td hidden>{{ date('d-m-Y', strtotime($pn->created_at)) }}</td>
                                    <td hidden>{{ date('d-m-Y', strtotime($pn->updated_at)) }}</td>
                                    <td class="text-center"><button data-toggle="modal" data-target="#modalDetailPerawatan"
                                            idPerawatan="{{ $pn->id }}" namaPerawatan ="{{ $pn->nama }}"
                                            class=" btn btn-warning waves-effect waves-light btnDetailPerawatan">Detail</button>
                                    </td>
                                    @if (Auth::user()->role == 'admin' || Auth::user()->karyawan->jenis_karyawan == 'admin')
                                        <td class="text-center"><a href="{{ route('perawatans.edit', $pn->id) }}"
                                                class=" btn btn-info waves-effect waves-light">Edit</a></td>
                                        <td class="text-center"><button data-toggle="modal"
                                                data-target="#modalKonfirmasiDeletePerawatan"
                                                idPerawatan = "{{ $pn->id }}" namaPerawatan="{{ $pn->nama }}"
                                                routeUrl = "{{ route('perawatans.destroy', $pn->id) }}"
                                                class=" btn btn-danger waves-effect waves-light btnHapusPerawatan">Hapus</button>
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

    <div id="modalDetailPerawatan" class="modal fade bs-example-modal-center" tabindex="-1" role="dialog"
        aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" style="max-width: 600px;">
            <div class="modal-content ">
                <div class="modal-header">
                    <h5 class="modal-title mt-0" id="modalNamaPerawatan">Detail Perawatan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="contentDetailPerawatan">
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

    <div id="modalKonfirmasiDeletePerawatan" class="modal fade bs-example-modal-center" tabindex="-1" role="dialog"
        aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <form id="formDeletePerawatan" action="{{ route('perawatans.destroy', '1') }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="modal-header">
                        <h5 id="modalNamaPerawatanDelete" class="modal-title mt-0">Konfirmasi Penghapusan Perawatan</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div id="modalBodyHapusPerawatan" class="modal-body text-center">
                        <h6>Apakah Anda yakin untuk menghapus perawatan?</h6>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">Batal</button>
                        <button id="btnKonfirmasiHapusPerawatan" type="submit"
                            class="btn btn-info waves-effect waves-light btnKonfirmasiHapusPerawatan">Hapus</button>
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
            $('#tabelDaftarPerawatanAktif').DataTable({
                language: {
                    emptyTable: "Tidak terdapat data perawatan aktif!",
                    infoEmpty: "Tidak terdapat data perawatan aktif!",
                }
            });

            $('#tabelDaftarPerawatanNonaktif').DataTable({
                language: {
                    emptyTable: "Tidak terdapat data perawatan nonaktif!",
                    infoEmpty: "Tidak terdapat data perawatan nonaktif!",
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

        $('.btnDetailPerawatan').on('click', function() {

            var idPerawatan = $(this).attr("idPerawatan");
            var nama = $(this).attr('namaPerawatan');
            $("#modalNamaPerawatan").text(" Detail Perawatan " + nama);
            $.ajax({
                type: 'POST',
                url: '{{ route('admin.getdetailperawatan') }}',
                data: {
                    '_token': '<?php echo csrf_token(); ?>',
                    'idPerawatan': idPerawatan,
                },
                success: function(data) {
                    $('#contentDetailPerawatan').html(data.msg);
                    $('#tabelDaftarPerawatanProduk').DataTable({});
                    $('#tabelDaftarPerawatanKaryawan').DataTable({
                        order: [
                            [1, "desc"]
                        ]
                    });
                }
            })

        });

        $('.btnHapusPerawatan').on('click', function() {

            var idPerawatan = $(this).attr("idPerawatan");
            var namaPerawatan = $(this).attr('namaPerawatan');
            var routeUrl = $(this).attr('routeUrl');
            $("#modalNamaPerawatanDelete").text("Konfirmasi Penghapusan Perawatan " + namaPerawatan);
            $("#modalBodyHapusPerawatan").html(
                "<h6>Apakah Anda yakin untuk menghapus perawatan <span class='text-danger'>" + namaPerawatan +
                "</span>?</h6>")
            $("#formDeletePerawatan").attr("action", routeUrl);
        });
    </script>
@endsection
