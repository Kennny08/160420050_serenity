@extends('layout.adminlayout')

@section('title', 'Admin || Daftar Penjualan')

@section('admincontent')
    <div class="page-title-box">
    </div>
    <!-- end page-title -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <h3 class="mt-0 header-title" id="grupAktif">Daftar Penjualan Hari Ini</h3>
                    <p class="sub-title">
                    </p>
                    {{-- <a class="btn btn-primary" data-toggle="modal" href="{{ route('categories.create') }}">Add Category</a> --}}
                    <a class="btn btn-info waves-effect waves-light" href={{ route('penjualans.admin.create') }}>Buat
                        Penjualan</a><br>
                    <br>
                    @if (session('status'))
                        <div class="alert alert-success alert-dismissible" role="alert">
                            <button type="button" class="close text-success" data-dismiss="alert" aria-label="Close">
                                <span class="text-success" aria-hidden="true">&times;</span>
                            </button>
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="form-group row">
                        <div class="col-md-12">
                            <h4>Daftar Penjualan Hari Ini - {{ $tanggalHariIni }}</h4>
                        </div>
                        
                    </div>

                    <div class="table-responsive">
                        <div>
                            <table id="tabelDaftarPenjualanHariIni"
                                class="tabelDaftarPenjualan table table-bordered dt-responsive nowrap text-center"
                                style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Waktu Pembuatan Penjualan</th>
                                        <th>Status</th>
                                        <th>Pelanggan</th>
                                        <th>No. Nota Penjualan</th>
                                        <th>Detail</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($penjualans as $penjualan)
                                        <tr id="tr_{{ $penjualan->id }}">
                                            <td>{{ $penjualan->id }}</td>
                                            <td>{{ date('H:i:s', strtotime($penjualan->created_at)) }}
                                            </td>
                                            <td>
                                                @if ($penjualan->status_selesai == "batal")
                                                    <span class="badge badge-danger font-16">Dibatalkan </span>
                                                @elseif($penjualan->status_selesai == 'selesai')
                                                    <span class="badge badge-success font-16">Selesai</span>
                                                @else
                                                    <span class="badge badge-warning font-16">Belum Diselesaikan</span>
                                                @endif
                                            </td>
                                            <td>{{ $penjualan->pelanggan->nama }}</td>
                                            <td>{{ $penjualan->nomor_nota }}</td>
                                            <td class="text-center"><a
                                                    href="{{ route('penjualans.admin.detailpenjualan', $penjualan->id) }}"
                                                    class="btn btn-info waves-effect waves-light">Detail</a></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end col -->
    </div>
@endsection

@section('javascript')
    <script>
        $(document).ready(function() {

            $("#tabelDaftarPenjualanHariIni").DataTable({
                order: [
                    [1, "asc"]
                ] ,
                language: {
                    emptyTable: "Tidak terdapat penjualan perawatan untuk hari ini!",
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
    </script>
@endsection
