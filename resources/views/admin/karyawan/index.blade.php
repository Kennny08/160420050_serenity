@extends('layout.adminlayout')

@section('admincontent')
    <div class="page-title-box">
    </div>
    <!-- end page-title -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <h3 class="mt-0 header-title">Daftar Karyawan</h3>
                    <p class="sub-title">
                    </p>
                    {{-- <a class="btn btn-primary" data-toggle="modal" href="{{ route('categories.create') }}">Add Category</a> --}}
                    <a class="btn btn-info waves-effect waves-light" href={{ route('karyawans.create') }}>Tambah
                        Karyawan</a><br>
                    <br>
                    @if (session('status'))
                        <div class="alert alert-success">
                            <button type="button" class="close text-danger" data-dismiss="alert" aria-label="Close">
                                <span class="text-success" aria-hidden="true">&times;</span>
                            </button>
                            {{ session('status') }}
                        </div>
                    @endif

                    <div>
                        <table id="tabelDaftarKaryawan" class="table table-bordered dt-responsive nowrap text-center w-100"
                            style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Username</th>
                                    <th hidden>Tanggal Lahir</th>
                                    <th hidden>Gaji Pokok</th>
                                    <th>Jenis Kelamin</th>
                                    <th hidden>Nomor Telepon</th>
                                    <th>Jenis</th>
                                    <th hidden>Tanggal Pembuatan</th>
                                    <th hidden>Tanggal Edit Terakhir</th>
                                    <th>Detail</th>
                                    <th>Edit</th>
                                    <th>Hapus</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($karyawans as $k)
                                    <tr id="tr_{{ $k->id }}">
                                        <td>{{ $k->id }}</td>
                                        <td>{{ $k->nama }}</td>
                                        <td>{{ $k->user->email }}</td>
                                        <td>{{ $k->user->username }}</td>
                                        <td hidden>{{ date('d-m-Y', strtotime($k->tanggal_lahir)) }}</td>
                                        <td hidden>{{ $k->gaji }}</td>
                                        <td>{{ $k->gender }}</td>
                                        <td hidden>{{ $k->nomor_telepon }}</td>
                                        <td>{{ $k->jenis_karyawan }}</td>
                                        <td hidden>{{ date('d-m-Y H:i:s', strtotime($k->created_at)) }}</td>
                                        <td hidden>{{ date('d-m-Y H:i:s', strtotime($k->updated_at)) }}</td>
                                        <td class="text-center"><button data-toggle="modal"
                                                data-target="#modalDetailKaryawan" gaji="{{ $k->gaji }}"
                                                nama ="{{ $k->nama }}" nomorTelepon={{ $k->nomor_telepon }}
                                                tanggalLahir = "{{ date('d-m-Y', strtotime($k->tanggal_lahir)) }}"
                                                tanggalPembuatan = "{{ date('d-m-Y H:i:s', strtotime($k->created_at)) }}"
                                                tanggalDiubah = "{{ date('d-m-Y H:i:s', strtotime($k->updated_at)) }}"
                                                idKaryawan = "{{ $k->id }}"
                                                class=" btn btn-warning waves-effect waves-light btnDetailKaryawan">Detail</button>
                                        </td>
                                        <td class="text-center"><a href="{{ route('karyawans.edit', $k->id) }}"
                                                class=" btn btn-info waves-effect waves-light">Edit</a></td>
                                        <td class="text-center"><a href="{{ route('karyawans.edit', $k->id) }}"
                                                class=" btn btn-danger waves-effect waves-light">Hapus</a></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>


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
            $('#tabelDaftarKaryawan').DataTable({

            });

        });

        $('.btnDetailKaryawan').on('click', function() {
            var idKaryawan = $(this).attr("idKaryawan");
            var nama = $(this).attr('nama');

            $("#modalNamaKaryawan").text(" Detail Karyawan " + nama);
            $.ajax({
                type: 'POST',
                url: '{{ route('admin.getdetailkaryawan') }}',
                data: {
                    '_token': '<?php echo csrf_token(); ?>',
                    'idKaryawan': idKaryawan,
                },
                success: function(data) {
                    $('#contentDetailKaryawan').html(data.msg);
                    $('#tabelDaftarKaryawanPerawatan').DataTable({});
                }
            })

        });
    </script>
@endsection
