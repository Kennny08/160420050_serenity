<div class="form-group row">
    <div class="form-group col-md-12">
        <table id="tabelDetailDaftarReservasi" class="table table-bordered dt-responsive nowrap text-center"
            style="border-collapse: collapse; border-spacing: 0; width: 100%;">
            <thead>
                <tr>
                    <th>ID</th>
                    <th hidden>tanggalHidden</th>
                    <th>Tanggal Pembuatan Reservasi</th>
                    <th>Tanggal Reservasi</th>

                    <th>Jam Mulai</th>
                    <th>Status</th>
                    <th>Pelanggan</th>
                    <th>No. Nota Penjualan</th>
                    <th>Detail</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($riwayatReservasis as $r)
                    <tr id="tr_{{ $r->id }}">
                        <td>{{ $r->id }}</td>
                        <td hidden>{{ date('Y-m-d', strtotime($r->tanggal_reservasi)) }}</td>
                        <td>{{ date('d-m-Y H:i:s', strtotime($r->tanggal_pembuatan_reservasi)) }}</td>
                        <td>{{ date('d-m-Y', strtotime($r->tanggal_reservasi)) }}</td>
                        <td>
                            {{ $r->penjualan->penjualanperawatans()->orderBy('id')->first()->slotjams()->orderBy('slot_jam_id')->first()->jam }}
                        </td>
                        <td>
                            @if ($r->status == 'dibatalkan salon' || $r->status == 'dibatalkan pelanggan' || $r->status == 'tidak hadir')
                                <span class="text-danger font-16">{{ $r->status }}</span>
                            @elseif($r->status == 'selesai')
                                <span class="text-success font-16">{{ $r->status }}</span>
                            @else
                                <span class="text-warning font-16">{{ $r->status }}</span>
                            @endif
                        </td>
                        <td>{{ $r->penjualan->pelanggan->nama }}</td>
                        <td>{{ $r->penjualan->nomor_nota }}</td>
                        <td class="text-center"><a href="{{ route('reservasi.admin.detailreservasi', $r->id) }}"
                                class=" btn btn-info waves-effect waves-light">Detail</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
