<div class="form-group row">
    <div class="form-group col-md-12">
        <table id="tabelDetailDaftarPenjualan" class="table table-bordered dt-responsive nowrap text-center"
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
                @foreach ($penjualans as $penjualan)
                    @foreach ($penjualan->penjualanperawatans as $pp)
                        @if ($pp->karyawan_id == Auth::user()->karyawan->id)
                            <tr>
                                <td>{{ $penjualan->id }}</td>
                                <td>{{ $penjualan->nomor_nota }}</td>
                                <td>
                                    @if ($penjualan->status_selesai == 'batal')
                                        <span class="text-danger font-16">{{ $penjualan->status_selesai }}</span>
                                    @elseif($penjualan->status_selesai == 'selesai')
                                        <span class="text-success font-16">{{ $penjualan->status_selesai }}</span>
                                    @else
                                        <span class="text-warning font-16">{{ $penjualan->status_selesai }}</span>
                                    @endif
                                </td>
                                <td>{{ $penjualan->pelanggan->nama }}</td>
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
