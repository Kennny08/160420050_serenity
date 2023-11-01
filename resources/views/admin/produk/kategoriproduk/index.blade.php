@extends('layout.adminlayout')

@section('admincontent')
    <div class="page-title-box">
    </div>
    <!-- end page-title -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <h3 class="mt-0 header-title">Daftar Kategori Produk</h3>
                    <p class="sub-title">
                    </p>
                    {{-- <a class="btn btn-primary" data-toggle="modal" href="{{ route('categories.create') }}">Add Category</a> --}}
                    <a class="btn btn-info waves-effect waves-light" href={{ route('kategoris.create') }}>Tambah
                        Kategori</a><br>
                    <br>
                    @if (session('status'))
                        <div class="alert alert-success">
                            <button type="button" class="close text-danger" data-dismiss="alert" aria-label="Close">
                                <span class="text-success" aria-hidden="true">&times;</span>
                            </button>
                            {{ session('status') }}
                        </div>
                    @endif

                    <table id="tabelDaftarKategori" class="table table-bordered dt-responsive nowrap text-center"
                        style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama Kategori</th>
                                <th>Tanggal Pembuatan</th>
                                <th>Tanggal Edit Terakhir</th>
                                <th>Edit</th>
                                <th>Hapus</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($kategoris as $k)
                                <tr id="tr_{{ $k->id }}">
                                    <td>{{ $k->id }}</td>
                                    <td>{{ $k->nama }}</td>
                                    <td>{{ date('d-m-Y H:i:s', strtotime($k->created_at)) }}</td>
                                    <td>{{ date('d-m-Y H:i:s', strtotime($k->updated_at)) }}</td>
                                    <td class="text-center"><a href="{{ route('kategoris.edit', $k->id) }}"
                                            class=" btn btn-info waves-effect waves-light">Edit</a></td>
                                    <td class="text-center"><a href="{{ route('kategoris.edit', $k->id) }}"
                                            class=" btn btn-danger waves-effect waves-light">Hapus</a></td>
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
            $('#tabelDaftarKategori').DataTable({

            });
        });
    </script>
@endsection
