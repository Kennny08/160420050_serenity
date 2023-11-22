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
                                    <input type="date" class="form-control" name="tanggalMulai" min="{{ $tanggalMulai }}"
                                        max="{{ $tanggalAkhir }}" id="tanggalMulaiPenjualan" aria-describedby="emailHelp"
                                        placeholder="Silahkan Pilih Tanggal Reservasi" required>
                                    <small id="emailHelp" class="form-text text-muted">Pilih Tanggal Mulai Riwayat
                                        Penjualan Anda
                                        disini!</small>

                                </div>

                                <div class="col-md-6">
                                    <label for="exampleInputEmail1"><strong>Tanggal Akhir Riwayat Penjualan</strong></label>
                                    <input type="date" class="form-control" name="tanggalAkhir" min="{{ $tanggalMulai }}"
                                        max="{{ $tanggalAkhir }}" id="tanggalAkhirPenjualan" aria-describedby="emailHelp"
                                        placeholder="Silahkan Pilih Tanggal Reservasi" required>
                                    <small id="emailHelp" class="form-text text-muted">Pilih Tanggal Akhir Riwayat Penjualan
                                        Anda
                                        disini!</small>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-6">
                                    <label for="exampleInputEmail1"><strong>Minimum Support (min 1)</strong></label>
                                    <input type="number" class="form-control" name="minSupport" min="1"
                                        id="txtMinimumSupport" aria-describedby="emailHelp" value="1"
                                        placeholder="nilai support" required>
                                    <small id="emailHelp" class="form-text text-muted">Masukkan nilai Minimum Support
                                        disini!</small>

                                </div>

                                <div class="col-md-6">
                                    <label for="exampleInputEmail1"><strong>Minimum Confidence(%)</strong></label>
                                    <input type="number" class="form-control" name="minConfidence" min="1"
                                        max="100" id="txtMinimumConfidence" aria-describedby="emailHelp" value="1"
                                        placeholder="minimum confidence" required>
                                    <small id="emailHelp" class="form-text text-muted">Masukkan nilai Minimum Confidence
                                        disini!</small>
                                </div>
                            </div>

                            <div class="form-group text-right">
                                <button id="tesbutton" style="width: 200px" type="submit"
                                    class="btn btn-primary btn-lg waves-effect waves-light">Konfirmasi</button>
                            </div>

                            @if (isset($freqItemSets) && isset($assocRules))
                                <div class="card shadow">
                                    <div class="card-body">
                                        <div class="form-group row text-center mt-3">
                                            <div class="form-group col-md-12">
                                                <h5>{{ $titleTabel }}</h5>
                                            </div>
                                        </div>
                                        <div class="form-group row text-center mt-3">
                                            <div class="form-group col-md-12">
                                                <table class="table table-bordered">
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
                                                <br>
                                                <table class="table table-bordered">
                                                    <thead class="thead-default">
                                                        <tr class="text-center">
                                                            <th>Kombinasi Perawatan atau Produk</th>
                                                            <th>Confidence</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($assocRules as $ar => $ar1)
                                                            @foreach ($ar1 as $ar2 => $ar3)
                                                                <tr>
                                                                    <td><span style="font-size: 15px"
                                                                            class="font-weight-bold">jika ingin mempromosikan </span>
                                                                        {{ $ar }} <span style="font-size: 15px"
                                                                            class="font-weight-bold">maka dapat dipasangkan dengan </span>
                                                                        {{ $ar2 }}
                                                                    </td>
                                                                    <td>{{ $ar3 }}%</td>
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
@endsection

@section('javascript')
    <script>
        
    </script>
@endsection
