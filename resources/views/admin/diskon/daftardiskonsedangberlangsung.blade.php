@extends('layout.adminlayout')

@section('title', 'Admin || Daftar Diskon Berlaku')

@section('admincontent')
    <div class="page-title-box">
    </div>
    <!-- end page-title -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <h3 class="mt-0 header-title" id="grupAktif">Daftar Diskon yang Sedang berlaku</h3>
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

                    <table id="tabelDaftarDiskonAktifBerlaku"
                        class="table table-striped table-bordered dt-responsive wrap text-center"
                        style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                            <tr>
                                <th class="align-middle">Nama</th>
                                <th class="align-middle">Jumlah Potongan (%)</th>
                                <th class="align-middle">Minimal Transaksi</th>
                                <th class="align-middle">Maksimum Potongan</th>
                                <th class="align-middle">Tanggal Mulai</th>
                                <th class="align-middle">Tanggal Berakhir</th>
                                <th class="align-middle">Kode Diskon</th>
                                <th hidden class="align-middle">Tanggal Pembuatan</th>
                                <th hidden class="align-middle">Tanggal Terakhir Diedit</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($diskonAktifBerlaku as $d)
                                <tr id="tr_{{ $d->id }}">
                                    <td>{{ $d->nama }}</td>
                                    <td>{{ $d->jumlah_potongan }}</td>
                                    <td>{{ number_format($d->minimal_transaksi, 2, ',', '.') }}</td>
                                    <td>{{ number_format($d->maksimum_potongan, 2, ',', '.') }}</td>
                                    <td>{{ date('d-m-Y', strtotime($d->tanggal_mulai)) }}</td>
                                    <td>{{ date('d-m-Y', strtotime($d->tanggal_berakhir)) }}</td>
                                    <td>{{ $d->kode_diskon }}</td>
                                    <td hidden>{{ date('d-m-Y', strtotime($d->created_at)) }}</td>
                                    <td hidden>{{ date('d-m-Y', strtotime($d->updated_at)) }}</td>
                                </tr>
                            @endforeach

                        </tbody>
                        <tfoot id="grupNonaktif">

                        </tfoot>
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
            $('#tabelDaftarDiskonAktifBerlaku').DataTable({
                language: {
                    emptyTable: "Tidak terdapat data diskon yang sedang berlaku!",
                }
            });
        });
    </script>
@endsection
