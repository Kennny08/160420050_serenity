@extends('layout.pelangganlayout')

@section('title', 'Pelanggan || Pilih Diskon untuk Reservasi')

@section('pelanggancontent')
    <div class="page-title-box">
    </div>
    <!-- end page-title -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <h3 class="mt-0 header-title" id="grupAktif">Pemilihan Diskon</h3>
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
                        <div class="col-md-12">
                            <h5>Nomor Nota : <span class="text-danger">{{ $penjualan->nomor_nota }}</span></h5>
                            <h5>Total Pembayaran : <span class="text-danger"> Rp.
                                    {{ number_format($penjualan->total_pembayaran, 2, ',', '.') }}</span>
                            </h5>

                        </div>
                    </div>
                    <br>

                    <div class="billing-info-wrap">
                        <div class="billing-select">
                            <div class="table-responsive table_page">
                                <table id="tabelDaftarDiskonAktifBerlaku"
                                    class="table table-striped table-bordered dt-responsive wrap text-center"
                                    style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                    <thead>
                                        <tr>
                                            <th class="align-middle">Nama</th>
                                            <th class="align-middle">Jumlah Potongan (%)</th>
                                            <th class="align-middle">Minimal Transaksi</th>
                                            <th class="align-middle">Maksimum Potongan</th>
                                            <th class="align-middle">Tanggal Mulai</th>
                                            <th class="align-middle">Tanggal Berakhir</th>
                                            <th class="align-middle">Kode Diskon</th>
                                            <th hidden class="align-middle">Tanggal Pembuatan</th>
                                            <th hidden class="align-middle">Tanggal Terakhir Diedit</th>
                                            <th class="align-middle">Pakai</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($diskonAktifBerlaku as $d)
                                            <tr id="tr_{{ $d->id }}">
                                                <td>{{ $d->nama }}</td>
                                                <td>{{ $d->jumlah_potongan }}</td>
                                                <td>{{ number_format($d->minimal_transaksi, 2, ',', '.') }}</td>
                                                <td>{{ number_format($d->maksimum_potongan, 2, ',', '.') }}</td>
                                                <td>{{ date('d-m-Y', strtotime($d->tanggal_mulai)) }}</td>
                                                <td>{{ date('d-m-Y', strtotime($d->tanggal_berakhir)) }}</td>
                                                <td>{{ $d->kode_diskon }}</td>
                                                <td hidden>{{ date('d-m-Y', strtotime($d->created_at)) }}</td>
                                                <td hidden>{{ date('d-m-Y', strtotime($d->updated_at)) }}</td>
                                                <td class="text-center product-wishlist-cart">
                                                    <button data-bs-toggle="modal" data-bs-target="#modalPakaiDiskon"
                                                        style='width:100%;height:30px; border-radius:4px;font-weight: normal; padding-right: 10px; padding-left: 10px; background-color: #273ED4;'
                                                        idDiskon="{{ $d->id }}" namaDiskon ="{{ $d->nama }}"
                                                        jumlahPotongan = "{{ $d->jumlah_potongan }}"
                                                        maksimumDiskon="{{ $d->maksimum_potongan }}"
                                                        class=" btn btn-info waves-effect waves-light btnPakaiDiskon text-white">Pakai</button>
                                                </td>
                                            </tr>
                                        @endforeach

                                    </tbody>
                                    <tfoot id="grupNonaktif">

                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                    <br>



                    <div class="form-group row">
                        <div class="col-md-2">
                            <div class="product-details-content quickview-content">
                                <div class="pro-details-quality" style="padding: 0px; margin: 0px;">
                                    <div class="pro-details-cart" style="width: 100%; display: flex; align-items: center;">
                                        <a class="add-cart" style="background-color: #273ED4;"
                                            href={{ route('reservasis.pelanggan.detailreservasi', $penjualan->reservasi->id) }}><span><i
                                                    class="fa fa-arrow-left" aria-hidden="true"></i>
                                                &nbsp;Detail Reservasi</span>
                                        </a>
                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end col -->
    </div>

    <div id="modalPakaiDiskon" class="modal fade bs-example-modal-center" tabindex="-1" role="dialog"
        aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content ">
                <form action="{{ route('diskons.pelanggan.prosespemakaiandiskon') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h4 class="modal-title mt-0" id="modalNamaDiskon">Konfirmasi Pemakaian Diskon</h4>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <input name="idPenjualan" type="hidden" value="{{ $penjualan->id }}" id="hiddenIdPenjualan"
                            totalPembayaran="{{ $penjualan->total_pembayaran }}">
                        <input type="hidden" name="idDiskon" value="" id="hiddenIdDiskon">
                        <div class="text-center" id="contentPakaiDiskon">

                        </div>


                    </div>
                    <div class="modal-footer">
                        <div class="product-details-content quickview-content">
                            <div class="pro-details-quality" style="padding: 0px;margin: 0px;">
                                <div class="pro-details-cart ml-auto" style="width: 100%;">
                                    <button type="button" data-bs-dismiss="modal" class="btn close add-cart "
                                        style="margin: 0px; width: 100%; ">Tutup</button>

                                </div>
                            </div>
                        </div>
                        <div class="product-details-content quickview-content">
                            <div class="pro-details-quality" style="padding: 0px;margin: 0px;">
                                <div class="pro-details-cart ml-auto" style="width: 100%;">
                                    <button type="submit" class="add-cart "
                                        style="margin: 0px; width: 100%; background-color: #273ED4;">Pakai</button>

                                </div>
                            </div>
                        </div>
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
            $('#tabelDaftarDiskonAktifBerlaku').DataTable({
                language: {
                    emptyTable: "Tidak terdapat data diskon yang sedang berlaku!",
                }
            });
        });

        $('.btnPakaiDiskon').on('click', function() {
            var idDiskon = $(this).attr("idDiskon");
            var nama = $(this).attr('namaDiskon');
            var jumlahPotongan = $(this).attr('jumlahPotongan');
            var maksimumPotongan = parseInt($(this).attr('maksimumDiskon'));
            var idPenjualan = $("#hiddenIdPenjualan").val();
            var totalPembayaran = parseInt($("#hiddenIdPenjualan").attr("totalPembayaran"));
            $("#hiddenIdDiskon").val(idDiskon);
            $("#modalNamaDiskon").text(" Detail Diskon " + nama);

            var totalPotongan = (jumlahPotongan * totalPembayaran) / 100;
            if (totalPotongan >= maksimumPotongan) {
                totalPotongan = maksimumPotongan;
            }

            $("#contentPakaiDiskon").html(
                "<h5>Apakah Anda yakin untuk menggunakan diskon <span class='text-danger'>" + nama +
                "</span>, dengan potongan sebesar <span class='text-danger'>" + jumlahPotongan +
                "%</span> berjumlah <span class='text-danger'>" + totalPotongan.toLocaleString('id-ID', {
                    style: 'currency',
                    currency: 'IDR'
                }) +
                "</span>?</h5><br><p class='text-danger'>*Diskon yang telah dipakai tidak bisa diubah</p>");
        });
    </script>
@endsection
