<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use App\Models\Produk;
use Illuminate\Http\Request;

class PenjualanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Penjualan  $penjualan
     * @return \Illuminate\Http\Response
     */
    public function show(Penjualan $penjualan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Penjualan  $penjualan
     * @return \Illuminate\Http\Response
     */
    public function edit(Penjualan $penjualan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Penjualan  $penjualan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Penjualan $penjualan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Penjualan  $penjualan
     * @return \Illuminate\Http\Response
     */
    public function destroy(Penjualan $penjualan)
    {
        //
    }

    public function errorPageNullPenjualan()
    {
        return view('admin.errorpagenullpenjualan');
    }

    public function konfirmasiPenambahanProduk(Request $request)
    {
        $arrayIdProduk = $request->get('arrayproduk');
        $arrayStokProduk = $request->get('arraystokproduk');
        $idPenjualan = $request->get('idPenjualan');
        $penjualan = Penjualan::find($idPenjualan);


        //Mengecek penjualan tersebut punya penjualan produk atau tidak
        if (count($penjualan->produks) > 0) {
            // Jika ya maka dicek lagi, apakah array id produk dan stok produk dari form berisi null atau tidak karena kalau null artinya dia ingin menghapus semua detail penjualan produk(sebelum penjualan dikonfirmasi selesai)
            if ($arrayIdProduk == null && $arrayStokProduk == null) {
                //Menghapus/detach semua produk dari detail penjualan produk
                foreach ($penjualan->produks as $p) {
                    $produkTerpilih = Produk::find($p->id);
                    $produkTerpilih->stok = $produkTerpilih->stok + $p->pivot->kuantitas;
                    $produkTerpilih->save();
                    $penjualan->produks()->detach($p);
                }

                if ($penjualan->reservasi != null) {
                    return redirect()->route('reservasi.admin.detailreservasi', $penjualan->reservasi->id)->with('status', 'Berhasil menambah produk yang ingin dibeli!');
                } else {
                    //
                }
            } else {
                //mengecek ketersediaan stok prouk jika ada produk yang sama seperti sebelumnya namun diubah kuantitasnya
                $arrayIdProdukDalamDetailPenjualan = [];
                foreach ($penjualan->produks as $p) {
                    array_push($arrayIdProdukDalamDetailPenjualan, $p->id);
                }
                // untuk cek produk yang ada dalam detail penjualan
                for ($i = 0; $i < count($arrayIdProduk); $i++) {
                    foreach ($penjualan->produks as $produkPenjualan) {
                        if ($arrayIdProduk[$i] == $produkPenjualan->id) {
                            $nilaiSementara = $produkPenjualan->stok - $produkPenjualan->minimum_stok + $produkPenjualan->pivot->kuantitas;
                            if ($nilaiSementara < $arrayStokProduk[$i]) {
                                return redirect()->back()->withErrors('Terdapat perubahan stok produk! Silahkan periksa kembali stok produk yang tersedia!');
                            } else {
                                break;
                            }
                        } else {
                            continue;
                        }
                    }

                }

                //untuk cek produk yang ada diluar detail penjualan
                for ($i = 0; $i < count($arrayIdProduk); $i++) {
                    if (!in_array($arrayIdProduk[$i], $arrayIdProdukDalamDetailPenjualan)) {
                        $produk = Produk::find($arrayIdProduk[$i]);
                        if ($produk->stok - $produk->minimum_stok < $arrayStokProduk[$i]) {
                            return redirect()->back()->withErrors('Terdapat perubahan stok produk! Silahkan periksa kembali stok produk yang tersedia!');
                        } else {
                            continue;
                        }
                    } else {
                        continue;
                    }
                }

                foreach ($penjualan->produks as $p) {
                    $produkTerpilih = Produk::find($p->id);
                    $produkTerpilih->stok = $produkTerpilih->stok + $p->pivot->kuantitas;
                    $produkTerpilih->save();
                    $penjualan->produks()->detach($p);
                }

                for ($i = 0; $i < count($arrayIdProduk); $i++) {
                    $produkTerpilih = Produk::find($arrayIdProduk[$i]);
                    $penjualan->produks()->attach($arrayIdProduk[$i], ['kuantitas' => $arrayStokProduk[$i], 'harga' => $produkTerpilih->harga_jual]);
                    $produkTerpilih->stok = $produkTerpilih->stok - $arrayStokProduk[$i];
                    $produkTerpilih->save();
                }

                if ($penjualan->reservasi != null) {
                    return redirect()->route('reservasi.admin.detailreservasi', $penjualan->reservasi->id)->with('status', 'Berhasil menambah produk yang ingin dibeli!');
                } else {
                    //
                }
            }
        } else {

            if ($arrayIdProduk == null && $arrayStokProduk == null) {
                if ($penjualan->reservasi != null) {
                    return redirect()->route('reservasi.admin.detailreservasi', $penjualan->reservasi->id)->with('status', 'Berhasil menambah produk yang ingin dibeli!');
                } else {
                    //
                }
            } else {
                for ($i = 0; $i < count($arrayIdProduk); $i++) {
                    $produk = Produk::find($arrayIdProduk[$i]);
                    if ($produk->stok - $produk->minimum_stok < $arrayStokProduk[$i]) {
                        return redirect()->back()->withErrors('Terdapat perubahan stok produk! Silahkan periksa kembali stok produk yang tersedia!');
                    } else {
                        continue;
                    }
                }

                for ($i = 0; $i < count($arrayIdProduk); $i++) {
                    $produkTerpilih = Produk::find($arrayIdProduk[$i]);
                    $penjualan->produks()->attach($arrayIdProduk[$i], ['kuantitas' => $arrayStokProduk[$i], 'harga' => $produkTerpilih->harga_jual]);
                    $produkTerpilih->stok = $produkTerpilih->stok - $arrayStokProduk[$i];
                    $produkTerpilih->save();
                }

                if ($penjualan->reservasi != null) {
                    return redirect()->route('reservasi.admin.detailreservasi', $penjualan->reservasi->id)->with('status', 'Berhasil menambah produk yang ingin dibeli!');
                } else {
                    //
                }
            }
        }
    }

    
}