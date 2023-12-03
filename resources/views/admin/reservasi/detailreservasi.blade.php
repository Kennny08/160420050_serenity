@extends('layout.adminlayout')

@section('title', 'Admin || Detail Reservasi')


@section('admincontent')
    <div class="page-title-box">

    </div>
    <!-- end page-title -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">


                    <div class="row">
                        <div class="col-md-6">
                            <h3 class="mt-0 header-title">Detail Reservasi</h3>
                        </div>
                        <div class="col-md-6 text-right">
                            <a class="btn btn-lg btn-info waves-effect waves-light mt-0"
                                href={{ route('penjualans.admin.detailnotareservasipenjualan', $reservasi->id) }}><i
                                    class="fas fa-file-invoice"></i>
                                &nbsp;Detail Nota Reservasi</a>
                        </div>
                    </div>
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

                    @if (is_array(session('status')) && array_key_exists('message', session('status')))
                        <div class="alert alert-success alert-dismissible" role="alert">
                            <button type="button" class="close text-success" data-dismiss="alert" aria-label="Close">
                                <span class="text-success" aria-hidden="true">&times;</span>
                            </button>
                            @foreach (session('status')['message'] as $item)
                                <p class="mt-0 mb-1">- {{ $item }}</p>
                            @endforeach

                        </div>
                    @endif


                    <div class="form-group col-md-12">
                        <div class="row">
                            <div class="col-6">
                                <address>
                                    <h3>{{ $reservasi->penjualan->pelanggan->nama }}</h3>
                                </address>
                            </div>
                            <div class="col-6 text-right">
                                <address>
                                    <h3>{{ $reservasi->penjualan->nomor_nota }}</h3>
                                </address>
                            </div>
                        </div>
                    </div>

                    <div class="form-group col-md-12">
                        <div class="row">
                            <div class="col-6">
                                <address>
                                    <strong class="font-weight-bold  font-16">Tanggal dan Jam Reservasi:</strong><br>
                                    {{ date('d-m-Y', strtotime($reservasi->tanggal_reservasi)) }} ||
                                    {{ $jamMulai->jam }}
                                </address>
                            </div>
                            <div class="col-6 text-right">
                                <address>
                                    <strong class="font-weight-bold font-16">Waktu Pembuatan Reservasi:</strong><br>
                                    {{ date('d-m-Y', strtotime($reservasi->tanggal_pembuatan_reservasi)) }} ||
                                    {{ date('H:i', strtotime($reservasi->tanggal_pembuatan_reservasi)) }}
                                </address>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <address>
                                    <strong class="font-weight-bold  font-16">Jumlah Perawatan:</strong><br>
                                    {{ count($reservasi->penjualan->penjualanperawatans) }} Perawatan
                                </address>
                            </div>
                            <div class="col-6 text-right">
                                <address>
                                    <strong class="font-weight-bold font-16">Jumlah Produk:</strong><br>
                                    {{ count($reservasi->penjualan->produks) }} Produk
                                </address>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <address>
                                    @php
                                        $totalDurasi = 0;
                                    @endphp
                                    <strong class="font-weight-bold  font-16">Total Durasi:</strong><br>
                                    @php
                                        foreach ($perawatanSlotJamNonKomplemen as $ps) {
                                            $totalDurasi += $ps['durasi'];
                                        }

                                        $totalDurasi += $arrKomplemen['durasiterlama'];
                                    @endphp
                                    {{ $totalDurasi }} Menit
                                </address>
                            </div>
                            <div class="col-6 text-right">
                                <address>
                                    <strong class="font-weight-bold font-16">Status Reservasi:</strong><br>
                                    @if ($reservasi->status == 'dibatalkan salon' || $reservasi->status == 'dibatalkan pelanggan')
                                        <span class="text-danger font-16 font-weight-bold">{{ $reservasi->status }}</span>
                                    @elseif($reservasi->status == 'selesai')
                                        <span class="text-success font-16 font-weight-bold">{{ $reservasi->status }}</span>
                                    @else
                                        <span class="text-warning font-16 font-weight-bold">{{ $reservasi->status }}</span>
                                    @endif

                                </address>
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-md-12">
                        <div class="card">
                            <div class="card-body">

                                <h5 class="mt-0">Detail Perawatan || Produk || Diskon || Ulasan</h5>


                                <!-- Nav tabs -->
                                <ul style="border-radius: 5px; border: 1px solid rgba(0, 0, 0, 0.1); box-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1)"
                                    class="nav nav-pills nav-justified" role="tablist">
                                    <li class="nav-item waves-effect waves-light">
                                        <a class="nav-link active" data-toggle="tab" href="#perawatan" role="tab">
                                            <span class="d-none d-md-block font-16">Perawatan</span><span
                                                class="d-block d-md-none"><i class="mdi mdi-home-variant h5"></i></span>
                                        </a>
                                    </li>
                                    <li class="nav-item waves-effect waves-light">
                                        <a class="nav-link" data-toggle="tab" href="#produk" role="tab">
                                            <span class="d-none d-md-block font-16">Produk</span><span
                                                class="d-block d-md-none"><i class="mdi mdi-account h5"></i></span>
                                        </a>
                                    </li>
                                    <li class="nav-item waves-effect waves-light">
                                        <a class="nav-link" data-toggle="tab" href="#diskon" role="tab">
                                            <span class="d-none d-md-block font-16">Diskon</span><span
                                                class="d-block d-md-none"><i class="mdi mdi-account h5"></i></span>
                                        </a>
                                    </li>

                                </ul>

                                <!-- Tab panes -->
                                <div style="border-radius: 5px" class="tab-content shadow">
                                    <div class="tab-pane active pt-4 pb-4 pl-4 pr-4" id="perawatan" role="tabpanel">
                                        <div class="mb-2">
                                            <div class="d-inline-block">
                                                <h3 class="mt-0 header-title">Perawatan Sekuensial/berurutan</h3>
                                            </div>

                                            <div class="d-inline-block">
                                                <button style="font-size: 12px" type="button"
                                                    class="btn btn-sm btn-info waves-effect waves-light"
                                                    data-toggle="tooltip" data-placement="right"
                                                    title="Perawatan dilakukan secara terpisah atau bertahap">
                                                    <i class="mdi mdi-information-outline"></i>
                                                </button>
                                            </div>
                                        </div>

                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <thead class="thead-default">
                                                    <tr class="text-center">
                                                        <th>Nama Perawatan</th>
                                                        <th>Jam Mulai</th>
                                                        <th>Durasi (Menit)</th>
                                                        {{-- <th>Harga (Rp)</th> --}}
                                                        <th>Karyawan</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($perawatanSlotJamNonKomplemen as $ps)
                                                        <tr class="text-center">
                                                            <td>
                                                                @if ($ps['namapaket'] == 'null')
                                                                    {{ $ps['penjualanperawatannonkomplemen']->perawatan->nama }}
                                                                @else
                                                                    {{ $ps['penjualanperawatannonkomplemen']->perawatan->nama }}
                                                                    <br>
                                                                    <span class="text-info font-weight-bold">*
                                                                        {{ $ps['namapaket'] }}
                                                                    </span>
                                                                @endif

                                                            </td>
                                                            <td>{{ $ps['jammulai'] }}</td>
                                                            <td>{{ $ps['durasi'] }}</td>
                                                            {{-- <td>{{ number_format($ps['penjualanperawatannonkomplemen']->harga, 2, ',', '.') }}
                                                            </td> --}}
                                                            <td>{{ $ps['karyawan']->nama }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>

                                        <br>
                                        <div class="mb-2">
                                            <div class="d-inline-block">
                                                <h3 class="mt-0 header-title">Perawatan Bersamaan</h3>
                                            </div>

                                            <div class="d-inline-block">
                                                <button style="font-size: 12px" type="button"
                                                    class="btn btn-sm btn-info waves-effect waves-light"
                                                    data-toggle="tooltip" data-placement="right"
                                                    title="Perawatan dapat dilakukan secara bersamaan pada durasi tertentu">
                                                    <i class="mdi mdi-information-outline"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <thead class="thead-default">
                                                    <tr class="text-center">
                                                        <th>Nama Perawatan</th>
                                                        <th>Jam Mulai</th>
                                                        <th>Durasi (Menit)</th>
                                                        {{-- <th>Harga (Rp)</th> --}}
                                                        <th>Karyawan</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php
                                                        $counter = 1;
                                                    @endphp
                                                    @foreach ($arrKomplemen['perawatans'] as $ps)
                                                        @if ($counter == 1)
                                                            <tr class="text-center">
                                                                <td>
                                                                    @if ($ps['namapaket'] == 'null')
                                                                        {{ $ps['penjualanperawatankomplemen']->perawatan->nama }}
                                                                    @else
                                                                        {{ $ps['penjualanperawatankomplemen']->perawatan->nama }}
                                                                        <br>
                                                                        <span class="text-info font-weight-bold">*
                                                                            {{ $ps['namapaket'] }}
                                                                        </span>
                                                                    @endif

                                                                </td>
                                                                <td class="text-center align-middle"
                                                                    rowspan="{{ count($arrKomplemen['perawatans']) }}">
                                                                    {{ $arrKomplemen['jammulai'] }}</td>
                                                                <td class="text-center align-middle"
                                                                    rowspan="{{ count($arrKomplemen['perawatans']) }}">
                                                                    {{ $arrKomplemen['durasiterlama'] }}
                                                                </td>
                                                                {{-- <td>{{ number_format($ps['penjualanperawatankomplemen']->harga, 2, ',', '.') }}
                                                                </td> --}}
                                                                <td>{{ $ps['karyawan']->nama }}</td>
                                                            </tr>
                                                        @else
                                                            <tr class="text-center">
                                                                <td>
                                                                    @if ($ps['namapaket'] == 'null')
                                                                        {{ $ps['penjualanperawatankomplemen']->perawatan->nama }}
                                                                    @else
                                                                        {{ $ps['penjualanperawatankomplemen']->perawatan->nama }}
                                                                        <br>
                                                                        <span class="text-info font-weight-bold">*
                                                                            {{ $ps['namapaket'] }}
                                                                        </span>
                                                                    @endif
                                                                </td>
                                                                {{-- <td>{{ number_format($ps['penjualanperawatankomplemen']->harga, 2, ',', '.') }}
                                                                </td> --}}
                                                                <td>{{ $ps['karyawan']->nama }}</td>

                                                            </tr>
                                                        @endif
                                                        @php
                                                            $counter = $counter + 1;
                                                        @endphp
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>

                                        <div class="form-group col-md-12">
                                            <div class="row">
                                                <div class="col-6 mt-2">
                                                    <address>

                                                        @if ($keteranganGantiKaryawan == true)
                                                            <form
                                                                action="{{ route('reservasi.admin.editpilihkaryawanperawatan') }}">
                                                                <input type="hidden" value="{{ $reservasi->id }}"
                                                                    name="hiddenIdReservasi">
                                                                <button class="btn btn-info waves-effect waves-light mt-3"
                                                                    type="submit">
                                                                    Edit Karyawan Perawatan</button>
                                                            </form>
                                                        @endif

                                                    </address>
                                                </div>
                                            </div>
                                        </div>



                                        {{-- <div class="form-group col-md-12">
                                            <div class="row">
                                                <div class="col-12 mt-2 text-right">
                                                    @php
                                                        $totalHargaPerawatan = 0;
                                                    @endphp
                                                    <address>
                                                        @foreach ($arrKomplemen['perawatans'] as $ps)
                                                            @php
                                                                $totalHargaPerawatan += $ps['penjualanperawatankomplemen']->harga;
                                                            @endphp
                                                        @endforeach
                                                        @foreach ($perawatanSlotJamNonKomplemen as $psnk)
                                                            @php
                                                                $totalHargaPerawatan += $psnk['penjualanperawatannonkomplemen']->harga;
                                                            @endphp
                                                        @endforeach
                                                        <h6 style="font-weight: normal">Total Harga Perawatan: </h6>
                                                        <h4><strong>Rp.
                                                                {{ number_format($totalHargaPerawatan, 2, ',', '.') }}</strong>
                                                        </h4>
                                                    </address>
                                                </div>
                                            </div>
                                        </div> --}}
                                    </div>

                                    <div class="tab-pane p-4" id="produk" role="tabpanel">
                                        <div class="table-responsive">
                                            <table id="tabelDaftarProduk"
                                                class="table table-striped table-bordered dt-responsive wrap text-center"
                                                style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                                <thead>
                                                    <tr>
                                                        <th>Kode Produk</th>
                                                        <th>Nama</th>
                                                        <th>Merek</th>
                                                        <th>Harga(Rp)</th>
                                                        <th>Stok Dibeli</th>
                                                        <th>Subtotal(Rp)</th>
                                                    </tr>
                                                </thead>

                                                <tbody>
                                                    @if (count($reservasi->penjualan->produks) > 0)
                                                        @foreach ($reservasi->penjualan->produks as $produk)
                                                            <tr id="tr_{{ $produk->id }}">
                                                                <td>{{ $produk->kode_produk }}</td>
                                                                <td>
                                                                    {{ $produk->nama }}
                                                                    @if (count($reservasi->penjualan->pakets) > 0)
                                                                        @foreach ($reservasi->penjualan->pakets as $paket)
                                                                            @if ($paket->produks->firstWhere('id', $produk->id) != null)
                                                                                <br>
                                                                                <span class="text-info font-weight-bold">*
                                                                                    {{ $paket->nama }} -
                                                                                    ({{ $paket->pivot->jumlah }})
                                                                                </span>
                                                                            @endif
                                                                        @endforeach
                                                                    @endif
                                                                </td>
                                                                <td>{{ $produk->merek->nama }}</td>
                                                                <td>{{ number_format($produk->pivot->harga, 2, ',', '.') }}
                                                                </td>
                                                                <td>{{ $produk->pivot->kuantitas }}</td>
                                                                <td class="text-center">
                                                                    {{ number_format($produk->pivot->kuantitas * $produk->pivot->harga, 2, ',', '.') }}
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    @else
                                                        <tr class="text-center">
                                                            <td colspan="9">Tidak terdapat produk tambahan yang dibeli!
                                                            </td>
                                                        </tr>
                                                    @endif

                                                </tbody>
                                            </table>
                                        </div>

                                        <div class="form-group col-md-12">
                                            <div class="row">
                                                <div class="col-6 mt-2">
                                                    <address>
                                                        @if ($reservasi->penjualan->status_selesai == 'belum')
                                                            <a class="btn btn-info waves-effect waves-light mt-3"
                                                                href="{{ route('penjualan.admin.penjualantambahproduk', $reservasi->penjualan->id) }}">
                                                                Edit Penambahan Produk</a>
                                                        @endif

                                                    </address>
                                                </div>
                                                {{-- <div class="col-6 text-right">
                                                    @php
                                                        $totalHargaProduk = 0;
                                                    @endphp
                                                    <address>
                                                        @foreach ($reservasi->penjualan->produks as $p)
                                                            @php
                                                                $totalHargaProduk += $p->pivot->kuantitas * $p->pivot->harga;
                                                            @endphp
                                                        @endforeach
                                                        <h6 class="mt-4" style="font-weight: normal">Total Harga
                                                            Produk:</h6>
                                                        <h4><strong>Rp.
                                                                {{ number_format($totalHargaProduk, 2, ',', '.') }}</strong>
                                                        </h4>
                                                    </address>
                                                </div> --}}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane p-3 text-center" id="diskon" role="tabpanel">
                                        @if ($reservasi->penjualan->diskon == null)
                                            <div class="col-md-12 text-center">
                                                <div class="card text-white bg-danger text-center w-60 mx-auto">
                                                    <div class="card-body">
                                                        <blockquote class="card-bodyquote mb-0">
                                                            <h4>
                                                                Tidak ada Diskon yang digunakan!
                                                            </h4>
                                                            <footer class=" text-white font-12">
                                                                @php
                                                                    $jumlahDiskonValid = 0;
                                                                    foreach ($diskonAktifBerlaku as $diskon) {
                                                                        if ($reservasi->penjualan->total_pembayaran >= $diskon->minimal_transaksi) {
                                                                            $jumlahDiskonValid += 1;
                                                                        }
                                                                    }
                                                                @endphp
                                                                @if ($reservasi->penjualan->status_selesai == 'belum')
                                                                    @if ($jumlahDiskonValid > 0)
                                                                        <h5>Silahkan pilih diskon yang tersedia! (Tersedia
                                                                            {{ $jumlahDiskonValid }} yang berlaku)</h5>
                                                                    @else
                                                                        <h5>Tidak terdapat Diskon yang tersedia</h5>
                                                                    @endif
                                                                @endif

                                                            </footer>
                                                        </blockquote>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <div class="col-md-12 text-center">
                                                <div class="card text-white bg-info text-center w-60 mx-auto">
                                                    <div class="card-body">
                                                        <blockquote class="card-bodyquote mb-0">
                                                            <h4>
                                                                {{ $reservasi->penjualan->diskon->nama }}
                                                            </h4>
                                                            <footer class=" text-white font-12">
                                                                <h5>Potongan sebesar
                                                                    {{ $reservasi->penjualan->diskon->jumlah_potongan }}%
                                                                    dengan maksimum potongan sebesar Rp.
                                                                    {{ number_format($reservasi->penjualan->diskon->maksimum_potongan, 2, ',', '.') }}
                                                                </h5>
                                                            </footer>
                                                        </blockquote>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        <div class="col-md-12">
                                            <div class="row">
                                                @if ($reservasi->penjualan->diskon == null && $jumlahDiskonValid > 0)
                                                    <div class="col-6 mt-2 text-left">
                                                        <address>
                                                            @if ($reservasi->penjualan->status_selesai == 'belum')
                                                                <a class="btn btn-lg btn-info waves-effect waves-light mt-3"
                                                                    style="width: 40%"
                                                                    href="{{ route('admin.diskons.pilihdiskon', $reservasi->penjualan->id) }}">Pilih
                                                                    Diskon</a>
                                                            @endif

                                                        </address>
                                                    </div>
                                                    <div class="col-6 text-right">
                                                        <address>
                                                            {{-- <h6 class="mt-3" style="font-weight: normal">Total Potongan:
                                                            </h6> --}}
                                                            @php
                                                                $jumlahPotongan = 0;
                                                                if ($reservasi->penjualan->diskon != null) {
                                                                    $jumlahPotongan = ($reservasi->penjualan->total_pembayaran * $reservasi->penjualan->diskon->jumlah_potongan) / 100;
                                                                    if ($jumlahPotongan > $reservasi->penjualan->diskon->maksimum_potongan) {
                                                                        $jumlahPotongan = $reservasi->penjualan->diskon->maksimum_potongan;
                                                                    }
                                                                }

                                                            @endphp
                                                            {{-- <h4>
                                                                <strong>
                                                                    Rp. {{ number_format($jumlahPotongan, 2, ',', '.') }}
                                                                </strong>
                                                            </h4> --}}
                                                        </address>
                                                    </div>
                                                @else
                                                    <div class="col-12 text-right">
                                                        <address>
                                                            {{-- <h6 class="mt-3" style="font-weight: normal">Total Potongan:
                                                            </h6> --}}
                                                            @php
                                                                $jumlahPotongan = 0;
                                                                if ($reservasi->penjualan->diskon != null) {
                                                                    $jumlahPotongan = ($reservasi->penjualan->total_pembayaran * $reservasi->penjualan->diskon->jumlah_potongan) / 100;
                                                                    if ($jumlahPotongan > $reservasi->penjualan->diskon->maksimum_potongan) {
                                                                        $jumlahPotongan = $reservasi->penjualan->diskon->maksimum_potongan;
                                                                    }
                                                                }

                                                            @endphp
                                                            {{-- <h4>
                                                                <strong>
                                                                    Rp. {{ number_format($jumlahPotongan, 2, ',', '.') }}
                                                                </strong>
                                                            </h4> --}}
                                                        </address>
                                                    </div>
                                                @endif

                                            </div>
                                        </div>


                                    </div>
                                </div>

                                <div class="form-group col-md-12 mt-3">
                                    <div class="row">
                                        <div class="col-7 mt-3">
                                            <address>

                                                @if ($reservasi->penjualan->status_selesai == 'belum')
                                                    @if (date('Y-m-d', strtotime($reservasi->tanggal_reservasi)) >= date('Y-m-d'))
                                                        <a class="btn btn-lg btn-info waves-effect waves-light mt-3 mr-3"
                                                            href={{ route('reservasis.index') }}><i
                                                                class="mdi mdi-keyboard-backspace"></i>
                                                            &nbsp;Daftar Reservasi</a>
                                                    @else
                                                        <a class="btn btn-lg btn-info waves-effect waves-light mt-3 mr-3"
                                                            href={{ route('riwayatreservasis.index') }}><i
                                                                class="mdi mdi-keyboard-backspace"></i>
                                                            &nbsp;Daftar Riwayat Reservasi</a>
                                                    @endif

                                                    <button
                                                        class="btn btn-lg btn-danger waves-effect waves-light mt-3 mr-3"
                                                        data-toggle="modal"
                                                        data-target="#modalKonfirmasiBatalkanReservasi"
                                                        id="btnKonfirmasiBatalkanReservasi"
                                                        namaPelanggan = "{{ $reservasi->penjualan->pelanggan->nama }}"
                                                        nomorNotaPenjualan = "{{ $reservasi->penjualan->nomor_nota }}">
                                                        Batalkan Reservasi</button>
                                                    <button class="btn btn-lg btn-primary waves-effect waves-light mt-3"
                                                        data-toggle="modal" data-target="#modalKonfirmasiSelesaiReservasi"
                                                        id="btnKonfirmasiSelesaiReservasi"
                                                        namaPelanggan = "{{ $reservasi->penjualan->pelanggan->nama }}"
                                                        nomorNotaPenjualan = "{{ $reservasi->penjualan->nomor_nota }}">
                                                        Selesai Reservasi</button>
                                                @else
                                                    @if (date('Y-m-d', strtotime($reservasi->tanggal_reservasi)) >= date('Y-m-d'))
                                                        <a class="btn btn-lg btn-info waves-effect waves-light mt-3 mr-3"
                                                            href={{ route('reservasis.index') }}><i
                                                                class="mdi mdi-keyboard-backspace"></i>
                                                            &nbsp;Daftar Reservasi</a>
                                                    @else
                                                        <a class="btn btn-lg btn-info waves-effect waves-light mt-3 mr-3"
                                                            href={{ route('riwayatreservasis.index') }}><i
                                                                class="mdi mdi-keyboard-backspace"></i>
                                                            &nbsp;Daftar Riwayat Reservasi</a>
                                                    @endif
                                                @endif
                                            </address>
                                        </div>
                                        <div class="col-5 text-right">
                                            <address>
                                                <h4 style="font-weight: normal;">Total : </h4>
                                                <h2 class="text-danger fw-bold">Rp.
                                                    {{ number_format($reservasi->penjualan->total_pembayaran, 2, ',', '.') }}
                                                </h2>
                                            </address>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="modalKonfirmasiBatalkanReservasi" class="modal fade bs-example-modal-center" tabindex="-1"
                        role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title mt-0">Konfirmasi Pembatalan Reservasi</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body text-center" id="modalBodyBatalReservasi">
                                    <h6>Apakah Anda yakin ingin membatalkan reservasi ini?</h6>
                                </div>
                                <div class="modal-footer">
                                    <form action="{{ route('reservasi.admin.batalkan') }}" method="post">
                                        @csrf
                                        <input type="hidden" name="idReservasiBatal" value="{{ $reservasi->id }}">
                                        <button type="button" class="btn btn-danger waves-effect mr-2"
                                            data-dismiss="modal">Tidak</button>
                                        <button type="submit" class="btn btn-info waves-effect">Ya</button>
                                    </form>


                                </div>
                            </div>
                            <!-- /.modal-content -->
                        </div>
                        <!-- /.modal-dialog -->
                    </div>

                    <div id="modalKonfirmasiSelesaiReservasi" class="modal fade bs-example-modal-center" tabindex="-1"
                        role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title mt-0">Konfirmasi Selesaikan Reservasi</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body text-center" id="modalBodySelesaiReservasi">
                                    <h6>Apakah Anda yakin ingin menyelesaikan reservasi ini?</h6>
                                </div>
                                <div class="modal-footer">
                                    <form action="{{ route('reservasi.admin.selesai') }}" method="post">
                                        @csrf
                                        <input type="hidden" name="idReservasiSelesai" value="{{ $reservasi->id }}">
                                        <button type="button" class="btn btn-danger waves-effect mr-2"
                                            data-dismiss="modal">Tidak</button>
                                        <button type="submit" class="btn btn-info waves-effect">Ya</button>
                                    </form>


                                </div>
                            </div>
                            <!-- /.modal-content -->
                        </div>
                        <!-- /.modal-dialog -->
                    </div>



                </div>
            </div>
        </div>
        <!-- end col -->
    </div>
@endsection

@section('javascript')
    <script>
        $('body').on('click', '#btnKonfirmasiBatalkanReservasi', function() {
            var namaPelanggan = $(this).attr('namaPelanggan');
            var nomorNota = $(this).attr('nomorNotaPenjualan');
            $("#modalBodyBatalReservasi").html(
                "<p class='h6 font-weight-normal'>Apakah Anda yakin untuk membatalkan reservasi dengan nomor nota <span class='text-danger h6'>" +
                nomorNota +
                "</span> atas nama <span class='text-danger h6'>" + namaPelanggan + "</span>?</p>");

        });

        $('body').on('click', '#btnKonfirmasiSelesaiReservasi', function() {
            var namaPelanggan = $(this).attr('namaPelanggan');
            var nomorNota = $(this).attr('nomorNotaPenjualan');
            $("#modalBodySelesaiReservasi").html(
                "<p class='h6 font-weight-normal'>Apakah Anda yakin untuk menyelasikan reservasi dengan nomor nota <span class='text-danger h6'>" +
                nomorNota +
                "</span> atas nama <span class='text-danger h6'>" + namaPelanggan + "</span>?</p>");

        });
    </script>
@endsection
