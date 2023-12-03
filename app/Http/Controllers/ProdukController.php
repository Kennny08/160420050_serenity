<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Kondisi;
use App\Models\Merek;
use App\Models\Paket;
use App\Models\Penjualan;
use App\Models\Produk;
use Illuminate\Http\Request;

class ProdukController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $produksAktifLebihMinimumStok = Produk::where('status', 'aktif')->whereRaw("stok > minimum_stok")->get();
        $produksAktifMinimumStok = Produk::where('status', 'aktif')->whereRaw("stok <= minimum_stok")->get();
        $produksNonaktif = Produk::where('status', 'nonaktif')->get();
        return view("admin.produk.index", compact("produksAktifLebihMinimumStok", "produksAktifMinimumStok", "produksNonaktif"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // $this->authorize('admin', 1);
        $mereks = Merek::orderBy('nama')->get();
        $kategoris = Kategori::orderBy('nama')->get();
        $kondisis = Kondisi::orderBy('keterangan')->get();
        return view("admin.produk.tambahproduk", compact("mereks", "kategoris", "kondisis"));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        date_default_timezone_set("Asia/Jakarta");
        $validatedData = $request->validate(
            [
                'namaProduk' => 'required|max:255',
                'kode_produk' => 'required|unique:produks|starts_with:p',
                'hargaJual' => 'required|numeric|min:1',
                'hargaBeli' => 'required|numeric|min:1',
                'stokProduk' => 'required|numeric|min:1',
                'minimumStok' => 'required|numeric|min:1',
                'arraykondisiid' => 'required|min:1'
            ],
            [
                'namaProduk.required' => 'Nama produk tidak boleh kosong!',
                'kode_produk.required' => 'Kode Produk tidak boleh kosong!',
                'kode_produk.unique' => 'Kode Produk sudah pernah dipakai, mohon gunakan kode yang lain!',
                'kode_produk.starts_with' => "Kode produk harap diawali dengan huruf 'p'",
                'hargaJual.required' => 'Harga Jual produk tidak boleh kosong!',
                'hargaJual.numeric' => 'Harga Jual produk harus berupa angka!',
                'hargaBeli.required' => 'Harga Beli produk tidak boleh kosong',
                'hargaBeli.numeric' => 'Harga Beli produk harus berupa angka',
                'stokProduk.required' => 'Stok produk tidak boleh kosong',
                'stokProduk.numeric' => 'Stok produk harus berupa angka',
                'minimumStok.required' => 'Minimum Stok produk tidak boleh kosong',
                'minimumStok.numeric' => 'Minimum Stok produk harus berupa angka',
                'arraykondisiid.required' => 'Keterangan Kondisi tidak boleh kosong, minimal pilih satu kondisi!',
                'arraykondisiid.min' => 'Minimal pilih 1 keterangan kondisi!',
                'hargaJual.min' => 'Harga Jual produk harus lebih dari Rp. 0!',
                'hargaBeli.min' => 'Harga Beli produk harus lebih dari Rp. 0!',
                'stokProduk.min' => 'Stok produk minimal 1!',
                'minimumStok.min' => 'Minimum Stok produk adalah 1!',
            ]
        );
        
        $namaProduk = $request->get("namaProduk");
        $kodeProduk = $request->get("kode_produk");
        $hargaJual = $request->get("hargaJual");
        $hargaBeli = $request->get("hargaBeli");
        $deskripsiProduk = $request->get("deskripsiProduk");
        $stokProduk = $request->get("stokProduk");
        $minimumStokProduk = $request->get("minimumStok");
        $statusKeaktifan = $request->get("radioStatusProduk");
        $statusJualProduk = $request->get("radioStatusJualProduk");
        $kategoriProduk = $request->get("kategoriProduk");
        $merekProduk = $request->get("merekProduk");
        $arrayKondisi = $request->get("arraykondisiid");

        $newProduk = new Produk();
        $newProduk->nama = $namaProduk;
        $newProduk->harga_beli = $hargaBeli;
        $newProduk->harga_jual = $hargaJual;
        $newProduk->deskripsi = $deskripsiProduk;
        $newProduk->stok = $stokProduk;
        $newProduk->status = $statusKeaktifan;
        $newProduk->kode_produk = $kodeProduk;
        $newProduk->status_jual = $statusJualProduk;
        $newProduk->minimum_stok = $minimumStokProduk;
        $newProduk->kategori_id = $kategoriProduk;
        $newProduk->merek_id = $merekProduk;
        $newProduk->created_at = date("Y-m-d H:i:s");
        $newProduk->updated_at = date("Y-m-d H:i:s");
        $newProduk->save();
        foreach ($arrayKondisi as $kondisi) {
            $newProduk->kondisis()->attach($kondisi);
        }
        return redirect()->route('produks.index')->with('status', 'Berhasil menambah produk ' . $namaProduk . '!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Produk  $produk
     * @return \Illuminate\Http\Response
     */
    public function show(Produk $produk)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Produk  $produk
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $produk = Produk::find($id);
        $mereks = Merek::orderBy('nama')->get();
        $kategoris = Kategori::orderBy('nama')->get();
        $kondisis = Kondisi::orderBy('keterangan')->get();
        return view("admin.produk.editproduk", compact("produk", "mereks", "kategoris", "kondisis"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Produk  $produk
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        date_default_timezone_set("Asia/Jakarta");
        $validatedData = $request->validate(
            [
                'namaProduk' => 'required|max:255',
                'kode_produk' => 'required',
                'hargaJual' => 'required|numeric|min:1',
                'hargaBeli' => 'required|numeric|min:1',
                'stokProduk' => 'required|numeric|min:1',
                'minimumStok' => 'required|numeric|min:1',
                'arraykondisiid' => 'required|min:1'
            ],
            [
                'namaProduk.required' => 'Nama produk tidak boleh kosong!',
                'kode_produk.required' => 'Kode Produk tidak boleh kosong!',
                'hargaJual.required' => 'Harga Jual produk tidak boleh kosong!',
                'hargaJual.numeric' => 'Harga Jual produk harus berupa angka!',
                'hargaBeli.required' => 'Harga Beli produk tidak boleh kosong',
                'hargaBeli.numeric' => 'Harga Beli produk harus berupa angka',
                'stokProduk.required' => 'Stok produk tidak boleh kosong',
                'stokProduk.numeric' => 'Stok produk harus berupa angka',
                'minimumStok.required' => 'Minimum Stok produk tidak boleh kosong',
                'minimumStok.numeric' => 'Minimum Stok produk harus berupa angka',
                'arraykondisiid.required' => 'Keterangan Kondisi tidak boleh kosong, minimal pilih satu kondisi!',
                'arraykondisiid.min' => 'Minimal pilih 1 keterangan kondisi!',
                'hargaJual.min' => 'Harga Jual produk harus lebih dari Rp. 0!',
                'hargaBeli.min' => 'Harga Beli produk harus lebih dari Rp. 0!',
                'stokProduk.min' => 'Stok produk minimal 1!',
                'minimumStok.min' => 'Minimum Stok produk adalah 1!',
            ]
        );

        $produk = Produk::find($id);

        $namaProduk = $request->get("namaProduk");
        $kodeProduk = $request->get("kode_produk");
        $hargaJual = $request->get("hargaJual");
        $hargaBeli = $request->get("hargaBeli");
        $deskripsiProduk = $request->get("deskripsiProduk");
        $stokProduk = $request->get("stokProduk");
        $minimumStokProduk = $request->get("minimumStok");
        $statusKeaktifan = $request->get("radioStatusProduk");
        $statusJualProduk = $request->get("radioStatusJualProduk");
        $kategoriProduk = $request->get("kategoriProduk");
        $merekProduk = $request->get("merekProduk");
        $arrayKondisi = $request->get("arraykondisiid");


        if ($produk->kode_produk != $request->get('kode_produk')) {
            $cekProduk = Produk::where('kode_produk', $kodeProduk)->first();
            if ($cekProduk != null) {
                return redirect()->back()->withErrors('Kode Produk sudah pernah dipakai, mohon gunakan kode yang lain!');
            }
        }

        $produk->nama = $namaProduk;
        $produk->harga_beli = $hargaBeli;

        //Jika harus update harga paket ketika harga produk berubah
        // if ($hargaJual > $produk->harga_jual) {
        //     $selisihPenambahan = $hargaJual - $produk->harga_jual;
        //     $daftarPaket = Paket::join("paket_produk", "paket_produk.paket_id", "=", "pakets.id")->where("paket_produk.produk_id", $produk->id)->get();
        //     if (count($daftarPaket) > 0) {
        //         foreach ($daftarPaket as $paket) {
        //             $kuantitas = $paket->produks->firstWhere("id", $produk->id)->pivot->jumlah;
        //             $paketTerpilih = Paket::find($paket->id);
        //             $paketTerpilih->harga = $paketTerpilih->harga + ($kuantitas * $selisihPenambahan);
        //             $paketTerpilih->updated_at = date("Y-m-d H:i:s");
        //             $paketTerpilih->save();
        //         }
        //     }
        //     $produk->harga_jual = $hargaJual;

        // } else if ($hargaJual < $produk->harga_jual) {
        //     $selisihPengurangan = $produk->harga_jual - $hargaJual;
        //     $daftarPaket = Paket::join("paket_produk", "paket_produk.paket_id", "=", "pakets.id")->where("paket_produk.produk_id", $produk->id)->get();
        //     if (count($daftarPaket) > 0) {
        //         foreach ($daftarPaket as $paket) {
        //             $kuantitas = $paket->produks->firstWhere("id", $produk->id)->pivot->jumlah;
        //             $paketTerpilih = Paket::find($paket->id);
        //             $paketTerpilih->harga = $paketTerpilih->harga - ($kuantitas * $selisihPengurangan);
        //             $paketTerpilih->updated_at = date("Y-m-d H:i:s");
        //             $paketTerpilih->save();
        //         }
        //     }
        //     $produk->harga_jual = $hargaJual;
        // } else {
        //     $produk->harga_jual = $hargaJual;
        // }

        $produk->harga_jual = $hargaJual;

        $produk->deskripsi = $deskripsiProduk;
        $produk->stok = $stokProduk;
        $produk->status = $statusKeaktifan;
        $produk->kode_produk = $kodeProduk;
        $produk->status_jual = $statusJualProduk;
        $produk->minimum_stok = $minimumStokProduk;
        $produk->kategori_id = $kategoriProduk;
        $produk->merek_id = $merekProduk;
        $produk->updated_at = date("Y-m-d H:i:s");
        $produk->save();

        foreach ($produk->kondisis as $kondisi) {
            $produk->kondisis()->detach($kondisi);
        }

        foreach ($arrayKondisi as $idKondisi) {
            $produk->kondisis()->attach($idKondisi);
        }
        return redirect()->route('produks.index')->with('status', 'Berhasil megedit produk ' . $namaProduk . '!');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Produk  $produk
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $objProduk = Produk::find($id);
        try {
            $objProduk->delete();
            return redirect()->route('produks.index')->with('status', 'Produk ' . $objProduk->nama . ' telah berhasil dihapus');
        } catch (\PDOException $ex) {
            $msg = "Data Gagal dihapus. Pastikan kembali tidak ada data yang berelasi sebelum dihapus";
            return redirect()->route('produks.index')->with('status', $msg);
        }
    }

    public function penjualanTambahProduk($id)
    {
        $idPenjualan = $id;
        $penjualan = Penjualan::find($idPenjualan);
        if ($penjualan == null) {
            return redirect()->route('penjualan.null.errorpage');
        } else {
            if ($penjualan->status_selesai == "belum") {
                $produks = Produk::where('status_jual', 'aktif')->where('status', 'aktif')->get();
                return view('admin.penjualanproduk.tambahbeliproduk', compact('produks', 'penjualan'));
            } else {
                if ($penjualan->reservasi != null) {
                    return redirect()->route('reservasi.admin.detailreservasi', $penjualan->reservasi->id);
                } else {
                    return redirect()->route('penjualans.admin.detailpenjualan', $penjualan->id); //nanti diganti dengan halaman detail penjualan dengan pesan error penjualan sudah selesai
                }

            }
        }

    }
}