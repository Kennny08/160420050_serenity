@extends('layout.adminlayout')

@section('title', 'Admin || Pemilihan Reservasi Karyawan')

@section('admincontent')
    <div class="page-title-box">

    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <h3>Pemilihan Karyawan</h3>
                    <p class="sub-title">
                    </p>
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
                    {{-- @if ($errors->any())
                <div class="alert alert-danger" role="alert">
                    <p class="mb-0"><strong>Maaf, terjadi kesalahan!</strong></p>
                    @foreach ($errors->all() as $error)
                        <p class="mt-0 mb-1">- {{ $error }}</p>
                    @endforeach
                </div>
            @endif --}}
                    @if (isset($pesanError))
                        <div class="alert alert-danger" role="alert">
                            <p class="mb-0"><strong>Maaf, terjadi kesalahan!</strong></p>
                            <p class="mt-0 mb-1">- {{ $pesanError }}</p>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('reservasi.admin.konfirmasireservasi') }}"
                        enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="tanggalReservasi" value="{{ $tanggalReservasi }}">
                        <input type="hidden" name="slotJam" value="{{ $idSlotJam }}">
                        <input type="hidden" name="idPelanggan" value="{{ $idPelanggan }}">
                        @foreach ($arrPerawatan as $p)
                            <input type="hidden" name="arrayperawatanid[]" value="{{ $p }}">
                        @endforeach
                        <div class="mb-2">
                            <div class="d-inline-block">
                                <h3 class="mt-0 header-title">Perawatan Non Komplemen</h3>
                            </div>

                            <div class="d-inline-block">
                                <button style="font-size: 12px" type="button"
                                    class="btn btn-sm btn-info waves-effect waves-light" data-toggle="tooltip"
                                    data-placement="right" title="Perawatan dilakukan secara terpisah atau bertahap">
                                    <i class="mdi mdi-information-outline"></i>
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
                                @foreach ($perawatanSlotJamNonKomplemen as $ps)
                                    <tr class="text-center">
                                        <td>{{ $ps['perawatan']->nama }}</td>
                                        <td>{{ $ps['jammulai'] }}</td>
                                        <td>{{ $ps['perawatan']->durasi }}</td>
                                        <td>
                                            <select required class="selectkaryawan form-control allkaryawan"
                                                name="selectkaryawan[]">
                                                @if (count($ps['karyawans']) == 0)
                                                    <option selected value="-">Tidak Tersedia Karyawan</option>
                                                @else
                                                    <option disabled selected value="null">Pilih Karyawan</option>
                                                    @foreach ($ps['karyawans'] as $k)
                                                        <option
                                                            value="{{ $k->id }},{{ $ps['perawatan']->id }},({{ $ps['idslotjam'] }})">
                                                            {{ $k->nama }}</option>
                                                    @endforeach
                                                @endif

                                            </select>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <br>
                        <div class="mb-2">
                            <div class="d-inline-block">
                                <h3 class="mt-0 header-title">Perawatan Komplemen</h3>
                            </div>

                            <div class="d-inline-block">
                                <button style="font-size: 12px" type="button"
                                    class="btn btn-sm btn-info waves-effect waves-light" data-toggle="tooltip"
                                    data-placement="right"
                                    title="Perawatan dapat dilakukan secara bersamaan pada durasi tertentu">
                                    <i class="mdi mdi-information-outline"></i>
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
                                @php
                                    $counter = 1;
                                @endphp
                                @foreach ($arrKomplemen['array'] as $ps)
                                    @if ($counter == 1)
                                        <tr class="text-center">
                                            <td>{{ $ps['perawatan']->nama }}</td>
                                            <td class="text-center align-middle"
                                                rowspan="{{ count($arrKomplemen['array']) }}">
                                                {{ $arrKomplemen['jammulai'] }}</td>
                                            <td class="text-center align-middle"
                                                rowspan="{{ count($arrKomplemen['array']) }}">
                                                {{ $arrKomplemen['durasiterlama'] }}
                                            </td>
                                            <td>
                                                <select required class="selectkaryawankomplemen form-control allkaryawan"
                                                    name="selectkaryawankomplemen[]">
                                                    @if (count($ps['karyawans']) == 0)
                                                        <option selected value="-">Tidak Tersedia Karyawan</option>
                                                    @else
                                                        <option disabled selected value="null">Pilih Karyawan</option>
                                                        @foreach ($ps['karyawans'] as $k)
                                                            <option
                                                                value="{{ $k->id }},{{ $ps['perawatan']->id }},({{ $arrKomplemen['idslotjam'] }})">
                                                                {{ $k->nama }}</option>
                                                        @endforeach
                                                    @endif

                                                </select>
                                            </td>
                                        </tr>
                                    @else
                                        <tr class="text-center">
                                            <td>{{ $ps['perawatan']->nama }}</td>
                                            <td>
                                                <select required class="selectkaryawankomplemen form-control allkaryawan"
                                                    name="selectkaryawankomplemen[]">
                                                    @if (count($ps['karyawans']) == 0)
                                                        <option selected value="-">Tidak Tersedia Karyawan</option>
                                                    @else
                                                        <option disabled selected value="null">Pilih Karyawan</option>
                                                        @foreach ($ps['karyawans'] as $k)
                                                            <option
                                                                value="{{ $k->id }},{{ $ps['perawatan']->id }},({{ $arrKomplemen['idslotjam'] }})">
                                                                {{ $k->nama }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </td>
                                        </tr>
                                    @endif
                                    @php
                                        $counter = $counter + 1;
                                    @endphp
                                @endforeach
                            </tbody>
                        </table>
                        <div class="row">
                            <div class="col-md-6 form-group text-left">
                                <a id="btnPilihPerawatan" style="width: 200px" href="{{ route('reservasi.admin.create') }}"
                                    class="btn btn-primary btn-lg btn-danger">Pilih
                                    Perawatan</a>
                            </div>

                            @if ($keteranganNull == 0)
                                <div class=" col-md-6 form-group text-right" id="divBtnKonfirmasi">
                                    <button id="btnKonfirmasiReservasi" style="width: 200px" type="submit" disabled
                                        data-toggle="tooltip" data-placement="right"
                                        title="Pastikan telah memilih karyawan untuk setiap perawawtan!"
                                        class="btn btn-primary btn-lg text-center">Konfirmasi
                                        Reservasi</button>
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
                    "<button id='btnKonfirmasiReservasi' style='width: 200px' type='submit' class='btn btn-primary btn-lg text-center'>Konfirmasi Reservasi</button>"
                    );
            } else {
                $("#btnKonfirmasiReservasi").attr('disabled', true);
            }
        });
    </script>
@endsection
