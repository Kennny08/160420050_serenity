@extends('layout.adminlayout')

@section('title', 'Admin || Edit Diskon')

@section('admincontent')
    <div class="page-title-box">

    </div>
    <!-- end page-title -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <h3 class="mt-0 header-title">Edit Diskon</h3>
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

                    <form id="formUpdateDiskon" method="POST" action="{{ route('diskons.update', $diskon->id) }}">
                        @csrf
                        @method('PUT')
                        <div class="form-group col-md-12">
                        </div>

                        <div class="form-group row">
                            <div class="col-md-12">
                                <label for="exampleInputEmail1"><strong>Nama Diskon</strong></label>
                                <input type="text" class="form-control" name="namaDiskon" id="txtNamaDiskon"
                                    aria-describedby="emailHelp" placeholder="Silahkan masukkan nama diskon" required
                                    value="{{ $diskon->nama }}">
                                <small id="emailHelp" class="form-text text-muted">Masukkan nama diskon disini!</small>
                            </div>

                        </div>

                        <div class="form-group row">
                            <div class="col-md-6">
                                <label for="exampleInputEmail1"><strong>Kode Diskon</strong></label>
                                <input type="text" class="form-control" name="kode_diskon" id="txtKodeDiskon"
                                    aria-describedby="emailHelp" placeholder="Silahkan masukkan kode diskon" disabled
                                    required value="{{ $diskon->kode_diskon }}">
                                <small id="emailHelp" class="form-text text-muted">Masukkan kode diskon disini!</small>
                            </div>
                            <div class="col-md-6">
                                <label for="exampleInputEmail1"><strong>Jumlah Potongan Diskon (%)</strong></label>
                                <input type="number" class="form-control" name="jumlahPotongan" id="numJumlahPotongan"
                                    min="1" max="100" aria-describedby="emailHelp"
                                    placeholder="Silahkan masukkan jumlah potongan diskon" required disabled
                                    value="{{ $diskon->jumlah_potongan }}">
                                <small id="emailHelp" class="form-text text-muted">Masukkan jumlah potongan diskon
                                    disini!</small>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6">
                                <label for="exampleInputEmail1"><strong>Minimal Transaksi (Rp)</strong></label>
                                <input type="number" class="form-control" name="minimalTransaksi" id="numMinimalTransaksi"
                                    min="1" aria-describedby="emailHelp"
                                    placeholder="Silahkan masukkan minimal transaksi" required disabled
                                    value="{{ $diskon->minimal_transaksi }}">
                                <small id="emailHelp" class="form-text text-muted">Masukkan jumlah potongan diskon
                                    disini!</small>
                            </div>
                            <div class="col-md-6">
                                <label for="exampleInputEmail1"><strong>Maksimum Potongan (Rp)</strong></label>
                                <input type="number" class="form-control" name="maksimumPotongan" id="numMaksimumPotongan"
                                    min="1" aria-describedby="emailHelp"
                                    placeholder="Silahkan masukkan maksimum potongan diskon" required disabled
                                    value="{{ $diskon->maksimum_potongan }}">
                                <small id="emailHelp" class="form-text text-muted">Masukkan maksimum potongan diskon
                                    disini!</small>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-4">
                                <label for="exampleInputEmail1"><strong>Status Keaktifan Diskon</strong></label>
                                <br>
                                <div class="btn-group btn-group-toggle border w-100" data-toggle="buttons">
                                    @if ($diskon->status == 'aktif')
                                        <label class="btn btn-info waves-effect waves-light" id="lblStatusDiskonAktif">
                                            <input type="radio" value="aktif" name="radioStatusDiskon"
                                                id="optionStatusDiskonAktif" class="radioStatusDiskon" checked>
                                            Aktif
                                        </label>
                                        <label class="btn waves-effect waves-light" id="lblStatusDiskonNonaktif">
                                            <input type="radio" value="nonaktif" name="radioStatusDiskon"
                                                id="optionStatusDiskonNonaktif" class="radioStatusDiskon">
                                            Nonaktif
                                        </label>
                                    @else
                                        <label class="btn waves-effect waves-light" id="lblStatusDiskonAktif">
                                            <input type="radio" value="aktif" name="radioStatusDiskon"
                                                id="optionStatusDiskonAktif" class="radioStatusDiskon"> Aktif
                                        </label>
                                        <label class="btn btn-info waves-effect waves-light" id="lblStatusDiskonNonaktif">
                                            <input type="radio" value="nonaktif" name="radioStatusDiskon"
                                                id="optionStatusDiskonNonaktif" class="radioStatusDiskon" checked>
                                            Nonaktif
                                        </label>
                                    @endif
                                </div>
                                <small id="emailHelp" class="form-text text-muted">Pilih Status keaktifan diskon
                                    disini!</small>
                            </div>
                            <div class="col-md-4">
                                <label for="exampleInputEmail1"><strong>Tanggal Mulai</strong></label>
                                <input type="date" class="form-control" name="tanggalMulai" id="tanggalMulai"
                                    aria-describedby="emailHelp" placeholder="Silahkan Pilih Tanggal Mulai"
                                    value="{{ date('Y-m-d', strtotime($diskon->tanggal_mulai)) }}" required disabled
                                    min="{{ date('Y-m-d', strtotime($diskon->tanggal_mulai)) }}">
                                <small id="emailHelp" class="form-text text-muted">Pilih tanggal mulai berlakunya diskon
                                    disini!</small>
                            </div>
                            <div class="col-md-4">
                                <label for="exampleInputEmail1"><strong>Tanggal Berakhir</strong></label>
                                <input type="date" class="form-control" name="tanggalBerakhir" id="tanggalBerakhir"
                                    aria-describedby="emailHelp" placeholder="Silahkan Pilih Tanggal Lahir"
                                    value="{{ date('Y-m-d', strtotime($diskon->tanggal_berakhir)) }}" required
                                    min="{{ date('Y-m-d', strtotime($diskon->tanggal_berakhir)) }}">
                                <small id="emailHelp" class="form-text text-muted">Pilih tanggal berakhirnya diskon
                                    disini!</small>
                            </div>

                        </div>

                        <div class="form-group row text-right">
                            <div class="col-md-12">
                                <a id="btnBatalEditProduk" href="{{ route('diskons.index') }}"
                                    class="btn btn-danger btn-lg waves-effect waves-light mr-3">Batal</a>
                                <button id="btnEditProduk" type="submit"
                                    class="btn btn-info btn-lg waves-effect waves-light text-right">Edit</button>
                            </div>
                        </div>

                    </form>


                </div>
            </div>
        </div>
        <!-- end col -->
    </div>
@endsection

@section('javascript')
    <script>
        $('.radioStatusDiskon').on('change', function() {
            var statusSaatIni = $(this).val();
            if (statusSaatIni == "nonaktif") {
                $("#lblStatusDiskonAktif").removeClass("btn-info");
                $("#lblStatusDiskonNonaktif").addClass("btn-info");
            } else {
                $("#lblStatusDiskonAktif").addClass("btn-info");
                $("#lblStatusDiskonNonaktif").removeClass("btn-info");
            }
        });

        $('body').on('change', '#tanggalMulai', function() {
            var tanggalMulai = $(this).val();
            $("#tanggalBerakhir").val(tanggalMulai);
            $("#tanggalBerakhir").attr("min", tanggalMulai);

        });
    </script>
@endsection
