<div class="form-group row text-center">
    @if (Auth::user()->role == 'admin')
        <div class="form-group col-md-4">
            <h6>Tanggal Lahir</h6>
            <p>{{ date('d-m-Y', strtotime($karyawan->tanggal_lahir)) }}</p>
        </div>
        <div class="form-group col-md-4">
            <h6>Nomor Telepon</h6>
            <p>{{ $karyawan->nomor_telepon }}</p>

        </div>
        <div class="form-group col-md-4">
            <h6>Gaji Pokok</h6>
            <p> Rp. {{ number_format($karyawan->gaji, 0, ',', '.') }}</p>
        </div>
    @else
        <div class="form-group col-md-6">
            <h6>Tanggal Lahir</h6>
            <p>{{ date('d-m-Y', strtotime($karyawan->tanggal_lahir)) }}</p>
        </div>
        <div class="form-group col-md-6">
            <h6>Nomor Telepon</h6>
            <p>{{ $karyawan->nomor_telepon }}</p>

        </div>
    @endif


</div>

<div class="form-group row text-center">
    <div class="form-group col-md-6">
        <h6>Tanggal Pembuatan</h6>
        <p>{{ date('d-m-Y H:i:s', strtotime($karyawan->created_at)) }}</p>
    </div>
    <div class="form-group col-md-6">
        <h6>Tanggal Terakhir Diubah</h6>
        <p>{{ date('d-m-Y H:i:s', strtotime($karyawan->updated_at)) }}</p>
    </div>


</div>


<div class="form-group text-center">
    <h6>Perawatan yang Dikuasai :</h6>
    <table id="tabelDaftarKaryawanPerawatan" class="table table-bordered table-striped text-center"
        style="border-collapse: collapse; border-spacing: 0; width: 100%;">
        <thead>
            <tr>
                <th>Nama Perawatan</th>
                <th>Jumlah Pelayanan</th>
            </tr>
        </thead>
        <tbody id="bodyListPerawatan">
            @foreach ($karyawan->perawatans as $p)
                <tr>
                    <td>
                        {{ $p->nama }}
                    </td>
                    <td>
                        {{ $karyawan->penjualanperawatans->where('perawatan.id', $p->id)->where('penjualan.status_selesai', 'selesai')->count() }}
                    </td>
                </tr>
            @endforeach

        </tbody>
    </table>
</div>
