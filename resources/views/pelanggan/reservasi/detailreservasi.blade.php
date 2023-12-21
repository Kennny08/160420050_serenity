@extends('layout.pelangganlayout')

@section('title', 'Pelanggan || Detail Reservasi')


@section('pelanggancontent')
    <style>
        @media only screen and (max-width: 480px) {
            .classNamaNomorNota * {
                text-align: center;
            }

            #iconBackBeranda {
                display: none;
            }
        }

        .custom-tooltip {
            position: relative;
        }

        .custom-tooltip::after {
            content: attr(data-tooltip);
            position: absolute;
            top: 50%;
            left: calc(100% + 5px);
            transform: translate(0, -50%);
            padding: 5px;
            background-color: #333;
            color: #fff;
            border-radius: 3px;
            white-space: nowrap;
            display: none;
            font-size: 0.8em;
        }

        .custom-tooltip:hover::after {
            display: block;
        }

        .nav-pills .nav-item .nav-link.active {
            background-color: #273ed4d8;
            color: #fff;
            font-size: 1.2em;
            font-weight: bold;
        }

        .text-serentity {
            color: #273ED4;
            font-weight: bold;
        }
    </style>

    <div class="row" style="padding: 30px;">
        <div class="col-12">
            <div>
                <div class="card-body">


                    <div class="row">
                        <div class="col-md-9 col-sm-12 col-xs-12">
                            <h3 class="mt-0 header-title fw-bold">Detail Reservasi</h3>
                        </div>
                        <div class="col-md-3 col-sm-12 col-xs-12">
                            <div class="product-details-content quickview-content">
                                <div class="pro-details-quality" style="padding: 0px;margin: 0px;">
                                    <div class="pro-details-cart ml-auto" style="width: 100%;">
                                        <a id="btnDetailNota" class="add-cart"
                                            style="background-color: #273ED4; margin-left: auto;width: 100%"
                                            href={{ route('penjualans.pelanggan.detailnotareservasipenjualan', $reservasi->id) }}>
                                            <span><i style="font-size: 20px; margin-top: 5px;"
                                                    class="pe-7s-news-paper "></i>
                                                &nbsp;Detail Nota Reservasi</a></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    </p>
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible" role="alert">
                            <button style="float: right; position: absolute;top: 0;right: 0;padding: 0.75rem 1.25rem"
                                type="button" class="close text-danger" data-bs-dismiss="alert" aria-label="Close">
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
                            <button style="float: right; position: absolute;top: 0;right: 0;padding: 0.75rem 1.25rem"
                                type="button" class="close text-danger" data-bs-dismiss="alert" aria-label="Close">
                                <span class="text-danger" aria-hidden="true">&times;</span>
                            </button>

                            @foreach (session('status')['message'] as $item)
                                <p class="mt-0 mb-1">- {{ $item }}</p>
                            @endforeach
                        </div>
                    @elseif(session('status'))
                        <div class="alert alert-success alert-dismissible" role="alert">
                            <button style="float: right; position: absolute;top: 0;right: 0;padding: 0.75rem 1.25rem"
                                type="button" class="close text-danger" data-bs-dismiss="alert" aria-label="Close">
                                <span class="text-danger" aria-hidden="true">&times;</span>
                            </button>
                            {{ session('status') }}
                        </div>
                    @endif


                    <div class="form-group">
                        <div class="row classNamaNomorNota">
                            <div class="col-md-8">
                                <address>
                                    <h3>{{ $reservasi->penjualan->pelanggan->nama }}</h3>
                                </address>
                            </div>
                            <div class="col-md-4 text-right" style="text-align: right;">
                                <h3>{{ $reservasi->penjualan->nomor_nota }}</h3>

                            </div>
                        </div>
                    </div>

                    <div class="form-group col-md-12">
                        <div class="row">
                            <div class="col-6">
                                <address>
                                    <strong class="font-weight-bold  font-16">Tanggal dan Jam
                                        Reservasi:</strong><br>
                                    {{ date('d-m-Y', strtotime($reservasi->tanggal_reservasi)) }} ||
                                    {{ $jamMulai->jam }}
                                </address>
                            </div>
                            <div class="col-6 text-right" style="text-align: right;">
                                <address>
                                    <strong class="font-weight-bold font-16">Waktu Pembuatan
                                        Reservasi:</strong><br>
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
                            <div class="col-6 text-right" style="text-align: right;">
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

                                        if (count($arrKomplemen) > 0) {
                                            $totalDurasi += $arrKomplemen['durasiterlama'];
                                        }

                                    @endphp
                                    {{ $totalDurasi }} Menit
                                </address>
                            </div>
                            <div class="col-6 text-right" style="text-align: right;">
                                <address>
                                    <strong class="font-weight-bold font-16">Status Reservasi:</strong><br>
                                    @if (
                                        $reservasi->status == 'dibatalkan salon' ||
                                            $reservasi->status == 'dibatalkan pelanggan' ||
                                            $reservasi->status == 'tidak hadir')
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
                        <div style="border-radius: 5px;">
                            <div class="card-body">


                                <!-- Nav tabs -->
                                <ul style="border-radius: 5px; border: 1px solid rgba(0, 0, 0, 0.1); box-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1)"
                                    class="nav nav-pills nav-justified" role="tablist">
                                    <li class="nav-item waves-effect waves-light">
                                        <a class="nav-link text-serentity active" data-bs-toggle="tab" href="#perawatan"
                                            role="tab">
                                            <span class="d-none d-md-block " style="font-size: 1.1em;">Perawatan</span>

                                            <span class="d-block d-md-none"><i class="fa fa-scissors"
                                                    aria-hidden="true"></i></span>
                                        </a>
                                    </li>
                                    <li class="nav-item waves-effect waves-light">
                                        <a class="nav-link text-serentity" data-bs-toggle="tab" href="#produk"
                                            role="tab">
                                            <span class="d-none d-md-block" style="font-size: 1.1em;">Produk</span><span
                                                class="d-block d-md-none"><i class="fa fa-shopping-bag"
                                                    aria-hidden="true"></i>
                                        </a>
                                    </li>
                                    <li class="nav-item waves-effect waves-light">
                                        <a class="nav-link text-serentity" data-bs-toggle="tab" href="#diskon"
                                            role="tab">
                                            <span class="d-none d-md-block" style="font-size: 1.1em;">Diskon</span>
                                            <span class="d-block d-md-none"><i class="fa fa-percent"
                                                    aria-hidden="true"></i></span>

                                        </a>
                                    </li>
                                    <li class="nav-item waves-effect waves-light">
                                        <a class="nav-link text-serentity" data-bs-toggle="tab" href="#ulasan"
                                            role="tab">
                                            <span class="d-none d-md-block" style="font-size: 1.1em;">Ulasan</span>
                                            <span class="d-block d-md-none"><i class="fa fa-star"
                                                    aria-hidden="true"></i></span>

                                        </a>
                                    </li>

                                </ul>

                                <!-- Tab panes -->
                                <div style="border-radius: 5px" class="tab-content shadow">
                                    <div class="tab-pane active" style="padding: 20px;" id="perawatan" role="tabpanel">
                                        <div class="mb-2">
                                            <div class="d-inline-block">
                                                <h3 class="mt-0 header-title">Perawatan Sekuensial/Berurutan
                                                </h3>
                                            </div>

                                            <div class="d-inline-block ml-5px">
                                                <button style="font-size: 20px" type="button"
                                                    class="btn waves-effect waves-light custom-tooltip"
                                                    data-bs-toggle="tooltip" data-bs-placement="right"
                                                    data-tooltip="Perawatan akan dilakukan secara terpisah atau bertahap">
                                                    <i style="background-color: #DFCFF9; border-radius: 3px; padding: 5px;"
                                                        class="fa fa-info-circle" aria-hidden="true"></i>

                                                </button>

                                            </div>
                                        </div>

                                        <div class="table-responsive table_page">
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
                                                    @if (count($perawatanSlotJamNonKomplemen) > 0)
                                                        @foreach ($perawatanSlotJamNonKomplemen as $ps)
                                                            <tr class="text-center align-middle">
                                                                <td>
                                                                    @if ($ps['namapaket'] == 'null')
                                                                        {{ $ps['penjualanperawatannonkomplemen']->perawatan->nama }}
                                                                    @else
                                                                        {{ $ps['penjualanperawatannonkomplemen']->perawatan->nama }}
                                                                        <br>
                                                                        <span class="text-serentity font-weight-bold">*
                                                                            {{ $ps['namapaket'] }}
                                                                        </span>
                                                                    @endif

                                                                </td>
                                                                <td>{{ $ps['jammulai'] }}</td>
                                                                <td>{{ $ps['durasi'] }}</td>
                                                                {{-- <td>{{ number_format($ps['penjualanperawatannonkomplemen']->harga, 2, ',', '.') }}
                                                            </td> --}}
                                                                <td>
                                                                    @if (in_array($ps['karyawan']->id, $karyawansIzinSakit))
                                                                        @foreach ($keteranganKaryawanIzinSakit as $objIzinSakit)
                                                                            @if ($objIzinSakit['idKaryawan'] == $ps['karyawan']->id)
                                                                                <span class="text-danger">
                                                                                    {{ $ps['karyawan']->nama }}
                                                                                    <br>
                                                                                    ({{ $objIzinSakit['keterangan'] }})
                                                                                </span>
                                                                            @break
                                                                        @endif
                                                                    @endforeach
                                                                @else
                                                                    {{ $ps['karyawan']->nama }}
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @else
                                                    <tr class="text-center align-middle">
                                                        <td colspan="4">
                                                            Tidak terdapat perawatan yang termasuk perawatan
                                                            sekuensial!
                                                        </td>
                                                    </tr>
                                                @endif

                                            </tbody>
                                        </table>
                                    </div>

                                    <br>
                                    <div class="mb-2">
                                        <div class="d-inline-block">
                                            <h3 class="mt-0 header-title">Perawatan Bersamaan</h3>
                                        </div>



                                        <div class="d-inline-block ml-5px">
                                            <button style="font-size: 20px" type="button"
                                                class="btn waves-effect waves-light custom-tooltip"
                                                data-bs-toggle="tooltip" data-bs-placement="right"
                                                data-tooltip="Perawatan dapat dilakukan secara bersamaan pada durasi tertentu">
                                                <i style="background-color: #DFCFF9; border-radius: 3px; padding: 5px;"
                                                    class="fa fa-info-circle" aria-hidden="true"></i>

                                            </button>

                                        </div>
                                    </div>
                                    <div class="table-responsive table_page">
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
                                                @if (count($arrKomplemen) > 0)
                                                    @php
                                                        $counter = 1;
                                                    @endphp
                                                    @foreach ($arrKomplemen['perawatans'] as $ps)
                                                        @if ($counter == 1)
                                                            <tr class="text-center align-middle">
                                                                <td>
                                                                    @if ($ps['namapaket'] == 'null')
                                                                        {{ $ps['penjualanperawatankomplemen']->perawatan->nama }}
                                                                    @else
                                                                        {{ $ps['penjualanperawatankomplemen']->perawatan->nama }}
                                                                        <br>
                                                                        <span class="text-serentity font-weight-bold">*
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
                                                                <td>
                                                                    @if (in_array($ps['karyawan']->id, $karyawansIzinSakit))
                                                                        @foreach ($keteranganKaryawanIzinSakit as $objIzinSakit)
                                                                            @if ($objIzinSakit['idKaryawan'] == $ps['karyawan']->id)
                                                                                <span class="text-danger">
                                                                                    {{ $ps['karyawan']->nama }}
                                                                                    <br>
                                                                                    ({{ $objIzinSakit['keterangan'] }})
                                                                                </span>
                                                                            @break
                                                                        @endif
                                                                    @endforeach
                                                                @else
                                                                    {{ $ps['karyawan']->nama }}
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @else
                                                        <tr class="text-center  align-middle">
                                                            <td>
                                                                @if ($ps['namapaket'] == 'null')
                                                                    {{ $ps['penjualanperawatankomplemen']->perawatan->nama }}
                                                                @else
                                                                    {{ $ps['penjualanperawatankomplemen']->perawatan->nama }}
                                                                    <br>
                                                                    <span class="text-serentity font-weight-bold">*
                                                                        {{ $ps['namapaket'] }}
                                                                    </span>
                                                                @endif
                                                            </td>
                                                            {{-- <td>{{ number_format($ps['penjualanperawatankomplemen']->harga, 2, ',', '.') }}
                                                                </td> --}}
                                                            <td>
                                                                @if (in_array($ps['karyawan']->id, $karyawansIzinSakit))
                                                                    @foreach ($keteranganKaryawanIzinSakit as $objIzinSakit)
                                                                        @if ($objIzinSakit['idKaryawan'] == $ps['karyawan']->id)
                                                                            <span class="text-danger">
                                                                                {{ $ps['karyawan']->nama }}
                                                                                <br>
                                                                                ({{ $objIzinSakit['keterangan'] }})
                                                                            </span>
                                                                        @break
                                                                    @endif
                                                                @endforeach
                                                            @else
                                                                {{ $ps['karyawan']->nama }}
                                                            @endif
                                                        </td>

                                                    </tr>
                                                @endif
                                                @php
                                                    $counter = $counter + 1;
                                                @endphp
                                            @endforeach
                                        @else
                                            <tr class="text-center align-middle">
                                                <td colspan="4">
                                                    Tidak terdapat perawatan yang termasuk perawatan
                                                    bersamaan!
                                                </td>
                                            </tr>
                                        @endif

                                    </tbody>
                                </table>
                            </div>

                            <div class="form-group col-md-12" style="margin-top: 30px">
                                <div class="row">
                                    <div class="col-md-12 mt-2">
                                        <address>
                                            @if ($keteranganGantiKaryawan == true && $reservasi->penjualan->status_selesai == 'belum')
                                                <form
                                                    action="{{ route('reservasis.pelanggan.editpilihkaryawanperawatan') }}"
                                                    method="POST">
                                                    @csrf
                                                    <input type="hidden" value="{{ $reservasi->id }}"
                                                        name="hiddenIdReservasi">

                                                    <div class="product-details-content quickview-content">
                                                        <div class="pro-details-quality"
                                                            style="padding: 0px;margin: 0px;">
                                                            <div class="pro-details-cart ml-auto"
                                                                style="width: 100%;display: flex; gap: 10px;">


                                                                <button class="add-cart " type="submit"
                                                                    style="margin: 0px; margin-left: auto;">
                                                                    <span> Edit Karyawan Perawatan
                                                                    </span>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </form>
                                            @endif

                                        </address>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane p-4" id="produk" role="tabpanel">
                            <div class="table-responsive table_page">
                                <table id="tabelDaftarProduk"
                                    class="table table-bordered dt-responsive wrap text-center "
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
                                                <tr class="align-middle" id="tr_{{ $produk->id }}">
                                                    <td>{{ $produk->kode_produk }}</td>
                                                    <td>
                                                        {{ $produk->nama }}
                                                        @if (count($reservasi->penjualan->pakets) > 0)
                                                            @foreach ($reservasi->penjualan->pakets as $paket)
                                                                @if ($paket->produks->firstWhere('id', $produk->id) != null)
                                                                    <br>
                                                                    <span class="text-serentity fw-bold">*
                                                                        {{ $paket->nama }} -
                                                                        ({{ $paket->produks->firstWhere('id', $produk->id)->pivot->jumlah }})
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
                                                <td colspan="9">Tidak terdapat produk tambahan
                                                    yang dibeli!
                                                </td>
                                            </tr>
                                        @endif

                                    </tbody>
                                </table>
                            </div>


                            <div class="row" style="margin-top: 30px;">
                                <div class="col-md-12">
                                    <address>
                                        @if ($reservasi->penjualan->status_selesai == 'belum')
                                            <div class="product-details-content quickview-content">
                                                <div class="pro-details-quality"
                                                    style="padding: 0px;margin: 0px;">
                                                    <div class="pro-details-cart ml-auto"
                                                        style="width: 100%;display: flex;">


                                                        <a class="add-cart " type="button"
                                                            href="{{ route('penjualans.pelanggan.penjualantambahproduk', $reservasi->penjualan->id) }}"
                                                            style="margin: 0px;margin-left: auto;">
                                                            <span>
                                                                Edit Penambahan Produk
                                                            </span>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                    </address>
                                </div>

                            </div>

                        </div>
                        <div class="tab-pane text-center" id="diskon" role="tabpanel"
                            style="margin-bottom: 0; padding-left: 50px;padding-right: 50px;">

                            @if ($reservasi->penjualan->diskon == null)
                                <div class="card text-white bg-danger text-center w-60 mx-auto">
                                    <div class="card-body">
                                        <blockquote class="card-bodyquote mb-0" style="color: white;">
                                            <h4 style="color: white;">
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
                                                        <h5 style="color: white;">Silahkan pilih diskon yang
                                                            tersedia!
                                                            (Tersedia
                                                            {{ $jumlahDiskonValid }} yang
                                                            berlaku)</h5>
                                                    @else
                                                        <h5 style="color: white;">Tidak terdapat Diskon yang
                                                            tersedia
                                                        </h5>
                                                    @endif
                                                @endif

                                            </footer>
                                        </blockquote>
                                    </div>
                                </div>
                            @else
                                <div class="col-md-12 text-center">
                                    <div class="card text-white text-center w-60 mx-auto"
                                        style="background-color: #DFCFF9">
                                        <div class="card-body">
                                            <blockquote class="card-bodyquote mb-0">
                                                <h4 class="">
                                                    {{ $reservasi->penjualan->diskon->nama }}
                                                </h4>
                                                <footer class=" text-white font-12">
                                                    <h5 class="">Potongan sebesar
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

                            <div class="col-md-12" style="margin-top: 45px;">
                                <div class="row">
                                    @if ($reservasi->penjualan->diskon == null && $jumlahDiskonValid > 0)
                                        <div class="col-md-12 mt-2 text-left">
                                            <address>

                                                @if ($reservasi->penjualan->status_selesai == 'belum')
                                                    <div class="product-details-content quickview-content">
                                                        <div class="pro-details-quality"
                                                            style="padding: 0px;margin: 0px;">
                                                            <div class="pro-details-cart ml-auto"
                                                                style="width: 100%;display: flex; gap: 10px;">


                                                                <a class="add-cart " type="button"
                                                                    href="{{ route('diskons.pelanggan.pilihdiskon', $reservasi->penjualan->id) }}"
                                                                    style="margin: 0px; margin-left: auto;">
                                                                    <span>
                                                                        Pilih
                                                                        Diskon
                                                                    </span>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif

                                            </address>
                                        </div>
                                        <div class="col-6 text-right">
                                            <address>
                                                @php
                                                    $jumlahPotongan = 0;
                                                    if ($reservasi->penjualan->diskon != null) {
                                                        $jumlahPotongan = ($reservasi->penjualan->total_pembayaran * $reservasi->penjualan->diskon->jumlah_potongan) / 100;
                                                        if ($jumlahPotongan > $reservasi->penjualan->diskon->maksimum_potongan) {
                                                            $jumlahPotongan = $reservasi->penjualan->diskon->maksimum_potongan;
                                                        }
                                                    }

                                                @endphp
                                            </address>
                                        </div>
                                    @else
                                        <div class="col-12 text-right">
                                            <address>
                                                @php
                                                    $jumlahPotongan = 0;
                                                    if ($reservasi->penjualan->diskon != null) {
                                                        $jumlahPotongan = ($reservasi->penjualan->total_pembayaran * $reservasi->penjualan->diskon->jumlah_potongan) / 100;
                                                        if ($jumlahPotongan > $reservasi->penjualan->diskon->maksimum_potongan) {
                                                            $jumlahPotongan = $reservasi->penjualan->diskon->maksimum_potongan;
                                                        }
                                                    }

                                                @endphp
                                            </address>
                                        </div>
                                    @endif

                                </div>
                            </div>


                        </div>

                        <div class="tab-pane text-center" id="ulasan" role="tabpanel"
                            style="margin-bottom: 0;padding-left: 50px;padding-right: 50px;">

                            @if ($reservasi->penjualan->status_selesai == 'belum')
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card text-white bg-danger text-center w-60 ">
                                            <div class="card-body">
                                                <blockquote class="card-bodyquote mb-0" style="color: white;">
                                                    <h4 style="color: white;">
                                                        Belum dapat memberikan Ulasan!
                                                    </h4>
                                                    <footer class=" text-white font-12">
                                                        <h5 style="color: white;">Silahkan menunggu pihak salon
                                                            menyelesaikan reservasi terlebih dahulu
                                                        </h5>

                                                    </footer>
                                                </blockquote>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12" style="margin-top: 45px;">

                                    </div>

                                </div>
                            @elseif($reservasi->penjualan->status_selesai == 'batal')
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card text-white bg-danger text-center w-60 ">
                                            <div class="card-body">
                                                <blockquote class="card-bodyquote mb-0" style="color: white;">
                                                    <h4 style="color: white;">
                                                        Tidak dapat memberikan Ulasan!
                                                    </h4>
                                                    @if ($reservasi->status == 'tidak hadir')
                                                        <footer class=" text-white font-12">
                                                            <h5 style="color: white;">Reservasi ini memiliki
                                                                status dibatalkan karena ketidakhadiran Anda
                                                            </h5>

                                                        </footer>
                                                    @elseif($reservasi->status == 'dibatalkan pelanggan')
                                                        <footer class=" text-white font-12">
                                                            <h5 style="color: white;">Reservasi ini memiliki
                                                                status dibatalkan oleh Anda
                                                            </h5>

                                                        </footer>
                                                    @else
                                                        <footer class=" text-white font-12">
                                                            <h5 style="color: white;">Reservasi ini memiliki
                                                                status dibatalkan oleh Pihak Salon
                                                            </h5>

                                                        </footer>
                                                    @endif

                                                </blockquote>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12" style="margin-top: 45px;">

                                    </div>

                                </div>
                            @else
                                @if ($reservasi->penjualan->ulasan == null)
                                    <div class="description-review-wrapper">
                                        <div class="ratting-form-wrapper">
                                            <div class="ratting-form">
                                                <form action="{{ route('reservasis.ulasan.store') }}"
                                                    method="POST">
                                                    @csrf
                                                    <h4 class="fw-bold">
                                                        Silahkan masukkan ulasan Anda!
                                                    </h4>
                                                    <br>
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="rating-form-style form-submit">
                                                                <input type="hidden"
                                                                    value="{{ $reservasi->penjualan->id }}"
                                                                    name="hiddenIdPenjualanReservasi">
                                                                <textarea rows="8" cols="50" name="ulasan" placeholder="Masukkan ulasan Anda"
                                                                    style="margin-top: 0;border-radius: 5px; margin-bottom: 30px;;"></textarea>

                                                                <div
                                                                    class="product-details-content quickview-content">
                                                                    <div class="pro-details-quality"
                                                                        style="padding: 0px;margin: 0px;">
                                                                        <div class="pro-details-cart ml-auto"
                                                                            style="width: 100%;display: flex; gap: 10px;">

                                                                            <button class="add-cart "
                                                                                type="submit"
                                                                                style="margin: 0px; margin-left: auto;">Simpan
                                                                                Ulasan
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12"
                                                                    style="margin-top: 45px;">

                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="row">
                                        <div class="col-md-12 text-center">
                                            <div class="card text-white text-center w-60 mx-auto"
                                                style="background-color: #DFCFF9">
                                                <div class="card-body">
                                                    <blockquote class="card-bodyquote mb-0">
                                                        <h4 class="fw-bold">
                                                            {{ $reservasi->penjualan->ulasan->ulasan }}
                                                        </h4>
                                                        {{-- <footer class=" text-white font-12">
                                                        <h5 class="">Potongan sebesar
                                                            {{ $reservasi->penjualan->diskon->jumlah_potongan }}%
                                                            dengan maksimum potongan sebesar Rp.
                                                            {{ number_format($reservasi->penjualan->diskon->maksimum_potongan, 2, ',', '.') }}
                                                        </h5>
                                                    </footer> --}}
                                                    </blockquote>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12" style="margin-top: 45px;">

                                        </div>
                                    </div>
                                @endif

                            @endif


                        </div>
                    </div>

                    <div class="form-group col-md-12 mt-3">
                        <div class="row">
                            <div class="col-md-7 mt-3">
                                <address>
                                    <div class="product-details-content quickview-content">
                                        <div class="pro-details-quality" style="padding: 0px; margin: 0px;">
                                            <div class="pro-details-cart"
                                                style="width: 100%; display: flex; align-items: center;">
                                                {{-- <button id="btnKonfirmasiPerawatan" class="add-cart"
                                                    onclick="goBack()" type="button" style="margin: 0px;">
                                                    <span><i class="fa fa-arrow-left"
                                                            aria-hidden="true"></i>&nbsp;&nbsp;Pilih
                                                        Perawatan</span>

                                                </button> --}}

                                                @if ($reservasi->penjualan->status_selesai == 'belum')
                                                    @if (date('Y-m-d', strtotime($reservasi->tanggal_reservasi)) >= date('Y-m-d'))
                                                        <a class="add-cart" style="background-color: #273ED4;"
                                                            href={{ route('pelanggans.index') }}>
                                                            <span><i id="iconBackBeranda"
                                                                    class="fa fa-arrow-left"
                                                                    aria-hidden="true"></i>
                                                                &nbsp;Beranda</span>
                                                        </a>
                                                    @else
                                                        <a class="add-cart" style="background-color: #273ED4;"
                                                            href={{ route('reservasis.riwayatreservasispelanggan.index') }}>
                                                            <span><i id="iconBackBeranda"
                                                                    class="fa fa-arrow-left"
                                                                    aria-hidden="true"></i>
                                                                &nbsp;Daftar Riwayat Reservasi</span>
                                                        </a>
                                                    @endif

                                                    <button class="add-cart" data-bs-toggle="modal"
                                                        data-bs-target="#modalKonfirmasiBatalkanReservasi"
                                                        id="btnKonfirmasiBatalkanReservasi"
                                                        namaPelanggan = "{{ $reservasi->penjualan->pelanggan->nama }}"
                                                        nomorNotaPenjualan = "{{ $reservasi->penjualan->nomor_nota }}">
                                                        Batalkan Reservasi</button>
                                                @else
                                                    @if (date('Y-m-d', strtotime($reservasi->tanggal_reservasi)) >= date('Y-m-d'))
                                                        <a class="add-cart" style="background-color: #273ED4;"
                                                            href={{ route('pelanggans.index') }}>
                                                            <span><i id="iconBackBeranda"
                                                                    class="fa fa-arrow-left"
                                                                    aria-hidden="true"></i>
                                                                &nbsp;Beranda</span>
                                                        </a>
                                                    @else
                                                        <a class="add-cart" style="background-color: #273ED4;"
                                                            href={{ route('reservasis.riwayatreservasispelanggan.index') }}>
                                                            <span><i id="iconBackBeranda"
                                                                    class="fa fa-arrow-left"
                                                                    aria-hidden="true"></i>
                                                                &nbsp;Daftar Riwayat Reservasi</span>
                                                        </a>
                                                    @endif
                                                @endif
                                            </div>
                                        </div>
                                    </div>


                                </address>
                            </div>
                            <div class="col-md-5 text-right" style="text-align: right">
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
                        <h4 class="modal-title mt-0">Konfirmasi Pembatalan Reservasi</h4>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body text-center" id="modalBodyBatalReservasi">
                        <h5>Apakah Anda yakin ingin membatalkan reservasi ini?</h5>
                    </div>
                    <div class="modal-footer">
                        <form action="{{ route('reservasis.pelanggan.batalkan') }}" method="post">
                            @csrf
                            <input type="hidden" name="idReservasiBatal" value="{{ $reservasi->id }}">


                            <div style="display: flex; gap: 10px;">
                                <div class="product-details-content quickview-content">
                                    <div class="pro-details-quality" style="padding: 0px;margin: 0px;">
                                        <div class="pro-details-cart ml-auto" style="width: 100%;">
                                            <button type="button" data-bs-dismiss="modal"
                                                class="btn close add-cart "
                                                style="margin: 0px; width: 100%; ">Tidak</button>

                                        </div>
                                    </div>
                                </div>
                                <div class="product-details-content quickview-content">
                                    <div class="pro-details-quality" style="padding: 0px;margin: 0px;">
                                        <div class="pro-details-cart ml-auto" style="width: 100%;">
                                            <button type="submit" class="add-cart "
                                                style="margin: 0px; width: 100%; background-color: #273ED4;">Ya</button>

                                        </div>
                                    </div>
                                </div>
                            </div>




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
            "<p class='h5 font-weight-normal'>Apakah Anda yakin untuk membatalkan reservasi dengan nomor nota<br> <span class='text-danger h5'>" +
            nomorNota +
            "</span> atas nama <span class='text-danger h5'>" + namaPelanggan + "</span>?</p>");

    });
</script>
@endsection
