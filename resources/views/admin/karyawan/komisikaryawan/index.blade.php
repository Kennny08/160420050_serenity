@extends('layout.adminlayout')

@section('title', 'Admin || Komisi Karyawan')

@section('admincontent')
    <div class="page-title-box">

    </div>
    <!-- end page-title -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <h3 class="mt-0 header-title">Komisi Karyawan</h3>
                    <p class="sub-title">
                    </p>
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
                        <div class="col-md-5">
                            <label for="exampleInputEmail1"><strong>Tahun Penjualan</strong></label>
                            <select name="tahunPenjualan" id="selectTahunPenjualan" class="form-control"
                                aria-label="Default select example" required>
                                <option selected value="semuaTahun">Semua Tahun</option>
                                @foreach ($distinctTahun as $tahun)
                                    <option value="{{ $tahun->tahun }}">
                                        {{ $tahun->tahun }}
                                    </option>
                                @endforeach

                            </select>
                            <small id="emailHelp" class="form-text text-muted">Pilih tahun penjualan!</small>

                        </div>

                        <div class="col-md-5">
                            <label for="exampleInputEmail1"><strong>Bulan Penjualan</strong></label>
                            <select name="bulanPenjualan" id="selectBulanPenjualan" class="form-control"
                                aria-label="Default select example" required>
                                <option selected value="semuaBulan">Semua Bulan</option>
                                @foreach ($bulans as $bulan)
                                    <option value="{{ $bulan['id'] }}">
                                        {{ $bulan['nama'] }}
                                    </option>
                                @endforeach

                            </select>
                            <small id="emailHelp" class="form-text text-muted">Pilih bulan penjualan disini!</small>
                        </div>

                        <div class="col-md-2 mt-4">
                            <button id="btnCari" style="width: 100%;" type="button"
                                class="btn btn-primary btn-lg waves-effect waves-light">Cari
                            </button>
                            <div hidden bulan="semuaBulan" namaBulan="semuaBulan" id="hiddenBulan"></div>
                            <div hidden tahun="semuaTahun" id="hiddenTahun"></div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-12">
                            <h4 id="judulTableDaftarKomisiKaryawan">Daftar Komisi Karyawan untuk Keseluruhan Penjualan</h4>
                        </div>
                    </div>
                    <table id="tabelDaftarKomisiKaryawan"
                        class="table table-bordered dt-responsive nowrap text-center w-100"
                        style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                            <tr>
                                <th>Nama Karyawan</th>
                                <th>Tanggal Penjualan Awal</th>
                                <th>Tanggal Penjualan Akhir</th>
                                <th>Total Perawatan</th>
                                <th>Total Komisi (Rp)</th>
                                <th>Detail</th>
                            </tr>
                        </thead>

                        <tbody id="tableBodyDaftarKomisiKaryawan">
                            @foreach ($komisiPerKaryawanKeseluruhan as $komisiKaryawan)
                                <tr>
                                    <td>{{ $komisiKaryawan['karyawan']->nama }}</td>
                                    <td>
                                        @if ($komisiKaryawan['jumlah_pelayanan'] == 0)
                                            Belum ada Penjualan yang Selesai
                                        @else
                                            {{ date('d-m-Y', strtotime($komisiKaryawan['tanggal_awal'])) }}
                                        @endif

                                    </td>
                                    <td>
                                        @if ($komisiKaryawan['jumlah_pelayanan'] == 0)
                                            Belum ada Penjualan yang Selesai
                                        @else
                                            {{ date('d-m-Y', strtotime($komisiKaryawan['tanggal_akhir'])) }}
                                        @endif

                                    </td>
                                    <td>{{ $komisiKaryawan['jumlah_pelayanan'] }}</td>
                                    <td>{{ number_format($komisiKaryawan['total_komisi'], 2, ',', '.') }}</td>
                                    <td>
                                        @if ($komisiKaryawan['jumlah_pelayanan'] != 0)
                                            <button data-toggle="modal" data-target="#modalDetailKomisiKaryawan"
                                                idKaryawan ="{{ $komisiKaryawan['karyawan']->id }}"
                                                namaKaryawan = "{{ $komisiKaryawan['karyawan']->nama }}"
                                                class=" btn btn-info waves-effect waves-light btnDetailKomisiKaryawan">Detail
                                            </button>
                                        @else
                                            <button disabled
                                                class=" btn btn-danger waves-effect waves-light btnDetailKomisiKaryawan">Detail
                                            </button>
                                        @endif

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
    <div id="modalDetailKomisiKaryawan" class="modal fade bs-example-modal-center" tabindex="-1" role="dialog"
        aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl" style="max-width: 90%">
            <div class="modal-content ">
                <div class="modal-header">
                    <h5 class="modal-title mt-0" id="modalNamaDetailKomisiKaryawan">Detail Komisi</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="contentDetailKomisiKaryawan">
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
@endsection

@section('javascript')
    <script>
        $(document).ready(function() {
            $('#tabelDaftarKomisiKaryawan').DataTable({
                language: {
                    emptyTable: "Tidak terdapat Daftar Komisi Karyawan",
                    infoEmpty: "Tidak terdapat Daftar Komisi Karyawan",
                }
                
            });
        });

        $("body").on("click", "#btnCari", function() {
            var tahun = $("#selectTahunPenjualan").val();
            var bulan = $("#selectBulanPenjualan").val();
            var bulanNama = $("#selectBulanPenjualan option:selected").text();

            $("#hiddenBulan").attr("bulan", bulan);
            $("#hiddenBulan").attr("namaBulan", bulanNama);
            $("#hiddenTahun").attr("tahun", tahun);

            $('#tableBodyDaftarKomisiKaryawan').html(
                "<tr class='text-center'><td colspan='6'><div class='text-center'>" +
                "<div class='spinner-border text-info' role='status'>" +
                "<span class='sr-only'>Loading...</span>" +
                "</div></div></td></tr>");

            if (tahun == "semuaTahun" || bulan == "semuaBulan") {
                if (tahun == "semuaTahun" && bulan != "semuaBulan") {
                    $("#judulTableDaftarKomisiKaryawan").text("Daftar Komisi Karyawan setiap Bulan " + bulanNama);
                } else if (tahun != "semuaTahun" && bulan == "semuaBulan") {
                    $("#judulTableDaftarKomisiKaryawan").text("Daftar Komisi Karyawan pada Tahun " + tahun);
                } else {
                    $("#judulTableDaftarKomisiKaryawan").text("Daftar Komisi Karyawan untuk Keseluruhan Penjualan");
                }
            } else {
                $("#judulTableDaftarKomisiKaryawan").text("Daftar Komisi Karyawan pada " + bulanNama + " " + tahun);
            }

            $.ajax({
                type: 'POST',
                url: '{{ route('admin.karyawans.proseskomisikaryawan') }}',
                data: {
                    '_token': '<?php echo csrf_token(); ?>',
                    'tahunPenjualan': tahun,
                    'bulanPenjualan': bulan,
                },
                success: function(data) {
                    $('#tableBodyDaftarKomisiKaryawan').html(data.msg);
                }
            })
        });

        $("body").on("click", ".btnDetailKomisiKaryawan", function() {
            var tahun = $("#hiddenTahun").attr("tahun");
            var bulan = $("#hiddenBulan").attr("bulan");
            var bulanNama = $("#hiddenBulan").attr("namaBulan");
            var idKaryawan = $(this).attr("idKaryawan");
            var namaKaryawan = $(this).attr("namaKaryawan");


            $('#contentDetailKomisiKaryawan').html(
                "<div class='text-center'>" +
                "<div class='spinner-border text-info' role='status'>" +
                "<span class='sr-only'>Loading...</span>" +
                "</div></div>");

            if (tahun == "semuaTahun" || bulan == "semuaBulan") {
                if (tahun == "semuaTahun" && bulan != "semuaBulan") {
                    $("#modalNamaDetailKomisiKaryawan").text("Detail Komisi Karyawan " + namaKaryawan +
                        " setiap Bulan " + bulanNama);
                } else if (tahun != "semuaTahun" && bulan == "semuaBulan") {
                    $("#modalNamaDetailKomisiKaryawan").text("Detail Komisi Karyawan " + namaKaryawan +
                        " pada Tahun " + tahun);
                } else {
                    $("#modalNamaDetailKomisiKaryawan").text("Detail Komisi Karyawan " + namaKaryawan +
                        " untuk Keseluruhan Penjualan");
                }
            } else {
                $("#modalNamaDetailKomisiKaryawan").text("Detail Komisi Karyawan " + namaKaryawan + " pada " +
                    bulanNama + " " + tahun);
            }

            $.ajax({
                type: 'POST',
                url: '{{ route('admin.karyawans.detailkomisikaryawan') }}',
                data: {
                    '_token': '<?php echo csrf_token(); ?>',
                    'tahunPenjualan': tahun,
                    'bulanPenjualan': bulan,
                    'idKaryawan': idKaryawan,
                },
                success: function(data) {
                    $('#contentDetailKomisiKaryawan').html(data.msg);
                    $('#tabelDaftarDetailKomisiKaryawan').DataTable({
                        order: [
                            [1, "desc"]
                        ],
                    });
                    // var totalKomisi = 0;
                    // table.column(6, {
                    //     search: 'applied',
                    //     order: 'applied'
                    // }).data().each(function(value) {
                    //     totalKomisi += parseFloat(value.replace(/[^0-9.-]+/g, ''));
                    // });

                    // Tampilkan total komisi pada footer
                    $('.summm').html('Haloo');
                }
            })
        });
    </script>
@endsection
