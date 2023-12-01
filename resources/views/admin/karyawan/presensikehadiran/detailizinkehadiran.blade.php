<div>
    <table id="tabelDaftarDetailRiwayatIzin" class="table table-bordered dt-responsive nowrap text-center w-100"
        style="border-collapse: collapse; border-spacing: 0; width: 100%;">
        <thead>
            <tr>
                <th>Nama Karyawan</th>
                <th>Keterangan</th>
                <th>Waktu Pengajuan izin</th>
                <th>Tanggal Izin</th>
                <th>Deskripsi Izin</th>
                <th>File Tambahan</th>
                <th>Status</th>
                <th>Dikonfirmasi Terakhir Pukul</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($daftarPresensiIzin as $p)
                <tr id="tr_{{ $p->id }}">
                    <td>{{ $p->karyawan->nama }}</td>
                    @if ($p->keterangan == 'izin')
                        <td class="text-warning">IZIN</td>
                    @elseif($p->keterangan == 'sakit')
                        <td class="text-info">SAKIT</td>
                    @endif
                    <td>
                        {{ date('d-m-Y H:i', strtotime($p->created_at)) }}
                    </td>
                    <td>
                        {{ date('d-m-Y', strtotime($p->tanggal_presensi)) }}
                    </td>

                    <td>{{ $p->deskripsi }}</td>

                    <td>
                        @if ($p->file_tambahan != null)
                            <button data-toggle="modal" data-target="#modalFileTambahan"
                                class="btn btn-info waves-effect waves-light btnLihatFileTambahan"
                                namaImage="{{ asset('assets_admin/images/izin_sakit_karyawan/') }}/{{ $p->file_tambahan }}">Lihat
                            </button>
                        @else
                            Tidak ada File Tambahan
                        @endif
                    </td>


                    <td id="statusKonfirmasi_{{ $p->id }}">
                        @if ($p->status == 'belum')
                            <span class="text-warning font-weight-bold">Belum
                                Dikonfirmasi</span>
                        @elseif($p->status == 'konfirmasi')
                            <span class="text-success font-weight-bold">Telah
                                Dikonfirmasi</span>
                        @elseif($p->status == 'tolak')
                            <span class="text-danger font-weight-bold">Izin Ditolak</span>
                        @endif
                    </td>

                    <td class="align-middle" id="waktuKonfirmasi_{{ $p->id }}">
                        @if ($p->status == 'belum')
                            <button id="btnKonfirmasiIzin" type="button" data-toggle= "modal"
                                data-target="#modalKonfirmasiIzin"
                                class="btn btn-info waves-effect mr-2 btnKonfirmasiIzin"
                                idPresensi="{{ $p->id }}" keterangan="konfirmasi"
                                namaKaryawan = "{{ $p->karyawan->nama }}"
                                tanggalIzin = "{{ date('d-m-Y', strtotime($p->tanggal_presensi)) }}">Konfirmasi</button>
                            <button id="btnTolakIzin" type="button" data-toggle= "modal"
                                data-target="#modalKonfirmasiIzin" class="btn btn-danger waves-effect btnKonfirmasiIzin"
                                idPresensi="{{ $p->id }}" keterangan="tolak"
                                namaKaryawan = "{{ $p->karyawan->nama }}"
                                tanggalIzin = "{{ date('d-m-Y', strtotime($p->tanggal_presensi)) }}">Tolak</button>
                        @else
                            {{ date('d-m-Y H:i', strtotime($p->updated_at)) }}
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
