@extends('layout.adminlayout')

@section('title', 'Admin || Daftar Keterangan Kondisi')

@section('admincontent')
    <div class="page-title-box">
    </div>
    <!-- end page-title -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <h3 class="mt-0 header-title">Daftar Keterangan Kondisi untuk Produk</h3>
                    <p class="sub-title">
                    </p>
                    {{-- <a class="btn btn-primary" data-toggle="modal" href="{{ route('categories.create') }}">Add Category</a> --}}
                    <a class="btn btn-info waves-effect waves-light" href={{ route('kondisis.create') }}>Tambah
                        Keterangan Kondisi</a><br>
                    <br>
                    @if (session('status'))
                        <div class="alert alert-success">
                            <button type="button" class="close text-danger" data-dismiss="alert" aria-label="Close">
                                <span class="text-success" aria-hidden="true">&times;</span>
                            </button>
                            {{ session('status') }}
                        </div>
                    @endif

                    <table id="tabelDaftarKondisi" class="table table-bordered dt-responsive nowrap text-center"
                        style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Keterangan Kondisi</th>
                                <th>Tanggal Pembuatan</th>
                                <th>Tanggal Edit Terakhir</th>
                                <th>Edit</th>
                                <th>Hapus</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($kondisis as $k)
                                <tr id="tr_{{ $k->id }}">
                                    <td>{{ $k->id }}</td>
                                    <td>{{ $k->keterangan }}</td>
                                    <td>{{ date('d-m-Y H:i:s', strtotime($k->updated_at)) }}</td>
                                    <td>{{ date('d-m-Y H:i:s', strtotime($k->updated_at)) }}</td>
                                    <td class="text-center"><a href="{{ route('kondisis.edit', $k->id) }}"
                                            class=" btn btn-info waves-effect waves-light">Edit</a></td>
                                    <td class="text-center"><button data-toggle="modal"
                                            data-target="#modalKonfirmasiDeleteKondisi"
                                            class=" btn btn-danger waves-effect waves-light btnHapusKondisi"
                                            idKondisi = "{{ $k->id }}" keteranganKondisi="{{ $k->keterangan }}"
                                            routeUrl = "{{ route('kondisis.destroy', $k->id) }}">Hapus</button></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
        <!-- end col -->
    </div>

    <div id="modalKonfirmasiDeleteKondisi" class="modal fade bs-example-modal-center" tabindex="-1" role="dialog"
        aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <form id="formDeleteKondisi" action="{{ route('kondisis.destroy', '1') }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="modal-header">
                        <h5 id="modalKeteranganKondisi" class="modal-title mt-0">Konfirmasi Penghapusan Kondisi</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div id="modalBodyHapusKondisi" class="modal-body text-center">
                        <h6>Apakah Anda yakin untuk menghapus kondisi?</h6>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">Batal</button>
                        <button id="btnKonfirmasiHapusKondisi" type="submit"
                            class="btn btn-info waves-effect waves-light btnKonfirmasiHapusKondisi">Hapus</button>
                    </div>
                </form>
            </div>

        </div>
        <!-- /.modal-dialog -->
    </div>
@endsection

@section('javascript')
    <script>
        $(document).ready(function() {
            $('#tabelDaftarKondisi').DataTable({

            });
        });

        $('.btnHapusKondisi').on('click', function() {

            var idKondisi = $(this).attr("idKondisi");
            var keteranganKondisi = $(this).attr('keteranganKondisi');
            var routeUrl = $(this).attr('routeUrl');
            $("#modalKeteranganKondisi").text("Konfirmasi Penghapusan Kondisi " + keteranganKondisi);
            $("#modalBodyHapusKondisi").html("<h6>Apakah Anda yakin untuk menghapus kondisi " + keteranganKondisi +
                "?</h6>")
            $("#formDeleteKondisi").attr("action", routeUrl);
        });
    </script>
@endsection
