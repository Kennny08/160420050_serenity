<h6 class="font-weight-normal">Gagal mengubah status karena pada slot jam tersebut dalam minggu ini terdapat reservasi
    pelanggan pada sebagai berikut:
    <h6>
        @foreach ($daftarReservasiFix as $reservasi)
            <p>* Tanggal <span class="text-danger">{{ date('d-m-Y', strtotime($reservasi->tanggal_reservasi)) }}</span>
                atas nama
                <span class="text-danger">{{ $reservasi->penjualan->pelanggan->nama }}</span> dengan nomor nota <span
                    class="text-danger">{{ $reservasi->penjualan->nomor_nota }}</span>
            </p>
        @endforeach
