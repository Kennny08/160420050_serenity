@extends('layout.adminlayout')

@section('title', 'Admin || Presensi Karyawan')

@section('admincontent')
    <div class="page-title-box">
    </div>
    <!-- end page-title -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <h3 class="mt-0 header-title">Daftar Presensi Kehadiran Karyawan</h3>
                    <p class="sub-title">
                    </p>
                    {{-- @if ($presensiKaryawan == null)
                        <a class="btn btn-info waves-effect waves-light" href={{ route('presensikehadirans.create') }}>Buka
                            Presensi
                            Karyawan</a>
                    @endif --}}
                    <br>
                    @if (session('status'))
                        <div class="alert alert-success">
                            <button type="button" class="close text-danger" data-dismiss="alert" aria-label="Close">
                                <span class="text-success" aria-hidden="true">&times;</span>
                            </button>
                            {{ session('status') }}
                        </div>
                        <br>
                    @endif

                    <div class="table-responsive">
                        <table id="tabelDaftarRiwayatPresensi"
                            class="table table-bordered dt-responsive nowrap text-center w-100"
                            style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th>Tanggal Presensi</th>
                                    <th>Waktu Pembuatan</th>
                                    <th>Waktu Karyawan Presensi</th>
                                    <th>Keterangan</th>
                                    <th>Status</th>
                                    <th>Dikonfirmasi Terakhir Pukul</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($daftarRiwayatPresensis as $p)
                                    <tr id="tr_{{ $p->id }}">
                                            <td>{{ date('d-m-Y', strtotime($p->tanggal_presensi)) }}</td>

                                            <td>
                                                @if ($p->keterangan == 'izin')
                                                    {{ date('d-m-Y H:i:s', strtotime($p->created_at)) }}
                                                @else
                                                    {{ date('H:i', strtotime($p->created_at)) }}
                                                @endif

                                            </td>
                                            <td>
                                                @if (date('H:i:s', strtotime($p->created_at)) == date('H:i:s', strtotime($p->tanggal_presensi)))
                                                    Menunggu Karyawan Presensi
                                                @else
                                                    @if ($p->keterangan == 'izin')
                                                        {{ date('d-m-Y H:i:s', strtotime($p->created_at)) }}
                                                    @else
                                                        {{ date('H:i', strtotime($p->tanggal_presensi)) }}
                                                    @endif
                                                @endif
                                            </td>

                                            @if ($p->keterangan == 'hadir')
                                                <td class="text-success">HADIR</td>
                                            @elseif($p->keterangan == 'absen')
                                                <td class="text-danger">ABSEN</td>
                                            @elseif($p->keterangan == 'izin')
                                                <td class="text-warning">IZIN</td>
                                            @elseif($p->keterangan == 'sakit')
                                                <td class="text-info">SAKIT</td>
                                            @endif

                                            @if ($p->keterangan == 'izin')
                                                @if ($p->status == 'belum')
                                                    <td><span style="font-size: 1em;padding: 0.5em 1em;"
                                                            class="badge badge-warning">Belum Dikonfirmasi</span></td>
                                                @elseif($p->status == 'konfirmasi')
                                                    <td><span style="font-size: 1em;padding: 0.5em 1em;"
                                                            class="badge badge-success">Telah Dikonfirmasi</span></td>
                                                @elseif($p->status == 'tolak')
                                                    <td><span style="font-size: 1em;padding: 0.5em 1em;"
                                                            class="badge badge-danger">Izin Ditolak</span></td>
                                                @endif
                                            @else
                                                @if ($p->status == 'belum')
                                                    <td><span style="font-size: 1em;padding: 0.5em 1em;"
                                                            class="badge badge-warning">Belum Dikonfirmasi</span></td>
                                                @elseif($p->status == 'konfirmasi')
                                                    <td><span style="font-size: 1em;padding: 0.5em 1em;"
                                                            class="badge badge-success">Telah Dikonfirmasi</span></td>
                                                @endif
                                            @endif

                                            <td>
                                                @if ($p->status == 'belum')
                                                    Menunggu Konfirmasi
                                                @else
                                                    {{ date('H:i', strtotime($p->updated_at)) }}
                                                @endif
                                            </td>
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

@endsection

@section('javascript')
    <script>
        $(document).ready(function() {
            $('#tabelDaftarRiwayatPresensi').DataTable({
                ordering:false,
                language: {
                    emptyTable: "Tidak terdapat data riwayat presensi Anda!",
                }
            });

        });
    </script>
@endsection
