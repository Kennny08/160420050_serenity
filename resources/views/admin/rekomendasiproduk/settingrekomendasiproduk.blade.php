@extends('layout.adminlayout')

@section('title', 'Admin || Setting Rekomendasi Produk')

@section('admincontent')
    <div class="page-title-box">

    </div>
    <!-- end page-title -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <h3 class="mt-0 header-title">Rekomendasi Produk</h3>
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

                    <form method="POST" action="{{ route('admin.prosesrekomendasiproduk') }}"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="form-group col-md-12">
                            {{-- <h3>Tanggal Pembuatan : {{ $tanggalHariIni }}</h3> --}}
                        </div>

                        @if ($keterangan == 'Berhasil')
                            <div class="form-group row">
                                <div class="col-md-6">
                                    <label for="exampleInputEmail1"><strong>Tanggal Mulai Riwayat Penjualan</strong></label>
                                    <input type="date" class="form-control" name="tanggalMulai"
                                        min="{{ $batasTanggalMulai }}" max="{{ $batasTanggalAkhir }}"
                                        id="tanggalMulaiPenjualan" aria-describedby="emailHelp"
                                        placeholder="Silahkan Pilih Tanggal Reservasi" required
                                        value="{{ date('Y-m-d', strtotime($tanggalMulai)) }}">
                                    <small id="emailHelp" class="form-text text-muted">Pilih Tanggal Mulai Riwayat
                                        Penjualan Anda
                                        disini!</small>

                                </div>

                                <div class="col-md-6">
                                    <label for="exampleInputEmail1"><strong>Tanggal Akhir Riwayat Penjualan</strong></label>
                                    <input type="date" class="form-control" name="tanggalAkhir"
                                        min="{{ $batasTanggalMulai }}" max="{{ $batasTanggalAkhir }}"
                                        id="tanggalAkhirPenjualan" aria-describedby="emailHelp"
                                        placeholder="Silahkan Pilih Tanggal Reservasi" required value="{{ $tanggalAkhir }}">
                                    <small id="emailHelp" class="form-text text-muted">Pilih Tanggal Akhir Riwayat Penjualan
                                        Anda
                                        disini!</small>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-6">
                                    <label for="exampleInputEmail1"><strong>Minimum Support (min 1)</strong></label>
                                    <input type="number" class="form-control" name="minSupport" min="1"
                                        id="txtMinimumSupport" aria-describedby="emailHelp" value="{{ $minSupport }}"
                                        placeholder="nilai support" required>
                                    <small id="emailHelp" class="form-text text-muted">Masukkan nilai Minimum Support
                                        disini!</small>

                                </div>

                                <div class="col-md-6">
                                    <label for="exampleInputEmail1"><strong>Minimum Confidence(%)</strong></label>
                                    <input type="number" class="form-control" name="minConfidence" min="1"
                                        max="100" id="txtMinimumConfidence" aria-describedby="emailHelp"
                                        value="{{ $minConfidence }}" placeholder="minimum confidence" required>
                                    <small id="emailHelp" class="form-text text-muted">Masukkan nilai Minimum Confidence
                                        disini!</small>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <button type="button" id="InfoRekomendasiProduk" data-toggle="modal"
                                        data-target="#modalInformasiRekomendasiProduk"
                                        class="btn btn-info btn-lg waves-effect waves-light"><i
                                            class="mdi mdi-information-outline"></i>&nbsp; Informasi Pemakaian
                                    </button>
                                    <button type="button" id="infoDetailPenjualan" data-toggle="modal"
                                        data-target="#modalInfoDetailPenjualan"
                                        class="btn btn-warning btn-lg waves-effect waves-light ml-2"><i
                                            class="mdi mdi-playlist-edit"></i>&nbsp; Detail Penjualan
                                    </button>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group text-right">
                                        <button id="tesbutton" type="submit"
                                            class="btn btn-primary btn-lg waves-effect waves-light">Konfirmasi</button>
                                    </div>
                                </div>
                            </div>
                            <br>


                            @if (isset($freqItemSets) && isset($assocRules))
                                <div class="card shadow">
                                    <div class="card-body">
                                        <div class="form-group row text-center mt-3">
                                            <div class="form-group col-md-12">
                                                <h5>{{ $titleTabel }}</h5>
                                                <h4 class="text-danger">(Hasil diurutkan dari yang punya peluang paling
                                                    besar)</h4>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="form-group col-md-12 table-responsive">
                                                {{-- <table class="table table-bordered">
                                                    <thead class="thead-default">
                                                        <tr class="text-center">
                                                            <th>Pasangan Perawatan atau Produk beserta Jumlah Kemunculannya</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($freqItemSets as $fis)
                                                            <tr>
                                                                <td>{{ $fis }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                                <br> --}}
                                                <table id="tableHasilAssociationRules"
                                                    class="table table-bordered dt-responsive nowrap text-center w-100"
                                                    style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                                    <thead class="thead-default">
                                                        <tr class="text-center">
                                                            <th>Item yang dipromosikan</th>
                                                            <th>Pasangan Item untuk Promosi</th>
                                                            <th hidden>Confidence </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($assocRules as $ar => $ar1)
                                                            @foreach ($ar1 as $ar2 => $ar3)
                                                                <tr>
                                                                    <td>
                                                                        <span
                                                                            style="font-size: 15px">{!! implode(',<br>', explode(',', $ar)) !!}
                                                                        </span>
                                                                    </td>
                                                                    <td>
                                                                        <span style="font-size: 15px">
                                                                            {!! implode(',<br>', explode(',', $ar2)) !!}
                                                                        </span>

                                                                    </td>

                                                                    </td>
                                                                    <td hidden>
                                                                        {{ $ar3 }}
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>


                                </div>
                            @endif
                        @else
                            <div class="col-xl-12">
                                <div class="card text-white bg-danger text-center">
                                    <div class="card-body">
                                        <blockquote class="card-bodyquote mb-0">
                                            <h2>Belum terdapat penjualan yang memiliki status selesai</h2>
                                            <footer class=" text-white font-12">
                                                <h6>Silahkan menunggu hingga terdapat penjualan atau reservasi yang telah
                                                    diselesaikan!</h6>
                                            </footer>
                                        </blockquote>
                                    </div>
                                </div>
                            </div>
                        @endif

                    </form>


                </div>
            </div>
        </div>
        <!-- end col -->
    </div>

    <div id="modalInformasiRekomendasiProduk" class="modal fade bs-example-modal-center" tabindex="-1" role="dialog"
        aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg" style="max-width: 800px;">
            <div class="modal-content ">
                <div class="modal-header">
                    <h5 class="modal-title mt-0" id="modalInformasiRekomendasiProduk">Informasi Rekomendasi Produk</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body overflow-auto">
                    <div class="form-group row">
                        <div class="col-md-12">

                            <h5 class="font-weight-normal text-dark">Berikut ini beberapa informasi terkait penggunaan
                                fitur Rekomendasi Produk:</h5>
                            <div>
                                <h5>Tujuan Pembuatan Fitur</h5>
                                <h6 class="font-weight-normal text-dark" style="text-align: justify; color: black">
                                    Fitur ini dapat diamnfaatkan oleh pihak salon untuk mendapatkan rekomendasi produk item
                                    penjualan pada salon seperti produk, perawwatan, atau paket berdasarkan pola transaksi
                                    customer dalam rentang waktu yang dipilih. Selain itu hasil dari fitur ini dapat
                                    digunakan sebagai pelauang untuk mempertimbangkan pembuatan promo bundling atau paket
                                    pada penjualan salon.
                                </h6>
                                <br>
                                <h5>Support</h5>
                                <h6 class="font-weight-normal text-dark" style="text-align: justify; color: black">
                                    * Support adalah jumlah minimum item-item penjualan (paket, perawatan, atau produk)
                                    yang harus terjual dalam rentang waktu
                                    tertentu.
                                    {{-- Nilai ini digunakan untuk melihat seberapa sering item penjualan tersebut
                                    dibeli oleh
                                    pelanggan selama periode waktu yang Anda tentukan. --}}
                                    <br>
                                    * Misalnya, jika Anda menetapkan Support sebesar 8, maka hanya akan dipertimbangkan
                                    item penjualan tersebut yang terjual setidaknya 8 kali dalam periode waktu yang Anda
                                    tentukan.
                                </h6>
                                <br>

                                <h5>Confidence</h5>
                                <h6 class="font-weight-normal text-dark" style="text-align: justify">
                                    * Confidence digunakan untuk seberapa sering item penjualan A (paket, perawatan, atau
                                    produk) dibeli bersamaan dengan item penjualan B.
                                    {{-- Nilai ini membantu untuk mengukur
                                    kuatnya hubungan
                                    pelanggan cenderung membeli dua atau lebih item penjualan secara bersamaan. --}}
                                    <br>
                                    * Contohnya, jika kita menetapkan Confidence sebesar 80%, maka hanya akan
                                    direkomendasikan item penjualan B jika pelanggan yang membeli item penjualan A memiliki
                                    peluang 80% atau
                                    lebih untuk juga membeli item penjualan B.
                                </h6>
                                <br>

                                <h5>Contoh Penggunaan</h5>
                                <h6 class="font-weight-normal text-dark" style="text-align: justify">
                                    * Misalnya Anda ingin melihat apakah perawatan gunting wolfcut, menicure, dan produk
                                    shampo A sering dibeli
                                    bersamaan. Jika Anda set Minimum Support sebesar 10, maka akan dipertimbangkan kombinasi
                                    gunting wolfcut, menicure, dan produk shampo A yang terjual minimal 10 kali selama
                                    periode waktu yang Anda
                                    pilih.
                                    <br>
                                    * Dengan Confidence sebesar 80%, maka akan direkomendasikan shampo A kepada
                                    pelanggan yang telah melakukan gunting wolfcut dan menicure jika peluangnya lebih dari
                                    80% bahwa mereka juga
                                    akan membeli shampo A. Hal ini dapat Anda manfaatkan untuk pertimbangan membuat paket
                                    dengan harga promo.
                                </h6>
                                <br>

                                <h5>Rekomendasi Pemilihan Nilai</h5>
                                <h6 class="font-weight-normal text-dark" style="text-align: justify">
                                    * Pilih nilai Support yang menunjukkan frekuensi produk yang umum digunakan di salon
                                    <br>
                                    * Pilih nilai Confidence yang memberikan rekomendasi yang relevan dan
                                    meyakinkan bagi pelanggan.
                                </h6>
                                <br>
                            </div>
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

    <div id="modalInfoDetailPenjualan" class="modal fade bs-example-modal-center" tabindex="-1" role="dialog"
        aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
            <div class="modal-content ">
                <div class="modal-header">
                    <h5 class="modal-title mt-0" id="modalNamaInfoDetailPenjualan">Informasi Detail Penjualan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body overflow-auto" id="contentInfoDetailPenjualan">
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
            $('#tableHasilAssociationRules').DataTable({
                order: [
                    [2, "desc"],
                ],
                language: {
                    emptyTable: "Tidak terdapat data Rekomendasi Produk!",
                }
            });
        });

        $('body').on('click', "#infoDetailPenjualan", function() {
            var tanggalMulai = $("#tanggalMulaiPenjualan").val();
            var tanggalAkhir = $("#tanggalAkhirPenjualan").val();
            $('#contentInfoDetailPenjualan').html(
                "<div class='text-center'><div class='spinner-border text-info' role='" +
                "status'><span class='sr-only'>Loading...</span></div></div>");

            var dateMulai = new Date(tanggalMulai);
            var dateAkhir = new Date(tanggalAkhir);

            var dayMulai = dateMulai.getDate().toString().padStart(2, '0');
            var monthMulai = (dateMulai.getMonth() + 1).toString().padStart(2, '0');
            var yearMulai = dateMulai.getFullYear();
            var formatDateMulai = dayMulai + '-' + monthMulai + '-' + yearMulai;

            var dateAkhir = new Date(tanggalAkhir);

            var dayAkhir = dateAkhir.getDate().toString().padStart(2, '0');
            var monthAkhir = (dateAkhir.getMonth() + 1).toString().padStart(2, '0');
            var yearAkhir = dateAkhir.getFullYear();
            var formatDateAkhir = dayAkhir + '-' + monthAkhir + '-' + yearAkhir;

            $("#modalNamaInfoDetailPenjualan").text("Informasi Detail Penjualan " + formatDateMulai + " - " +
                formatDateAkhir);
            $.ajax({
                type: 'POST',
                url: '{{ route('admin.rekomendasiproduk.detailpenjualan') }}',
                data: {
                    '_token': '<?php echo csrf_token(); ?>',
                    'tanggalMulai': tanggalMulai,
                    'tanggalAkhir': tanggalAkhir,
                },
                success: function(data) {
                    $('#contentInfoDetailPenjualan').html(data.msg);

                    $('#tabelInformasiDetailPenjualan').DataTable({
                        order: [
                            [0, "asc"],
                        ],
                        language: {
                            emptyTable: "Tidak terdapat informasi detail penjualan untuk rentang tanggal yang dipilih!",
                        }
                    });
                }
            })
        });
    </script>
@endsection
