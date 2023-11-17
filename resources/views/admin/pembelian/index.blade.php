@extends('layout.adminlayout')

@section('title', 'Admin || Daftar Pembelian')

@section('admincontent')
    <div class="page-title-box">
    </div>
    <!-- end page-title -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <h3 class="mt-0 header-title" id="grupPembelianBerlangsung">Daftar Pembelian Produk</h3>
                    <p class="sub-title">
                    </p>
                    <a class="btn btn-info waves-effect waves-light" href="{{ route('pembelians.create') }}"
                        id="btnTambahPerawatan">
                        Tambah Pembelian
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
                            <h4>Pembelian Berlangsung</h4>
                        </div>
                        <div class="col-md-6 text-right">
                            <div class="btn-group btn-group-toggle border">
                                <a href="#grupPembelianBerlangsung"
                                    class="btn btn-info waves-effect waves-light radioPembelianBerlangsung">
                                    Pembelian Berlangsung
                                </a>
                                <a href="#grupRiwayatPembelian" class="btn waves-effect waves-light radioRiwayatPembelian">
                                    Riwayat Pembelian
                                </a>
                            </div>
                        </div>
                    </div>

                    <table id="tabelDaftarPembelianBerlangsung"
                        class="table table-striped table-bordered dt-responsive wrap text-center"
                        style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                            <tr>
                                <th class="align-middle">Nomor Nota</th>
                                <th class="align-middle">Nama Supplier</th>
                                <th class="align-middle">Tanggal Pembelian</th>
                                <th class="align-middle">Total Pembelian</th>
                                <th class="align-middle">Karyawan Penerima</th>
                                <th hidden class="align-middle">Tanggal Pembuatan</th>
                                <th hidden class="align-middle">Tanggal Edit Terakhir</th>
                                <th class="align-middle">Detail</th>
                                <th class="align-middle">Tanggal Pembayaran</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($pembeliansBelumBayar as $pb)
                                <tr id="tr_{{ $pb->id }}">
                                    <td>{{ $pb->nomor_nota }}</td>
                                    <td>{{ $pb->supplier->nama }}</td>
                                    <td>{{ date('d-m-Y', strtotime($pb->tanggal_beli)) }}</td>
                                    <td>{{ number_format($pb->total, 2, ',', '.') }}</td>
                                    <td>{{ $pb->karyawan->nama }}</td>
                                    <td hidden>{{ date('d-m-Y', strtotime($pb->created_at)) }}</td>
                                    <td hidden>{{ date('d-m-Y', strtotime($pb->updated_at)) }}</td>
                                    <td class="text-center">
                                        <button data-toggle ="modal" data-target="#modalDetailPembelian"
                                            class=" btn btn-info waves-effect waves-light btnDetailPembelian"
                                            idPembelian = "{{ $pb->id }}" namaSupplier="{{ $pb->supplier->nama }}"
                                            nomorNota = "{{ $pb->nomor_nota }}">
                                            Detail
                                        </button>
                                    </td>
                                    <td class="text-center"><button data-toggle="modal"
                                            data-target="#modalPilihTanggalPembayaran" idPembelian="{{ $pb->id }}"
                                            tanggalBeli="{{ date('Y-m-d', strtotime($pb->tanggal_beli)) }}"
                                            nomorNota="{{ $pb->nomor_nota }}" namaSupplier = "{{ $pb->supplier->nama }}"
                                            class="btn btn-warning waves-effect waves-light btnPilihTanggalPembayaran">Pilih
                                            Tanggal</button></td>
                                </tr>
                            @endforeach

                        </tbody>
                        <tfoot id="grupRiwayatPembelian">

                        </tfoot>
                    </table>
                    <br>
                    <br>
                    <div class="form-group row">
                        <div class="col-md-6">
                            <h4>Riwayat Pembelian Stok Produk</h4>
                        </div>
                        <div class="col-md-6 text-right">
                            <div class="btn-group btn-group-toggle border">
                                <a href="#grupPembelianBerlangsung"
                                    class="btn btn-info waves-effect waves-light radioPembelianBerlangsung">
                                    Pembelian Berlangsung
                                </a>
                                <a href="#grupRiwayatPembelian" class="btn waves-effect waves-light radioRiwayatPembelian">
                                    Riwayat Pembelian
                                </a>
                            </div>
                        </div>
                    </div>

                    <table id="tabelDaftarRiwayatPembelian"
                        class="table table-striped table-bordered dt-responsive wrap text-center"
                        style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                            <tr>
                                <th class="align-middle">Nomor Nota</th>
                                <th class="align-middle">Nama Supplier</th>
                                <th class="align-middle">Tanggal Pembelian</th>
                                <th class="align-middle">Tanggal Pembayaran</th>
                                <th class="align-middle">Total Pembayaran</th>
                                <th class="align-middle">Karyawan Penerima</th>
                                <th hidden class="align-middle">Tanggal Pembuatan</th>
                                <th hidden class="align-middle">Tanggal Edit Terakhir</th>
                                <th class="align-middle">Detail</th>
                                {{-- <th class="align-middle">Edit</th> --}}
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($pembelians as $p)
                                <tr id="tr_{{ $p->id }}">
                                    <td>{{ $p->nomor_nota }}</td>
                                    <td>{{ $p->supplier->nama }}</td>
                                    <td>{{ date('d-m-Y', strtotime($p->tanggal_beli)) }}</td>
                                    <td>{{ date('d-m-Y', strtotime($p->tanggal_bayar)) }}</td>
                                    <td>{{ number_format($p->total, 2, ',', '.') }}</td>
                                    <td>{{ $p->karyawan->nama }}</td>
                                    <td hidden>{{ date('d-m-Y', strtotime($p->created_at)) }}</td>
                                    <td hidden>{{ date('d-m-Y', strtotime($p->updated_at)) }}</td>
                                    <td class="text-center">
                                        <button data-toggle ="modal" data-target="#modalDetailPembelian"
                                            class=" btn btn-info waves-effect waves-light btnDetailPembelian"
                                            idPembelian = "{{ $p->id }}" namaSupplier="{{ $p->supplier->nama }}"
                                            nomorNota = "{{ $p->nomor_nota }}">
                                            Detail
                                        </button>
                                    </td>
                                    {{-- <td class="text-center"><a href="{{ route('pembelians.edit', $p->id) }}"
                                                class=" btn btn-info waves-effect waves-light">Edit</a></td> --}}
                                </tr>
                            @endforeach

                        </tbody>
                    </table>

                </div>
            </div>
        </div>
        <!-- end col -->
    </div>

    <div id="modalDetailPembelian" class="modal fade bs-example-modal-center" tabindex="-1" role="dialog"
        aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl" style="max-width: 90%">
            <div class="modal-content ">
                <div class="modal-header">
                    <h5 class="modal-title mt-0" id="modalNamaDetailPembelian">Detail Pembelian</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="contentDetailPembelian">
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

    <div id="modalPilihTanggalPembayaran" class="modal fade bs-example-modal-center" tabindex="-1" role="dialog"
        aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content ">
                <form action="{{ route('admin.pembelians.prosestanggalbayar') }}" method="post">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title mt-0" id="modalNamaPilihTanggalPembayaran">Pilih Tanggal Pembayaran</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" id="contentPilihTanggalPembayaran">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <h6>Silahkan Pilih Tanggal Pembayaran</h6>
                                <input type="hidden" name="hiddenIdPembelian" value="0" id="hiddenIdPembelian">
                            </div>
                            <div class="col-md-12">
                                <input type="date" class="form-control" name="tanggalPembayaran"
                                    id="tanggalPembayaran" value="{{ date('Y-m-d') }}" aria-describedby="emailHelp"
                                    min="{{ date('Y-m-d') }}" placeholder="Silahkan Pilih Tanggal Pembayaran" required>
                                <small id="emailHelp" class="form-text text-muted">Pilih Tanggal Pembayaran
                                    disini!</small>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">Tutup
                        </button>
                        <button type="submit" class="btn btn-info waves-effect" >Konfirmasi
                        </button>
                    </div>
                </form>

            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
@endsection

@section('javascript')
    <script>
        $(document).ready(function() {
            $('#tabelDaftarPembelianBerlangsung').DataTable({
                language: {
                    emptyTable: "Tidak terdapat Daftar Pembelian Berlangsung",
                    infoEmpty: "Tidak terdapat Daftar Pembelian Berlangsung",
                },
                order: [
                    [2, "desc"]
                ]
            });

            $('#tabelDaftarRiwayatPembelian').DataTable({
                language: {
                    emptyTable: "Tidak terdapat Daftar Riwayat Pembelian",
                    infoEmpty: "Tidak terdapat Daftar Riwayat Pembelian",
                },
                order: [
                    [2, "desc"]
                ]
            });

        });

        $("body").on("click", ".btnDetailPembelian", function() {
            var idPembelian = $(this).attr("idPembelian");
            var namaSupplier = $(this).attr("namaSupplier");
            var nomorNota = $(this).attr("nomorNota");

            $("#modalNamaDetailPembelian").text("Detail Pembelian dari " + namaSupplier + " - " + nomorNota);
            $.ajax({
                type: 'POST',
                url: '{{ route('admin.pembelians.getdetailpembelian') }}',
                data: {
                    '_token': '<?php echo csrf_token(); ?>',
                    'idPembelian': idPembelian,
                },
                success: function(data) {
                    $('#contentDetailPembelian').html(data.msg);
                    $('#tabelDetailPembelian').DataTable({});
                }
            })
        });

        $("body").on("click", ".btnPilihTanggalPembayaran", function() {
            var idPembelian = $(this).attr("idPembelian");
            var nomorNota = $(this).attr("nomorNota");
            var namaSupplier = $(this).attr("namaSupplier");
            var tanggalBeli = $(this).attr("tanggalBeli");

            $("#modalNamaPilihTanggalPembayaran").text("Pilih Tanggal Pembayaran " + namaSupplier + " (" +
                nomorNota + ")");

            $("#hiddenIdPembelian").val(idPembelian);
            $("#tanggalPembayaran").attr("min", tanggalBeli);
        });


        $('.radioPembelianBerlangsung').on('click', function() {
            $(".radioPembelianBerlangsung").addClass("btn-info");
            $(".radioRiwayatPembelian").removeClass("btn-info");
        });

        $('.radioRiwayatPembelian').on('click', function() {
            $(".radioRiwayatPembelian").addClass("btn-info");
            $(".radioPembelianBerlangsung").removeClass("btn-info");
        });
    </script>
@endsection
