@extends('layout.adminlayout')

@section('title', 'Admin || Edit Keterangan Kondisi')


@section('admincontent')
    <div class="page-title-box">

    </div>
    <!-- end page-title -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <h3 class="mt-0 header-title">Edit Keterangan Kondisi untuk Produk</h3>
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

                    <form method="POST" action="{{ route('kondisis.update', $objKondisi->id) }}">
                        @csrf
                        @method('PUT')
                        <div class="form-group col-md-12">
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6">
                                <label for="exampleInputEmail1"><strong>Keterangan Kondisi</strong></label>
                                <input type="text" class="form-control" name="keteranganKondisi" id="textNamaKondisi" value="{{ $objKondisi->keterangan }}"
                                    aria-describedby="emailHelp" placeholder="Silahkan masukkan keterangan kondisi" required>
                                <small id="emailHelp" class="form-text text-muted">Masukkan keterangan kondisi disini!</small>

                            </div>
                        </div>
                        <div class="form-group text-right">
                            <div class="col-md-6">
                                <a id="btnBatalEditKondisi" href="{{ route('kondisis.index') }}"
                                    class="btn btn-danger btn-lg waves-effect waves-light mr-3">Batal</a>
                                <button id="btnEditKondisi" type="submit"
                                    class="btn btn-info btn-lg waves-effect waves-light">Simpan</button>
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
    <script></script>
@endsection
