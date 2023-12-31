@extends('layout.adminlayout')

@section('title', 'Admin || Buka Presensi')

@section('admincontent')
    <div class="page-title-box">
    </div>
    <!-- end page-title -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <h3 class="mt-0 header-title">Buka Presensi</h3>
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


                    <form method="POST" action="{{ route('presensikehadirans.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group row">
                            <div class="form-group col-md-6">
                                <h3>Tanggal Presensi : {{ $tanggalHariIniTeks }}</h3>
                                <h6>Dibuka pada pukul <span class="text-danger">{{ date('H:i') }}</span></h6>
                                <input type="hidden" name="waktuBukaPresensi" value="{{ date('H:i') }}">
                            </div>
                            <div class="form-group col-md-6 text-right">
                                <button class="btn btn-info waves-effect waves-light mt-2" type="submit">
                                    Konfirmasi Buka Presensi</button>
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
                                                $arrKeterangan = ['absen', 'hadir', 'sakit', 'izin'];
                                                $arrKeteranganTolak = ['absen', 'hadir'];
                                                $arrStatusIzin = ['belum', 'konfirmasi', 'tolak'];
                                                $arrStatus = ['belum', 'konfirmasi'];

                                            @endphp

                                            @foreach ($karyawans as $karyawan)
                                                <tr id="tr_{{ $karyawan->id }}">
                                                    <td>{{ $karyawan->nama }}</td>
                                                    <td>{{ $tanggalHariIniTeks }}</td>

                                                    @if (!in_array($karyawan->id, $idKaryawansIzin))
                                                        <input type="hidden" value="{{ $karyawan->id }}"
                                                            name="daftarNamaKaryawan[]">
                                                        <td>
                                                            <div class="col-md-12">
                                                                @php
                                                                    $presensi = $arrObjectPresensiHariIniTanpaIzin->firstWhere('karyawan_id', $karyawan->id);
                                                                @endphp
                                                                @if ($presensi != null)
                                                                    <input type="hidden" name="keteranganPresensi[]"
                                                                        value="{{ $presensi->keterangan }}">
                                                                    {{ strtoupper($presensi->keterangan) }}
                                                                @else
                                                                    <input type="hidden" name="keteranganPresensi[]"
                                                                        value="absen">
                                                                    <span class="text-danger">ABSEN</span>
                                                                @endif
                                                                {{-- <select name="keteranganPresensi[]"
                                                                    idKaryawan = "{{ $karyawan->id }}"
                                                                    id="keteranganPresensiSelect"
                                                                    class="form-control keteranganPresensiSelect"
                                                                    aria-label="Default select example" required>
                                                                    @if (old('keteranganPresensi') != null)
                                                                        @foreach ($arrKeterangan as $k)
                                                                            @if (old('keteranganPresensi')[$counter] == $k)
                                                                                @if ($k == 'null')
                                                                                    <option class="text-danger" selected
                                                                                        value="null">
                                                                                        Pilih Keterangan
                                                                                        Presensi</option>
                                                                                @else
                                                                                    <option selected
                                                                                        value="{{ $k }}">
                                                                                        {{ strtoupper($k) }}</option>
                                                                                @endif
                                                                            @else
                                                                                @if ($k == 'null')
                                                                                    <option class="text-danger"
                                                                                        value="null">
                                                                                        Pilih Keterangan
                                                                                        Presensi</option>
                                                                                @else
                                                                                    <option value="{{ $k }}">
                                                                                        {{ strtoupper($k) }}</option>
                                                                                @endif
                                                                            @endif
                                                                        @endforeach
                                                                    @else
                                                                        <option class="text-danger" selected value="null">
                                                                            Pilih Keterangan
                                                                            Presensi</option>
                                                                        <option value="hadir">HADIR</option>
                                                                        <option value="sakit">SAKIT</option>
                                                                        <option value="absen">ABSEN</option>
                                                                        <option value="izin">IZIN</option>
                                                                    @endif

                                                                </select> --}}
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <span class="text-warning h6 font-weight-bold">Akan
                                                                Dikonfirmasi</span>
                                                            <input type="hidden" name="statusPresensi[]" value="belum">
                                                            {{-- <div class="col-md-12">
                                                                @if (old('keteranganPresensi') != null)
                                                                    @if (old('keteranganPresensi')[$counter] == 'izin')
                                                                        @if (old('statusPresensi') != null)
                                                                            <select name="statusPresensi[]"
                                                                                id="statusPresensiSelect_{{ $karyawan->id }}"
                                                                                class="form-control statusPresensiSelect"
                                                                                aria-label="Default select example"
                                                                                required>
                                                                                @foreach ($arrStatusIzin as $s)
                                                                                    @if (old('statusPresensi')[$counter] == $s)
                                                                                        @if ($s == 'null')
                                                                                            <option class="text-danger"
                                                                                                selected value="null">
                                                                                                Pilih Status Presensi
                                                                                            </option>
                                                                                        @else
                                                                                            <option selected
                                                                                                value="{{ $s }}">
                                                                                                {{ $s }}
                                                                                            </option>
                                                                                        @endif
                                                                                    @else
                                                                                        @if ($s == 'null')
                                                                                            <option class="text-danger"
                                                                                                value="null">
                                                                                                Pilih Status Presensi
                                                                                            </option>
                                                                                        @else
                                                                                            <option
                                                                                                value="{{ $s }}">
                                                                                                {{ $s }}
                                                                                            </option>
                                                                                        @endif
                                                                                    @endif
                                                                                @endforeach
                                                                            </select>
                                                                        @else
                                                                            <select name="statusPresensi[]"
                                                                                id="statusPresensiSelect_{{ $karyawan->id }}"
                                                                                class="form-control statusPresensiSelect"
                                                                                aria-label="Default select example"
                                                                                required>
                                                                                <option class="text-danger" selected
                                                                                    value="null">
                                                                                    Pilih Status Presensi</option>
                                                                                <option value="konfirmasi">konfimasi
                                                                                </option>
                                                                                <option value="belum">belum
                                                                                </option>
                                                                                <option value="tolak">tolak
                                                                                </option>
                                                                            </select>
                                                                        @endif
                                                                    @else
                                                                        @if (old('statusPresensi') != null)
                                                                            <select name="statusPresensi[]"
                                                                                id="statusPresensiSelect_{{ $karyawan->id }}"
                                                                                class="form-control statusPresensiSelect"
                                                                                aria-label="Default select example"
                                                                                required>
                                                                                @foreach ($arrStatus as $s)
                                                                                    @if (old('statusPresensi')[$counter] == $s)
                                                                                        @if ($s == 'null')
                                                                                            <option class="text-danger"
                                                                                                selected value="null">
                                                                                                Pilih Status Presensi
                                                                                            </option>
                                                                                        @else
                                                                                            <option selected
                                                                                                value="{{ $s }}">
                                                                                                {{ $s }}
                                                                                            </option>
                                                                                        @endif
                                                                                    @else
                                                                                        @if ($s == 'null')
                                                                                            <option class="text-danger"
                                                                                                value="null">
                                                                                                Pilih Status Presensi
                                                                                            </option>
                                                                                        @else
                                                                                            <option
                                                                                                value="{{ $s }}">
                                                                                                {{ $s }}
                                                                                            </option>
                                                                                        @endif
                                                                                    @endif
                                                                                @endforeach
                                                                            </select>
                                                                        @else
                                                                            <select name="statusPresensi[]"
                                                                                id="statusPresensiSelect_{{ $karyawan->id }}"
                                                                                class="form-control statusPresensiSelect"
                                                                                aria-label="Default select example"
                                                                                required>
                                                                                <option class="text-danger" selected
                                                                                    value="null">
                                                                                    Pilih Status Presensi</option>
                                                                                <option value="konfirmasi">konfirmasi
                                                                                </option>
                                                                                <option value="belum">belum
                                                                                </option>
                                                                            </select>
                                                                        @endif
                                                                    @endif
                                                                @else
                                                                    <select name="statusPresensi[]"
                                                                        id="statusPresensiSelect_{{ $karyawan->id }}"
                                                                        class="form-control statusPresensiSelect"
                                                                        aria-label="Default select example" required>
                                                                        <option class="text-danger" selected value="null">
                                                                            Pilih Status Presensi</option>
                                                                        <option value="konfirmasi">konfirmasi
                                                                        </option>
                                                                        <option value="belum">belum
                                                                        </option>
                                                                    </select>
                                                                @endif
                                                            </div> --}}

                                                        </td>
                                                        @php
                                                            $counter++;
                                                        @endphp
                                                    @else
                                                        @php
                                                            $presensiIzinKaryawanTerpilih = $daftarIzinPresensiHariIni->firstWhere('karyawan_id', $karyawan->id);
                                                        @endphp

                                                        @if ($presensiIzinKaryawanTerpilih->status == 'konfirmasi')
                                                            <td style="font-size: 1.3em">
                                                                @if ($presensiIzinKaryawanTerpilih->keterangan == 'izin')
                                                                    <span class="font-weight-bold text-warning">IZIN</span>
                                                                @else
                                                                    <span class="font-weight-bold text-info">SAKIT</span>
                                                                @endif

                                                            </td>
                                                            <td>
                                                                <span class="text-success font-weight-bold">Telah
                                                                    Dikonfirmasi</span>
                                                            </td>
                                                        @elseif($presensiIzinKaryawanTerpilih->status == 'tolak')
                                                            <input type="hidden" value="{{ $karyawan->id }}"
                                                                name="daftarNamaKaryawan[]">
                                                            <td>
                                                                <div class="col-md-12">
                                                                    <select name="keteranganPresensi[]"
                                                                        idKaryawan = "{{ $karyawan->id }}"
                                                                        id="keteranganPresensiSelect"
                                                                        class="form-control keteranganPresensiSelect"
                                                                        aria-label="Default select example" required>
                                                                        @if (old('keteranganPresensi') != null)
                                                                            @foreach ($arrKeteranganTolak as $k)
                                                                                @if (old('keteranganPresensi')[$counter] == $k)
                                                                                    <option selected
                                                                                        value="{{ $k }}">
                                                                                        {{ strtoupper($k) }}
                                                                                    </option>
                                                                                @else
                                                                                    <option value="{{ $k }}">
                                                                                        {{ strtoupper($k) }}
                                                                                    </option>
                                                                                @endif
                                                                            @endforeach
                                                                        @else
                                                                            <option value="absen">ABSEN</option>
                                                                            <option value="hadir">HADIR</option>
                                                                        @endif

                                                                    </select>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="col-md-12">
                                                                    @if (old('keteranganPresensi') != null)
                                                                        @if (old('keteranganPresensi')[$counter] == 'izin' || old('keteranganPresensi')[$counter] == 'sakit')
                                                                            @if (old('statusPresensi') != null)
                                                                                <select name="statusPresensi[]"
                                                                                    id="statusPresensiSelect_{{ $karyawan->id }}"
                                                                                    class="form-control statusPresensiSelect"
                                                                                    aria-label="Default select example"
                                                                                    required>
                                                                                    @foreach ($arrStatusIzin as $s)
                                                                                        @if (old('statusPresensi')[$counter] == $s)
                                                                                            <option selected
                                                                                                value="{{ $s }}">
                                                                                                {{ $s }}
                                                                                            </option>
                                                                                        @else
                                                                                            <option
                                                                                                value="{{ $s }}">
                                                                                                {{ $s }}
                                                                                            </option>
                                                                                        @endif
                                                                                    @endforeach
                                                                                </select>
                                                                            @else
                                                                                <select name="statusPresensi[]"
                                                                                    id="statusPresensiSelect_{{ $karyawan->id }}"
                                                                                    class="form-control statusPresensiSelect"
                                                                                    aria-label="Default select example"
                                                                                    required>
                                                                                    <option value="belum">belum
                                                                                    </option>
                                                                                    <option value="konfirmasi">konfimasi
                                                                                    </option>
                                                                                    <option value="tolak">tolak
                                                                                    </option>
                                                                                </select>
                                                                            @endif
                                                                        @else
                                                                            @if (old('statusPresensi') != null)
                                                                                <select name="statusPresensi[]"
                                                                                    id="statusPresensiSelect_{{ $karyawan->id }}"
                                                                                    class="form-control statusPresensiSelect"
                                                                                    aria-label="Default select example"
                                                                                    required>
                                                                                    @foreach ($arrStatus as $s)
                                                                                        @if (old('statusPresensi')[$counter] == $s)
                                                                                            <option selected
                                                                                                value="{{ $s }}">
                                                                                                {{ $s }}
                                                                                            </option>
                                                                                        @else
                                                                                            <option
                                                                                                value="{{ $s }}">
                                                                                                {{ $s }}
                                                                                            </option>
                                                                                        @endif
                                                                                    @endforeach
                                                                                </select>
                                                                            @else
                                                                                <select name="statusPresensi[]"
                                                                                    id="statusPresensiSelect_{{ $karyawan->id }}"
                                                                                    class="form-control statusPresensiSelect"
                                                                                    aria-label="Default select example"
                                                                                    required>
                                                                                    <option value="belum">belum
                                                                                    </option>
                                                                                    <option value="konfirmasi">konfirmasi
                                                                                    </option>

                                                                                </select>
                                                                            @endif
                                                                        @endif
                                                                    @else
                                                                        <select name="statusPresensi[]"
                                                                            id="statusPresensiSelect_{{ $karyawan->id }}"
                                                                            class="form-control statusPresensiSelect"
                                                                            aria-label="Default select example" required>
                                                                            <option value="belum">belum
                                                                            </option>
                                                                            <option value="konfirmasi">konfirmasi
                                                                            </option>
                                                                        </select>
                                                                    @endif
                                                                </div>
                                                            </td>
                                                            @php
                                                                $counter++;
                                                            @endphp
                                                        @else
                                                            <input type="hidden" value="{{ $karyawan->id }}"
                                                                name="daftarNamaKaryawan[]">

                                                            <td style="font-size: 1.3em">
                                                                @if ($presensiIzinKaryawanTerpilih->keterangan == 'izin')
                                                                    <span class="font-weight-bold text-warning">IZIN</span>
                                                                @else
                                                                    <span class="font-weight-bold text-info">SAKIT</span>
                                                                @endif

                                                            </td>
                                                            @if ($presensiIzinKaryawanTerpilih->keterangan == 'izin')
                                                                <input type="hidden" value="izin"
                                                                    name="keteranganPresensi[]">
                                                            @else
                                                                <input type="hidden" value="sakit"
                                                                    name="keteranganPresensi[]">
                                                            @endif


                                                            <td>
                                                                <div class="col-md-12">
                                                                    @if (old('keteranganPresensi') != null)
                                                                        @if (old('statusPresensi') != null)
                                                                            <select name="statusPresensi[]"
                                                                                id="statusPresensiSelect_{{ $karyawan->id }}"
                                                                                class="form-control statusPresensiSelect"
                                                                                aria-label="Default select example"
                                                                                required>
                                                                                @foreach ($arrStatusIzin as $s)
                                                                                    @if (old('statusPresensi')[$counter] == $s)
                                                                                        <option selected
                                                                                            value="{{ $s }}">
                                                                                            {{ $s }}
                                                                                        </option>
                                                                                    @else
                                                                                        <option
                                                                                            value="{{ $s }}">
                                                                                            {{ $s }}
                                                                                        </option>
                                                                                    @endif
                                                                                @endforeach
                                                                            </select>
                                                                        @else
                                                                            <select name="statusPresensi[]"
                                                                                id="statusPresensiSelect_{{ $karyawan->id }}"
                                                                                class="form-control statusPresensiSelect"
                                                                                aria-label="Default select example"
                                                                                required>
                                                                                <option value="belum">belum
                                                                                </option>
                                                                                <option value="konfirmasi">konfimasi
                                                                                </option>

                                                                                <option value="tolak">tolak
                                                                                </option>
                                                                            </select>
                                                                        @endif
                                                                    @else
                                                                        <select name="statusPresensi[]"
                                                                            id="statusPresensiSelect_{{ $karyawan->id }}"
                                                                            class="form-control statusPresensiSelect"
                                                                            aria-label="Default select example" required>
                                                                            <option value="belum">belum
                                                                            </option>
                                                                            <option value="konfirmasi">konfirmasi
                                                                            </option>
                                                                            <option value="tolak">tolak
                                                                            </option>
                                                                        </select>
                                                                    @endif
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

    <div id="modalDetailKaryawan" class="modal fade bs-example-modal-center" tabindex="-1" role="dialog"
        aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" style="max-width: 600px;">
            <div class="modal-content ">
                <div class="modal-header">
                    <h5 class="modal-title mt-0" id="modalNamaKaryawan">Detail Karyawan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="contentDetailKaryawan">
                    <div class="text-center">
                        <div class="spinner-border text-info" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>

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
        $(document).ready(function() {
            // $('#tabelDaftarPresensiKaryawan').DataTable({

            // });

        });

        
        $('.btnHapusKaryawan').on('click', function() {

            var idKaryawan = $(this).attr("idKaryawan");
            var namaKaryawan = $(this).attr('namaKaryawan');
            var routeUrl = $(this).attr('routeUrl');
            $("#modalNamaKaryawanDelete").text("Konfirmasi Penghapusan Karyawan " + namaKaryawan);
            $("#modalBodyHapusKaryawan").html("<h6>Apakah Anda yakin untuk menghapus perawatan " + namaKaryawan +
                "?</h6>")
            $("#formDeleteKaryawan").attr("action", routeUrl);
        });

        $('body').on('change', '.keteranganPresensiSelect', function() {
            var valueKeterangan = $(this).val();
            var idKaryawan = $(this).attr("idKaryawan");

            if (valueKeterangan == "izin") {
                $("#statusPresensiSelect_" + idKaryawan + " option[value='tolak']").remove();
                $("#statusPresensiSelect_" + idKaryawan).append("<option value='tolak'>tolak</option>");
            } else {
                $("#statusPresensiSelect_" + idKaryawan + " option[value='tolak']").remove();
            }

        });
    </script>
@endsection
