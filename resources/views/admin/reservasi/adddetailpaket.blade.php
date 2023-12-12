<tr>
    <td> {{ $paket->nama }} </td>

    <td>
        {{ $paket->perawatans->sum('durasi') }}
    </td>
    <td class="text-left">
        <strong class="font-weight-bold">Detail Perawatan</strong>
        <ul>
            @foreach ($paket->perawatans()->withPivot("urutan")->orderBy("urutan")->get() as $perawatan)
                <li>{{ $perawatan->nama }} - {{ $perawatan->durasi }} menit</li>
            @endforeach
        </ul>
        @if (count($paket->produks) > 0)
            <strong class="font-weight-bold">Detail Produk</strong>
            <ul>
                @foreach ($paket->produks as $produk)
                    <li>{{ $produk->nama }} - ({{ $produk->pivot->jumlah }})</li>
                @endforeach
            </ul>
        @endif

    </td>
    <td> {{ $paket->deskripsi }} </td>
    <td>{{ number_format($paket->harga, 2, ',', '.') }}</td>
    <td>
        <button type='button' class='deletePaket btn btn-danger waves-effect waves-light' idPaket = "{{ $paket->id }}"
            namaPaket = "{{ $paket->nama }}" kodePaket="{{ $paket->kode_paket }}">Hapus</button>
    </td>

</tr>
