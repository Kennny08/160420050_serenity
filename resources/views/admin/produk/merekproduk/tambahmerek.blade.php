@extends('layout.adminlayout')

@section('title', 'Admin || Tambah Merek Produk')

@section('admincontent')
    <div class="page-title-box">

    </div>
    <!-- end page-title -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <h3 class="mt-0 header-title">Tambah Merek Produk</h3>
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

                    <form method="POST" action="{{ route('mereks.store') }}">
                        @csrf
                        <div class="form-group col-md-12">
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6">
                                <label for="exampleInputEmail1"><strong>Merek Produk</strong></label>
                                <input type="text" class="form-control" name="namaMerek" id="textNamaMerek"
                                    aria-describedby="emailHelp" placeholder="Silahkan masukkan nama produk" required>
                                <small id="emailHelp" class="form-text text-muted">Masukkan Merek Produk disini!</small>

                            </div>
                        </div>
                        <div class="form-group text-right">
                            <div class="col-md-6">
                                <a id="btnBatalTambahMerek" href="{{ route('mereks.index') }}"
                                    class="btn btn-danger btn-lg waves-effect waves-light mr-3">Batal</a>
                                <button id="btnTambahMerek" type="submit"
                                    class="btn btn-info btn-lg waves-effect waves-light">Tambah</button>
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
