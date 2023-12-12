@extends('layout.pelangganlayout')

@section('title', 'Pelanggan || Riwayat Reservasi')

@section('pelanggancontent')
    <div class="page-title-box">
    </div>
    <!-- end page-title -->

    <div class="row" style="padding: 20px;">
        <div class="col-12">
            <div>
                <div class="card-body">

                    <h3 class="mt-0 header-title" id="grupAktif">Daftar Reservasi Perawatan</h3>
                    <p class="sub-title">
                    </p>
                    @if (session('status'))
                        <div class="alert alert-success alert-dismissible" role="alert">
                            <button type="button" class="close text-success" data-dismiss="alert" aria-label="Close">
                                <span class="text-success" aria-hidden="true">&times;</span>
                            </button>
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="billing-info-wrap">
                        <div class="billing-select">
                            <div class="table-responsive table_page">
                                <div>
                                    <table id="tabelDaftarReservasiHariIni"
                                        class="tabelDaftarReservasi table table-bordered dt-responsive nowrap text-center"
                                        style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th hidden>tanggalHidden</th>
                                                <th>Tanggal Pembuatan Reservasi</th>
                                                <th>Tanggal Reservasi</th>
                                                <th>Jam Mulai</th>
                                                <th>Status</th>
                                                <th>No. Nota Penjualan</th>
                                                <th>Detail</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @foreach ($reservasis as $r)
                                                <tr id="tr_{{ $r->id }}">
                                                    <td>{{ $r->id }}</td>
                                                    <td hidden> {{ date('Y-m-d', strtotime($r->tanggal_reservasi)) }}</td>
                                                    <td>
                                                        {{ date('d-m-Y H:i:s', strtotime($r->tanggal_pembuatan_reservasi)) }}
                                                    </td>
                                                    <td>{{ date('d-m-Y', strtotime($r->tanggal_reservasi)) }}</td>

                                                    <td>
                                                        {{ $r->penjualan->penjualanperawatans()->orderBy('id')->first()->slotjams()->orderBy('slot_jam_id')->first()->jam }}
                                                    </td>
                                                    <td>
                                                        @if ($r->status == 'dibatalkan salon' || $r->status == 'dibatalkan pelanggan' || $r->status == 'tidak hadir')
                                                            <span
                                                                class="text-danger font-16">{{ $r->status }}</span>
                                                        @elseif($r->status == 'selesai')
                                                            <span
                                                                class="text-success font-16">{{ $r->status }}</span>
                                                        @else
                                                            <span
                                                                class="text-warning font-16">{{ $r->status }}</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $r->penjualan->nomor_nota }}</td>
                                                    <td class="text-center product-wishlist-cart"><a
                                                        style='width:100%;height:30px; border-radius:3px;font-weight: normal; padding-right: 10px; padding-left: 10px; background-color: #273ED4'
                                                            href="{{ route('reservasis.pelanggan.detailreservasi', $r->id) }}"
                                                            class="btn waves-effect waves-light text-white">Detail</a></td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
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

            $("#tabelDaftarReservasiHariIni").DataTable({
                order: [
                    [1, "desc"],
                    [4, "asc"],
                ],
                language: {
                    emptyTable: "Tidak terdapat reservasi perawatan untuk hari ini!",
                }
            });

        });
    </script>
@endsection
