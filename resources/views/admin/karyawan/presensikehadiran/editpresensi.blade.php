@extends('layout.adminlayout')

@section('title', 'Admin || Edit Presensi')

@section('admincontent')
    <div class="page-title-box">
    </div>
    <!-- end page-title -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <h3 class="mt-0 header-title">Edit Presensi</h3>
                    <p class="sub-title">
                    </p>
                    @if (session('status'))
                        <div class="alert alert-success">
                            <button type="button" class="close text-danger" data-dismiss="alert" aria-label="Close">
                                <span class="text-success" aria-hidden="true">&times;</span>
                            </button>
                            {{ session('status') }}
                        </div>
                    @endif
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


                    <form method="POST" action="{{ route('admin.presensikehadirans.updatepresensikehadiran') }}"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="form-group row">
                            <div class="form-group col-md-6">
                                <input type="hidden" name="tanggalPresensi" value="{{ $tanggalPresensi }}">
                                <h3>Tanggal Presensi : {{ $tanggalPresensiTeks }}</h3>
                            </div>
                            <div class="form-group col-md-6 text-right">
                                <button class="btn btn-info waves-effect waves-light mt-2" type="submit">
                                    Edit Presensi</button>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="form-group col-md-12">
                                <div>
                                    <table id="tabelDaftarPresensiKaryawan"
                                        class="table table-bordered dt-responsive nowrap text-center table-striped w-100"
                                        style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                        <thead>
                                            <tr>
                                                <th>Nama Karyawan</th>
                                                <th>Tanggal Presensi</th>
                                                <th>Keterangan</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @php
                                                $counter = 0;
                                                $arrKeterangan = ['hadir', 'sakit', 'izin', 'absen'];
                                                $arrKeteranganTolak = ['absen', 'hadir'];
                                                $arrStatusIzin = ['belum', 'konfirmasi', 'tolak'];
                                                $arrStatus = ['belum', 'konfirmasi'];

                                            @endphp

                                            @foreach ($arrObjectPresensiKehadiran as $presensi)
                                                <tr id="tr_{{ $presensi->id }}">
                                                    <td>{{ $presensi->karyawan->nama }}</td>
                                                    <td>{{ $tanggalPresensiTeks }}</td>


                                                    @if ($presensi->keterangan != 'izin' && $presensi->keterangan != 'sakit')
                                                        <input type="hidden" value="{{ $presensi->karyawan->id }}"
                                                            name="daftarNamaKaryawan[]">
                                                        <td>
                                                            <div class="col-md-12">
                                                                <select name="keteranganPresensi[]"
                                                                    idKaryawan = "{{ $presensi->karyawan->id }}"
                                                                    id="keteranganPresensiSelect"
                                                                    class="form-control keteranganPresensiSelect"
                                                                    aria-label="Default select example" required>
                                                                    @if (in_array($presensi->karyawan->id, $idUnikKaryawanYangIzinDitolak))
                                                                        @foreach ($arrKeteranganTolak as $k)
                                                                            @if ($presensi->keterangan == $k)
                                                                                <option selected
                                                                                    value="{{ $k }}">
                                                                                    {{ strtoupper($k) }}</option>
                                                                            @else
                                                                                <option value="{{ $k }}">
                                                                                    {{ strtoupper($k) }}</option>
                                                                            @endif
                                                                        @endforeach
                                                                        <option disabled class="text-danger" value="tolak">
                                                                            SAKIT - ditolak</option>
                                                                        <option disabled class="text-danger" value="tolak">
                                                                            IZIN - ditolak</option>
                                                                    @else
                                                                        @foreach ($arrKeterangan as $k)
                                                                            @if ($presensi->keterangan == $k)
                                                                                <option selected
                                                                                    value="{{ $k }}">
                                                                                    {{ strtoupper($k) }}</option>
                                                                            @else
                                                                                <option value="{{ $k }}">
                                                                                    {{ strtoupper($k) }}</option>
                                                                            @endif
                                                                        @endforeach
                                                                    @endif
                                                                </select>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="col-md-12">
                                                                <select name="statusPresensi[]"
                                                                    id="statusPresensiSelect_{{ $presensi->karyawan->id }}"
                                                                    class="form-control statusPresensiSelect"
                                                                    aria-label="Default select example" required>
                                                                    @foreach ($arrStatus as $s)
                                                                        @if ($presensi->status == $s)
                                                                            <option selected value="{{ $s }}">
                                                                                {{ $s }}
                                                                            </option>
                                                                        @else
                                                                            <option value="{{ $s }}">
                                                                                {{ $s }}
                                                                            </option>
                                                                        @endif
                                                                    @endforeach
                                                                </select>
                                                            </div>

                                                        </td>
                                                        @php
                                                            $counter++;
                                                        @endphp
                                                    @else
                                                        {{-- @php
                                                            $presensiIzinKaryawanTerpilih = $daftarIzinPresensiHariIni->firstWhere('karyawan_id', $presensi->karyawan->id);
                                                        @endphp --}}

                                                        @if ($presensi->status == 'konfirmasi')
                                                            <input type="hidden" value="{{ $presensi->karyawan->id }}"
                                                                name="daftarNamaKaryawan[]">
                                                            <td>
                                                                <div class="col-md-12">
                                                                    <select name="keteranganPresensi[]"
                                                                        idKaryawan = "{{ $presensi->karyawan->id }}"
                                                                        id="keteranganPresensiSelect"
                                                                        class="form-control keteranganPresensiSelect"
                                                                        aria-label="Default select example" required>
                                                                        @foreach ($arrKeterangan as $k)
                                                                            @if ($presensi->keterangan == $k)
                                                                                <option selected
                                                                                    value="{{ $k }}">
                                                                                    {{ strtoupper($k) }}</option>
                                                                            @else
                                                                                <option value="{{ $k }}">
                                                                                    {{ strtoupper($k) }}</option>
                                                                            @endif
                                                                        @endforeach

                                                                    </select>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="col-md-12">
                                                                    <select name="statusPresensi[]"
                                                                        id="statusPresensiSelect_{{ $presensi->karyawan->id }}"
                                                                        class="form-control statusPresensiSelect"
                                                                        aria-label="Default select example" required>
                                                                        @foreach ($arrStatusIzin as $s)
                                                                            @if ($presensi->status == $s)
                                                                                <option selected
                                                                                    value="{{ $s }}">
                                                                                    {{ $s }}
                                                                                </option>
                                                                            @else
                                                                                <option value="{{ $s }}">
                                                                                    {{ $s }}
                                                                                </option>
                                                                            @endif
                                                                        @endforeach
                                                                    </select>
                                                                </div>

                                                            </td>
                                                            @php
                                                                $counter++;
                                                            @endphp
                                                        @elseif($presensi->status == 'tolak')
                                                            <input type="hidden" value="{{ $presensi->karyawan->id }}"
                                                                name="daftarNamaKaryawan[]">
                                                            <td>
                                                                <div class="col-md-12">
                                                                    <select name="keteranganPresensi[]"
                                                                        idKaryawan = "{{ $presensi->karyawan->id }}"
                                                                        id="keteranganPresensiSelect"
                                                                        class="form-control keteranganPresensiSelect"
                                                                        aria-label="Default select example" required>
                                                                        @foreach ($arrKeteranganTolak as $k)
                                                                            @if ($presensi->keterangan == $k)
                                                                                <option selected
                                                                                    value="{{ $k }}">
                                                                                    {{ strtoupper($k) }}</option>
                                                                            @else
                                                                                <option value="{{ $k }}">
                                                                                    {{ strtoupper($k) }}</option>
                                                                            @endif
                                                                        @endforeach
                                                                        <option disabled class="text-danger" value="tolak">
                                                                            SAKIT - ditolak</option>
                                                                        <option disabled class="text-danger" value="tolak">
                                                                            IZIN - ditolak</option>

                                                                    </select>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="col-md-12">
                                                                    <select name="statusPresensi[]"
                                                                        id="statusPresensiSelect_{{ $presensi->karyawan->id }}"
                                                                        class="form-control statusPresensiSelect"
                                                                        aria-label="Default select example" required>
                                                                        @foreach ($arrStatus as $s)
                                                                            @if ($presensi->status == $s)
                                                                                <option selected
                                                                                    value="{{ $s }}">
                                                                                    {{ $s }}
                                                                                </option>
                                                                            @else
                                                                                <option value="{{ $s }}">
                                                                                    {{ $s }}
                                                                                </option>
                                                                            @endif
                                                                        @endforeach
                                                                    </select>
                                                                </div>

                                                            </td>
                                                            @php
                                                                $counter++;
                                                            @endphp
                                                        @else
                                                            <input type="hidden" value="{{ $presensi->karyawan->id }}"
                                                                name="daftarNamaKaryawan[]">
                                                            <td>
                                                                <div class="col-md-12">
                                                                    <select name="keteranganPresensi[]"
                                                                        idKaryawan = "{{ $presensi->karyawan->id }}"
                                                                        id="keteranganPresensiSelect"
                                                                        class="form-control keteranganPresensiSelect"
                                                                        aria-label="Default select example" required>
                                                                        @foreach ($arrKeterangan as $k)
                                                                            @if ($presensi->keterangan == $k)
                                                                                <option selected
                                                                                    value="{{ $k }}">
                                                                                    {{ strtoupper($k) }}</option>
                                                                            @else
                                                                                <option value="{{ $k }}">
                                                                                    {{ strtoupper($k) }}</option>
                                                                            @endif
                                                                        @endforeach

                                                                    </select>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="col-md-12">
                                                                    <select name="statusPresensi[]"
                                                                        id="statusPresensiSelect_{{ $presensi->karyawan->id }}"
                                                                        class="form-control statusPresensiSelect"
                                                                        aria-label="Default select example" required>
                                                                        @foreach ($arrStatusIzin as $s)
                                                                            @if ($presensi->status == $s)
                                                                                <option selected
                                                                                    value="{{ $s }}">
                                                                                    {{ $s }}
                                                                                </option>
                                                                            @else
                                                                                <option value="{{ $s }}">
                                                                                    {{ $s }}
                                                                                </option>
                                                                            @endif
                                                                        @endforeach
                                                                    </select>
                                                                </div>

                                                            </td>
                                                            @php
                                                                $counter++;
                                                            @endphp
                                                            {{-- <td>
                                                                <div class="col-md-12">
                                                                    <select name="statusPresensi[]"
                                                                        id="keteranganPresensiSelect" class="form-control"
                                                                        aria-label="Default select example" required>
                                                                        <option class="text-danger" selected
                                                                            value="null">
                                                                            Pilih Status Presensi</option>
                                                                        <option value="konfirmasi">konfirmasi</option>
                                                                        <option value="belum">belum</option>
                                                                        <option value="tolak">tolak</option>
                                                                    </select>
                                                                </div>
                                                            </td>
                                                            @php
                                                                $counter++;
                                                            @endphp --}}
                                                        @endif
                                                    @endif
                                                </tr>

                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
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
        $(document).ready(function() {
            // $('#tabelDaftarPresensiKaryawan').DataTable({

            // });

        });
        $('body').on('change', '.keteranganPresensiSelect', function() {
            var valueKeterangan = $(this).val();
            var idKaryawan = $(this).attr("idKaryawan");

            if (valueKeterangan == "izin" || valueKeterangan == "sakit") {
                $("#statusPresensiSelect_" + idKaryawan + " option[value='tolak']").remove();
                $("#statusPresensiSelect_" + idKaryawan).append("<option value='tolak'>tolak</option>");
            } else {
                $("#statusPresensiSelect_" + idKaryawan + " option[value='tolak']").remove();
            }

        });
    </script>
@endsection
