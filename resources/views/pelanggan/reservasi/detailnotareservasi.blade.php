@extends('layout.pelangganlayout')

@section('title', 'Pelanggan || Detail Nota Reservasi')


@section('pelanggancontent')
    <style>
        @media print {
            body * {
                visibility: hidden;

            }

            #isiKonten,
            #isiKonten * {
                visibility: visible;
            }

            #isiKonten {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;

            }
        }


        .ulDetailPaket {
            padding-left: 30px;
        }

        .liDetailPaket {
            list-style-type: disc;
            margin-bottom: 5px;
        }
    </style>
    <div id="isiKonten">
        <div class="page-title-box">
        </div>
        <!-- end page-title -->

        <div class="row" style="padding: 20px;">
            <div class="col-12">
                <div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="invoice-title d-flex justify-content-between align-items-center">
                                    <h3 class="mt-0">
                                        <img src="{{ asset('assets_admin/images/logo-serenity/serenity-logo-no-background.png') }}"
                                            alt="logo" height="80" />
                                    </h3>
                                    <h3 class="float-right font-20"><strong>{{ $reservasi->penjualan->nomor_nota }}</strong>
                                    </h3>

                                </div>
                                <hr>
                                <div class="form-group col-md-12">
                                    <div class="row">
                                        <div class="col-6">
                                            <address>
                                                <strong class="font-weight-bold  font-16">Pelanggan:</strong><br>
                                                {{ $reservasi->penjualan->pelanggan->id }} -
                                                {{ $reservasi->penjualan->pelanggan->nama }} <br>
                                                {{ $reservasi->penjualan->pelanggan->alamat }} <br>
                                                {{ $reservasi->penjualan->pelanggan->nomor_telepon }}

                                            </address>
                                        </div>
                                        <div class="col-6 text-right" style="text-align: right;">
                                            <address>
                                                <strong class="font-weight-bold font-16">Salon:</strong><br>
                                                Serenity Salon <br>
                                                Jl. Bahari Jaya No.08 <br>
                                                082188888888
                                            </address>
                                        </div>
                                    </div>
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
                                                    $arrPerawatanNonKomplemen = [];
                                                    $arrPerawatanKomplemen = [];
                                                    foreach ($reservasi->penjualan->penjualanperawatans as $pp) {
                                                        if ($pp->perawatan->status_komplemen == 'ya') {
                                                            array_push($arrPerawatanKomplemen, $pp);
                                                        } else {
                                                            array_push($arrPerawatanNonKomplemen, $pp);
                                                        }
                                                    }

                                                    foreach ($arrPerawatanNonKomplemen as $pk) {
                                                        $jumlahSlot = $pk->slotjams->count();
                                                        $totalDurasi += $jumlahSlot * 30;
                                                    }

                                                    $daftarSlotPerawatanKomplemen = [];
                                                    foreach ($arrPerawatanKomplemen as $pnk) {
                                                        array_push($daftarSlotPerawatanKomplemen, $pnk->slotjams->count());
                                                    }
                                                    if (count($daftarSlotPerawatanKomplemen) > 0) {
                                                        $totalDurasi += max($daftarSlotPerawatanKomplemen) * 30;
                                                    }

                                                @endphp
                                                {{ $totalDurasi }} Menit
                                            </address>
                                        </div>
                                        <div class="col-6 text-right" style="text-align: right;">
                                            <address>
                                                <strong class="font-weight-bold font-16">Status Reservasi:</strong><br>
                                                @if ($reservasi->status == 'dibatalkan salon' || $reservasi->status == 'dibatalkan pelanggan' || $reservasi->status == 'tidak hadir')
                                                    <span
                                                        class="text-danger font-16 font-weight-bold">{{ $reservasi->status }}</span>
                                                @elseif($reservasi->status == 'selesai')
                                                    <span
                                                        class="text-success font-16 font-weight-bold">{{ $reservasi->status }}</span>
                                                @else
                                                    <span
                                                        class="text-warning font-16 font-weight-bold">{{ $reservasi->status }}</span>
                                                @endif

                                            </address>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="panel panel-default">
                                    <div class="p-2">
                                        <h3 class="panel-title font-20"><strong>Detail Item</strong></h3>
                                    </div>
                                    <div class="">
                                        <div class="table-responsive">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th class="h6">No.</th>
                                                        <th class="h6"><strong>Nama item</strong></th>
                                                        <th class="text-center h6"><strong>Harga (Rp)</strong></th>
                                                        <th class="text-center h6"><strong>Kuantitas</strong>
                                                        </th>
                                                        <th class="text-right h6"><strong>Total (Rp)</strong></th>
                                                    </tr>
                                                </thead>
                                                @php
                                                    $counter = 1;
                                                    $subTotal = 0;
                                                @endphp
                                                <tbody>
                                                    <!-- foreach ($order->lineItems as $line) or some such thing here -->
                                                    @foreach ($arrPaket as $paket)
                                                        <tr class="align-middle">
                                                            <td class="h6 ">{{ $counter }}. </td>
                                                            <td>
                                                                <span
                                                                    class="h6 font-weight-bold">{{ $paket->nama }}</span>
                                                                <br>
                                                                Perawatan:
                                                                <ul class="ulDetailPaket">
                                                                    @foreach ($paket->perawatans()->withPivot("urutan")->orderBy("urutan")->get() as $perawatan)
                                                                        <li class="liDetailPaket">
                                                                            <span class="">
                                                                                {{ $perawatan->nama }}
                                                                            </span>
                                                                        </li>
                                                                    @endforeach
                                                                </ul>
                                                                @if (count($paket->produks) > 0)
                                                                    Produk:
                                                                    <ul class="ulDetailPaket">
                                                                        @foreach ($paket->produks as $produk)
                                                                            <li class="liDetailPaket">
                                                                                <span class="">
                                                                                    {{ $produk->nama }} -
                                                                                    ({{ $produk->pivot->jumlah }})
                                                                                </span>
                                                                            </li>
                                                                        @endforeach
                                                                    </ul>
                                                                @endif

                                                            </td>
                                                            <td class="text-center h6 font-weight-normal">
                                                                {{ number_format($paket->pivot->harga, 2, ',', '.') }}</td>
                                                            <td class="text-center h6 font-weight-normal">1</td>
                                                            <td class="text-right h6 font-weight-normal">
                                                                {{ number_format($paket->pivot->harga, 2, ',', '.') }}</td>
                                                            @php
                                                                $subTotal += $paket->pivot->harga;
                                                            @endphp
                                                        </tr>
                                                        @php
                                                            $counter++;
                                                        @endphp
                                                    @endforeach

                                                    @foreach ($arrPerawatan as $perawatan)
                                                        <tr class="align-middle">
                                                            <td class="h6 ">{{ $counter }}. </td>
                                                            <td class="h6 font-weight-bold">
                                                                {{ $perawatan->perawatan->nama }}
                                                            </td>
                                                            <td class="text-center h6 font-weight-normal">
                                                                {{ number_format($perawatan->harga, 2, ',', '.') }}</td>
                                                            <td class="text-center h6 font-weight-normal">1</td>
                                                            <td class="text-right h6 font-weight-normal">
                                                                {{ number_format($perawatan->harga, 2, ',', '.') }}</td>
                                                            @php
                                                                $subTotal += $perawatan->harga;
                                                            @endphp
                                                        </tr>

                                                        @php
                                                            $counter++;
                                                        @endphp
                                                    @endforeach

                                                    @foreach ($arrProduk as $produk)
                                                        <tr class="align-middle">
                                                            <td class="h6 ">{{ $counter }}. </td>
                                                            <td class="h6 font-weight-bold">{{ $produk['object']->nama }}
                                                            </td>
                                                            <td class="text-center h6 font-weight-normal">
                                                                {{ number_format($produk['harga'], 2, ',', '.') }}
                                                            </td>
                                                            <td class="text-center h6 font-weight-normal">
                                                                {{ $produk['kuantitas'] }}</td>
                                                            <td class="text-right h6 font-weight-normal">
                                                                {{ number_format($produk['subtotal'], 2, ',', '.') }}
                                                            </td>
                                                            @php
                                                                $subTotal += $produk['subtotal'];
                                                            @endphp
                                                        </tr>

                                                        @php
                                                            $counter++;
                                                        @endphp
                                                    @endforeach
                                                    <tr class="align-middle">
                                                        <td class="thick-line"></td>
                                                        <td class="thick-line"></td>
                                                        <td class="thick-line"></td>
                                                        <td class="h6 thick-line text-center">
                                                            <strong>Subtotal</strong>
                                                        </td>
                                                        <td class="h6 thick-line text-right">
                                                            {{ number_format($subTotal, 2, ',', '.') }}</td>
                                                    </tr>
                                                    <tr class="align-middle">
                                                        <td class="thick-line"></td>
                                                        <td class="no-line"></td>
                                                        <td class="no-line"></td>

                                                        @if ($reservasi->penjualan->diskon_id != null)
                                                            <td class="h6 no-line text-center">
                                                                <strong>Diskon</strong><br>
                                                                <strong
                                                                    style="color: #273ED4;">{{ $reservasi->penjualan->diskon->nama }}</strong>
                                                            </td>
                                                            <td class="h6 no-line text-right">
                                                                @php
                                                                    $jumlahDiskon = ($reservasi->penjualan->diskon->jumlah_potongan * $subTotal) / 100;
                                                                    if ($jumlahDiskon > $reservasi->penjualan->diskon->maksimum_potongan) {
                                                                        $jumlahDiskon = $reservasi->penjualan->diskon->maksimum_potongan;
                                                                    }
                                                                @endphp
                                                                <span
                                                                    class="text-danger">({{ number_format($jumlahDiskon, 2, ',', '.') }})</span>
                                                            </td>
                                                        @else
                                                            @php
                                                                $jumlahDiskon = 0;
                                                            @endphp
                                                            <td class="h6 no-line text-center">
                                                                <strong>Diskon</strong>
                                                            </td>
                                                            <td class="h6 no-line text-right"><span
                                                                    class="text-danger">(0,00)</span></td>
                                                        @endif

                                                    </tr>
                                                    <tr class="align-middle">
                                                        <td class="thick-line"></td>
                                                        <td class="no-line"></td>
                                                        <td class="no-line"></td>
                                                        <td class="h5 font-weight-bold no-line text-center">
                                                            <strong>Total</strong>
                                                        </td>
                                                        <td class="no-line text-right">
                                                            <h3 class="m-0">
                                                                {{ number_format($subTotal - $jumlahDiskon, 2, ',', '.') }}
                                                            </h3>
                                                        </td>
                                                    </tr>

                                                </tbody>
                                            </table>
                                        </div>

                                        <div class="d-print-none mo-mt-2">
                                            <div class="row">
                                                <div class="col-md-2">
                                                    <div class="product-details-content quickview-content">
                                                        <div class="pro-details-quality"
                                                            style="padding: 0px;margin: 0px;">
                                                            <div class="pro-details-cart ml-auto" style="width: 100%;">

                                                                <a class="add-cart" style="margin: 0px; width: 100%;"
                                                                    href={{ route('reservasis.pelanggan.detailreservasi', $reservasi->id) }}>
                                                                    <span><i id="iconBackBeranda" class="fa fa-arrow-left"
                                                                            aria-hidden="true"></i>
                                                                        &nbsp; Detail Reservasi</span>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="col-md-8 mt-2">

                                                </div>
                                                <div class="col-md-2">
                                                    <div class="float-right">
                                                        <div class="product-details-content quickview-content">
                                                            <div class="pro-details-quality"
                                                                style="padding: 0px;margin: 0px;">
                                                                <div class="pro-details-cart ml-auto"
                                                                    style="width: 100%;">

                                                                    <a href="javascript:window.print()" class="add-cart"
                                                                        style="margin: 0px; width: 100%; background-color: #273ED4;">
                                                                        <span><i class="fa fa-print">
                                                                            </i> &nbsp;
                                                                            Cetak Nota</span>

                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>

                                            </div>

                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <!-- end row -->

                    </div>
                </div>
            </div>
            <!-- end col -->
        </div>
    </div>

@endsection

@section('javascript')
    <script></script>
@endsection
