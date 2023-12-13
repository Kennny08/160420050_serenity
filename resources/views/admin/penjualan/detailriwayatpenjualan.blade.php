<div class="form-group row">
    <div class="form-group col-md-12">
        <table id="tabelDetailDaftarPenjualan" class="table table-bordered dt-responsive nowrap text-center"
            style="border-collapse: collapse; border-spacing: 0; width: 100%;">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Waktu Pembuatan Penjualan</th>
                    <th>Jam Mulai</th>
                    <th>Status</th>
                    <th>Pelanggan</th>
                    <th>No. Nota Penjualan</th>
                    <th>Detail</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($riwayatPenjualans as $penjualan)
                    <tr id="tr_{{ $penjualan->id }}">
                        <td>{{ $penjualan->id }}</td>
                        <td>{{ date('H:i:s', strtotime($penjualan->created_at)) }}
                        </td>
                        <td>
                            {{ $penjualan->penjualanperawatans()->orderBy('id')->first()->slotjams()->orderBy('slot_jam_id')->first()->jam }}
                        </td>
                        <td>
                            @if ($penjualan->status_selesai == 'batal')
                                <span class="text-danger font-16">Dibatalkan </span>
                            @elseif($penjualan->status_selesai == 'selesai')
                                <span class="text-success font-16">Selesai</span>
                            @else
                                <span class="text-warning font-16">Belum Diselesaikan</span>
                            @endif
                        </td>
                        <td>{{ $penjualan->pelanggan->nama }}</td>
                        <td>{{ $penjualan->nomor_nota }}</td>
                        <td class="text-center"><a href="{{ route('penjualans.admin.detailpenjualan', $penjualan->id) }}"
                                class="btn btn-info waves-effect waves-light">Detail</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
