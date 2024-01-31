<div>
    <table id="tabelDaftarDetailRiwayatPresensi" class="table table-bordered dt-responsive nowrap text-center w-100"
        style="border-collapse: collapse; border-spacing: 0; width: 100%;">
        <thead>
            <tr>
                <th>Nama Karyawan</th>
                <th>Waktu Presensi Karyawan</th>
                <th>Keterangan</th>
                <th>Status</th>
                <th>Dikonfirmasi Terakhir Pukul</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($presensis as $p)
                <tr id="tr_{{ $p->id }}">
                    <td>{{ $p->karyawan->nama }}</td>
                    <td>
                        @if (date('H:i:s', strtotime($p->created_at)) == date('H:i:s', strtotime($p->tanggal_presensi)))
                            @if ($p->keterangan == 'izin' || $p->keterangan == 'sakit')
                                {{ date('d-m-Y H:i', strtotime($p->created_at)) }}
                            @else
                                @if ($p->status == 'konfirmasi')
                                    {{ date('H:i', strtotime($p->updated_at)) }} <br> <span class="text-danger">*oleh
                                        Admin</span>
                                @else
                                    <span class="text-danger">Tidak Presensi</span>
                                @endif
                            @endif
                        @else
                            @if ($p->keterangan == 'izin' || $p->keterangan == 'sakit')
                                {{ date('d-m-Y H:i', strtotime($p->created_at)) }}
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
                            <td><span class="text-warning font-weight-bold">Belum
                                    Dikonfirmasi</span></td>
                        @elseif($p->status == 'konfirmasi')
                            <td><span class="text-success font-weight-bold">Telah
                                    Dikonfirmasi</span></td>
                        @elseif($p->status == 'tolak')
                            <td><span class="text-danger font-weight-bold">Izin Ditolak</span></td>
                        @endif
                    @else
                        @if ($p->status == 'belum')
                            <td><span class="text-warning font-weight-bold">Belum
                                    Dikonfirmasi</span></td>
                        @elseif($p->status == 'konfirmasi')
                            <td><span class="text-success font-weight-bold">Telah
                                    Dikonfirmasi</span></td>
                        @endif
                    @endif

                    <td>
                        @if ($p->status == 'belum')
                            Menunggu Konfirmasi
                        @else
                            {{ date('d-m-Y H:i', strtotime($p->updated_at)) }}
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
