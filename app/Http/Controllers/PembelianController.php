<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use App\Models\Pembelian;
use App\Models\Produk;
use App\Models\Supplier;
use Illuminate\Http\Request;

class PembelianController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pembeliansBelumBayar = Pembelian::where('tanggal_bayar', NULL)->orderBy('tanggal_beli', 'asc')->get();
        $pembelians = Pembelian::where('tanggal_bayar', '!=', NULL)->orderBy('tanggal_beli', 'desc')->get();

        return view('admin.pembelian.index', compact('pembeliansBelumBayar', 'pembelians'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $produksKurangDariMinimumStok = Produk::whereRaw('stok <= minimum_stok')->get();
        $produks = Produk::whereRaw('stok > minimum_stok')->get();
        $karyawansAdmin = Karyawan::where('jenis_karyawan', 'admin')->get();
        $suppliers = Supplier::all();
        return view('admin.pembelian.tambahpembelian', compact('produksKurangDariMinimumStok', 'produks', 'karyawansAdmin', 'suppliers'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
        date_default_timezone_set("Asia/Jakarta");

        $nomorNota = $request->get("nomorNota");
        $supplierPembelian = $request->get("supplierPembelian");
        $karyawan = $request->get("namaKaryawan");
        $tanggalPembelian = $request->get("tanggalPembelian");
        $tanggalPembayaran = $request->get("tanggalPembayaran");
        $arrayIdProduk = $request->get("arrayproduk");
        $arrayStokproduk = $request->get("arraystokproduk");
        $arrayHargaProduk = $request->get("arrayhargaproduk");

        $totalHarga = 0;
        for ($i = 0; $i < count($arrayIdProduk); $i++) {
            $totalHarga += $arrayStokproduk[$i] * $arrayHargaProduk[$i];
        }

        $newPembelian = new Pembelian();
        $newPembelian->total = $totalHarga;
        $newPembelian->tanggal_beli = $tanggalPembelian;
        if ($tanggalPembayaran != null) {
            $newPembelian->tanggal_bayar = $tanggalPembayaran;
        }
        $newPembelian->nomor_nota = $nomorNota;
        $newPembelian->supplier_id = $supplierPembelian;
        $newPembelian->karyawan_id = $karyawan;
        $newPembelian->created_at = date("Y-m-d H:i:s");
        $newPembelian->updated_at = date("Y-m-d H:i:s");
        $newPembelian->save();

        for ($i = 0; $i < count($arrayIdProduk); $i++) {
            $idProduk = $arrayIdProduk[$i];
            $stokBeli = $arrayStokproduk[$i];
            $hargaBeli = $arrayHargaProduk[$i];

            $newPembelian->produks()->attach($idProduk, ['kuantitas' => $stokBeli, 'harga' => $hargaBeli]);

            $produk = Produk::find($idProduk);
            if ($produk->harga_beli != $hargaBeli) {
                $hargaBaru = ceil((($produk->stok * $produk->harga_beli) + ($stokBeli * $hargaBeli)) / ($produk->stok + $stokBeli));
                $produk->harga_beli = $hargaBaru;
                $produk->stok = $produk->stok + $stokBeli;
            } else {
                $produk->stok = $produk->stok + $stokBeli;
            }
            $produk->updated_at = date('Y-m-d H:i:s');
            $produk->save();
        }
        return redirect()->route('pembelians.index')->with('status', 'Berhasil menambahkan data pembelian baru dari supplier ' . $newPembelian->supplier->nama . ' dengan nomor nota ' . $nomorNota . '!');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Pembelian  $pembelian
     * @return \Illuminate\Http\Response
     */
    public function show(Pembelian $pembelian)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Pembelian  $pembelian
     * @return \Illuminate\Http\Response
     */
    public function edit(Pembelian $pembelian)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Pembelian  $pembelian
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Pembelian $pembelian)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Pembelian  $pembelian
     * @return \Illuminate\Http\Response
     */
    public function destroy(Pembelian $pembelian)
    {
        //
    }
}
