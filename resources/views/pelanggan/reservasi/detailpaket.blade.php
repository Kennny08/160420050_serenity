<div class="align-middle alert alert-danger" role="alert">
    <h6><strong>{{ $paket->kode_paket }} - {{ $paket->nama }}</strong></h6>
</div>
<h6>Detail Perawatan</h6>
@foreach ($paket->perawatans()->withPivot("urutan")->orderBy("urutan")->get() as $perawatan)
    <p class="card-text">{{ $perawatan->nama }} - {{ $perawatan->durasi }} menit</p>
@endforeach

<br>

@if (count($paket->produks) > 0)
    <h6>Detail Produk</h6>
    @foreach ($paket->produks as $produk)
        <p class="card-text">{{ $produk->nama }} - ( {{ $produk->pivot->jumlah }} )</p>
    @endforeach
@endif

<br>

<p class="card-text"><strong class="h6 font-weight-bold">Harga :</strong> Rp. {{ number_format($paket->harga, 2, ',', '.') }}</p>
<p class="card-text"><strong class="h6 font-weight-bold">Deskripsi :</strong> {{ $paket->deskripsi }}</p>
