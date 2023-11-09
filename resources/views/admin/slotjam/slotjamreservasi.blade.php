@extends('layout.adminlayout')

@section('title', 'Admin || Daftar Slot Jam Reservasi')

@section('admincontent')
    <div class="page-title-box">
    </div>
    <!-- end page-title -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <h4 class="mt-0">Pengaturan Slot Jam Reservasi</h4>
                    <h6>pengaturan slot jadwal berlaku untuk minggu ini <span class="text-danger">(Minggu, {{ date("d-m-Y", strtotime($tanggalMingguIni)) }} - Sabtu, {{ date("d-m-Y", strtotime($tanggalSabtuIni)) }})</span></h6>
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

                    <div class="table-responsive">
                        <table id="tabelDaftarSlotJam" class="table dt-responsive table-bordered wrap text-center w-100"
                            style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead class="font-weight-bold">
                                <tr>
                                    <th>Jam</th>
                                    <th>
                                        Senin <input style="transform: scale(1.5)" class="float-right" type="checkbox"
                                            value="apa"></th>
                                    <th>Selasa</th>
                                    <th>Rabu</th>
                                    <th>Kamis</th>
                                    <th>Jumat</th>
                                    <th>Sabtu</th>
                                    <th>Minggu</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($daftarSlotJams as $slotJam)
                                    <tr>
                                        <td class="font-weight-bold">{{ $slotJam[0]->jam }}</td>
                                        @foreach ($slotJam as $s)
                                            <td>
                                                @if ($s->status == 'aktif')
                                                    <div class="form-group row">
                                                        <div class="col-md-12">
                                                            <div class="btn-group btn-group-toggle border w-100"
                                                                data-toggle="buttons">
                                                                <label class="btn btn-info waves-effect waves-light"
                                                                    id="lblSlotJamAktif_{{ $s->id }}">
                                                                    <span value="aktif"
                                                                        id="optionStatusSlotJamAktif_{{ $s->id }}"
                                                                        class="radioStatusAktifSlotJam"
                                                                        idSlotJam="{{ $s->id }}">
                                                                        Buka</span>
                                                                </label>
                                                                <label class="btn waves-effect waves-light"
                                                                    id="lblSlotJamNonaktif_{{ $s->id }}">
                                                                    <span value="nonaktif"
                                                                        id="optionStatusSlotJamNonaktif_{{ $s->id }}"
                                                                        class="radioStatusAktifSlotJam"
                                                                        idSlotJam="{{ $s->id }}">
                                                                        Tutup</span>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="form-group row">
                                                        <div class="col-md-12">
                                                            <div class="btn-group btn-group-toggle border w-100"
                                                                data-toggle="buttons">
                                                                <label class="btn waves-effect waves-light"
                                                                    id="lblSlotJamAktif_{{ $s->id }}">
                                                                    <span type="radio" value="aktif"
                                                                        name="radioStatusAktifSlotJam_{{ $s->id }}"
                                                                        id="optionStatusSlotJamAktif_{{ $s->id }}"
                                                                        class="radioStatusAktifSlotJam"
                                                                        idSlotJam="{{ $s->id }}">
                                                                        Buka</span>
                                                                </label>
                                                                <label class="btn btn-danger waves-effect waves-light"
                                                                    id="lblSlotJamNonaktif_{{ $s->id }}">
                                                                    <span type="radio" value="nonaktif"
                                                                        name="radioStatusAktifSlotJam_{{ $s->id }}"
                                                                        id="optionStatusSlotJamNonaktif_{{ $s->id }}"
                                                                        class="radioStatusAktifSlotJam"
                                                                        idSlotJam="{{ $s->id }}">
                                                                        Tutup</span>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </td>
                                        @endforeach
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

    <div id="modalDetailGagalEditStatus" class="modal fade bs-example-modal-center" tabindex="-1" role="dialog"
        aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" style="max-width: 600px;">
            <div class="modal-content ">
                <div class="modal-header">
                    <h5 class="modal-title mt-0" id="modalNamaKaryawan">Terjadi Kesalahan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="contentDetailGagalEditStatus">
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
            // $('#tabelDaftarSlotJam').DataTable({

            // });

        });

        $('body').on('click', '.radioStatusAktifSlotJam', function() {
            var statusSlotJam = $(this).attr("value");
            var idSlotJam = $(this).attr("idSlotJam");

            $.ajax({
                type: 'POST',
                url: '{{ route('admin.editstatusslotjam') }}',
                data: {
                    '_token': '<?php echo csrf_token(); ?>',
                    'idSlotJam': idSlotJam,
                    'status': statusSlotJam,
                },
                success: function(data) {
                    if (statusSlotJam == "nonaktif") {
                        if (data.status == "berhasil") {
                            $("#lblSlotJamAktif_" + idSlotJam).removeClass("btn-info");
                            $("#lblSlotJamNonaktif_" + idSlotJam).addClass("btn-danger");
                        } else {
                            $("#contentDetailGagalEditStatus").html(data.msg);
                            $("#modalDetailGagalEditStatus").modal('show');

                        }
                    } else {
                        if (data.status == "berhasil") {
                            $("#lblSlotJamAktif_" + idSlotJam).addClass("btn-info");
                            $("#lblSlotJamNonaktif_" + idSlotJam).removeClass("btn-danger");
                        }
                    }
                }
            })
        });

        // $('.btnDetailKaryawan').on('click', function() {
        //     var idKaryawan = $(this).attr("idKaryawan");
        //     var nama = $(this).attr('nama');

        //     $("#modalNamaKaryawan").text(" Detail Karyawan " + nama);
        //     $.ajax({
        //         type: 'POST',
        //         url: '{{ route('admin.getdetailkaryawan') }}',
        //         data: {
        //             '_token': '<?php echo csrf_token(); ?>',
        //             'idKaryawan': idKaryawan,
        //         },
        //         success: function(data) {
        //             $('#contentDetailKaryawan').html(data.msg);
        //             $('#tabelDaftarKaryawanPerawatan').DataTable({});
        //         }
        //     })
        // });
        $('.btnHapusKaryawan').on('click', function() {

            var idKaryawan = $(this).attr("idKaryawan");
            var namaKaryawan = $(this).attr('namaKaryawan');
            var routeUrl = $(this).attr('routeUrl');
            $("#modalNamaKaryawanDelete").text("Konfirmasi Penghapusan Karyawan " + namaKaryawan);
            $("#modalBodyHapusKaryawan").html("<h6>Apakah Anda yakin untuk menghapus perawatan " + namaKaryawan +
                "?</h6>")
            $("#formDeleteKaryawan").attr("action", routeUrl);
        });
    </script>
@endsection
