@extends('layout.adminlayout')

@section('title', 'Admin || Buat Reservasi')

@section('admincontent')
    <div class="page-title-box">

    </div>
    <!-- end page-title -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <h3 class="mt-0 header-title">Buat Jadwal Reservasi (ADMIN)</h3>
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

                    <form id="formPilihPerawatan" method="POST" action="{{ route('reservasi.admin.pilihkaryawan') }}"
                        enctype="multipart/form-data">
                        @csrf

                        <div class="form-group row">
                            <div class="form-group col-md-6">
                                <h3>Tanggal Pembuatan : {{ $tanggalHariIni }}</h3>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="exampleInputEmail1"><strong>Nama Pelanggan</strong></label><br>
                                <select name="idPelanggan" id="userPelanggan" class="form-control"
                                    aria-label="Default select example" required>
                                    <option value="null" selected disabled>Pilih Pelanggan</option>
                                    @if (session('daftarPelanggans'))
                                        @foreach (session('daftarPelanggans') as $dp)
                                            @if ($dp->id == session('idPelanggan'))
                                                <option selected value="{{ $dp->id }}">{{ $dp->nama }}</option>
                                            @else
                                                <option value="{{ $dp->id }}">{{ $dp->nama }}</option>
                                            @endif
                                        @endforeach
                                    @else
                                        @foreach ($daftarPelanggans as $pelanggan)
                                            <option value="{{ $pelanggan->id }}">{{ $pelanggan->nama }}</option>
                                        @endforeach
                                    @endif

                                </select>
                                <small id="emailHelp" class="form-text text-muted">Pilih Nama Pelanggan
                                    disini!</small>
                            </div>


                        </div>



                        <div class="form-group row">
                            <div class="col-md-6">
                                <label for="exampleInputEmail1"><strong>Tanggal Reservasi</strong></label>
                                @if (session('tanggalReservasi'))
                                    <input type="date" class="form-control" name="tanggalReservasi" id="tanggalReservasi"
                                        min="{{ $tanggalPertamaDalamMinggu }}" max="{{ $tanggalTerakhirDalamMinggu }}"
                                        aria-describedby="emailHelp" placeholder="Silahkan Pilih Tanggal Reservasi"
                                        value="{{ session('tanggalReservasi') }}" required>
                                    <small id="emailHelp" class="form-text text-muted">Pilih Tanggal Reservasi Anda
                                        disini!</small>
                                @else
                                    <input type="date" class="form-control" name="tanggalReservasi" id="tanggalReservasi"
                                        min="{{ $tanggalPertamaDalamMinggu }}" max="{{ $tanggalTerakhirDalamMinggu }}"
                                        aria-describedby="emailHelp" placeholder="Silahkan Pilih Tanggal Reservasi"
                                        required>
                                    <small id="emailHelp" class="form-text text-muted">Pilih Tanggal Reservasi Anda
                                        disini!</small>
                                @endif

                            </div>

                            <div class="col-md-6">
                                <label for="exampleInputEmail1"><strong>Jam Reservasi</strong></label><br>
                                <select name="slotJam" id="slotJamSelect" class="form-control"
                                    aria-label="Default select example" required>
                                    @if (session('daftarSlotJam'))
                                        @foreach (session('daftarSlotJam') as $sj)
                                            @if ($sj->id == session('idSlotJam'))
                                                <option selected value="{{ $sj->id }}">{{ $sj->jam }}</option>
                                            @else
                                                @if ($sj->status == 'aktif')
                                                    <option value="{{ $sj->id }}">{{ $sj->jam }}</option>
                                                @else
                                                    <option class="text-danger" disabled value="{{ $sj->id }}">
                                                        {{ $sj->jam }} - Tutup</option>
                                                @endif
                                            @endif
                                        @endforeach
                                    @else
                                        @if (count($slotJams) == 0)
                                            <option selected disabled value="null">Tidak ada Slot Jam Tersedia</option>
                                        @else
                                            <option selected disabled value="null">Pilih Slot Jam</option>
                                            @foreach ($slotJams as $sj)
                                                @if ($sj->status == 'aktif')
                                                    <option value="{{ $sj->id }}">{{ $sj->jam }}</option>
                                                @else
                                                    <option class="text-danger" disabled value="{{ $sj->id }}">
                                                        {{ $sj->jam }} - Tutup</option>
                                                @endif
                                            @endforeach
                                        @endif

                                    @endif

                                </select>
                                <small id="emailHelp" class="form-text text-muted">Pilih Jam Reservasi
                                    disini!</small>
                            </div>
                        </div>


                        <div class="form-group row" id="containerLayananPerawatan">
                            <div class="col-md-12">
                                <label for="exampleInputEmail1"><strong>Layanan Perawatan</strong></label>
                            </div>
                            <div class="col-md-12">
                                <div class="input-group">
                                    <select name="perawatan" id="perawatanSelect" class="form-control"
                                        aria-label="Default select example" required>
                                        <option value="null" selected disabled>Pilih Perawatan</option>
                                        @if (session('arrPerawatan'))
                                            @if (session('arrPaketObject'))
                                                @php
                                                    $arrIdPerawatanDariPaketDanPerawatan = [];
                                                    foreach (session('arrPerawatan') as $idPerawatan) {
                                                        array_push($arrIdPerawatanDariPaketDanPerawatan, $idPerawatan);
                                                    }
                                                    foreach (session('arrPaketObject') as $objPaket) {
                                                        foreach ($objPaket->perawatans as $objPerawatan) {
                                                            array_push($arrIdPerawatanDariPaketDanPerawatan, $objPerawatan->id);
                                                        }
                                                    }
                                                @endphp
                                                @foreach ($perawatans as $p)
                                                    @if (!in_array($p->id, $arrIdPerawatanDariPaketDanPerawatan))
                                                        <option value="{{ $p->id }}" durasi="{{ $p->durasi }}"
                                                            harga="{{ $p->harga }}" deskripsi="{{ $p->deskripsi }}"
                                                            kode = "{{ $p->kode_perawatan }}">
                                                            {{ $p->nama }}
                                                        </option>
                                                    @endif
                                                @endforeach
                                            @else
                                                @foreach ($perawatans as $p)
                                                    @if (!in_array($p->id, session('arrPerawatan')))
                                                        <option value="{{ $p->id }}" durasi="{{ $p->durasi }}"
                                                            harga="{{ $p->harga }}" deskripsi="{{ $p->deskripsi }}"
                                                            kode = "{{ $p->kode_perawatan }}">
                                                            {{ $p->nama }}
                                                        </option>
                                                    @endif
                                                @endforeach
                                            @endif
                                        @else
                                            @if (session('arrPaketObject'))
                                                @php
                                                    $arrIdPerawatanDariPaketDanPerawatan = [];
                                                    foreach (session('arrPaketObject') as $objPaket) {
                                                        foreach ($objPaket->perawatans as $objPerawatan) {
                                                            array_push($arrIdPerawatanDariPaketDanPerawatan, $objPerawatan->id);
                                                        }
                                                    }
                                                @endphp
                                                @foreach ($perawatans as $p)
                                                    @if (!in_array($p->id, $arrIdPerawatanDariPaketDanPerawatan))
                                                        <option value="{{ $p->id }}" durasi="{{ $p->durasi }}"
                                                            harga="{{ $p->harga }}" deskripsi="{{ $p->deskripsi }}"
                                                            kode = "{{ $p->kode_perawatan }}">
                                                            {{ $p->nama }}
                                                        </option>
                                                    @endif
                                                @endforeach
                                            @else
                                                @foreach ($perawatans as $p)
                                                    <option value="{{ $p->id }}" durasi="{{ $p->durasi }}"
                                                        harga="{{ $p->harga }}" deskripsi="{{ $p->deskripsi }}"
                                                        kode = "{{ $p->kode_perawatan }}">
                                                        {{ $p->nama }}
                                                    </option>
                                                @endforeach
                                            @endif
                                        @endif

                                    </select>
                                    <button type="button" id="btnTambahLayanan" style="width: 200px;"
                                        class="btn btn-info waves-effect waves-light ml-2">Tambah
                                        Layanan</button>
                                </div>

                                <small id="emailHelp" class="form-text text-muted">Pilih Layanan Perawatan disini!</small>
                            </div>

                            @if (session('arrPerawatanObject'))
                                @foreach (session('arrPerawatanObject') as $aro)
                                    <input id="perawatan{{ $aro->id }}" type="hidden"
                                        class='classarrayperawatanid' value="{{ $aro->id }}"
                                        name="arrayperawatanid[]">
                                @endforeach
                            @endif
                        </div>

                        <div class="form-group row" id="containerPaket">
                            <div class="col-md-12">
                                <label for="exampleInputEmail1"><strong>Paket Layanan</strong></label>
                            </div>
                            <div class="col-md-12">
                                <div class="input-group">
                                    <select name="paket" id="paketSelect" class="form-control"
                                        aria-label="Default select example" required>
                                        <option value="null" selected disabled>Pilih Paket</option>
                                        @if (session('arrPaket'))
                                            @foreach ($pakets as $pk)
                                                @if (!in_array($pk->id, session('arrPaket')))
                                                    <option value="{{ $pk->id }}" kode="{{ $pk->kode_paket }}">
                                                        {{ $pk->nama }}
                                                    </option>
                                                @endif
                                            @endforeach
                                        @else
                                            @foreach ($pakets as $pk)
                                                <option value="{{ $pk->id }}" kode="{{ $pk->kode_paket }}">
                                                    {{ $pk->nama }}
                                                </option>
                                            @endforeach
                                        @endif

                                    </select>
                                    <button type="button" id="btnTambahPaket" style="width: 200px;"
                                        class="btn btn-info waves-effect waves-light ml-2">Tambah
                                        Paket</button>
                                </div>

                                <small id="emailHelp" class="form-text text-muted">Pilih Paket Layanan disini!</small>
                            </div>

                            @if (session('arrPaketObject'))
                                @foreach (session('arrPaketObject') as $apo)
                                    <input id="paket{{ $apo->id }}" type="hidden" class='classarraypaketid'
                                        value="{{ $apo->id }}" name="arraypaketid[]">
                                @endforeach
                            @endif
                        </div>

                        <div class="form-group row">
                            <div class="col-md-12">
                                <div class="shadow card">
                                    <div class="card-body text-center" id="cardDetailPerawatan">
                                        <div class="align-middle alert alert-info" role="alert">
                                            <h6><strong>Silahkan Pilih Perawatan atau Paket terlebih dahulu!</strong></h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="form-group row text-center">
                            <div class="form-group col-md-12 table-responsive">
                                <div>
                                    <table class="table table-bordered table-striped dt-responsive wrap">
                                        <thead>
                                            <tr>
                                                <th class="text-center">Nama Perawatan/Paket</th>
                                                <th class="text-center">Durasi (Menit)</th>
                                                <th class="text-center">Detail Paket <span
                                                        class="text-danger">(*paket)</span></th>
                                                <th class="text-center">Deskripsi</th>
                                                <th class="text-center">Harga (Rp)</th>
                                                <th class="text-center">Hapus</th>
                                            </tr>
                                        </thead>
                                        <tbody id="bodyListPerawatan">

                                            @if (session('arrKodeKeseluruhan'))
                                                @foreach (session('arrKodeKeseluruhan') as $kode)
                                                    @php
                                                        $kodeDepan = substr($kode, 0, 1);
                                                    @endphp
                                                    @if ($kodeDepan == 's')
                                                        @php
                                                            $perawatanTerpilih = $perawatans->firstWhere('kode_perawatan', $kode);
                                                        @endphp
                                                        <tr>
                                                            <td> {{ $perawatanTerpilih->nama }} </td>
                                                            <td>{{ $perawatanTerpilih->durasi }} </td>
                                                            <td> - </td>
                                                            <td>{{ $perawatanTerpilih->deskripsi }}</td>
                                                            <td>{{ number_format($perawatanTerpilih->harga, 2, ',', '.') }}
                                                            </td>
                                                            <td><button type='button'
                                                                    class='deletePerawatan btn btn-danger waves-effect waves-light'
                                                                    idPerawatan="{{ $perawatanTerpilih->id }}"
                                                                    namaPerawatan="{{ $perawatanTerpilih->nama }}"
                                                                    durasiPerawatan="{{ $perawatanTerpilih->durasi }}"
                                                                    hargaPerawatan="{{ $perawatanTerpilih->harga }}"
                                                                    deskripsiPerawatan="{{ $perawatanTerpilih->deskripsi }}"
                                                                    kodePerawatan="{{ $perawatanTerpilih->kode_perawatan }}">Hapus</button>
                                                            </td>
                                                        </tr>
                                                    @else
                                                        @php
                                                            $paketTerpilih = $pakets->firstWhere('kode_paket', $kode);
                                                        @endphp
                                                        <tr>
                                                            <td> {{ $paketTerpilih->nama }} </td>

                                                            <td>
                                                                {{ $paketTerpilih->perawatans->sum('durasi') }}
                                                            </td>
                                                            <td class="text-left">
                                                                <strong class="font-weight-bold">Detail Perawatan</strong>
                                                                <ul>
                                                                    @foreach ($paketTerpilih->perawatans as $perawatan)
                                                                        <li>{{ $perawatan->nama }} -
                                                                            {{ $perawatan->durasi }} menit</li>
                                                                    @endforeach
                                                                </ul>
                                                                @if (count($paketTerpilih->produks) > 0)
                                                                    <strong class="font-weight-bold">Detail Produk</strong>
                                                                    <ul>
                                                                        @foreach ($paketTerpilih->produks as $produk)
                                                                            <li>{{ $produk->nama }} -
                                                                                ({{ $produk->pivot->jumlah }})
                                                                            </li>
                                                                        @endforeach
                                                                    </ul>
                                                                @endif

                                                            </td>
                                                            <td> {{ $paketTerpilih->deskripsi }} </td>
                                                            <td>{{ number_format($paketTerpilih->harga, 2, ',', '.') }}
                                                            </td>
                                                            <td>
                                                                <button type='button'
                                                                    class='deletePaket btn btn-danger waves-effect waves-light'
                                                                    idPaket = "{{ $paketTerpilih->id }}"
                                                                    namaPaket = "{{ $paketTerpilih->nama }}"
                                                                    kodePaket="{{ $paketTerpilih->kode_paket }}">Hapus</button>
                                                            </td>

                                                        </tr>
                                                    @endif
                                                @endforeach
                                            @else
                                                <tr id="trSilahkan">
                                                    <td colspan="6">
                                                        Silahkan pilih Layanan Perawatan atau Paket
                                                    </td>
                                                </tr>
                                            @endif

                                            {{-- @if (session('arrPerawatanObject'))
                                                @foreach (session('arrPerawatanObject') as $aro)
                                                    
                                                @endforeach
                                            @else
                                            @endif --}}

                                        </tbody>
                                    </table>
                                </div>

                            </div>

                        </div>

                        <div id="containerKodeKeseluruhan">
                            @if (session('arrKodeKeseluruhan'))
                                @foreach (session('arrKodeKeseluruhan') as $kode)
                                    <input id='kode{{ $kode }}' type="hidden" class='classarraykodeid'
                                        value="{{ $kode }}" name="arraykodekeseluruhan[]">
                                    {{-- @php
                                        $kodeDepan = substr($kode, 0, 1);
                                    @endphp
                                    @if ($kodeDepan == 's')
                                        @php
                                            $perawatanTerpilih = $perawatans->firstWhere('kode_perawatan', $kode);
                                        @endphp
                                        <input id='kode{{ $perawatanTerpilih->id }}' type="hidden"
                                            class='classarraykodeid' value="{{ $kode }}"
                                            name="arraykodekeseluruhan[]">
                                    @else
                                        @php
                                            $paketTerpilih = $pakets->firstWhere('kode_paket', $kode);
                                        @endphp
                                        <input id='kode{{ $paketTerpilih->id }}' type="hidden" class='classarraykodeid'
                                            value="{{ $kode }}" name="arraykodekeseluruhan[]">
                                    @endif --}}
                                @endforeach
                            @endif
                        </div>




                        {{-- <div class="form-group row">
                            <div class="col-md-12">
                                <div class="shadow card">
                                    <div class="card-body text-center" id="cardDetailPaket">
                                        <div class="align-middle alert alert-info" role="alert">
                                            <h6><strong>Silahkan Pilih Paket terlebih dahulu!</strong></h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> --}}

                        {{-- <div class="form-group row text-center">
                            <div class="form-group col-md-12 table-responsive">
                                <div>
                                    <table class="table table-bordered table-striped dt-responsive wrap">
                                        <thead>
                                            <tr>
                                                <th class="text-center">Nama Paket</th>
                                                <th class="text-center">Detail Perawatan</th>
                                                <th class="text-center">Durasi (Menit)</th>
                                                <th class="text-center">Detail Produk</th>
                                                <th class="text-center">Deskripsi</th>
                                                <th class="text-center">Harga (Rp)</th>
                                                <th class="text-center">Hapus</th>
                                            </tr>
                                        </thead>
                                        <tbody id="bodyListPaket">
                                            @if (session('arrPaketObject'))
                                                @foreach (session('arrPaketObject') as $aro)
                                                    <tr>
                                                        <td> {{ $aro->nama }} </td>
                                                        <td>{{ $aro->durasi }} </td>
                                                        <td>{{ $aro->harga }} </td>
                                                        <td>{{ $aro->deskripsi }}</td>
                                                        <td><button type='button'
                                                                class='deletePerawatan btn btn-danger waves-effect waves-light'
                                                                idPerawatan="{{ $aro->id }}"
                                                                namaPerawatan="{{ $aro->nama }}"
                                                                durasiPerawatan="{{ $aro->durasi }}"
                                                                hargaPerawatan="{{ $aro->harga }}"
                                                                deskripsiPerawatan="{{ $aro->deskripsi }}">Hapus</button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr id="trSilahkan">
                                                    <td colspan="5">
                                                        Silahkan pilih Layanan Perawatan
                                                    </td>
                                                </tr>
                                            @endif

                                        </tbody>
                                    </table>
                                </div>

                            </div>

                        </div> --}}

                        <div class="form-group text-right">
                            <button id="btnKonfirmasiPerawatan" type="button" data-toggle = "modal"
                                data-target="#modalKonfirmasiPerawatan"
                                class="btn btn-primary btn-lg waves-effect waves-light">Konfirmasi Reservasi</button>
                        </div>

                        <div id="modalKonfirmasiPerawatan" class="modal fade bs-example-modal-center" tabindex="-1"
                            role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title mt-0">Konfirmasi Pemilihan Karyawan</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body text-center" id="bodyModalKonfirmasiPerawatan">
                                        <h6>Apakah Anda ingin memilih karyawan yang akan melayani perawatan Anda?</h6>
                                        <input id="inputPilihKaryawan" type="hidden" name="keteranganPilihKaryawan"
                                            value="tidak">
                                    </div>
                                    <div class="modal-footer">
                                        <button id="btnTidakPilihKaryawan" type="button"
                                            class="btn btn-primary waves-effect">Tidak</button>
                                        <button id="btnPilihKaryawan" type="button"
                                            class="btn btn-info waves-effect waves-light">Ya</button>
                                    </div>
                                </div>
                                <!-- /.modal-content -->
                            </div>
                            <!-- /.modal-dialog -->
                        </div>
                    </form>


                </div>
            </div>
        </div>
        <!-- end col -->
    </div>

    <div id="modalPemberitahuanPerawatan" class="modal fade bs-example-modal-center" tabindex="-1" role="dialog"
        aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title mt-0">Pemberitahuan untuk Menambah Paket</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center" id="bodyModalPemberitahuanPerawatan">
                    <h6></h6>

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
        var jamReservasi = '09.00';
        var jamTutup = '20:00';

        $('#tanggalReservasi').on('change', function() {
            var tanggal = $(this).val();

            $.ajax({
                type: 'POST',
                url: '{{ route('reservasi.admin.getslotjamaktif') }}',
                data: {
                    '_token': '<?php echo csrf_token(); ?>',
                    'tanggal': tanggal,
                },
                success: function(data) {
                    if (data.status == "ok") {
                        $('#slotJamSelect').html(data.msg);
                    } else {
                        $('#slotJamSelect').html(data.msg);
                    }
                }
            })
        });

        $('#slotJamSelect').on('change', function() {});

        $('#perawatanSelect').on('change', function() {
            var idPerawatan = $(this).val();
            $.ajax({
                type: 'POST',
                url: '{{ route('reservasi.admin.getdetailperawatan') }}',
                data: {
                    '_token': '<?php echo csrf_token(); ?>',
                    'idPerawatan': idPerawatan,
                },
                success: function(data) {
                    $('#cardDetailPerawatan').html(data.msg);
                }
            })
        });

        $('#paketSelect').on('change', function() {
            var idPaket = $(this).val();
            $.ajax({
                type: 'POST',
                url: '{{ route('reservasi.admin.getdetailpaketreservasi') }}',
                data: {
                    '_token': '<?php echo csrf_token(); ?>',
                    'idPaket': idPaket,
                },
                success: function(data) {
                    $('#cardDetailPerawatan').html(data.msg);
                }
            })
        });

        $('body').on('click', '#btnTambahLayanan', function() {
            var perawatanid = $("#perawatanSelect").val();
            var namaperawatan = $("#perawatanSelect option:selected").text();
            var durasiperawatan = $("#perawatanSelect option:selected").attr('durasi');
            var hargaperawatan = $("#perawatanSelect option:selected").attr('harga');
            var deskripsiperawatan = $("#perawatanSelect option:selected").attr('deskripsi');
            var kodeperawatan = $("#perawatanSelect option:selected").attr('kode');
            var tanggalReservasi = $("#tanggalReservasi").val();

            if (perawatanid != null) {
                $("#containerLayananPerawatan").append("<input id='perawatan" + perawatanid +
                    "' type='hidden' class='classarrayperawatanid' value='" +
                    perawatanid +
                    "' name='arrayperawatanid[]'>");
                $("#containerKodeKeseluruhan").append("<input id='kode" + kodeperawatan +
                    "' type='hidden' class='classarraykodeid' value='" +
                    kodeperawatan +
                    "' name='arraykodekeseluruhan[]'>");
                $('#trSilahkan').remove();
                $("#bodyListPerawatan").append(
                    "<tr><td>" + namaperawatan + "</td>" +
                    "<td>" + durasiperawatan + "</td>" +
                    "<td> - </td>" +
                    "<td>" + deskripsiperawatan + "</td>" +
                    "<td>" + parseInt(hargaperawatan).toLocaleString('id-ID', {
                        style: 'currency',
                        currency: 'IDR'
                    }).toString().replace("Rp", "") + "</td>" +
                    "<td>" +
                    "<button type='button' class='deletePerawatan btn btn-danger waves-effect waves-light' idPerawatan='" +
                    perawatanid +
                    "' namaPerawatan='" + namaperawatan + "' durasiPerawatan='" + durasiperawatan +
                    "' hargaPerawatan='" +
                    hargaperawatan + "' deskripsiPerawatan='" + deskripsiperawatan + "' kodePerawatan='" +
                    kodeperawatan + "'>Hapus</button>" +
                    "</td>" +
                    "</tr>");
                $("#perawatanSelect option:selected").remove();
                $("#perawatanSelect").val('null');
                $('#cardDetailPerawatan').html(
                    "<div class='alert alert-info' role='alert'><h6><strong>Silahkan Pilih Perawatan atau Paket terlebih dahulu!</strong></h6></div>"
                );
            }
        });

        $('body').on('click', '#btnTambahPaket', function() {
            var paketId = $("#paketSelect").val();
            var paketKode = $("#paketSelect option:selected").attr("kode");

            var arrPerawatan = [];
            $('.classarrayperawatanid').each(function() {
                arrPerawatan.push($(this).val());

            });

            if (paketId != null) {
                $.ajax({
                    type: 'POST',
                    url: '{{ route('reservasi.admin.addpaketreservasitolist') }}',
                    data: {
                        '_token': '<?php echo csrf_token(); ?>',
                        'idPaket': paketId,
                    },
                    success: function(data) {

                        var check = 0;
                        var arrPerawatanSudahAda = [];
                        data.perawatans.forEach(function(perawatan) {
                            if (jQuery.inArray(perawatan.id.toString(), arrPerawatan) != -1) {
                                check = check + 1;
                                arrPerawatanSudahAda.push(perawatan.nama);
                            }
                        });

                        var arrKode = [];
                        $(".classarraykodeid").each(function() {
                            if ($(this).val().substr(0, 1) == "m") {
                                arrKode.push($(this).val());
                            }
                        });

                        var check2 = 0;
                        var arrNamaPerawatanPaket = [];
                        if (arrKode.length > 0) {
                            $.ajax({
                                type: 'POST',
                                url: '{{ route('reservasi.admin.checkpaketisisama') }}',
                                data: {
                                    '_token': '<?php echo csrf_token(); ?>',
                                    'idPaket': paketId,
                                    'daftarPaketDiambil': arrKode.join(","),
                                },
                                success: function(data1) {
                                    if (data1.arrPerawatanObjPaket.length > 0) {
                                        data1.arrPerawatanObjPaket.forEach(function(
                                            perawatan) {
                                            arrNamaPerawatanPaket.push(perawatan
                                                .nama);
                                        });
                                        check2 = check2 + 1;
                                    }

                                    if (check > 0 || check2 > 0) {
                                        var pesan = "";
                                        if (check > 0) {
                                            pesan = pesan +
                                                " * Paket ini memiliki perawatan <span class='text-danger'>" +
                                                arrPerawatanSudahAda.join(", ") +
                                                "</span> yang sudah pernah Anda tambahkan sebelumnya!<br>";
                                        }

                                        if (check2 > 0) {
                                            pesan = pesan +
                                                " * Paket ini memiliki perawatan <span class='text-danger'>" +
                                                arrNamaPerawatanPaket.join(", ") +
                                                "</span> yang sudah ada pada paket yang telah Anda tambahkan sebelumnya!<br>";
                                        }

                                        $("#bodyModalPemberitahuanPerawatan").html("<h6>" +
                                            pesan + "</h6>");
                                        $("#modalPemberitahuanPerawatan").modal("show");
                                    } else {
                                        $("#containerPaket").append("<input id='paket" +
                                            paketId +
                                            "' type='hidden' class='classarraypaketid' value='" +
                                            paketId +
                                            "' name='arraypaketid[]'>");
                                        $("#containerKodeKeseluruhan").append(
                                            "<input id='kode" + paketKode +
                                            "' type='hidden' class='classarraykodeid' value='" +
                                            paketKode +
                                            "' name='arraykodekeseluruhan[]'>");
                                        $('#trSilahkan').remove();

                                        $("#bodyListPerawatan").append(data.msg);

                                        $("#paketSelect option:selected").remove();
                                        $("#paketSelect").val('null');
                                        $('#cardDetailPerawatan').html(
                                            "<div class='alert alert-info' role='alert'><h6><strong>Silahkan Pilih Perawatan atau Paket terlebih dahulu!</strong></h6></div>"
                                        );
                                        data.perawatans.forEach(function(perawatan) {
                                            $("#perawatanSelect option[value='" +
                                                    perawatan.id + "']")
                                                .remove();
                                        });

                                        var select = $("#perawatanSelect");
                                        $("#perawatanSelect option[value='null']").remove();
                                        var options = select.find("option");
                                        options.sort(function(a, b) {
                                            return a.text.localeCompare(b.text);
                                        });
                                        select.empty();
                                        select.append(
                                            "<option value='null' selected disabled>Pilih Perawatan</option>"
                                        )
                                        select.append(options);
                                        select.val("null");
                                    }
                                }
                            })
                        } else {
                            if (check > 0 || check2 > 0) {
                                var pesan = "";
                                if (check > 0) {
                                    pesan = pesan +
                                        " * Paket ini memiliki perawatan <span class='text-danger'>" +
                                        arrPerawatanSudahAda.join(", ") +
                                        "</span> yang sudah pernah Anda tambahkan sebelumnya!<br>";
                                }

                                if (check2 > 0) {
                                    pesan = pesan +
                                        " * Paket ini memiliki perawatan <span class='text-danger'>" +
                                        arrNamaPerawatanPaket.join(", ") +
                                        "</span> yang sudah ada pada paket yang telah Anda tambahkan sebelumnya!<br>";
                                }

                                $("#bodyModalPemberitahuanPerawatan").html("<h6>" + pesan + "</h6>");
                                $("#modalPemberitahuanPerawatan").modal("show");
                            } else {
                                $("#containerPaket").append("<input id='paket" + paketId +
                                    "' type='hidden' class='classarraypaketid' value='" +
                                    paketId +
                                    "' name='arraypaketid[]'>");
                                $("#containerKodeKeseluruhan").append("<input id='kode" + paketKode +
                                    "' type='hidden' class='classarraykodeid' value='" +
                                    paketKode +
                                    "' name='arraykodekeseluruhan[]'>");
                                $('#trSilahkan').remove();

                                $("#bodyListPerawatan").append(data.msg);

                                $("#paketSelect option:selected").remove();
                                $("#paketSelect").val('null');
                                $('#cardDetailPerawatan').html(
                                    "<div class='alert alert-info' role='alert'><h6><strong>Silahkan Pilih Perawatan atau Paket terlebih dahulu!</strong></h6></div>"
                                );
                                data.perawatans.forEach(function(perawatan) {
                                    $("#perawatanSelect option[value='" + perawatan.id + "']")
                                        .remove();
                                });

                                var select = $("#perawatanSelect");
                                $("#perawatanSelect option[value='null']").remove();
                                var options = select.find("option");
                                options.sort(function(a, b) {
                                    return a.text.localeCompare(b.text);
                                });
                                select.empty();
                                select.append(
                                    "<option value='null' selected disabled>Pilih Perawatan</option>"
                                )
                                select.append(options);
                                select.val("null");
                            }
                        }



                    }
                })


            }
        });

        $('body').on('click', '.deletePerawatan', function() {
            var perawatanid = $(this).attr('idPerawatan');
            var namaPerawatan = $(this).attr('namaPerawatan');
            var durasiperawatan = $(this).attr('durasiPerawatan');
            var hargaperawatan = $(this).attr('hargaPerawatan');
            var deskripsiperawatan = $(this).attr('deskripsiPerawatan');
            var kodeperawatan = $(this).attr('kodePerawatan');
            var tanggalReservasi = $("#tanggalReservasi").val();

            $("#perawatanSelect").append("<option value='" + perawatanid + "' durasi='" + durasiperawatan +
                "' harga='" + hargaperawatan + "' deskripsi='" + deskripsiperawatan + "' kode='" +
                kodeperawatan + "'>" + namaPerawatan +
                "</option>");
            $(this).parent().parent().remove();
            $("#perawatan" + perawatanid).remove();
            $("#kode" + kodeperawatan).remove();

            var select = $("#perawatanSelect");
            $("#perawatanSelect option[value='null']").remove();
            var options = select.find("option");
            options.sort(function(a, b) {
                return a.text.localeCompare(b.text);
            });
            select.empty();
            select.append("<option value='null' selected disabled>Pilih Perawatan</option>")
            select.append(options);
            select.val("null");

            if ($('#bodyListPerawatan').find("tr").length == 0) {
                $('#bodyListPerawatan').html(
                    "<tr id='trSilahkan'><td colspan='6'>Silahkan Pilih Perawatan atau Paket</td></tr>");
            }


        });

        $('body').on('click', '.deletePaket', function() {
            var paketId = $(this).attr('idPaket');
            var paketNama = $(this).attr('namaPaket');
            var paketKode = $(this).attr('kodePaket');


            $("#paketSelect").append("<option value='" + paketId + "' kode='" + paketKode +
                "'>" + paketNama +
                "</option>");
            $(this).parent().parent().remove();
            $("#paket" + paketId).remove();
            $("#kode" + paketKode).remove();


            var select = $("#paketSelect");
            $("#paketSelect option[value='null']").remove();
            var options = select.find("option");
            options.sort(function(a, b) {
                return a.text.localeCompare(b.text);
            });
            select.empty();
            select.append("<option value='null' selected disabled>Pilih Paket</option>")
            select.append(options);
            select.val("null");

            if ($('#bodyListPerawatan').find("tr").length == 0) {
                $('#bodyListPerawatan').html(
                    "<tr id='trSilahkan'><td colspan='6'>Silahkan Pilih Perawatan atau Paket</td></tr>");
            }
            $.ajax({
                type: 'POST',
                url: '{{ route('reservasi.admin.updatecbperawatanafterdeletepaket') }}',
                data: {
                    '_token': '<?php echo csrf_token(); ?>',
                    'idPaket': paketId,
                },
                success: function(data) {
                    data.perawatans.forEach(function(perawatan) {
                        $("#perawatanSelect").append("<option value='" + perawatan.id +
                            "' durasi='" + perawatan.durasi +
                            "' harga='" + perawatan.harga + "' deskripsi='" +
                            perawatan.deskripsi + "' kode='" +
                            perawatan.kode_perawatan + "'>" + perawatan.nama +
                            "</option>"
                        );

                    });
                    var select = $("#perawatanSelect");
                    $("#perawatanSelect option[value='null']").remove();
                    var options = select.find("option");
                    options.sort(function(a, b) {
                        return a.text.localeCompare(b.text);
                    });
                    select.empty();
                    select.append(
                        "<option value='null' selected disabled>Pilih Perawatan</option>"
                    )
                    select.append(options);
                    select.val("null");
                }
            })
        });

        $('body').on('click', '#btnPilihKaryawan', function() {

            $("#inputPilihKaryawan").val("ya");
            $("#modalKonfirmasiPerawatan").modal("hide");
            $("#formPilihPerawatan").submit();
        });

        $('body').on('click', '#btnTidakPilihKaryawan', function() {

            $("#inputPilihKaryawan").val("tidak");
            $("#modalKonfirmasiPerawatan").modal("hide");
            $("#formPilihPerawatan").submit();
        });
    </script>
@endsection
