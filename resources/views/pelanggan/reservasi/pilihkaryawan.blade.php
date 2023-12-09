@extends('layout.pelangganlayout')

@section('title', 'Pelanggan || Buat Reservasi')

@section('pelanggancontent')
    <div class="row" style="padding: 20px;">
        <div class="col-12">
            <div>
                <div class="card-body">

                    <h4 class="mt-0 header-title">Pilih Karyawan untuk Reservasi</h4>
                    <p class="sub-title">
                    </p>
                    {{-- @if ($errors->any())
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
                    @endif --}}

                    @php
                        $keteranganNull = 0;
                    @endphp
                    @foreach ($perawatanSlotJamNonKomplemen as $ps)
                        @if (count($ps['karyawans']) == 0)
                            @php
                                $keteranganNull += 1;
                            @endphp
                        @endif
                    @endforeach

                    @foreach ($arrKomplemen['array'] as $ps)
                        @if (count($ps['karyawans']) == 0)
                            @php
                                $keteranganNull += 1;
                            @endphp
                        @endif
                    @endforeach

                    @if (isset($pesanError))
                        <div class="alert alert-danger alert-dismissible" role="alert">
                            <button style="float: right; position: absolute;top: 0;right: 0;padding: 0.75rem 1.25rem"
                                type="button" class="close text-danger" data-bs-dismiss="alert" aria-label="Close">
                                <span class="text-danger" aria-hidden="true">&times;</span>
                            </button>
                            <p class="mb-0"><strong>Maaf, terjadi kesalahan!</strong></p>
                            <p class="mt-0 mb-1">- {{ $pesanError }}</p>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('reservasis.pelanggan.konfirmasireservasi') }}"
                        enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="tanggalReservasi" value="{{ $tanggalReservasi }}">
                        <input type="hidden" name="slotJam" value="{{ $idSlotJam }}">
                        @foreach ($arrPerawatan as $p)
                            <input type="hidden" name="arrayperawatanid[]" value="{{ $p }}">
                        @endforeach
                        @foreach ($arrPaket as $pk)
                            <input type="hidden" name="arraypaketid[]" value="{{ $pk }}">
                        @endforeach
                        @foreach ($arrKodeKeseluruhan as $k)
                            <input type="hidden" name="arraykodekeseluruhan[]" value="{{ $k }}">
                        @endforeach
                        <div class="mb-2">
                            <div class="d-inline-block">
                                <h3 class="mt-0 header-title">Perawatan Sekuensial/Berurutan</h3>
                            </div>

                            <div class="d-inline-block ml-5px">
                                <button style="font-size: 20px" type="button" class="btn waves-effect waves-light"
                                    data-bs-toggle="tooltip" data-bs-placement="right"
                                    title="Perawatan dilakukan secara terpisah atau bertahap">
                                    <i style="background-color: #DFCFF9; border-radius: 3px; padding: 5px;"
                                        class="fa fa-info-circle" aria-hidden="true"></i>
                                </button>
                            </div>
                        </div>


                        <table class="table table-bordered">
                            <thead class="thead-default">
                                <tr class="text-center">
                                    <th>Nama Perawatan</th>
                                    <th>Jam Mulai</th>
                                    <th>Durasi</th>
                                    <th>Karyawan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (count($perawatanSlotJamNonKomplemen) > 0)
                                    @foreach ($perawatanSlotJamNonKomplemen as $ps)
                                        <tr class="text-center align-middle">
                                            <td>{{ $ps['perawatan']->nama }}</td>
                                            <td>{{ $ps['jammulai'] }}</td>
                                            <td>{{ $ps['perawatan']->durasi }}</td>
                                            <td>
                                                <div class="billing-info-wrap">
                                                    <div class="billing-select">
                                                        <select required class="selectkaryawan allkaryawan"
                                                            name="selectkaryawan[]">
                                                            @if (count($ps['karyawans']) == 0)
                                                                <option selected value="-">Tidak Tersedia Karyawan
                                                                </option>
                                                            @else
                                                                <option disabled selected value="null">Pilih Karyawan
                                                                </option>
                                                                @foreach ($ps['karyawans'] as $k)
                                                                    <option
                                                                        value="{{ $k->id }},{{ $ps['perawatan']->id }},({{ $ps['idslotjam'] }})">
                                                                        {{ $k->nama }}</option>
                                                                @endforeach
                                                            @endif

                                                        </select>
                                                    </div>
                                                </div>

                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr class="text-center">
                                        <td colspan="4">
                                            Tidak terdapat perawatan yang termasuk perawatan sekuensial!
                                        </td>
                                    </tr>
                                @endif

                            </tbody>
                        </table>
                        <br>
                        <div class="mb-2">
                            <div class="d-inline-block">
                                <h3 class="mt-0 header-title">Perawatan Bersamaan</h3>
                            </div>

                            <div class="d-inline-block">
                                <button style="font-size: 20px" type="button" class="btn waves-effect waves-light"
                                    data-bs-toggle="tooltip" data-bs-placement="right"
                                    title="Perawatan dapat dilakukan secara bersamaan pada durasi tertentu">
                                    <i style="background-color: #DFCFF9; border-radius: 3px; padding: 5px;"
                                        class="fa fa-info-circle" aria-hidden="true"></i>
                                </button>
                            </div>
                        </div>
                        <table class="table table-bordered">
                            <thead class="thead-default">
                                <tr class="text-center">
                                    <th>Nama Perawatan</th>
                                    <th>Jam Mulai</th>
                                    <th>Durasi</th>
                                    <th>Karyawan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (count($arrKomplemen['array']) > 0)
                                    @php
                                        $counter = 1;
                                    @endphp
                                    @foreach ($arrKomplemen['array'] as $ps)
                                        @if ($counter == 1)
                                            <tr class="text-center align-middle">
                                                <td>{{ $ps['perawatan']->nama }}</td>
                                                <td class="text-center align-middle"
                                                    rowspan="{{ count($arrKomplemen['array']) }}">
                                                    {{ $arrKomplemen['jammulai'] }}</td>
                                                <td class="text-center align-middle"
                                                    rowspan="{{ count($arrKomplemen['array']) }}">
                                                    {{ $arrKomplemen['durasiterlama'] }}
                                                </td>
                                                <td>
                                                    <div class="billing-info-wrap">
                                                        <div class="billing-select">
                                                            <select required class="selectkaryawankomplemen allkaryawan"
                                                                name="selectkaryawankomplemen[]">
                                                                @if (count($ps['karyawans']) == 0)
                                                                    <option selected value="-">Tidak Tersedia Karyawan
                                                                    </option>
                                                                @else
                                                                    <option disabled selected value="null">Pilih Karyawan
                                                                    </option>
                                                                    @foreach ($ps['karyawans'] as $k)
                                                                        <option
                                                                            value="{{ $k->id }},{{ $ps['perawatan']->id }},({{ $arrKomplemen['idslotjam'] }})">
                                                                            {{ $k->nama }}</option>
                                                                    @endforeach
                                                                @endif

                                                            </select>
                                                        </div>
                                                    </div>


                                                </td>
                                            </tr>
                                        @else
                                            <tr class="text-center align-middle">
                                                <td>{{ $ps['perawatan']->nama }}</td>
                                                <td>
                                                    <div class="billing-info-wrap">
                                                        <div class="billing-select">
                                                            <select required class="selectkaryawankomplemen allkaryawan"
                                                                name="selectkaryawankomplemen[]">
                                                                @if (count($ps['karyawans']) == 0)
                                                                    <option selected value="-">Tidak Tersedia Karyawan
                                                                    </option>
                                                                @else
                                                                    <option disabled selected value="null">Pilih Karyawan
                                                                    </option>
                                                                    @foreach ($ps['karyawans'] as $k)
                                                                        <option
                                                                            value="{{ $k->id }},{{ $ps['perawatan']->id }},({{ $arrKomplemen['idslotjam'] }})">
                                                                            {{ $k->nama }}</option>
                                                                    @endforeach
                                                                @endif
                                                            </select>
                                                        </div>
                                                    </div>

                                                </td>
                                            </tr>
                                        @endif
                                        @php
                                            $counter = $counter + 1;
                                        @endphp
                                    @endforeach
                                @else
                                    <tr class="text-center">
                                        <td colspan="4">
                                            Tidak terdapat perawatan yang termasuk perawatan bersamaan!
                                        </td>
                                    </tr>
                                @endif

                            </tbody>
                        </table>

                        <div class="row">
                            <div class="col-md-10 text-left">
                                <div class="product-details-content quickview-content">
                                    <div class="pro-details-quality" style="padding: 0px; margin: 0px;">
                                        <div class="pro-details-cart"
                                            style="width: 100%; display: flex; align-items: center;">
                                            <button id="btnKonfirmasiPerawatan" class="add-cart" onclick="goBack()"
                                                type="button" style="margin: 0px;">
                                                <span><i class="fa fa-arrow-left" aria-hidden="true"></i>&nbsp;&nbsp;Pilih
                                                    Perawatan</span>

                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if ($keteranganNull == 0)
                                <div class=" col-md-2 form-group text-right">
                                    <div class="product-details-content quickview-content">
                                        <div class="pro-details-quality" style="padding: 0px;margin: 0px;">
                                            <div class="pro-details-cart ml-auto" style="width: 100%;"
                                                id="divBtnKonfirmasi">
                                                <button id="btnKonfirmasiPerawatan" class="add-cart " type="submit"
                                                    disabled data-bs-toggle="tooltip" data-bs-placement="right"
                                                    title="Pastikan telah memilih karyawan untuk setiap perawatan!"
                                                    style="margin: 0px; width: 100%; background-color: #273ED4ab;">Konfirmasi
                                                    Reservasi</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
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
        function goBack() {
            window.history.back();
        }
        $('.allkaryawan').on('change', function() {
            var count = 0;
            $(".allkaryawan").each(function(index) {
                if ($(this).val() == null) {
                    count += 1;
                }
            });
            if (count == 0) {
                $("#divBtnKonfirmasi").html(
                    "<button id='btnKonfirmasiPerawatan' class='add-cart ' type='submit' data-bs-toggle='tooltip' data-bs-placement='right' title='Pastikan telah memilih karyawan untuk setiap perawatan!' style='margin: 0px; width: 100%; background-color: #273ED4;'>Konfirmasi Reservasi</button> </div>"
                );
            } else {
                $("#btnKonfirmasiReservasi").attr('disabled', true);
            }
        });
    </script>
@endsection
