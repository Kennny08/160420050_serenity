<div class="table-responsive">
    <div>
        <table id="tabelDetailReservasiPerTanggal"
            class="tabelDaftarReservasi table table-bordered dt-responsive nowrap text-center"
            style="border-collapse: collapse; border-spacing: 0; width: 100%;">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>No. Nota Penjualan</th>
                    <th>Status</th>
                    <th>Pelanggan</th>
                    <th>Perawatan</th>
                    <th>Durasi (Menit)</th>
                    <th>Jam Pelayanan</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($reservasis as $r)
                    @foreach ($r->penjualan->penjualanperawatans as $pp)
                        @if ($pp->karyawan_id == Auth::user()->karyawan->id)
                            <tr>
                                <td>{{ $r->id }}</td>
                                <td>{{ $r->penjualan->nomor_nota }}</td>
                                <td>
                                    @if ($r->status == 'dibatalkan salon' || $r->status == 'dibatalkan pelanggan')
                                        <span class="badge badge-danger font-16">{{ $r->status }}</span>
                                    @elseif($r->status == 'selesai')
                                        <span class="badge badge-success font-16">{{ $r->status }}</span>
                                    @else
                                        <span class="badge badge-warning font-16">{{ $r->status }}</span>
                                    @endif
                                </td>
                                <td>{{ $r->penjualan->pelanggan->nama }}</td>
                                <td>{{ $pp->perawatan->nama }}</td>
                                <td>{{ $pp->slotjams->count() * 30 }}</td>
                                <td>
                                    @php
                                        $idMin = $pp->slotjams->min('id');
                                    @endphp
                                    {{ $pp->slotjams->firstWhere('id', $idMin)->jam }}
                                </td>
                            </tr>
                        @endif
                    @endforeach
                @endforeach
            </tbody>
        </table>
    </div>
</div>
