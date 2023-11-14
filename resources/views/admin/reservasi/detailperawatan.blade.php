<div class="align-middle alert alert-info" role="alert">
    <h6><strong>{{ $perawatan->kode_perawatan }} - {{ $perawatan->nama }}</strong></h6>
</div>
<p class="card-text">Durasi : {{ $perawatan->durasi }} menit</p>
<p class="card-text">Harga : Rp. {{ number_format($perawatan->harga, 2, ',', '.') }}</p>
<p class="card-text">Deskripsi : {{ $perawatan->deskripsi }}</p>
