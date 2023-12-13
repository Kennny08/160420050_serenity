@extends('layout.adminlayout')

@section('title', 'Admin || Daftar Ulasan')

@section('admincontent')
    <div class="page-title-box">
    </div>
    <!-- end page-title -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <h3 class="mt-0 header-title" id="grupAktif">Daftar Ulasan</h3>
                    <p class="sub-title">
                    </p>

                    <br>
                    @if (session('status'))
                        <div class="alert alert-success alert-dismissible" role="alert">
                            <button type="button" class="close text-success" data-dismiss="alert" aria-label="Close">
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

                    <form id="formUbahStatus" hidden action="{{ route('ulasans.admin.editStatusUlasan') }}" method="POST">
                        @csrf
                        <input type="hidden" name="idUlasan" id="idUlasanEdit" value="0">
                    </form>

                    <div class="form-group row">
                        <div class="col-md-6">
                            <h4>Ulasan Aktif</h4>
                        </div>
                        <div class="col-md-6 text-right">
                            <div class="btn-group btn-group-toggle border">
                                <a href="#grupAktif" class="btn btn-info waves-effect waves-light radioAktif">
                                    Ulasan Aktif
                                </a>
                                <a href="#grupNonaktif" class="btn waves-effect waves-light radioNonaktif">
                                    Ulasan Nonaktif
                                </a>
                            </div>
                        </div>
                    </div>

                    <table id="tabelDaftarUlasanAktif"
                        class="table table-striped table-bordered dt-responsive wrap text-center"
                        style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                            <tr>
                                <th class="align-middle">Nomor Nota</th>
                                <th class="align-middle">Nama Pelanggan</th>
                                <th class="align-middle">Ulasan</th>
                                <th >Tanggal Pembuatan</th>
                                <th >Tanggal Terakhir Diedit</th>
                                <th class="align-middle">Nonaktifkan</th>

                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($ulasanAktif as $ua)
                                <tr>
                                    <td>{{ $ua->penjualan->nomor_nota }}</td>
                                    <td>{{ $ua->penjualan->pelanggan->nama }}</td>
                                    <td>{{ $ua->ulasan }}</td>
                                    <td>{{ date('d-m-Y H:i:s', strtotime($ua->created_at)) }}</td>
                                    <td>{{ date('d-m-Y H:i:s', strtotime($ua->updated_at)) }}</td>
                                    <td>
                                        <button 
                                            idUlasan="{{ $ua->id }}"
                                            class=" btn btn-danger waves-effect waves-light btnUbahStatusUlasan">Nonaktifkan</button>
                                    </td>


                                </tr>
                            @endforeach

                        </tbody>
                        <tfoot id="grupNonaktif">

                        </tfoot>
                    </table>
                    <br>
                    <br>
                    <div class="form-group row">
                        <div class="col-md-6">
                            <h4>Ulasan Nonaktif</h4>
                        </div>
                        <div class="col-md-6 text-right">
                            <div class="btn-group btn-group-toggle border">
                                <a href="#grupAktif" class="btn btn-info waves-effect waves-light radioAktif">
                                    Ulasan Aktif
                                </a>
                                <a href="#grupNonaktif" class="btn waves-effect waves-light radioNonaktif">
                                    Ulasan Nonaktif
                                </a>
                            </div>
                        </div>
                    </div>

                    <table id="tabelDaftarUlasanNonaktif"
                        class="table table-striped table-bordered dt-responsive wrap text-center"
                        style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                            <tr>
                                <th class="align-middle">Nomor Nota</th>
                                <th class="align-middle">Nama Pelanggan</th>
                                <th class="align-middle">Ulasan</th>
                                <th >Tanggal Pembuatan</th>
                                <th >Tanggal Terakhir Diedit</th>
                                <th class="align-middle">Aktifkan</th>

                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($ulasanNonaktif as $un)
                                <tr>
                                    <td>{{ $un->penjualan->nomor_nota }}</td>
                                    <td>{{ $un->penjualan->pelanggan->nama }}</td>
                                    <td>{{ $ua->ulasan }}</td>
                                    <td>{{ date('d-m-Y H:i:s', strtotime($un->created_at)) }}</td>
                                    <td>{{ date('d-m-Y H:i:s', strtotime($un->updated_at)) }}</td>
                                    <td>
                                        <button 
                                            idUlasan="{{ $un->id }}"
                                            class=" btn btn-info waves-effect waves-light btnUbahStatusUlasan">Aktifkan</button>
                                    </td>


                                </tr>
                            @endforeach

                        </tbody>
                    </table>

                </div>
            </div>
        </div>
        <!-- end col -->
    </div>

@endsection

@section('javascript')
    <script>
        $(document).ready(function() {
            $('#tabelDaftarUlasanAktif').DataTable({
                language: {
                    emptyTable: "Tidak terdapat data ulasan aktif!",
                }
            });

            $('#tabelDaftarUlasanNonaktif').DataTable({
                language: {
                    emptyTable: "Tidak terdapat data ulasan nonaktif!",
                }
            });

        });

        $('.radioAktif').on('click', function() {
            $(".radioAktif").addClass("btn-info");
            $(".radioNonaktif").removeClass("btn-info");
        });

        $('.radioNonaktif').on('click', function() {
            $(".radioNonaktif").addClass("btn-info");
            $(".radioAktif").removeClass("btn-info");
        });

        $('body').on('click', ".btnUbahStatusUlasan", function() {

            var idUlasan = $(this).attr("idUlasan");
            $("#idUlasanEdit").val(idUlasan);

            $("#formUbahStatus").submit();
            

        });
    </script>
@endsection
