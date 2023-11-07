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
                                <th class="align-middle">Edit</th>
                            </tr>
                        </thead>

                        <tbody>
                            @if (count($pembeliansBelumBayar) == 0)
                                <tr class="text-center">
                                    <td colspan="9">Tidak ada data pembelian yang sedang berlangsung!</td>
                                </tr>
                            @else
                                @foreach ($pembeliansBelumBayar as $pb)
                                    <tr id="tr_{{ $pb->id }}">
                                        <td>{{ $pb->nomor_nota }}</td>
                                        <td>{{ $pb->supplier->nama }}</td>
                                        <td>{{ date('d-m-Y', strtotime($pb->tanggal_beli)) }}</td>
                                        <td>{{ number_format($pb->total, 0, ',', '.') }}</td>
                                        <td>{{ $pb->karyawan->nama }}</td>
                                        <td hidden>{{ date('d-m-Y', strtotime($pb->created_at)) }}</td>
                                        <td hidden>{{ date('d-m-Y', strtotime($pb->updated_at)) }}</td>
                                        <td class="text-center"><a href="{{ route('pembelians.show', $pb->id) }}"
                                                class=" btn btn-warning waves-effect waves-light btnDetailPembelian">Detail</a>
                                        </td>
                                        <td class="text-center"><a href="{{ route('pembelians.edit', $pb->id) }}"
                                                class=" btn btn-info waves-effect waves-light">Edit</a></td>
                                    </tr>
                                @endforeach
                            @endif

                        </tbody>
                        <tfoot id="grupRiwayatPembelian">

                        </tfoot>
                    </table>
                    <br>
                    <br>
                    <div class="form-group row">
                        <div class="col-md-6">
                            <h4>Riwayat Pembelian Produk</h4>
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
                                <th class="align-middle">Total Pembelian</th>
                                <th class="align-middle">Total Pembayaran</th>
                                <th class="align-middle">Karyawan Penerima</th>
                                <th hidden class="align-middle">Tanggal Pembuatan</th>
                                <th hidden class="align-middle">Tanggal Edit Terakhir</th>
                                <th class="align-middle">Detail</th>
                                <th class="align-middle">Edit</th>
                            </tr>
                        </thead>

                        <tbody>
                            @if (count($pembelians) == 0)
                                <tr class="text-center">
                                    <td colspan="10">Tidak ada data riwayat pembelian!</td>
                                </tr>
                            @else
                                @foreach ($pembelians as $p)
                                    <tr id="tr_{{ $p->id }}">
                                        <td>{{ $p->nomor_nota }}</td>
                                        <td>{{ $p->supplier->nama }}</td>
                                        <td>{{ date('d-m-Y', strtotime($p->tanggal_beli)) }}</td>
                                        <td>{{ date('d-m-Y', strtotime($p->tanggal_bayar)) }}</td>
                                        <td>{{ number_format($p->total, 0, ',', '.') }}</td>
                                        <td>{{ $p->karyawan->nama }}</td>
                                        <td hidden>{{ date('d-m-Y', strtotime($p->created_at)) }}</td>
                                        <td hidden>{{ date('d-m-Y', strtotime($p->updated_at)) }}</td>
                                        <td class="text-center"><a href="{{ route('pembelians.show', $p->id) }}"
                                                class=" btn btn-warning waves-effect waves-light btnDetailPembelian">Detail</a>
                                        </td>
                                        <td class="text-center"><a href="{{ route('pembelians.edit', $p->id) }}"
                                                class=" btn btn-info waves-effect waves-light">Edit</a></td>
                                    </tr>
                                @endforeach
                            @endif

                        </tbody>
                    </table>

                </div>
            </div>
        </div>
        <!-- end col -->
    </div>
@endsection

@section('javascript')
    <script>
        $(document).ready(function() {
            $('#tabelDaftarPembelianBerlangsung').DataTable({
                ordering: false
            });

            $('#tabelDaftarRiwayatPembelian').DataTable({
                ordering: false
            });

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
