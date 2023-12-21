@extends('layout.adminlayout')

@section('title', 'Karyawan || Daftar Reservasi')

@section('admincontent')
    <div class="page-title-box">
    </div>
    <!-- end page-title -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <h3 class="mt-0 header-title" id="grupAktif">Daftar Reservasi Perawatan</h3>
                    <p class="sub-title">
                    </p>
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
                        <div class="col-md-6">
                            <h4>Daftar Reservasi Hari Ini</h4>
                        </div>
                        <div class="col-md-6 text-right">
                            <div class="btn-group btn-group-toggle border">
                                <a href="#grupAktif" class="btn btn-info waves-effect waves-light radioAktif">
                                    Reservasi Hari Ini
                                </a>
                                <a href="#grupNonaktif" class="btn waves-effect waves-light radioNonaktif">
                                    Reservasi Akan Datang
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <div>
                            <table id="tabelDaftarReservasiHariIni"
                                class="tabelDaftarReservasi table table-bordered dt-responsive nowrap text-center"
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
                                    @foreach ($reservasis as $r)
                                        @foreach ($r->penjualan->penjualanperawatans as $pp)
                                            @if ($pp->karyawan_id == Auth::user()->karyawan->id)
                                                <tr>
                                                    <td>{{ $r->id }}</td>
                                                    <td>{{ $r->penjualan->nomor_nota }}</td>
                                                    <td>
                                                        @if ($r->status == 'dibatalkan salon' || $r->status == 'dibatalkan pelanggan')
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
                                                    <td>{{ $r->penjualan->pelanggan->nama }}</td>
                                                    <td>{{ $pp->perawatan->nama }}</td>
                                                    <td>{{ $pp->slotjams->count() * 30 }}</td>
                                                    <td>
                                                        @php
                                                            $idMin = $pp->slotjams->min('id');
                                                        @endphp
                                                        {{ $pp->slotjams->firstWhere('id', $idMin)->jam }}
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <br>
                    <br>
                    <div class="form-group row">
                        <div class="col-md-6">
                            <h4>Daftar Reservasi Akan Datang</h4>
                        </div>
                        <div class="col-md-6 text-right">
                            <div class="btn-group btn-group-toggle border">
                                <a href="#grupAktif" class="btn btn-info waves-effect waves-light radioAktif">
                                    Reservasi Hari Ini
                                </a>
                                <a href="#grupNonaktif" class="btn waves-effect waves-light radioNonaktif">
                                    Reservasi Akan Datang
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <div>
                            <table id="tabelDaftarReservasiAkanDatang"
                                class="tabelDaftarReservasi table table-bordered dt-responsive nowrap text-center"
                                style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead>
                                    <tr>
                                        <th>Hari</th>
                                        <th>Tanggal Reservasi</th>
                                        <th>Total Reservasi</th>
                                        <th>Total Pelayanan</th>
                                        <th>Detail</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($reservasisAkanDatang as $r)
                                        <tr>
                                            <td>{{ $r['hari_reservasi'] }}</td>
                                            <td>{{ date('d-m-Y', strtotime($r['tanggal_reservasi'])) }}</td>
                                            <td>{{ $r['total_reservasi'] }}</td>
                                            <td>{{ $r['total_pelayanan'] }}</td>
                                            <td class="text-center"><button data-toggle = "modal"
                                                    data-target = "#modalDetailReservasiPerTanggal"
                                                    tanggalReservasi = "{{ date('Y-m-d', strtotime($r['tanggal_reservasi'])) }}"
                                                    hariTanggalReservasi = "{{ $r['hari_reservasi'] }}, {{ date('d-m-Y', strtotime($r['tanggal_reservasi'])) }}"
                                                    class=" btn btn-info waves-effect waves-light btnDetailReservasiPerTanggal">Detail</button>
                                            </td>
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
    <div id="modalDetailReservasiPerTanggal" class="modal fade bs-example-modal-center" tabindex="-1" role="dialog"
        aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl" style="max-width: 90%;">
            <div class="modal-content ">
                <div class="modal-header">
                    <h5 class="modal-title mt-0" id="modalNamaDetailReservasiPerTanggal">Detail Riwayat Presensi</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="contentDetailReservasiPerTanggal">
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
@endsection

@section('javascript')
    <script>
        $(document).ready(function() {

            $("#tabelDaftarReservasiHariIni").DataTable({
                order: [
                    [6, "asc"],
                ],
                language: {
                    emptyTable: "Tidak terdapat reservasi perawatan untuk hari ini!",
                }
            });

            $("#tabelDaftarReservasiAkanDatang").DataTable({
                order: [
                    [1, "asc"],
                ],
                language: {
                    emptyTable: "Tidak terdapat reservasi perawatan untuk hari yang akan datang!",
                }
            });

        });

        $('body').on('click', '.btnDetailReservasiPerTanggal', function() {
            var tanggalReservasi = $(this).attr("tanggalReservasi");
            var hariTanggalReservasi = $(this).attr("hariTanggalReservasi");

            $("#modalNamaDetailReservasiPerTanggal").text(" Daftar Reservasi untuk " + hariTanggalReservasi);

            $('#contentDetailReservasiPerTanggal').html("<div class='text-center'>" +
                "<div class='spinner-border text-info' role='status'>" +
                "<span class='sr-only'>Loading...</span>" +
                "</div></div>");

            $.ajax({
                type: 'POST',
                url: '{{ route('karyawans.detailreservasiperhari') }}',
                data: {
                    '_token': '<?php echo csrf_token(); ?>',
                    'tanggalReservasi': tanggalReservasi,
                },
                success: function(data) {
                    $('#contentDetailReservasiPerTanggal').html(data.msg);
                    $('#tabelDetailReservasiPerTanggal').DataTable({
                        order: [
                            [6, "asc"],
                        ],

                    });
                }
            })
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
