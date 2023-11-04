@extends('layout.adminlayout')

@section('title', 'Admin || Daftar Reservasi')

@section('admincontent')
    <div class="page-title-box">
    </div>
    <!-- end page-title -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <h3 class="mt-0 header-title">Daftar Reservasi Perawatan</h3>
                    <p class="sub-title">
                    </p>
                    {{-- <a class="btn btn-primary" data-toggle="modal" href="{{ route('categories.create') }}">Add Category</a> --}}
                    <a class="btn btn-info waves-effect waves-light" href={{ route('reservasi.admin.create') }}>Buat
                        Reservasi</a><br>
                    <br>
                    @if (session('status'))
                        <div class="alert alert-success alert-dismissible" role="alert">
                            <button type="button" class="close text-success" data-dismiss="alert" aria-label="Close">
                                <span class="text-success" aria-hidden="true">&times;</span>
                            </button>
                            {{ session('status') }}
                        </div>
                    @endif

                    <table id="tabelDaftarReservasi" class="table table-bordered dt-responsive nowrap text-center"
                        style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tanggal Reservasi</th>
                                <th>Tanggal Pembuatan Reservasi</th>
                                <th>Status</th>
                                <th>Pelanggan</th>
                                <th>No. Nota Penjualan</th>
                                <th>Detail</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($reservasis as $r)
                                <tr id="tr_{{ $r->id }}">
                                    <td>{{ $r->id }}</td>
                                    <td>{{ date('d-m-Y H:i:s', strtotime($r->tanggal_reservasi)) }}</td>
                                    <td>{{ date('d-m-Y H:i:s', strtotime($r->tanggal_pembuatan_reservasi)) }}</td>
                                    <td>
                                        @if ($r->status == 'dibatalkan salon' || $r->status == 'dibatalkan pelanggan')
                                            <span class="badge badge-danger font-16">{{ $r->status }}</span>
                                        @elseif($r->status == 'selesai')
                                            <span class="badge badge-success font-16">{{ $r->status }}</span>
                                        @else
                                            <span class="badge badge-warning font-16">{{ $r->status }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $r->penjualan->pelanggan->nama }}</td>
                                    <td>{{ $r->penjualan->nomor_nota }}</td>
                                    <td class="text-center"><a
                                            href="{{ route('reservasi.admin.detailreservasi', $r->id) }}"
                                            class=" btn btn-info waves-effect waves-light">Detail</a></td>
                                </tr>
                            @endforeach
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
            $('#tabelDaftarReservasi').DataTable({
                "order": [
                    [0, "desc"], // Kolom pertama diurutkan berdasarkan ID terbesar (descending)
                ]
            });
        });
    </script>
@endsection
