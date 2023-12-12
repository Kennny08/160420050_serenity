<tr class='align-middle'>
    <td> {{ $paket->nama }} </td>

    <td>
        {{ $paket->perawatans->sum('durasi') }}
    </td>
    <td style="text-align: left;">
        <strong class="font-weight-bold">Detail Perawatan</strong>
        <ul>
            @foreach ($paket->perawatans()->withPivot("urutan")->orderBy("urutan")->get() as $perawatan)
                <li>* {{ $perawatan->nama }} - {{ $perawatan->durasi }} menit</li>
            @endforeach
        </ul>
        @if (count($paket->produks) > 0)
            <strong class="font-weight-bold">Detail Produk</strong>
            <ul>
                @foreach ($paket->produks as $produk)
                    <li>* {{ $produk->nama }} - ({{ $produk->pivot->jumlah }})</li>
                @endforeach
            </ul>
        @endif

    </td>
    <td> {{ $paket->deskripsi }} </td>
    <td>{{ number_format($paket->harga, 2, ',', '.') }}</td>
    <td class='product-wishlist-cart'>
        <button type='button' style='width:100%;height:30px; border-radius:3px;font-weight: normal; padding-right: 10px; padding-left: 10px;'
            class='deletePaket btn btn-danger waves-effect waves-light' idPaket = "{{ $paket->id }}"
            namaPaket = "{{ $paket->nama }}" kodePaket="{{ $paket->kode_paket }}">Hapus</button>
    </td>

</tr>
