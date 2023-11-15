<div>
    <table id="tabelDaftarDetailRiwayatIzin" class="table table-bordered dt-responsive nowrap text-center w-100"
        style="border-collapse: collapse; border-spacing: 0; width: 100%;">
        <thead>
            <tr>
                <th>Nama Karyawan</th>
                <th>Waktu Pengajuan izin</th>
                <th>Tanggal Izin</th>
                <th>Keterangan</th>
                <th>Status</th>
                <th>Dikonfirmasi Terakhir Pukul</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($daftarPresensiIzin as $p)
                <tr id="tr_{{ $p->id }}">
                    <td>{{ $p->karyawan->nama }}</td>
                    <td>
                        {{ date('d-m-Y H:i', strtotime($p->created_at)) }}
                    </td>
                    <td>
                        {{ date('d-m-Y', strtotime($p->tanggal_presensi)) }}
                    </td>

                    <td class="text-warning">IZIN</td>

                    <td id="statusKonfirmasi_{{ $p->id }}">
                        @if ($p->status == 'belum')
                            <span style="font-size: 1em;padding: 0.5em 1em;" class="badge badge-warning">Belum
                                Dikonfirmasi</span>
                        @elseif($p->status == 'konfirmasi')
                            <span style="font-size: 1em;padding: 0.5em 1em;" class="badge badge-success">Telah
                                Dikonfirmasi</span>
                        @elseif($p->status == 'tolak')
                            <span style="font-size: 1em;padding: 0.5em 1em;" class="badge badge-danger">Izin
                                Ditolak</span>
                        @endif
                    </td>

                    <td class="align-middle" id="waktuKonfirmasi_{{ $p->id }}">
                        @if ($p->status == 'belum')
                            <button id="btnKonfirmasiIzin" type="button" data-toggle= "modal"
                                data-target="#modalKonfirmasiIzin"
                                class="btn btn-info waves-effect mr-2 btnKonfirmasiIzin"
                                idPresensi="{{ $p->id }}" keterangan="konfirmasi"
                                namaKaryawan = "{{ $p->karyawan->nama }}"
                                tanggalIzin = "{{ $p->tanggal_presensi }}">Konfirmasi</button>
                            <button id="btnTolakIzin" type="button" data-toggle= "modal"
                                data-target="#modalKonfirmasiIzin" class="btn btn-danger waves-effect btnKonfirmasiIzin"
                                idPresensi="{{ $p->id }}" keterangan="tolak"
                                namaKaryawan = "{{ $p->karyawan->nama }}"
                                tanggalIzin = "{{ date('d-m-Y H:i:s', strtotime($p->tanggal_presensi)) }}">Tolak</button>
                        @else
                            {{ date('d-m-Y H:i', strtotime($p->updated_at)) }}
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
