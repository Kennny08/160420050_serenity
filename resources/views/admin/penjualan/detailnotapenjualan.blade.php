@extends('layout.adminlayout')

@section('title', 'Admin || Detail Nota Penjualan')


@section('admincontent')
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

            #btnTest {
                visibility: visible;
            }


        }
    </style>
    <div id="isiKonten">
        <div class="page-title-box">
        </div>
        <!-- end page-title -->

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="invoice-title d-flex justify-content-between align-items-center">
                                    <h3 class="mt-0">
                                        <img src="{{ asset('assets_admin/images/logo-serenity/serenity-logo-no-background.png') }}"
                                            alt="logo" height="80" />
                                    </h3>
                                    <h3 class="float-right font-20"><strong>{{ $penjualan->nomor_nota }}</strong>
                                    </h3>

                                </div>
                                <hr>
                                <div class="form-group col-md-12">
                                    <div class="row">
                                        <div class="col-6">
                                            <address>
                                                <strong class="font-weight-bold  font-16">Pelanggan:</strong><br>
                                                {{ $penjualan->pelanggan->id }} -
                                                {{ $penjualan->pelanggan->nama }} <br>
                                                {{ $penjualan->pelanggan->alamat }} <br>
                                                {{ $penjualan->pelanggan->nomor_telepon }}

                                            </address>
                                        </div>
                                        <div class="col-6 text-right">
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
                                                    Penjualan:</strong><br>
                                                {{ date('d-m-Y', strtotime($penjualan->tanggal_penjualan)) }} ||
                                                {{ $jamMulai->jam }}
                                            </address>
                                        </div>
                                        <div class="col-6 text-right">
                                            <address>
                                                <strong class="font-weight-bold font-16">Waktu Pembuatan
                                                    Reservasi:</strong><br>
                                                {{ date('d-m-Y', strtotime($penjualan->created_at)) }} ||
                                                {{ date('H:i', strtotime($penjualan->created_at)) }}
                                            </address>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6">
                                            <address>
                                                <strong class="font-weight-bold  font-16">Jumlah Perawatan:</strong><br>
                                                {{ count($penjualan->penjualanperawatans) }} Perawatan
                                            </address>
                                        </div>
                                        <div class="col-6 text-right">
                                            <address>
                                                <strong class="font-weight-bold font-16">Jumlah Produk:</strong><br>
                                                {{ count($penjualan->produks) }} Produk
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
                                                    foreach ($penjualan->penjualanperawatans as $pp) {
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
                                        <div class="col-6 text-right">
                                            <address>
                                                <strong class="font-weight-bold font-16">Status Penjualan:</strong><br>
                                                @if ($penjualan->status_selesai == 'batal')
                                                    <span
                                                        class="text-danger font-16 font-weight-bold">{{ $penjualan->status_selesai }}</span>
                                                @elseif($penjualan->status_selesai == 'selesai')
                                                    <span
                                                        class="text-success font-16 font-weight-bold">{{ $penjualan->status_selesai }}</span>
                                                @else
                                                    <span class="text-warning font-16 font-weight-bold">Menunggu Konfirmasi
                                                        Salon</span>
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
                                                        <tr>
                                                            <td class="h6 ">{{ $counter }}. </td>
                                                            <td>
                                                                <span
                                                                    class="h6 font-weight-bold">{{ $paket->nama }}</span>
                                                                <br>
                                                                Perawatan:
                                                                <ul>
                                                                    @foreach ($paket->perawatans()->withPivot("urutan")->orderBy("urutan")->get() as $perawatan)
                                                                        <li>
                                                                            <span class="">
                                                                                {{ $perawatan->nama }}
                                                                            </span>
                                                                        </li>
                                                                    @endforeach
                                                                </ul>
                                                                @if (count($paket->produks) > 0)
                                                                    Produk:
                                                                    <ul>
                                                                        @foreach ($paket->produks as $produk)
                                                                            <li>
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
                                                        <tr>
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
                                                        <tr>
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
                                                    <tr>
                                                        <td class="thick-line"></td>
                                                        <td class="thick-line"></td>
                                                        <td class="thick-line"></td>
                                                        <td class="h6 thick-line text-center">
                                                            <strong>Subtotal</strong>
                                                        </td>
                                                        <td class="h6 thick-line text-right">
                                                            {{ number_format($subTotal, 2, ',', '.') }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="thick-line"></td>
                                                        <td class="no-line"></td>
                                                        <td class="no-line"></td>

                                                        @if ($penjualan->diskon_id != null)
                                                            <td class="h6 no-line text-center">
                                                                <strong>Diskon</strong><br>
                                                                <strong class="text-info">{{ $penjualan->diskon->nama }}</strong>
                                                            </td>
                                                            <td class="h6 no-line text-right">
                                                                @php
                                                                    $jumlahDiskon = ($penjualan->diskon->jumlah_potongan * $subTotal) / 100;
                                                                    if ($jumlahDiskon > $penjualan->diskon->maksimum_potongan) {
                                                                        $jumlahDiskon = $penjualan->diskon->maksimum_potongan;
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
                                                    <tr>
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
                                                <div class="col-md-6">
                                                    <a class="btn btn-lg btn-info waves-effect waves-light mt-0"
                                                        href={{ route('penjualans.admin.detailpenjualan', $penjualan->id) }}><i
                                                            class="mdi mdi-keyboard-backspace"></i>
                                                        &nbsp; Detail Penjualan</a>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="float-right">
                                                        <a id="btnTest" href="javascript:window.print()"
                                                            class="btn btn-lg btn-success waves-effect waves-light">
                                                            <i class="fa fa-print">
                                                            </i>
                                                            Cetak Nota
                                                        </a>
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
