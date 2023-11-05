@extends('layout.adminlayout')

@section('title', 'Admin || Tambah Supplier')

@section('admincontent')
    <div class="page-title-box">

    </div>
    <!-- end page-title -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <h3 class="mt-0 header-title">Tambah Supplier</h3>
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

                    <form id="formStoreKaryawan" method="POST" action="{{ route('suppliers.store') }}">
                        @csrf
                        <div class="form-group col-md-12">
                        </div>

                        <div class="form-group row">
                            <div class="col-md-12">
                                <label for="exampleInputEmail1"><strong>Nama Supplier</strong></label>
                                <input type="text" class="form-control" name="namaSupplier" id="txtNamaSupplier"
                                    aria-describedby="emailHelp" placeholder="Silahkan masukkan nama supplier" required
                                    value="{{ old('namaSupplier') }}">
                                <small id="emailHelp" class="form-text text-muted">Masukkan nama supplier disini!</small>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-4">
                                <label for="exampleInputEmail1"><strong>Alamat Supplier</strong></label>
                                <input type="text" class="form-control" name="alamatSupplier" id="txtAlamatSupplier"
                                    aria-describedby="emailHelp" placeholder="Silahkan masukkan alamat supplier" required
                                    value="{{ old('alamatSupplier') }}">
                                <small id="emailHelp" class="form-text text-muted">Masukkan alamat supplier disini!</small>
                            </div>
                            <div class="col-md-4">
                                <label for="exampleInputEmail1"><strong>Nomor Telepon Supplier</strong></label>
                                <input type="text" class="form-control" name="nomor_telepon"
                                    id="txtNomorTeleponSupplier" aria-describedby="emailHelp"
                                    placeholder="Silahkan masukkan nomor telepon supplier" required
                                    value="{{ old('nomor_telepon') }}">
                                <small id="emailHelp" class="form-text text-muted">Masukkan nomor telepon supplier
                                    disini!</small>
                            </div>
                            <div class="col-md-4">
                                <label for="exampleInputEmail1"><strong>Email Supplier</strong></label>
                                <input type="email" class="form-control" name="email" id="txtEmailSupplier"
                                    aria-describedby="emailHelp" placeholder="Silahkan masukkan email supplier" required
                                    value="{{ old('email') }}">
                                <small id="emailHelp" class="form-text text-muted">Masukkan email supplier disini!</small>
                            </div>
                        </div>

                        <div class="form-group row text-right">
                            <div class="col-md-12">
                                <a id="btnBatalTambahSupplier" href="{{ route('suppliers.index') }}"
                                    class="btn btn-danger btn-lg waves-effect waves-light mr-3">Batal</a>
                                <button id="btnTambahSupplier" type="submit"
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
        
    </script>
@endsection
