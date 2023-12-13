@extends('layout.adminlayout')

@section('title', 'Admin || Tambah Karyawan')

@section('admincontent')
    <div class="page-title-box">

    </div>
    <!-- end page-title -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <h3 class="mt-0 header-title">Tambah Karyawan</h3>
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

                    <form id="formStoreKaryawan" method="POST" action="{{ route('karyawans.store') }}">
                        @csrf
                        <div class="form-group col-md-12">
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6">
                                <label for="exampleInputEmail1"><strong>Nama Karyawan</strong></label>
                                <input type="text" class="form-control" name="namaKaryawan" id="txtNamaKaryawan"
                                    aria-describedby="emailHelp" placeholder="Silahkan masukkan nama karyawan" required
                                    value="{{ old('namaKaryawan') }}">
                                <small id="emailHelp" class="form-text text-muted">Masukkan nama karyawan disini!</small>
                            </div>
                            <div class="col-md-6">
                                <label for="exampleInputEmail1"><strong>Email</strong></label>
                                <input type="email" class="form-control" name="emailKaryawan" id="txtEmailKaryawan"
                                    aria-describedby="emailHelp"
                                    placeholder="Silahkan masukkan email karyawan (ferro@gmail.com)" required
                                    value="{{ old('emailKaryawan') }}">
                                <small id="emailHelp" class="form-text text-muted">Masukkan email karyawan disini!</small>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6">
                                <label for="exampleInputEmail1"><strong>Gaji Pokok Karyawan</strong></label>
                                <input type="number" class="form-control" name="gajiKaryawan" id="numGajiKaryawan"
                                    min="1" aria-describedby="emailHelp"
                                    placeholder="Silahkan masukkan gaji pokok karyawan" required
                                    value="{{ old('gajiKaryawan') }}">
                                <small id="emailHelp" class="form-text text-muted">Masukkan gaji pokok karyawan
                                    disini!</small>
                            </div>
                            <div class="col-md-6">
                                <label for="exampleInputEmail1"><strong>Nomor Telepon</strong></label>
                                <input type="text" class="form-control" name="nomor_telepon" id="txtNomorTeleponKaryawan"
                                    aria-describedby="emailHelp" placeholder="Silahkan masukkan nomor telepon karyawan"
                                    required value="{{ old('nomor_telepon') }}">
                                <small id="emailHelp" class="form-text text-muted">Masukkan nomor telepon karyawan
                                    disini!</small>
                            </div>

                        </div>

                        <div class="form-group row">

                            <div class="col-md-4">
                                <label for="exampleInputEmail1"><strong>Jenis Karyawan</strong></label>
                                <br>
                                <div class="btn-group btn-group-toggle border w-100" data-toggle="buttons">
                                    @if (old('radioJenisKaryawan') == 'pekerja salon')
                                        <label class="btn btn-info waves-effect waves-light"
                                            id="lblJenisKaryawanPekerjaSalon">
                                            <input type="radio" value="pekerja salon" name="radioJenisKaryawan"
                                                id="optionJenisKaryawanPekerjaSalon" class="radioJenisKaryawan" checked>
                                            Karyawan Salon
                                        </label>
                                        <label class="btn waves-effect waves-light" id="lblJenisKaryawanAdmin">
                                            <input type="radio" value="admin" name="radioJenisKaryawan"
                                                id="optionJenisKaryawanAdmin" class="radioJenisKaryawan">
                                            Admin Salon
                                        </label>
                                    @elseif(old('radioJenisKaryawan') == null)
                                        <label class="btn btn-info waves-effect waves-light"
                                            id="lblJenisKaryawanPekerjaSalon">
                                            <input type="radio" value="pekerja salon" name="radioJenisKaryawan"
                                                id="optionJenisKaryawanPekerjaSalon" class="radioJenisKaryawan" checked>
                                            Karyawan Salon
                                        </label>
                                        <label class="btn waves-effect waves-light" id="lblJenisKaryawanAdmin">
                                            <input type="radio" value="admin" name="radioJenisKaryawan"
                                                id="optionJenisKaryawanAdmin" class="radioJenisKaryawan">
                                            Admin Salon
                                        </label>
                                    @else
                                        <label class="btn waves-effect waves-light" id="lblJenisKaryawanPekerjaSalon">
                                            <input type="radio" value="pekerja salon" name="radioJenisKaryawan"
                                                id="optionJenisKaryawanPekerjaSalon" class="radioJenisKaryawan">
                                            Karyawan Salon
                                        </label>
                                        <label class="btn btn-info waves-effect waves-light" id="lblJenisKaryawanAdmin">
                                            <input type="radio" value="admin" name="radioJenisKaryawan"
                                                id="optionJenisKaryawanAdmin" class="radioJenisKaryawan" checked>
                                            Admin Salon
                                        </label>
                                    @endif
                                </div>
                                <small id="emailHelp" class="form-text text-muted">Pilih jenis karyawan
                                    disini!</small>
                            </div>
                            <div class="col-md-4">
                                <label for="exampleInputEmail1"><strong>Tanggal Lahir</strong></label>
                                <input type="date" class="form-control" name="tanggalLahir" id="tanggalLahir"
                                    aria-describedby="emailHelp" placeholder="Silahkan Pilih Tanggal Lahir"
                                    value="{{ old('tanggalLahir') }}" required>
                                <small id="emailHelp" class="form-text text-muted">Pilih tanggal lahir Karyawan
                                    disini!</small>
                            </div>
                            <div class="col-md-4">
                                <label for="exampleInputEmail1"><strong>Gender Karyawan</strong></label>
                                <br>
                                <div class="btn-group btn-group-toggle border w-100" data-toggle="buttons">
                                    @if (old('radioGenderKaryawan') == 'pria')
                                        <label class="btn btn-info waves-effect waves-light" id="lblGenderPria">
                                            <input type="radio" value="pria" name="radioGenderKaryawan"
                                                id="optionGenderPria" class="radioGenderKaryawan" checked>
                                            Pria
                                        </label>
                                        <label class="btn waves-effect waves-light" id="lblGenderWanita">
                                            <input type="radio" value="wanita" name="radioGenderKaryawan"
                                                id="optionGenderWanita" class="radioGenderKaryawan">
                                            Wanita
                                        </label>
                                    @elseif(old('radioGenderKaryawan') == null)
                                        <label class="btn btn-info waves-effect waves-light" id="lblGenderPria">
                                            <input type="radio" value="pria" name="radioGenderKaryawan"
                                                id="optionGenderPria" class="radioGenderKaryawan" checked>
                                            Pria
                                        </label>
                                        <label class="btn waves-effect waves-light" id="lblGenderWanita">
                                            <input type="radio" value="wanita" name="radioGenderKaryawan"
                                                id="optionGenderWanita" class="radioGenderKaryawan">
                                            Wanita
                                        </label>
                                    @else
                                        <label class="btn waves-effect waves-light" id="lblGenderPria">
                                            <input type="radio" value="pria" name="radioGenderKaryawan"
                                                id="optionGenderPria" class="radioGenderKaryawan">
                                            Pria
                                        </label>
                                        <label class="btn btn-info waves-effect waves-light" id="lblGenderWanita">
                                            <input type="radio" value="wanita" name="radioGenderKaryawan"
                                                id="optionGenderWanita" class="radioGenderKaryawan" checked>
                                            Wanita
                                        </label>
                                    @endif
                                </div>
                                <small id="emailHelp" class="form-text text-muted">Pilih gender karyawan
                                    disini!</small>
                            </div>
                        </div>



                        @if (old('radioJenisKaryawan') != null)
                            @if (old('radioJenisKaryawan') == 'admin')
                                <div class="form-group row komponenPilihPerawatan" id="containerKaryawanPerawatan" hidden>
                                    <div class="col-md-12">
                                        <label for="exampleInputEmail1"><strong>Perawatan yang Dikuasai</strong></label>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="input-group" style="width: 100%">
                                            <select style="width: 70%" name="perawatan" id="selectPerawatan"
                                                class="form-control" aria-label="Default select example" required>
                                                <option value="null" selected disabled>Pilih Perawatan</option>
                                                @if (old('arrayperawatanid') == null)
                                                    @foreach ($perawatans as $perawatan)
                                                        <option value="{{ $perawatan->id }}"
                                                            nama="{{ $perawatan->nama }}">
                                                            {{ $perawatan->nama }}
                                                        </option>
                                                    @endforeach
                                                @else
                                                    @foreach ($perawatans as $perawatan)
                                                        @if (!in_array($perawatan->id, old('arrayperawatanid')))
                                                            <option value="{{ $perawatan->id }}"
                                                                nama="{{ $perawatan->nama }}">
                                                                {{ $perawatan->nama }}
                                                            </option>
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </select>
                                            <button style="margin-left: 1%; width: 20%" type="button"
                                                id="btnTambahPerawatan"
                                                class="btn btn-info waves-effect waves-light">Tambah
                                                Perawatan</button>
                                        </div>
                                        <small id="emailHelp" class="form-text text-muted">Pilih perawatan yang dikuasai
                                            disini!</small>
                                    </div>
                                    @if (old('arrayperawatanid') != null)
                                        @foreach (old('arrayperawatanid') as $perawatan)
                                            <input id='perawatan{{ $perawatan }}' type='hidden'
                                                class='classarrayperawatanid' value='{{ $perawatan }}'
                                                name='arrayperawatanid[]' disabled>
                                        @endforeach
                                    @endif
                                </div>
                            @else
                                <div class="form-group row komponenPilihPerawatan" id="containerKaryawanPerawatan">
                                    <div class="col-md-12">
                                        <label for="exampleInputEmail1"><strong>Perawatan yang Dikuasai</strong></label>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="input-group" style="width: 100%">
                                            <select style="width: 70%" name="perawatan" id="selectPerawatan"
                                                class="form-control" aria-label="Default select example" required>
                                                <option value="null" selected disabled>Pilih Perawatan</option>
                                                @if (old('arrayperawatanid') == null)
                                                    @foreach ($perawatans as $perawatan)
                                                        <option value="{{ $perawatan->id }}"
                                                            nama="{{ $perawatan->nama }}">
                                                            {{ $perawatan->nama }}
                                                        </option>
                                                    @endforeach
                                                @else
                                                    @foreach ($perawatans as $perawatan)
                                                        @if (!in_array($perawatan->id, old('arrayperawatanid')))
                                                            <option value="{{ $perawatan->id }}"
                                                                nama="{{ $perawatan->nama }}">
                                                                {{ $perawatan->nama }}
                                                            </option>
                                                        @endif
                                                    @endforeach
                                                @endif
                                            </select>
                                            <button style="margin-left: 1%; width: 20%" type="button"
                                                id="btnTambahPerawatan"
                                                class="btn btn-info waves-effect waves-light">Tambah
                                                Perawatan</button>
                                        </div>
                                        <small id="emailHelp" class="form-text text-muted">Pilih perawatan yang dikuasai
                                            disini!</small>
                                    </div>
                                    @if (old('arrayperawatanid') != null)
                                        @foreach (old('arrayperawatanid') as $perawatan)
                                            <input id='perawatan{{ $perawatan }}' type='hidden'
                                                class='classarrayperawatanid' value='{{ $perawatan }}'
                                                name='arrayperawatanid[]'>
                                        @endforeach
                                    @endif
                                </div>
                            @endif
                        @else
                            <div class="form-group row komponenPilihPerawatan" id="containerKaryawanPerawatan">
                                <div class="col-md-12">
                                    <label for="exampleInputEmail1"><strong>Perawatan yang Dikuasai</strong></label>
                                </div>
                                <div class="col-md-12">
                                    <div class="input-group" style="width: 100%">
                                        <select style="width: 70%" name="perawatan" id="selectPerawatan"
                                            class="form-control" aria-label="Default select example" required>
                                            <option value="null" selected disabled>Pilih Perawatan</option>
                                            @if (old('arrayperawatanid') == null)
                                                @foreach ($perawatans as $perawatan)
                                                    <option value="{{ $perawatan->id }}" nama="{{ $perawatan->nama }}">
                                                        {{ $perawatan->nama }}
                                                    </option>
                                                @endforeach
                                            @else
                                                @foreach ($perawatans as $perawatan)
                                                    @if (!in_array($perawatan->id, old('arrayperawatanid')))
                                                        <option value="{{ $perawatan->id }}"
                                                            nama="{{ $perawatan->nama }}">
                                                            {{ $perawatan->nama }}
                                                        </option>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </select>
                                        <button style="margin-left: 1%; width: 20%" type="button"
                                            id="btnTambahPerawatan" class="btn btn-info waves-effect waves-light">Tambah
                                            Perawatan</button>
                                    </div>
                                    <small id="emailHelp" class="form-text text-muted">Pilih perawatan yang dikuasai
                                        disini!</small>
                                </div>
                                @if (old('arrayperawatanid') != null)
                                    @foreach (old('arrayperawatanid') as $perawatan)
                                        <input id='perawatan{{ $perawatan }}' type='hidden'
                                            class='classarrayperawatanid' value='{{ $perawatan }}'
                                            name='arrayperawatanid[]'>
                                    @endforeach
                                @endif
                            </div>
                        @endif


                        @if (old('radioJenisKaryawan') != null)

                            @if (old('radioJenisKaryawan') == 'admin')
                                <div class="form-group text-center komponenPilihPerawatan" hidden>
                                    <table class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th class="text-center">Perawatan</th>
                                                <th class="text-center">Hapus</th>
                                            </tr>
                                        </thead>
                                        <tbody id="bodyListPerawatan">
                                            @if (old('arrayperawatanid') == null)
                                                <tr id="trSilahkan">
                                                    <td colspan="2">
                                                        Silahkan pilih Perawatan
                                                    </td>
                                                </tr>
                                            @else
                                                @foreach ($perawatans as $perawatan)
                                                    @if (in_array($perawatan->id, old('arrayperawatanid')))
                                                        <tr>
                                                            <td>
                                                                {{ $perawatan->nama }}
                                                            </td>
                                                            <td>
                                                                <button type='button'
                                                                    class='deletePerawatan btn btn-danger waves-effect waves-light'
                                                                    idPerawatan="{{ $perawatan->id }}"
                                                                    namaPerawatan="{{ $perawatan->nama }}">Hapus</button>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="form-group text-center komponenPilihPerawatan">
                                    <table class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th class="text-center">Perawatan</th>
                                                <th class="text-center">Hapus</th>
                                            </tr>
                                        </thead>
                                        <tbody id="bodyListPerawatan">
                                            @if (old('arrayperawatanid') == null)
                                                <tr id="trSilahkan">
                                                    <td colspan="2">
                                                        Silahkan pilih Perawatan
                                                    </td>
                                                </tr>
                                            @else
                                                @foreach ($perawatans as $perawatan)
                                                    @if (in_array($perawatan->id, old('arrayperawatanid')))
                                                        <tr>
                                                            <td>
                                                                {{ $perawatan->nama }}
                                                            </td>
                                                            <td>
                                                                <button type='button'
                                                                    class='deletePerawatan btn btn-danger waves-effect waves-light'
                                                                    idPerawatan="{{ $perawatan->id }}"
                                                                    namaPerawatan="{{ $perawatan->nama }}">Hapus</button>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        @else
                            <div class="form-group text-center komponenPilihPerawatan">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Perawatan</th>
                                            <th class="text-center">Hapus</th>
                                        </tr>
                                    </thead>
                                    <tbody id="bodyListPerawatan">
                                        @if (old('arrayperawatanid') == null)
                                            <tr id="trSilahkan">
                                                <td colspan="2">
                                                    Silahkan pilih Perawatan
                                                </td>
                                            </tr>
                                        @else
                                            @foreach ($perawatans as $perawatan)
                                                @if (in_array($perawatan->id, old('arrayperawatanid')))
                                                    <tr>
                                                        <td>
                                                            {{ $perawatan->nama }}
                                                        </td>
                                                        <td>
                                                            <button type='button'
                                                                class='deletePerawatan btn btn-danger waves-effect waves-light'
                                                                idPerawatan="{{ $perawatan->id }}"
                                                                namaPerawatan="{{ $perawatan->nama }}">Hapus</button>
                                                        </td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        @endif


                        <div class="form-group row text-right">
                            <div class="col-md-12">
                                <a id="btnBatalTambahProduk" href="{{ route('karyawans.index') }}"
                                    class="btn btn-danger btn-lg waves-effect waves-light mr-3">Batal</a>
                                <button id="btnTambahProduk" type="submit"
                                    class="btn btn-info btn-lg waves-effect waves-light text-right">Tambah</button>
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
        $('.radioJenisKaryawan').on('change', function() {
            var jenisKaryawanSaatIni = $(this).val();
            if (jenisKaryawanSaatIni == "admin") {
                $("#lblJenisKaryawanPekerjaSalon").removeClass("btn-info");
                $("#lblJenisKaryawanAdmin").addClass("btn-info");
                $(".komponenPilihPerawatan").attr("hidden", true);
                $(".classarrayperawatanid").attr("disabled", true);
            } else {
                $("#lblJenisKaryawanPekerjaSalon").addClass("btn-info");
                $("#lblJenisKaryawanAdmin").removeClass("btn-info");
                $(".komponenPilihPerawatan").attr("hidden", false);
                $(".classarrayperawatanid").attr("disabled", false);
            }
        });

        $('.radioGenderKaryawan').on('change', function() {
            var statusSaatIni = $(this).val();
            if (statusSaatIni == "wanita") {
                $("#lblGenderPria").removeClass("btn-info");
                $("#lblGenderWanita").addClass("btn-info");
            } else {
                $("#lblGenderPria").addClass("btn-info");
                $("#lblGenderWanita").removeClass("btn-info");
            }
        });


        $('body').on('click', '#btnTambahPerawatan', function() {
            var idPerawatan = $("#selectPerawatan").val();
            var namaPerawatan = $("#selectPerawatan option:selected").text();

            if (idPerawatan != null) {
                $("#containerKaryawanPerawatan").append("<input id='perawatan" + idPerawatan +
                    "' type='hidden' class='classarrayperawatanid' value='" +
                    idPerawatan +
                    "' name='arrayperawatanid[]'>");
                $('#trSilahkan').remove();
                $("#bodyListPerawatan").append(
                    "<tr><td>" + namaPerawatan + "</td>" +
                    "<td>" +
                    "<button type='button' class='deletePerawatan btn btn-danger waves-effect waves-light' idPerawatan='" +
                    idPerawatan +
                    "' namaPerawatan='" + namaPerawatan + "' >Hapus</button>" +
                    "</td>" +
                    "</tr>");
                $("#selectPerawatan option:selected").remove();
                $("#selectPerawatan").val('null');
            }
        });

        $('body').on('click', '.deletePerawatan', function() {
            var idPerawatan = $(this).attr('idPerawatan');
            var namaPerawatan = $(this).attr('namaPerawatan');


            $("#selectPerawatan").append("<option value='" + idPerawatan + "'>" + namaPerawatan +
                "</option>");
            $(this).parent().parent().remove();
            $("#perawatan" + idPerawatan).remove();

            var select = $("#selectPerawatan");
            $("#selectPerawatan option[value='null']").remove();
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
                    "<tr id='trSilahkan'><td colspan='5=2'>Silahkan Pilih Keterangan Perawatan</td></tr>");
            }
        });
    </script>
@endsection
