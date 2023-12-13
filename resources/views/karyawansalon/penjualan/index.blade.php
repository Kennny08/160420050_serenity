@extends('layout.adminlayout')

@section('title', 'Karyawan || Daftar Penjualan')

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
                                        <th>No. Nota Penjualan</th>
                                        <th>Status</th>
                                        <th>Pelanggan</th>
                                        <th>Perawatan</th>
                                        <th>Durasi (Menit)</th>
                                        <th>Jam Pelayanan</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($penjualans as $penjualan)
                                        @foreach ($penjualan->penjualanperawatans as $pp)
                                            @if (Auth::user()->karyawan->id == $pp->karyawan_id)
                                                <tr id="tr_{{ $penjualan->id }}">
                                                    <td>{{ $penjualan->id }}</td>
                                                    <td>
                                                        {{ $penjualan->nomor_nota }}
                                                    </td>

                                                    <td>
                                                        @if ($penjualan->status_selesai == 'batal')
                                                            <span class="text-danger font-16">Dibatalkan </span>
                                                        @elseif($penjualan->status_selesai == 'selesai')
                                                            <span class="text-success font-16">Selesai</span>
                                                        @else
                                                            <span class="text-warning font-16">Belum Diselesaikan</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $penjualan->pelanggan->nama }}</td>
                                                    <td>{{ $pp->perawatan->nama }}</td>
                                                    <td>{{ count($pp->slotjams) * 30 }}</td>
                                                    <td>
                                                        {{ $penjualan->penjualanperawatans()->orderBy('id')->first()->slotjams()->orderBy('slot_jam_id')->first()->jam }}
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
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
                    [2, "asc"]
                ],
                language: {
                    emptyTable: "Tidak terdapat penjualan perawatan untuk hari ini!",
                }
            });


        });
    </script>
@endsection
