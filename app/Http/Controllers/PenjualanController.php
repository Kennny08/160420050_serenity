<?php

namespace App\Http\Controllers;

use App\Models\Diskon;
use App\Models\Karyawan;
use App\Models\Paket;
use App\Models\Pelanggan;
use App\Models\Penjualan;
use App\Models\PenjualanPerawatan;
use App\Models\Perawatan;
use App\Models\PresensiKehadiran;
use App\Models\Produk;
use App\Models\Reservasi;
use App\Models\SlotJam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PenjualanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        date_default_timezone_set('Asia/Jakarta');
        $tanggal = date('Y-m-d');
        $hariIndonesia = array('Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu');
        $nomorHariDalamMingguan = date("w");
        $tanggalHariIni = $hariIndonesia[$nomorHariDalamMingguan] . ", " . date('d-m-Y');
        $semuaPenjualanHariIni = Penjualan::whereRaw("DATE(tanggal_penjualan) = '" . $tanggal . "'")->orderByRaw("DATE(tanggal_penjualan) asc")->get();
        $penjualans = [];
        foreach ($semuaPenjualanHariIni as $penjualan) {
            if ($penjualan->reservasi == null) {
                array_push($penjualans, $penjualan);
            }
        }
        return view('admin.penjualan.index', compact('penjualans', 'tanggalHariIni'));
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
        date_default_timezone_set('Asia/Jakarta');
        $arrayIdProduk = $request->get('arrayproduk');
        $arrayStokProduk = $request->get('arraystokproduk');
        $idPenjualan = $request->get('idPenjualan');
        $penjualan = Penjualan::find($idPenjualan);


        //Mengecek penjualan tersebut punya penjualan produk atau tidak
        if (count($penjualan->produks) > 0) {
            // Jika ya maka dicek lagi, apakah array id produk dan stok produk dari form berisi null atau tidak karena kalau null artinya dia ingin menghapus semua detail penjualan produk(sebelum penjualan dikonfirmasi selesai)
            if ($arrayIdProduk == null && $arrayStokProduk == null) {
                //Menghapus/detach semua produk dari detail penjualan produk

                $totalPenguranganPembayaran = 0;
                foreach ($penjualan->produks as $p) {
                    $totalPenguranganPembayaran += $p->pivot->kuantitas * $p->pivot->harga;
                    $produkTerpilih = Produk::find($p->id);
                    $produkTerpilih->stok = $produkTerpilih->stok + $p->pivot->kuantitas;
                    $produkTerpilih->save();
                    $penjualan->produks()->detach($p);
                }

                $penjualanBaru = Penjualan::find($idPenjualan);

                //Lepas Diskon karena ada perubahan produk
                $totalPembayaranSekarang = $penjualanBaru->total_pembayaran;
                if ($penjualanBaru->diskon_id != null) {
                    $diskonTerpakai = $penjualanBaru->diskon;
                    $hargaSebelumDiskon = $totalPembayaranSekarang / ((100 - $diskonTerpakai->jumlah_potongan) / 100);
                    $selisihPotongan = $hargaSebelumDiskon - $totalPembayaranSekarang;
                    if ($selisihPotongan > $diskonTerpakai->maksimum_potongan) {
                        $selisihPotongan = $diskonTerpakai->maksimum_potongan;
                    }
                    $penjualanBaru->total_pembayaran = $penjualanBaru->total_pembayaran + $selisihPotongan;
                    $penjualanBaru->diskon_id = null;
                    $penjualanBaru->updated_at = date("Y-m-d H:i:s");
                    $penjualanBaru->save();
                }

                $penjualanBaru->total_pembayaran = $penjualanBaru->total_pembayaran - $totalPenguranganPembayaran;
                $penjualanBaru->updated_at = date("Y-m-d H:i:s");
                $penjualanBaru->save();

                if ($penjualan->reservasi != null) {
                    return redirect()->route('reservasi.admin.detailreservasi', $penjualan->reservasi->id)->with('status', ["message" => ['Berhasil menambah atau mengubah produk yang ingin dibeli!', 'Silahkan mengatur ulang diskon jika tersedia!']]);
                } else {
                    return redirect()->route('penjualans.admin.detailpenjualan', $penjualan->id)->with('status', ["message" => ['Berhasil menambah atau mengubah produk yang ingin dibeli!', 'Silahkan mengatur ulang diskon jika tersedia!']]);
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
                            $nilaiSementara = $produkPenjualan->stok + $produkPenjualan->pivot->kuantitas;
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
                        if ($produk->stok < $arrayStokProduk[$i]) {
                            return redirect()->back()->withErrors('Terdapat perubahan stok produk! Silahkan periksa kembali stok produk yang tersedia!');
                        } else {
                            continue;
                        }
                    } else {
                        continue;
                    }
                }

                $totalPenguranganPembayaran = 0;
                $arrProdukSebelumPengurangan = $penjualan->produks;

                $idPaketProduk = [];

                if (count($penjualan->pakets) > 0) {
                    foreach ($penjualan->pakets as $paket) {
                        foreach ($paket->produks as $produk) {
                            array_push($idPaketProduk, $produk->id);
                        }
                    }
                }

                //Menghapus detail penjualan produk dan mengembalikan stok


                foreach ($penjualan->produks as $p) {
                    if (!in_array($p->id, $idPaketProduk)) {
                        $totalPenguranganPembayaran += $p->pivot->kuantitas * $p->pivot->harga;
                        $produkTerpilih = Produk::find($p->id);
                        $produkTerpilih->stok = $produkTerpilih->stok + $p->pivot->kuantitas;
                        $produkTerpilih->save();
                        $penjualan->produks()->detach($p);
                    } else {
                        $totalKeseluruhan = $p->pivot->kuantitas;
                        $totalDariPaket = 0;
                        foreach ($penjualan->pakets as $paket) {
                            if ($paket->produks->firstWhere("id", $p->id) != null) {
                                $totalDariPaket += $paket->produks->firstWhere("id", $p->id)->pivot->jumlah;
                            }
                        }
                        if ($totalKeseluruhan > $totalDariPaket) {
                            $totalPenguranganPembayaran += ($totalKeseluruhan - $totalDariPaket) * $p->pivot->harga;
                            $produkTerpilih = Produk::find($p->id);
                            $produkTerpilih->stok = $produkTerpilih->stok + ($totalKeseluruhan - $totalDariPaket);
                            $produkTerpilih->save();
                        }


                        $penjualan->produks()->updateExistingPivot($p->id, ["kuantitas" => $totalDariPaket]);

                        // if ($totalKeseluruhan > $totalDariPaket) {
                        //     // $totalProdukDiluarPaket = $totalKeseluruhan - $totalDariPaket;
                        //     // $penjualan->produks()->updateExistingPivot($p->id, ["kuantitas" => $totalDariPaket]);
                        // }
                    }
                }




                //Menambah detail penjualan produk dan mengurangi stok
                for ($i = 0; $i < count($arrayIdProduk); $i++) {

                    if (!in_array($arrayIdProduk[$i], $idPaketProduk)) {
                        $produkTerpilih = Produk::find($arrayIdProduk[$i]);
                        $penjualan->produks()->attach($arrayIdProduk[$i], ['kuantitas' => $arrayStokProduk[$i], 'harga' => $produkTerpilih->harga_jual]);
                        $produkTerpilih->stok = $produkTerpilih->stok - $arrayStokProduk[$i];
                        $produkTerpilih->save();
                    } else {
                        $produkTerpilih = Produk::find($arrayIdProduk[$i]);
                        $penjualan = Penjualan::find($idPenjualan);
                        $totalKeseluruhan = $arrayStokProduk[$i];
                        $totalDariPaket = 0;
                        foreach ($penjualan->pakets as $paket) {
                            if ($paket->produks->firstWhere("id", $arrayIdProduk[$i]) != null) {
                                $totalDariPaket += $paket->produks->firstWhere("id", $arrayIdProduk[$i])->pivot->jumlah;
                            }
                        }

                        $penjualan->produks()->updateExistingPivot($produkTerpilih->id, ["kuantitas" => $totalKeseluruhan, "harga" => $produkTerpilih->harga_jual]);

                        if ($totalKeseluruhan > $totalDariPaket) {
                            // $totalPenguranganPembayaran += ($totalKeseluruhan - $totalDariPaket) * $produkTerpilih->harga_jual;
                            // $produkTerpilih = Produk::find($p->id);
                            $produkTerpilih->stok = $produkTerpilih->stok - ($totalKeseluruhan - $totalDariPaket);
                            $produkTerpilih->save();
                        }
                    }
                }

                $penjualanBaru = Penjualan::find($idPenjualan);
                $totalSubtotalProduk = 0;

                $idPaketProduk = [];

                if (count($penjualanBaru->pakets) > 0) {
                    foreach ($penjualanBaru->pakets as $paket) {
                        foreach ($paket->produks as $produk) {
                            array_push($idPaketProduk, $produk->id);
                        }
                    }
                }


                foreach ($penjualanBaru->produks as $p) {
                    if (!in_array($p->id, $idPaketProduk)) {
                        $totalSubtotalProduk += $p->pivot->kuantitas * $p->pivot->harga;
                    } else {
                        $totalKeseluruhan = $p->pivot->kuantitas;
                        $totalDariPaket = 0;
                        foreach ($penjualanBaru->pakets as $paket) {
                            if ($paket->produks->firstWhere("id", $p->id) != null) {
                                $totalDariPaket += $paket->produks->firstWhere("id", $p->id)->pivot->jumlah;
                            }
                        }

                        if ($totalKeseluruhan > $totalDariPaket) {
                            $sisaJumlahDiluarPaket = $totalKeseluruhan - $totalDariPaket;
                            $totalSubtotalProduk += $sisaJumlahDiluarPaket * $p->pivot->harga;
                        }
                    }
                }

                // foreach ($penjualanBaru->produks as $produk) {
                //     $totalSubtotalProduk += $produk->pivot->kuantitas * $produk->pivot->harga;
                // }

                //Lepas Diskon karena ada perubahan produk
                $totalPembayaranSekarang = $penjualanBaru->total_pembayaran;
                if ($penjualanBaru->diskon_id != null) {
                    $diskonTerpakai = $penjualanBaru->diskon;
                    $hargaSebelumDiskon = $totalPembayaranSekarang / ((100 - $diskonTerpakai->jumlah_potongan) / 100);
                    $selisihPotongan = $hargaSebelumDiskon - $totalPembayaranSekarang;
                    if ($selisihPotongan > $diskonTerpakai->maksimum_potongan) {
                        $selisihPotongan = $diskonTerpakai->maksimum_potongan;
                    }
                    $penjualanBaru->total_pembayaran = $penjualanBaru->total_pembayaran + $selisihPotongan;
                    $penjualanBaru->diskon_id = null;
                    $penjualanBaru->updated_at = date("Y-m-d H:i:s");
                    $penjualanBaru->save();
                }

                $penjualanBaru->total_pembayaran = $penjualanBaru->total_pembayaran - $totalPenguranganPembayaran + $totalSubtotalProduk;
                $penjualanBaru->updated_at = date("Y-m-d H:i:s");
                $penjualanBaru->save();

                if ($penjualan->reservasi != null) {
                    return redirect()->route('reservasi.admin.detailreservasi', $penjualan->reservasi->id)->with('status', ["message" => ['Berhasil menambah atau mengubah produk yang ingin dibeli!', 'Silahkan mengatur ulang diskon jika tersedia!']]);
                } else {
                    return redirect()->route('penjualans.admin.detailpenjualan', $penjualan->id)->with('status', ["message" => ['Berhasil menambah atau mengubah produk yang ingin dibeli!', 'Silahkan mengatur ulang diskon jika tersedia!']]);
                }
            }
        } else {

            if ($arrayIdProduk == null && $arrayStokProduk == null) {
                if ($penjualan->reservasi != null) {
                    return redirect()->route('reservasi.admin.detailreservasi', $penjualan->reservasi->id)->with('status', ["message" => ['Berhasil menambah atau mengubah produk yang ingin dibeli!', 'Silahkan mengecek diskon jika tersedia!']]);
                } else {
                    return redirect()->route('penjualans.admin.detailpenjualan', $penjualan->id)->with('status', ["message" => ['Berhasil menambah atau mengubah produk yang ingin dibeli!', 'Silahkan mengecek diskon jika tersedia!']]);
                }
            } else {

                for ($i = 0; $i < count($arrayIdProduk); $i++) {
                    $produk = Produk::find($arrayIdProduk[$i]);
                    if ($produk->stok < $arrayStokProduk[$i]) {
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



                $penjualanBaru = Penjualan::find($idPenjualan);
                $totalSubtotalProduk = 0;
                foreach ($penjualanBaru->produks as $produk) {
                    $totalSubtotalProduk += $produk->pivot->kuantitas * $produk->pivot->harga;
                }

                //Lepas Diskon karena ada perubahan produk
                $totalPembayaranSekarang = $penjualanBaru->total_pembayaran;
                if ($penjualanBaru->diskon_id != null) {
                    $diskonTerpakai = $penjualanBaru->diskon;
                    $hargaSebelumDiskon = $totalPembayaranSekarang / ((100 - $diskonTerpakai->jumlah_potongan) / 100);
                    $selisihPotongan = $hargaSebelumDiskon - $totalPembayaranSekarang;
                    if ($selisihPotongan > $diskonTerpakai->maksimum_potongan) {
                        $selisihPotongan = $diskonTerpakai->maksimum_potongan;
                    }
                    $penjualanBaru->total_pembayaran = $penjualanBaru->total_pembayaran + $selisihPotongan;
                    $penjualanBaru->diskon_id = null;
                    $penjualanBaru->updated_at = date("Y-m-d H:i:s");
                    $penjualanBaru->save();
                }

                $totalPembayaranSaatIni = $penjualan->total_pembayaran;
                $totalPembayaranBaru = $totalPembayaranSaatIni + $totalSubtotalProduk;
                $penjualanBaru->total_pembayaran = $totalPembayaranBaru;
                $penjualanBaru->updated_at = date("Y-m-d H:i:s");
                $penjualanBaru->save();


                if ($penjualan->reservasi != null) {
                    return redirect()->route('reservasi.admin.detailreservasi', $penjualan->reservasi->id)->with('status', ["message" => ['Berhasil menambah atau mengubah produk yang ingin dibeli!', 'Silahkan mengatur ulang diskon jika tersedia!']]);
                } else {
                    return redirect()->route('penjualans.admin.detailpenjualan', $penjualan->id)->with('status', ["message" => ['Berhasil menambah atau mengubah produk yang ingin dibeli!', 'Silahkan mengatur ulang diskon jika tersedia!']]);
                }
            }
        }
    }
    public function konfirmasiPenambahanProdukPelanggan(Request $request)
    {
        date_default_timezone_set('Asia/Jakarta');
        $arrayIdProduk = $request->get('arrayproduk');
        $arrayStokProduk = $request->get('arraystokproduk');
        $idPenjualan = $request->get('idPenjualan');
        $penjualan = Penjualan::find($idPenjualan);


        //Mengecek penjualan tersebut punya penjualan produk atau tidak
        if (count($penjualan->produks) > 0) {
            // Jika ya maka dicek lagi, apakah array id produk dan stok produk dari form berisi null atau tidak karena kalau null artinya dia ingin menghapus semua detail penjualan produk(sebelum penjualan dikonfirmasi selesai)
            if ($arrayIdProduk == null && $arrayStokProduk == null) {
                //Menghapus/detach semua produk dari detail penjualan produk

                $totalPenguranganPembayaran = 0;
                foreach ($penjualan->produks as $p) {
                    $totalPenguranganPembayaran += $p->pivot->kuantitas * $p->pivot->harga;
                    $produkTerpilih = Produk::find($p->id);
                    $produkTerpilih->stok = $produkTerpilih->stok + $p->pivot->kuantitas;
                    $produkTerpilih->save();
                    $penjualan->produks()->detach($p);
                }

                $penjualanBaru = Penjualan::find($idPenjualan);

                //Lepas Diskon karena ada perubahan produk
                $totalPembayaranSekarang = $penjualanBaru->total_pembayaran;
                if ($penjualanBaru->diskon_id != null) {
                    $diskonTerpakai = $penjualanBaru->diskon;
                    $hargaSebelumDiskon = $totalPembayaranSekarang / ((100 - $diskonTerpakai->jumlah_potongan) / 100);
                    $selisihPotongan = $hargaSebelumDiskon - $totalPembayaranSekarang;
                    if ($selisihPotongan > $diskonTerpakai->maksimum_potongan) {
                        $selisihPotongan = $diskonTerpakai->maksimum_potongan;
                    }
                    $penjualanBaru->total_pembayaran = $penjualanBaru->total_pembayaran + $selisihPotongan;
                    $penjualanBaru->diskon_id = null;
                    $penjualanBaru->updated_at = date("Y-m-d H:i:s");
                    $penjualanBaru->save();
                }

                $penjualanBaru->total_pembayaran = $penjualanBaru->total_pembayaran - $totalPenguranganPembayaran;
                $penjualanBaru->updated_at = date("Y-m-d H:i:s");
                $penjualanBaru->save();

                if ($penjualan->reservasi != null) {
                    return redirect()->route('reservasis.pelanggan.detailreservasi', $penjualan->reservasi->id)->with('status', ["message" => ['Berhasil menambah atau mengubah produk yang ingin dibeli!', 'Silahkan mengatur ulang diskon jika tersedia!']]);
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
                            $nilaiSementara = $produkPenjualan->stok + $produkPenjualan->pivot->kuantitas;
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
                        if ($produk->stok < $arrayStokProduk[$i]) {
                            return redirect()->back()->withErrors('Terdapat perubahan stok produk! Silahkan periksa kembali stok produk yang tersedia!');
                        } else {
                            continue;
                        }
                    } else {
                        continue;
                    }
                }

                $totalPenguranganPembayaran = 0;
                $arrProdukSebelumPengurangan = $penjualan->produks;

                $idPaketProduk = [];

                if (count($penjualan->pakets) > 0) {
                    foreach ($penjualan->pakets as $paket) {
                        foreach ($paket->produks as $produk) {
                            array_push($idPaketProduk, $produk->id);
                        }
                    }
                }

                //Menghapus detail penjualan produk dan mengembalikan stok


                foreach ($penjualan->produks as $p) {
                    if (!in_array($p->id, $idPaketProduk)) {
                        $totalPenguranganPembayaran += $p->pivot->kuantitas * $p->pivot->harga;
                        $produkTerpilih = Produk::find($p->id);
                        $produkTerpilih->stok = $produkTerpilih->stok + $p->pivot->kuantitas;
                        $produkTerpilih->save();
                        $penjualan->produks()->detach($p);
                    } else {
                        $totalKeseluruhan = $p->pivot->kuantitas;
                        $totalDariPaket = 0;
                        foreach ($penjualan->pakets as $paket) {
                            if ($paket->produks->firstWhere("id", $p->id) != null) {
                                $totalDariPaket += $paket->produks->firstWhere("id", $p->id)->pivot->jumlah;
                            }
                        }
                        if ($totalKeseluruhan > $totalDariPaket) {
                            $totalPenguranganPembayaran += ($totalKeseluruhan - $totalDariPaket) * $p->pivot->harga;
                            $produkTerpilih = Produk::find($p->id);
                            $produkTerpilih->stok = $produkTerpilih->stok + ($totalKeseluruhan - $totalDariPaket);
                            $produkTerpilih->save();
                        }


                        $penjualan->produks()->updateExistingPivot($p->id, ["kuantitas" => $totalDariPaket]);

                        // if ($totalKeseluruhan > $totalDariPaket) {
                        //     // $totalProdukDiluarPaket = $totalKeseluruhan - $totalDariPaket;
                        //     // $penjualan->produks()->updateExistingPivot($p->id, ["kuantitas" => $totalDariPaket]);
                        // }
                    }
                }




                //Menambah detail penjualan produk dan mengurangi stok
                for ($i = 0; $i < count($arrayIdProduk); $i++) {

                    if (!in_array($arrayIdProduk[$i], $idPaketProduk)) {
                        $produkTerpilih = Produk::find($arrayIdProduk[$i]);
                        $penjualan->produks()->attach($arrayIdProduk[$i], ['kuantitas' => $arrayStokProduk[$i], 'harga' => $produkTerpilih->harga_jual]);
                        $produkTerpilih->stok = $produkTerpilih->stok - $arrayStokProduk[$i];
                        $produkTerpilih->save();
                    } else {
                        $produkTerpilih = Produk::find($arrayIdProduk[$i]);
                        $penjualan = Penjualan::find($idPenjualan);
                        $totalKeseluruhan = $arrayStokProduk[$i];
                        $totalDariPaket = 0;
                        foreach ($penjualan->pakets as $paket) {
                            if ($paket->produks->firstWhere("id", $arrayIdProduk[$i]) != null) {
                                $totalDariPaket += $paket->produks->firstWhere("id", $arrayIdProduk[$i])->pivot->jumlah;
                            }
                        }

                        $penjualan->produks()->updateExistingPivot($produkTerpilih->id, ["kuantitas" => $totalKeseluruhan, "harga" => $produkTerpilih->harga_jual]);

                        if ($totalKeseluruhan > $totalDariPaket) {
                            // $totalPenguranganPembayaran += ($totalKeseluruhan - $totalDariPaket) * $produkTerpilih->harga_jual;
                            // $produkTerpilih = Produk::find($p->id);
                            $produkTerpilih->stok = $produkTerpilih->stok - ($totalKeseluruhan - $totalDariPaket);
                            $produkTerpilih->save();
                        }
                    }
                }

                $penjualanBaru = Penjualan::find($idPenjualan);
                $totalSubtotalProduk = 0;

                $idPaketProduk = [];

                if (count($penjualanBaru->pakets) > 0) {
                    foreach ($penjualanBaru->pakets as $paket) {
                        foreach ($paket->produks as $produk) {
                            array_push($idPaketProduk, $produk->id);
                        }
                    }
                }


                foreach ($penjualanBaru->produks as $p) {
                    if (!in_array($p->id, $idPaketProduk)) {
                        $totalSubtotalProduk += $p->pivot->kuantitas * $p->pivot->harga;
                    } else {
                        $totalKeseluruhan = $p->pivot->kuantitas;
                        $totalDariPaket = 0;
                        foreach ($penjualanBaru->pakets as $paket) {
                            if ($paket->produks->firstWhere("id", $p->id) != null) {
                                $totalDariPaket += $paket->produks->firstWhere("id", $p->id)->pivot->jumlah;
                            }
                        }

                        if ($totalKeseluruhan > $totalDariPaket) {
                            $sisaJumlahDiluarPaket = $totalKeseluruhan - $totalDariPaket;
                            $totalSubtotalProduk += $sisaJumlahDiluarPaket * $p->pivot->harga;
                        }
                    }
                }

                // foreach ($penjualanBaru->produks as $produk) {
                //     $totalSubtotalProduk += $produk->pivot->kuantitas * $produk->pivot->harga;
                // }

                //Lepas Diskon karena ada perubahan produk
                $totalPembayaranSekarang = $penjualanBaru->total_pembayaran;
                if ($penjualanBaru->diskon_id != null) {
                    $diskonTerpakai = $penjualanBaru->diskon;
                    $hargaSebelumDiskon = $totalPembayaranSekarang / ((100 - $diskonTerpakai->jumlah_potongan) / 100);
                    $selisihPotongan = $hargaSebelumDiskon - $totalPembayaranSekarang;
                    if ($selisihPotongan > $diskonTerpakai->maksimum_potongan) {
                        $selisihPotongan = $diskonTerpakai->maksimum_potongan;
                    }
                    $penjualanBaru->total_pembayaran = $penjualanBaru->total_pembayaran + $selisihPotongan;
                    $penjualanBaru->diskon_id = null;
                    $penjualanBaru->updated_at = date("Y-m-d H:i:s");
                    $penjualanBaru->save();
                }

                $penjualanBaru->total_pembayaran = $penjualanBaru->total_pembayaran - $totalPenguranganPembayaran + $totalSubtotalProduk;
                $penjualanBaru->updated_at = date("Y-m-d H:i:s");
                $penjualanBaru->save();

                if ($penjualan->reservasi != null) {
                    return redirect()->route('reservasis.pelanggan.detailreservasi', $penjualan->reservasi->id)->with('status', ["message" => ['Berhasil menambah atau mengubah produk yang ingin dibeli!', 'Silahkan mengatur ulang diskon jika tersedia!']]);
                }
            }
        } else {

            if ($arrayIdProduk == null && $arrayStokProduk == null) {
                if ($penjualan->reservasi != null) {
                    return redirect()->route('reservasis.pelanggan.detailreservasi', $penjualan->reservasi->id)->with('status', ["message" => ['Berhasil menambah atau mengubah produk yang ingin dibeli!', 'Silahkan mengecek diskon jika tersedia!']]);
                }
            } else {

                for ($i = 0; $i < count($arrayIdProduk); $i++) {
                    $produk = Produk::find($arrayIdProduk[$i]);
                    if ($produk->stok < $arrayStokProduk[$i]) {
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



                $penjualanBaru = Penjualan::find($idPenjualan);
                $totalSubtotalProduk = 0;
                foreach ($penjualanBaru->produks as $produk) {
                    $totalSubtotalProduk += $produk->pivot->kuantitas * $produk->pivot->harga;
                }

                //Lepas Diskon karena ada perubahan produk
                $totalPembayaranSekarang = $penjualanBaru->total_pembayaran;
                if ($penjualanBaru->diskon_id != null) {
                    $diskonTerpakai = $penjualanBaru->diskon;
                    $hargaSebelumDiskon = $totalPembayaranSekarang / ((100 - $diskonTerpakai->jumlah_potongan) / 100);
                    $selisihPotongan = $hargaSebelumDiskon - $totalPembayaranSekarang;
                    if ($selisihPotongan > $diskonTerpakai->maksimum_potongan) {
                        $selisihPotongan = $diskonTerpakai->maksimum_potongan;
                    }
                    $penjualanBaru->total_pembayaran = $penjualanBaru->total_pembayaran + $selisihPotongan;
                    $penjualanBaru->diskon_id = null;
                    $penjualanBaru->updated_at = date("Y-m-d H:i:s");
                    $penjualanBaru->save();
                }

                $totalPembayaranSaatIni = $penjualan->total_pembayaran;
                $totalPembayaranBaru = $totalPembayaranSaatIni + $totalSubtotalProduk;
                $penjualanBaru->total_pembayaran = $totalPembayaranBaru;
                $penjualanBaru->updated_at = date("Y-m-d H:i:s");
                $penjualanBaru->save();


                if ($penjualan->reservasi != null) {
                    return redirect()->route('reservasis.pelanggan.detailreservasi', $penjualan->reservasi->id)->with('status', ["message" => ['Berhasil menambah atau mengubah produk yang ingin dibeli!', 'Silahkan mengatur ulang diskon jika tersedia!']]);
                }
            }
        }
    }

    public function penjualanAdminCreate()
    {
        date_default_timezone_set('Asia/Jakarta');
        $hariIndonesia = array('Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu');
        $perawatans = Perawatan::select('id', 'kode_perawatan', 'nama', 'harga', 'deskripsi', 'durasi')->where('status', 'aktif')->orderBy('nama')->get();
        $pakets = Paket::where('status', 'aktif')->orderBy("nama")->get();

        $tanggal = date('d-m-Y');
        $tanggalPertamaDalamMinggu = date('Y-m-d');

        $tanggalTerakhirDalamMinggu = date('Y-m-d', strtotime('this saturday'));

        $nomorHariDalamMingguan = date("w");
        $tanggalHariIni = $hariIndonesia[$nomorHariDalamMingguan] . ", " . date('d-m-Y');

        $slotJams = SlotJam::where('hari', $hariIndonesia[$nomorHariDalamMingguan])->where('jam', ">=", date("H.i"))->get();
        $daftarPelanggans = Pelanggan::all();
        return view('admin.penjualan.tambahpenjualan', compact('perawatans', 'slotJams', 'tanggalHariIni', 'tanggalPertamaDalamMinggu', 'tanggalTerakhirDalamMinggu', 'daftarPelanggans', 'pakets'));
    }

    //PENGECEKAN UNTUK RESERVASI
    // 1. lewat jam tutup
    // 2. mengandung slot jam yang tutup
    // 3. tidak tersedia karyawan
    // 4. didahului pelanggan lain
    // 5. stok produk dalam paket tidak cukup
    public function penjualanAdminPilihKaryawan(Request $request)
    {
        date_default_timezone_set('Asia/Jakarta');
        $tanggalPenjualan = date("Y-m-d");
        $idSlotJam = $request->get("slotJam");
        $arrPerawatan = $request->get("arrayperawatanid");
        $idPelanggan = $request->get("idPelanggan");
        $arrKodeKeseluruhan = $request->get("arraykodekeseluruhan");
        $arrPaket = $request->get("arraypaketid");
        $keteranganPilihKaryawan = $request->get("keteranganPilihKaryawan");

        $pesanError = [];
        if ($idSlotJam == null || ($arrPerawatan == null && $arrPaket == null) || $idPelanggan == null) {
            if ($idSlotJam == null) {
                array_push($pesanError, "Slot Jam tidak boleh kosong!");
            }
            if ($arrPerawatan == null && $arrPaket == null) {
                array_push($pesanError, "Harap memilih setidaknya satu perawatan atau satu paket!");
            }
            if ($idPelanggan == null) {
                array_push($pesanError, "Nama Pelanggan tidak boleh kosong!");
            }
            return redirect()->back()->withErrors($pesanError);
        }

        //buat array kosongan untuk simpan perawatan slot jam perawatan komplemen dan nonkomplemen

        if ($arrPerawatan == null) {
            $arrPerawatan = [];
        }

        if ($arrPaket == null) {
            $arrPaket = [];
        }

        $perawatanSlotJamNonKomplemen = [];
        $perawatanSlotJamKomplemen = [];

        //select perawatan komplemen dan non yang id nya sesuai dengan id perawtan yang dipilih dari form buatreservasiadmin
        $perawatanNonKomplemen = [];
        $perawatanKomplemen = [];
        //$arrNonKomplemenSementara = Perawatan::select('id')->where('status_komplemen', 'tidak')->whereIn('id', $arrPerawatan)->get();
        //$arrKomplemenSementara = Perawatan::select('id')->where('status_komplemen', 'ya')->whereIn('id', $arrPerawatan)->get();
        foreach ($arrKodeKeseluruhan as $kode) {
            $kodePertama = substr($kode, 0, 1);
            if ($kodePertama == "s") {
                $perawatanSementara = Perawatan::where("kode_perawatan", $kode)->first();
                if ($perawatanSementara->status_komplemen == "tidak") {
                    array_push($perawatanNonKomplemen, $perawatanSementara);
                } else {
                    array_push($perawatanKomplemen, $perawatanSementara);
                }
            } else {
                $paketSementara = Paket::where("kode_paket", $kode)->first();
                foreach ($paketSementara->perawatans()->withPivot("urutan")->orderBy("urutan")->get() as $perawatanPaket) {
                    if ($perawatanPaket->status_komplemen == "tidak") {
                        array_push($perawatanNonKomplemen, $perawatanPaket);
                    } else {
                        array_push($perawatanKomplemen, $perawatanPaket);
                    }
                }
            }
        }

        //Pengecekan perawatan yang dipilih tidak boleh melebihi jam tutup
        $penghitungJam = SlotJam::find($idSlotJam);
        $jamPengubah = strtotime($penghitungJam->jam);
        $jamTutup = SlotJam::where('hari', $penghitungJam->hari)->where('status', 'aktif')->max('jam');
        $jamTutup = strtotime("+30 minutes", strtotime($jamTutup));

        foreach ($perawatanNonKomplemen as $perawatan) {
            $jumlahSlotTerpakai = ceil($perawatan->durasi / 30);
            $jamPengubah = strtotime("+" . ($jumlahSlotTerpakai * 30) . " minutes", $jamPengubah);
        }

        $arrIdPerawatanKomplemen = [];
        foreach ($perawatanKomplemen as $pk) {
            array_push($arrIdPerawatanKomplemen, $pk->id);
        }

        $maxDurasiPerawatanKomplemen = Perawatan::whereIn('id', $arrIdPerawatanKomplemen)->max('durasi');
        $jumlahSlotTerpakaiMax = ceil($maxDurasiPerawatanKomplemen / 30);
        $jamPengubah = strtotime("+" . ($jumlahSlotTerpakaiMax * 30) . " minutes", $jamPengubah);
        // $jamJamSekarang = date('H.i', $jamPengubah);
        // $jamJamTutup = date('H.i', $jamTutup);

        // dd($jamJamSekarang, $jamJamTutup);

        if ($jamPengubah > $jamTutup) {
            $arrPerawatanSend = [];
            if ($arrPerawatan != null) {
                foreach ($arrPerawatan as $ap) {
                    $p = Perawatan::select('id', 'kode_perawatan', 'nama', 'harga', 'deskripsi', 'durasi')->where('status', 'aktif')->where('id', $ap)->first();
                    array_push($arrPerawatanSend, $p);
                }
            }

            $arrPaketSend = [];
            if ($arrPaket != null) {
                foreach ($arrPaket as $idPaket) {
                    $paketvar = Paket::where('status', 'aktif')->where('id', $idPaket)->first();
                    array_push($arrPaketSend, $paketvar);
                }
            }

            $daftarPelanggans = Pelanggan::all();
            $varSlotJam = SlotJam::find($idSlotJam);
            if ($tanggalPenjualan == date("Y-m-d")) {
                $daftarSlotJam = SlotJam::where('hari', $varSlotJam->hari)->where('jam', ">=", date("H.i"))->orderBy('id')->get();
            } else {
                $daftarSlotJam = SlotJam::where('hari', $varSlotJam->hari)->orderBy('id')->get();
            }

            return redirect()->route('penjualans.admin.create')->with([
                'idPelanggan' => $idPelanggan,
                'daftarPelanggans' => $daftarPelanggans,
                'idSlotJam' => $idSlotJam,
                'daftarSlotJam' => $daftarSlotJam,
                'tanggalPenjualan' => $tanggalPenjualan,
                'arrPerawatan' => $arrPerawatan,
                'arrPerawatanObject' => $arrPerawatanSend,
                'arrPaket' => $arrPaket,
                'arrPaketObject' => $arrPaketSend,
                'arrKodeKeseluruhan' => $arrKodeKeseluruhan,

            ])->withErrors('Perawatan atau Paket yang dipilih telah melewati batas jam buka pada hari tersebut!');
        } else {
            if ($keteranganPilihKaryawan == "ya") {
                //cari slot jam mulai dari form buatreservasiadmin
                $slotJamBerubah = SlotJam::find($idSlotJam);

                //BAGIAN PERAWATAN NON KOMPLEMEN-------------------------------------------------------------------------
                //foreach $perawatanNonKomplemen untuk mencari karyawan tersedia untuk setiap perawatan
                if (count($perawatanNonKomplemen)) {
                    foreach ($perawatanNonKomplemen as $perawatan) {
                        $perawatanPerSlot = [];
                        $perawatanPerSlot["perawatan"] = $perawatan;
                        $perawatanPerSlot["jammulai"] = $slotJamBerubah->jam;

                        $jumlahSlotTerpakai = ceil($perawatan->durasi / 30);
                        $intJamTerakhir = strtotime("+" . ($jumlahSlotTerpakai * 30) - 30 . " minutes", strtotime($slotJamBerubah->jam));
                        $jamTerakhir = date('H.i', $intJamTerakhir);
                        $arrIdSlotJamTerpakai = SlotJam::select('id')->where('jam', '>=', $slotJamBerubah->jam)->where('jam', '<=', $jamTerakhir)->where('hari', $slotJamBerubah->hari)->get();
                        $arrObjectSlotJamTerpakai = SlotJam::where('jam', '>=', $slotJamBerubah->jam)->where('jam', '<=', $jamTerakhir)->where('hari', $slotJamBerubah->hari)->get();
                        // dd($jumlahSlotTerpakai, count($arrIdSlotJamTerpakai), $arrIdSlotJamTerpakai);

                        //mengecek apakah perawatan yang dipilih pelanggan memuat slot jam yang sedang ditutup atau tidak aktif
                        $slotJamTutup = [];
                        foreach ($arrObjectSlotJamTerpakai as $slotJam) {
                            if ($slotJam->status == 'nonaktif') {
                                array_push($slotJamTutup, $slotJam->jam);
                            }
                        }
                        if (count($slotJamTutup) > 0) {
                            $stringArrJamTutup = implode(', ', $slotJamTutup);

                            $arrPerawatanSend = [];
                            if ($arrPerawatan != null) {
                                foreach ($arrPerawatan as $ap) {
                                    $p = Perawatan::select('id', 'kode_perawatan', 'nama', 'harga', 'deskripsi', 'durasi')->where('status', 'aktif')->where('id', $ap)->first();
                                    array_push($arrPerawatanSend, $p);
                                }
                            }

                            $arrPaketSend = [];
                            if ($arrPaket != null) {
                                foreach ($arrPaket as $idPaket) {
                                    $paketvar = Paket::where('status', 'aktif')->where('id', $idPaket)->first();
                                    array_push($arrPaketSend, $paketvar);
                                }
                            }

                            $daftarPelanggans = Pelanggan::all();
                            $varSlotJam = SlotJam::find($idSlotJam);
                            if ($tanggalPenjualan == date("Y-m-d")) {
                                $daftarSlotJam = SlotJam::where('hari', $varSlotJam->hari)->where('jam', ">=", date("H.i"))->orderBy('id')->get();
                            } else {
                                $daftarSlotJam = SlotJam::where('hari', $varSlotJam->hari)->orderBy('id')->get();
                            }
                            return redirect()->route('penjualans.admin.create')->with([
                                'idPelanggan' => $idPelanggan,
                                'daftarPelanggans' => $daftarPelanggans,
                                'idSlotJam' => $idSlotJam,
                                'daftarSlotJam' => $daftarSlotJam,
                                'tanggalPenjualan' => $tanggalPenjualan,
                                'arrPerawatan' => $arrPerawatan,
                                'arrPerawatanObject' => $arrPerawatanSend,
                                'arrPaket' => $arrPaket,
                                'arrPaketObject' => $arrPaketSend,
                                'arrKodeKeseluruhan' => $arrKodeKeseluruhan,

                            ])->withErrors('Perawatan atau Paket yang dipilih memuat jam waktu tutup salon yaitu pada jam ' . $stringArrJamTutup);
                        }

                        $daftarSlotJam = [];
                        foreach ($arrIdSlotJamTerpakai as $sj) {
                            array_push($daftarSlotJam, $sj->id);
                        }
                        $stringDaftarSlotJam = implode(".", $daftarSlotJam);
                        $perawatanPerSlot['idslotjam'] = $stringDaftarSlotJam;

                        $arrIdKaryawanTerpakaiSlotJam = Karyawan::select('karyawans.id')->distinct()
                            ->join('karyawan_perawatan', 'karyawan_perawatan.karyawan_id', '=', 'karyawans.id')
                            ->join('penjualan_perawatan', 'penjualan_perawatan.karyawan_id', '=', 'karyawans.id')
                            ->join('penjualans', 'penjualans.id', '=', 'penjualan_perawatan.penjualan_id')
                            ->join('slot_jam_penjualan_perawatan', 'slot_jam_penjualan_perawatan.penjualan_perawatan_id', '=', 'penjualan_perawatan.id')
                            ->join('slot_jams', 'slot_jams.id', '=', 'slot_jam_penjualan_perawatan.slot_jam_id')
                            ->whereIn('slot_jams.id', $arrIdSlotJamTerpakai)
                            ->where('penjualans.tanggal_penjualan', $tanggalPenjualan)
                            ->where('penjualans.status_selesai', 'belum')
                            ->get();

                        $arrIdKaryawanIzinSakit = Karyawan::select('karyawans.id')->distinct()
                            ->join('presensi_kehadiran', 'presensi_kehadiran.karyawan_id', '=', 'karyawans.id')
                            ->where(function ($query) {
                                $query->where('presensi_kehadiran.keterangan', 'izin')->orWhere('presensi_kehadiran.keterangan', 'sakit');
                            })->whereRaw("DATE(presensi_kehadiran.tanggal_presensi) = '" . $tanggalPenjualan . "'")->where("presensi_kehadiran.status", "konfirmasi")->get();

                        // $idKaryawan
                        $karyawanTersedia = Karyawan::select('karyawans.*')->join('karyawan_perawatan', 'karyawan_perawatan.karyawan_id', '=', 'karyawans.id')
                            ->join('perawatans', 'perawatans.id', '=', 'karyawan_perawatan.perawatan_id')
                            ->whereNotIn('karyawans.id', $arrIdKaryawanTerpakaiSlotJam)->whereNotIn('karyawans.id', $arrIdKaryawanIzinSakit)->where('perawatans.id', $perawatan->id)->get();


                        $perawatanPerSlot["karyawans"] = $karyawanTersedia;
                        array_push($perawatanSlotJamNonKomplemen, $perawatanPerSlot);

                        //update slotJamBerubah ke slot selanjutnya
                        $intJamTerakhir = strtotime("+30 minutes", strtotime($jamTerakhir));
                        $jamTerakhir = date('H.i', $intJamTerakhir);
                        $slotJamBerubah = SlotJam::where('jam', $jamTerakhir)->where('hari', $slotJamBerubah->hari)->first();
                    }
                }

                $arrKomplemen = [];
                $arrKomplemen["array"] = $perawatanSlotJamKomplemen;
                //BAGIAN PERAWATAN KOMPLEMEN-------------------------------------------------------------------------
                if (count($perawatanKomplemen) > 0) {
                    $arrKomplemen['jammulai'] = $slotJamBerubah->jam;

                    $arrIdPerawatanKomplemen = [];
                    foreach ($perawatanKomplemen as $pk) {
                        array_push($arrIdPerawatanKomplemen, $pk->id);
                    }

                    $durasiTerlamaPerawatanKomplemen = Perawatan::whereIn('id', $arrIdPerawatanKomplemen)->max('durasi');
                    $arrKomplemen['durasiterlama'] = $durasiTerlamaPerawatanKomplemen;
                    $jumlahSlotTerpakai = ceil($durasiTerlamaPerawatanKomplemen / 30);
                    $intJamTerakhir = strtotime("+" . ($jumlahSlotTerpakai * 30) - 30 . " minutes", strtotime($slotJamBerubah->jam));
                    $jamTerakhir = date('H.i', $intJamTerakhir);

                    $arrIdSlotJamTerpakai = SlotJam::select('id')->where('jam', '>=', $slotJamBerubah->jam)->where('jam', '<=', $jamTerakhir)->where('hari', $slotJamBerubah->hari)->get();
                    $arrObjectSlotJamTerpakai = SlotJam::where('jam', '>=', $slotJamBerubah->jam)->where('jam', '<=', $jamTerakhir)->where('hari', $slotJamBerubah->hari)->get();
                    //mengecek apakah perawatan yang dipilih pelanggan memuat slot jam yan sedang ditutup atau tidak aktif
                    $slotJamTutup = [];
                    foreach ($arrObjectSlotJamTerpakai as $slotJam) {
                        if ($slotJam->status == 'nonaktif') {
                            array_push($slotJamTutup, $slotJam->jam);
                        }
                    }
                    if (count($slotJamTutup) > 0) {
                        $stringArrJamTutup = implode(', ', $slotJamTutup);

                        $arrPerawatanSend = [];
                        if ($arrPerawatan != null) {
                            foreach ($arrPerawatan as $ap) {
                                $p = Perawatan::select('id', 'kode_perawatan', 'nama', 'harga', 'deskripsi', 'durasi')->where('status', 'aktif')->where('id', $ap)->first();
                                array_push($arrPerawatanSend, $p);
                            }
                        }

                        $arrPaketSend = [];
                        if ($arrPaket != null) {
                            foreach ($arrPaket as $idPaket) {
                                $paketvar = Paket::where('status', 'aktif')->where('id', $idPaket)->first();
                                array_push($arrPaketSend, $paketvar);
                            }
                        }

                        $daftarPelanggans = Pelanggan::all();
                        $varSlotJam = SlotJam::find($idSlotJam);
                        if ($tanggalPenjualan == date("Y-m-d")) {
                            $daftarSlotJam = SlotJam::where('hari', $varSlotJam->hari)->where('jam', ">=", date("H.i"))->orderBy('id')->get();
                        } else {
                            $daftarSlotJam = SlotJam::where('hari', $varSlotJam->hari)->orderBy('id')->get();
                        }
                        return redirect()->route('penjualans.admin.create')->with([
                            'idPelanggan' => $idPelanggan,
                            'daftarPelanggans' => $daftarPelanggans,
                            'idSlotJam' => $idSlotJam,
                            'daftarSlotJam' => $daftarSlotJam,
                            'tanggalPenjualan' => $tanggalPenjualan,
                            'arrPerawatan' => $arrPerawatan,
                            'arrPerawatanObject' => $arrPerawatanSend,
                            'arrPaket' => $arrPaket,
                            'arrPaketObject' => $arrPaketSend,
                            'arrKodeKeseluruhan' => $arrKodeKeseluruhan,

                        ])->withErrors('Perawatan atau Paket yang dipilih memuat jam waktu tutup salon yaitu pada jam ' . $stringArrJamTutup);
                    }

                    $daftarSlotJam = [];
                    foreach ($arrIdSlotJamTerpakai as $sj) {
                        array_push($daftarSlotJam, $sj->id);
                    }
                    $stringDaftarSlotJam = implode(".", $daftarSlotJam);
                    $arrKomplemen['idslotjam'] = $stringDaftarSlotJam;

                    $arrIdKaryawanTerpakaiSlotJam = Karyawan::select('karyawans.id')->distinct()
                        ->join('karyawan_perawatan', 'karyawan_perawatan.karyawan_id', '=', 'karyawans.id')
                        ->join('penjualan_perawatan', 'penjualan_perawatan.karyawan_id', '=', 'karyawans.id')
                        ->join('penjualans', 'penjualans.id', '=', 'penjualan_perawatan.penjualan_id')
                        ->join('slot_jam_penjualan_perawatan', 'slot_jam_penjualan_perawatan.penjualan_perawatan_id', '=', 'penjualan_perawatan.id')
                        ->join('slot_jams', 'slot_jams.id', '=', 'slot_jam_penjualan_perawatan.slot_jam_id')
                        ->whereIn('slot_jams.id', $arrIdSlotJamTerpakai)
                        ->where('penjualans.tanggal_penjualan', $tanggalPenjualan)
                        ->where('penjualans.status_selesai', 'belum')
                        ->get();

                    $arrIdKaryawanIzinSakit = Karyawan::select('karyawans.id')->distinct()
                        ->join('presensi_kehadiran', 'presensi_kehadiran.karyawan_id', '=', 'karyawans.id')
                        ->where(function ($query) {
                            $query->where('presensi_kehadiran.keterangan', 'izin')->orWhere('presensi_kehadiran.keterangan', 'sakit');
                        })->whereRaw("DATE(presensi_kehadiran.tanggal_presensi) = '" . $tanggalPenjualan . "'")->where("presensi_kehadiran.status", "konfirmasi")->get();


                    foreach ($perawatanKomplemen as $perawatanK) {
                        $perawatanPerSlot = [];
                        $perawatanPerSlot["perawatan"] = $perawatanK;
                        $karyawanTersedia = Karyawan::select('karyawans.*')->join('karyawan_perawatan', 'karyawan_perawatan.karyawan_id', '=', 'karyawans.id')
                            ->join('perawatans', 'perawatans.id', '=', 'karyawan_perawatan.perawatan_id')
                            ->whereNotIn('karyawans.id', $arrIdKaryawanTerpakaiSlotJam)->whereNotIn('karyawans.id', $arrIdKaryawanIzinSakit)->where('perawatans.id', $perawatanK->id)->get();


                        $perawatanPerSlot["karyawans"] = $karyawanTersedia;
                        array_push($perawatanSlotJamKomplemen, $perawatanPerSlot);
                    }
                    $arrKomplemen['array'] = $perawatanSlotJamKomplemen;
                }


                $keteranganNull = 0;

                foreach ($perawatanSlotJamNonKomplemen as $ps) {
                    if (count($ps['karyawans']) == 0) {
                        $keteranganNull += 1;
                    }
                }
                foreach ($arrKomplemen['array'] as $ps) {
                    if (count($ps['karyawans']) == 0) {
                        $keteranganNull += 1;
                    }
                }
                if ($keteranganNull > 0) {
                    $pesanError = "Terdapat perawatan yang memiliki karyawan yang tidak tersedia pada jam tersebut, silahkan mengatur ulang jam mulai, urutan perawatan Anda, atau hari reservasi lainnya!";
                    return view('admin.penjualan.pilihkaryawan', compact('perawatanSlotJamNonKomplemen', 'arrKomplemen', 'tanggalPenjualan', 'idSlotJam', 'arrPerawatan', 'idPelanggan', 'pesanError', 'arrPaket', 'arrKodeKeseluruhan'));
                } else {
                    return view('admin.penjualan.pilihkaryawan', compact('perawatanSlotJamNonKomplemen', 'arrKomplemen', 'tanggalPenjualan', 'idSlotJam', 'arrPerawatan', 'idPelanggan', 'arrPaket', 'arrKodeKeseluruhan'));
                }

            } else {
                //cari slot jam mulai dari form buatreservasiadmin
                $slotJamBerubah = SlotJam::find($idSlotJam);

                //BAGIAN PERAWATAN NON KOMPLEMEN-------------------------------------------------------------------------
                //foreach $perawatanNonKomplemen untuk mencari karyawan tersedia untuk setiap perawatan
                if (count($perawatanNonKomplemen) > 0) {
                    foreach ($perawatanNonKomplemen as $perawatan) {
                        $perawatanPerSlot = [];
                        $perawatanPerSlot["perawatan"] = $perawatan;
                        $perawatanPerSlot["jammulai"] = $slotJamBerubah->jam;

                        $jumlahSlotTerpakai = ceil($perawatan->durasi / 30);
                        $intJamTerakhir = strtotime("+" . ($jumlahSlotTerpakai * 30) - 30 . " minutes", strtotime($slotJamBerubah->jam));
                        $jamTerakhir = date('H.i', $intJamTerakhir);
                        $arrIdSlotJamTerpakai = SlotJam::select('id')->where('jam', '>=', $slotJamBerubah->jam)->where('jam', '<=', $jamTerakhir)->where('hari', $slotJamBerubah->hari)->get();
                        $arrObjectSlotJamTerpakai = SlotJam::where('jam', '>=', $slotJamBerubah->jam)->where('jam', '<=', $jamTerakhir)->where('hari', $slotJamBerubah->hari)->get();
                        // dd($jumlahSlotTerpakai, count($arrIdSlotJamTerpakai), $arrIdSlotJamTerpakai);

                        //mengecek apakah perawatan yang dipilih pelanggan memuat slot jam yang sedang ditutup atau tidak aktif
                        $slotJamTutup = [];
                        foreach ($arrObjectSlotJamTerpakai as $slotJam) {
                            if ($slotJam->status == 'nonaktif') {
                                array_push($slotJamTutup, $slotJam->jam);
                            }
                        }
                        if (count($slotJamTutup) > 0) {
                            $stringArrJamTutup = implode(', ', $slotJamTutup);

                            $arrPerawatanSend = [];
                            if ($arrPerawatan != null) {
                                foreach ($arrPerawatan as $ap) {
                                    $p = Perawatan::select('id', 'kode_perawatan', 'nama', 'harga', 'deskripsi', 'durasi')->where('status', 'aktif')->where('id', $ap)->first();
                                    array_push($arrPerawatanSend, $p);
                                }
                            }

                            $arrPaketSend = [];
                            if ($arrPaket != null) {
                                foreach ($arrPaket as $idPaket) {
                                    $paketvar = Paket::where('status', 'aktif')->where('id', $idPaket)->first();
                                    array_push($arrPaketSend, $paketvar);
                                }
                            }

                            $daftarPelanggans = Pelanggan::all();
                            $varSlotJam = SlotJam::find($idSlotJam);
                            if ($tanggalPenjualan == date("Y-m-d")) {
                                $daftarSlotJam = SlotJam::where('hari', $varSlotJam->hari)->where('jam', ">=", date("H.i"))->orderBy('id')->get();
                            } else {
                                $daftarSlotJam = SlotJam::where('hari', $varSlotJam->hari)->orderBy('id')->get();
                            }
                            return redirect()->route('penjualans.admin.create')->with([
                                'idPelanggan' => $idPelanggan,
                                'daftarPelanggans' => $daftarPelanggans,
                                'idSlotJam' => $idSlotJam,
                                'daftarSlotJam' => $daftarSlotJam,
                                'tanggalPenjualan' => $tanggalPenjualan,
                                'arrPerawatan' => $arrPerawatan,
                                'arrPerawatanObject' => $arrPerawatanSend,
                                'arrPaket' => $arrPaket,
                                'arrPaketObject' => $arrPaketSend,
                                'arrKodeKeseluruhan' => $arrKodeKeseluruhan,

                            ])->withErrors('Perawatan atau Paket yang dipilih memuat jam waktu tutup salon yaitu pada jam ' . $stringArrJamTutup);
                        }

                        $daftarSlotJam = [];
                        foreach ($arrIdSlotJamTerpakai as $sj) {
                            array_push($daftarSlotJam, $sj->id);
                        }
                        $stringDaftarSlotJam = implode(".", $daftarSlotJam);
                        $perawatanPerSlot['idslotjam'] = $stringDaftarSlotJam;

                        $arrIdKaryawanTerpakaiSlotJam = Karyawan::select('karyawans.id')->distinct()
                            ->join('karyawan_perawatan', 'karyawan_perawatan.karyawan_id', '=', 'karyawans.id')
                            ->join('penjualan_perawatan', 'penjualan_perawatan.karyawan_id', '=', 'karyawans.id')
                            ->join('penjualans', 'penjualans.id', '=', 'penjualan_perawatan.penjualan_id')
                            ->join('slot_jam_penjualan_perawatan', 'slot_jam_penjualan_perawatan.penjualan_perawatan_id', '=', 'penjualan_perawatan.id')
                            ->join('slot_jams', 'slot_jams.id', '=', 'slot_jam_penjualan_perawatan.slot_jam_id')
                            ->whereIn('slot_jams.id', $arrIdSlotJamTerpakai)
                            ->where('penjualans.tanggal_penjualan', $tanggalPenjualan)
                            ->where('penjualans.status_selesai', 'belum')
                            ->get();

                        $arrIdKaryawanIzinSakit = Karyawan::select('karyawans.id')->distinct()
                            ->join('presensi_kehadiran', 'presensi_kehadiran.karyawan_id', '=', 'karyawans.id')
                            ->where(function ($query) {
                                $query->where('presensi_kehadiran.keterangan', 'izin')->orWhere('presensi_kehadiran.keterangan', 'sakit');
                            })->whereRaw("DATE(presensi_kehadiran.tanggal_presensi) = '" . $tanggalPenjualan . "'")->where("presensi_kehadiran.status", "konfirmasi")->get();

                        // $idKaryawan
                        $arrKaryawanTersedia = [];
                        $karyawanTersedia = Karyawan::select('karyawans.*')->join('karyawan_perawatan', 'karyawan_perawatan.karyawan_id', '=', 'karyawans.id')
                            ->join('perawatans', 'perawatans.id', '=', 'karyawan_perawatan.perawatan_id')
                            ->whereNotIn('karyawans.id', $arrIdKaryawanTerpakaiSlotJam)->whereNotIn('karyawans.id', $arrIdKaryawanIzinSakit)->where('perawatans.id', $perawatan->id)->first();


                        if ($karyawanTersedia != null) {
                            array_push($arrKaryawanTersedia, $karyawanTersedia);
                        }

                        $perawatanPerSlot["karyawans"] = $arrKaryawanTersedia;
                        array_push($perawatanSlotJamNonKomplemen, $perawatanPerSlot);

                        //update slotJamBerubah ke slot selanjutnya
                        $intJamTerakhir = strtotime("+30 minutes", strtotime($jamTerakhir));
                        $jamTerakhir = date('H.i', $intJamTerakhir);
                        $slotJamBerubah = SlotJam::where('jam', $jamTerakhir)->where('hari', $slotJamBerubah->hari)->first();
                    }
                }


                //BAGIAN PERAWATAN KOMPLEMEN-------------------------------------------------------------------------
                $arrKomplemen = [];
                $arrKomplemen["array"] = $perawatanSlotJamKomplemen;

                if (count($perawatanNonKomplemen) > 0) {
                    $arrKomplemen['jammulai'] = $slotJamBerubah->jam;

                    $arrIdPerawatanKomplemen = [];
                    foreach ($perawatanKomplemen as $pk) {
                        array_push($arrIdPerawatanKomplemen, $pk->id);
                    }

                    $durasiTerlamaPerawatanKomplemen = Perawatan::whereIn('id', $arrIdPerawatanKomplemen)->max('durasi');
                    $arrKomplemen['durasiterlama'] = $durasiTerlamaPerawatanKomplemen;
                    $jumlahSlotTerpakai = ceil($durasiTerlamaPerawatanKomplemen / 30);
                    $intJamTerakhir = strtotime("+" . ($jumlahSlotTerpakai * 30) - 30 . " minutes", strtotime($slotJamBerubah->jam));
                    $jamTerakhir = date('H.i', $intJamTerakhir);

                    $arrIdSlotJamTerpakai = SlotJam::select('id')->where('jam', '>=', $slotJamBerubah->jam)->where('jam', '<=', $jamTerakhir)->where('hari', $slotJamBerubah->hari)->get();
                    $arrObjectSlotJamTerpakai = SlotJam::where('jam', '>=', $slotJamBerubah->jam)->where('jam', '<=', $jamTerakhir)->where('hari', $slotJamBerubah->hari)->get();
                    //mengecek apakah perawatan yang dipilih pelanggan memuat slot jam yan sedang ditutup atau tidak aktif
                    $slotJamTutup = [];
                    foreach ($arrObjectSlotJamTerpakai as $slotJam) {
                        if ($slotJam->status == 'nonaktif') {
                            array_push($slotJamTutup, $slotJam->jam);
                        }
                    }
                    if (count($slotJamTutup) > 0) {
                        $stringArrJamTutup = implode(', ', $slotJamTutup);

                        $arrPerawatanSend = [];
                        if ($arrPerawatan != null) {
                            foreach ($arrPerawatan as $ap) {
                                $p = Perawatan::select('id', 'kode_perawatan', 'nama', 'harga', 'deskripsi', 'durasi')->where('status', 'aktif')->where('id', $ap)->first();
                                array_push($arrPerawatanSend, $p);
                            }
                        }

                        $arrPaketSend = [];
                        if ($arrPaket != null) {
                            foreach ($arrPaket as $idPaket) {
                                $paketvar = Paket::where('status', 'aktif')->where('id', $idPaket)->first();
                                array_push($arrPaketSend, $paketvar);
                            }
                        }

                        $daftarPelanggans = Pelanggan::all();
                        $varSlotJam = SlotJam::find($idSlotJam);
                        if ($tanggalPenjualan == date("Y-m-d")) {
                            $daftarSlotJam = SlotJam::where('hari', $varSlotJam->hari)->where('jam', ">=", date("H.i"))->orderBy('id')->get();
                        } else {
                            $daftarSlotJam = SlotJam::where('hari', $varSlotJam->hari)->orderBy('id')->get();
                        }
                        return redirect()->route('penjualans.admin.create')->with([
                            'idPelanggan' => $idPelanggan,
                            'daftarPelanggans' => $daftarPelanggans,
                            'idSlotJam' => $idSlotJam,
                            'daftarSlotJam' => $daftarSlotJam,
                            'tanggalPenjualan' => $tanggalPenjualan,
                            'arrPerawatan' => $arrPerawatan,
                            'arrPerawatanObject' => $arrPerawatanSend,
                            'arrPaket' => $arrPaket,
                            'arrPaketObject' => $arrPaketSend,
                            'arrKodeKeseluruhan' => $arrKodeKeseluruhan,

                        ])->withErrors('Perawatan atau Paket yang dipilih memuat jam waktu tutup salon yaitu pada jam ' . $stringArrJamTutup);
                    }

                    $daftarSlotJam = [];
                    foreach ($arrIdSlotJamTerpakai as $sj) {
                        array_push($daftarSlotJam, $sj->id);
                    }
                    $stringDaftarSlotJam = implode(".", $daftarSlotJam);
                    $arrKomplemen['idslotjam'] = $stringDaftarSlotJam;

                    $arrIdKaryawanTerpakaiSlotJam = Karyawan::select('karyawans.id')->distinct()
                        ->join('karyawan_perawatan', 'karyawan_perawatan.karyawan_id', '=', 'karyawans.id')
                        ->join('penjualan_perawatan', 'penjualan_perawatan.karyawan_id', '=', 'karyawans.id')
                        ->join('penjualans', 'penjualans.id', '=', 'penjualan_perawatan.penjualan_id')
                        ->join('slot_jam_penjualan_perawatan', 'slot_jam_penjualan_perawatan.penjualan_perawatan_id', '=', 'penjualan_perawatan.id')
                        ->join('slot_jams', 'slot_jams.id', '=', 'slot_jam_penjualan_perawatan.slot_jam_id')
                        ->whereIn('slot_jams.id', $arrIdSlotJamTerpakai)
                        ->where('penjualans.tanggal_penjualan', $tanggalPenjualan)
                        ->where('penjualans.status_selesai', 'belum')
                        ->get();

                    $arrIdKaryawanIzinSakit = Karyawan::select('karyawans.id')->distinct()
                        ->join('presensi_kehadiran', 'presensi_kehadiran.karyawan_id', '=', 'karyawans.id')
                        ->where(function ($query) {
                            $query->where('presensi_kehadiran.keterangan', 'izin')->orWhere('presensi_kehadiran.keterangan', 'sakit');
                        })->whereRaw("DATE(presensi_kehadiran.tanggal_presensi) = '" . $tanggalPenjualan . "'")->where("presensi_kehadiran.status", "konfirmasi")->get();


                    foreach ($perawatanKomplemen as $perawatanK) {
                        $perawatanPerSlot = [];
                        $perawatanPerSlot["perawatan"] = $perawatanK;

                        $arrKaryawanTersedia = [];
                        $karyawanTersedia = Karyawan::select('karyawans.*')->join('karyawan_perawatan', 'karyawan_perawatan.karyawan_id', '=', 'karyawans.id')
                            ->join('perawatans', 'perawatans.id', '=', 'karyawan_perawatan.perawatan_id')
                            ->whereNotIn('karyawans.id', $arrIdKaryawanTerpakaiSlotJam)->whereNotIn('karyawans.id', $arrIdKaryawanIzinSakit)->where('perawatans.id', $perawatanK->id)->first();


                        if ($karyawanTersedia != null) {
                            array_push($arrKaryawanTersedia, $karyawanTersedia);
                        }

                        $perawatanPerSlot["karyawans"] = $arrKaryawanTersedia;
                        array_push($perawatanSlotJamKomplemen, $perawatanPerSlot);
                    }
                    $arrKomplemen['array'] = $perawatanSlotJamKomplemen;
                }


                $keteranganNull = 0;

                $arrPerawatanTidakAdaKaryawan = [];
                foreach ($perawatanSlotJamNonKomplemen as $ps) {
                    if (count($ps['karyawans']) == 0) {
                        $keteranganNull += 1;
                        array_push($arrPerawatanTidakAdaKaryawan, $ps["perawatan"]->nama);
                    }
                }
                foreach ($arrKomplemen['array'] as $ps) {
                    if (count($ps['karyawans']) == 0) {
                        $keteranganNull += 1;
                        array_push($arrPerawatanTidakAdaKaryawan, $ps["perawatan"]->nama);
                    }
                }


                if ($keteranganNull > 0) {
                    $stringPerawatanTidakAdaKaryawan = implode(",", $arrPerawatanTidakAdaKaryawan);
                    $pesanError = "Terdapat perawatan seperti " . $stringPerawatanTidakAdaKaryawan . " yang memiliki karyawan yang tidak tersedia pada jam kerja, silahkan mengatur ulang jam mulai, urutan perawatan/paket Anda, atau hari reservasi lainnya!";

                    $arrPerawatanSend = [];
                    if ($arrPerawatan != null) {
                        foreach ($arrPerawatan as $ap) {
                            $p = Perawatan::select('id', 'kode_perawatan', 'nama', 'harga', 'deskripsi', 'durasi')->where('status', 'aktif')->where('id', $ap)->first();
                            array_push($arrPerawatanSend, $p);
                        }
                    }

                    $arrPaketSend = [];
                    if ($arrPaket != null) {
                        foreach ($arrPaket as $idPaket) {
                            $paketvar = Paket::where('status', 'aktif')->where('id', $idPaket)->first();
                            array_push($arrPaketSend, $paketvar);
                        }
                    }

                    $daftarPelanggans = Pelanggan::all();
                    $varSlotJam = SlotJam::find($idSlotJam);
                    if ($tanggalPenjualan == date("Y-m-d")) {
                        $daftarSlotJam = SlotJam::where('hari', $varSlotJam->hari)->where('jam', ">=", date("H.i"))->orderBy('id')->get();
                    } else {
                        $daftarSlotJam = SlotJam::where('hari', $varSlotJam->hari)->orderBy('id')->get();
                    }
                    return redirect()->route('penjualans.admin.create')->with([
                        'idPelanggan' => $idPelanggan,
                        'daftarPelanggans' => $daftarPelanggans,
                        'idSlotJam' => $idSlotJam,
                        'daftarSlotJam' => $daftarSlotJam,
                        'tanggalPenjualan' => $tanggalPenjualan,
                        'arrPerawatan' => $arrPerawatan,
                        'arrPerawatanObject' => $arrPerawatanSend,
                        'arrPaket' => $arrPaket,
                        'arrPaketObject' => $arrPaketSend,
                        'arrKodeKeseluruhan' => $arrKodeKeseluruhan,

                    ])->withErrors($pesanError);
                } else {
                    $daftarKaryawanPerawatan = [];
                    $daftarKaryawanPerawatanKomplemen = [];

                    foreach ($perawatanSlotJamNonKomplemen as $ps) {
                        $stringSlot = "";
                        foreach ($ps['karyawans'] as $k) {
                            $stringSlot .= $k->id . "," . $ps['perawatan']->id . ",(" . $ps['idslotjam'] . ")";
                        }
                        array_push($daftarKaryawanPerawatan, $stringSlot);
                    }

                    foreach ($arrKomplemen['array'] as $ps) {
                        $stringSlot = "";
                        foreach ($ps['karyawans'] as $k) {
                            $stringSlot .= $k->id . "," . $ps['perawatan']->id . ",(" . $arrKomplemen['idslotjam'] . ")";
                        }
                        array_push($daftarKaryawanPerawatanKomplemen, $stringSlot);
                    }

                    //pengecekan sebelum insert data slot jam dan karyawan untuk memastikan tidak ada pelanggan lain yang reservasi duluan
                    $slotJamKaryawanTerpakai = [];
                    foreach ($daftarKaryawanPerawatan as $karyawanPerawatan) {
                        $idKaryawan = explode(",", $karyawanPerawatan)[0];
                        $idPerawatan = explode(",", $karyawanPerawatan)[1];


                        $arrayStringSlotjam = str_replace(["(", ")"], "", explode(",", $karyawanPerawatan)[2]);
                        $arraySlotJam = explode(".", $arrayStringSlotjam);

                        $karyawan = Karyawan::select('karyawans.id')->distinct()
                            ->join('penjualan_perawatan', 'penjualan_perawatan.karyawan_id', '=', 'karyawans.id')
                            ->join('penjualans', 'penjualans.id', '=', 'penjualan_perawatan.penjualan_id')
                            ->join('slot_jam_penjualan_perawatan', 'slot_jam_penjualan_perawatan.penjualan_perawatan_id', '=', 'penjualan_perawatan.id')
                            ->join('slot_jams', 'slot_jams.id', '=', 'slot_jam_penjualan_perawatan.slot_jam_id')
                            ->whereIn('slot_jams.id', $arraySlotJam)
                            ->where('penjualans.tanggal_penjualan', $tanggalPenjualan)
                            ->where('karyawans.id', $idKaryawan)
                            ->get();

                        if (count($karyawan) > 0) {
                            $karyawanTerpakai = [];
                            $karyawanTerpakai['karyawan'] = Karyawan::find($idKaryawan);
                            $karyawanTerpakai['perawatan'] = Perawatan::find($idPerawatan);
                            $slotJam = SlotJam::whereIn('id', $arraySlotJam)->get();
                            $array = [];
                            foreach ($slotJam as $sj) {
                                array_push($array, $sj->jam);
                            }
                            $karyawanTerpakai['slotjam'] = implode(',', $array);
                            array_push($slotJamKaryawanTerpakai, $karyawanTerpakai);
                        }
                    }
                    foreach ($daftarKaryawanPerawatanKomplemen as $karyawanPerawatanKomplemen) {
                        $idKaryawan = explode(",", $karyawanPerawatanKomplemen)[0];
                        $idPerawatan = explode(",", $karyawanPerawatanKomplemen)[1];
                        $perawatan = Perawatan::find($idPerawatan);
                        $arrayStringSlotjam = str_replace(["(", ")"], "", explode(",", $karyawanPerawatanKomplemen)[2]);

                        $arraySlotJam = explode(".", $arrayStringSlotjam);

                        $durasiPerawatan = $perawatan->durasi;

                        $slotTerpakai = ceil($durasiPerawatan / 30);
                        $daftarSlotjamFinal = [];
                        for ($i = 0; $i < $slotTerpakai; $i++) {
                            array_push($daftarSlotjamFinal, $arraySlotJam[$i]);
                        }

                        $karyawan = Karyawan::select('karyawans.id')->distinct()
                            ->join('penjualan_perawatan', 'penjualan_perawatan.karyawan_id', '=', 'karyawans.id')
                            ->join('penjualans', 'penjualans.id', '=', 'penjualan_perawatan.penjualan_id')
                            ->join('slot_jam_penjualan_perawatan', 'slot_jam_penjualan_perawatan.penjualan_perawatan_id', '=', 'penjualan_perawatan.id')
                            ->join('slot_jams', 'slot_jams.id', '=', 'slot_jam_penjualan_perawatan.slot_jam_id')
                            ->whereIn('slot_jams.id', $daftarSlotjamFinal)
                            ->where('penjualans.tanggal_penjualan', $tanggalPenjualan)
                            ->where('karyawans.id', $idKaryawan)
                            ->get();
                        if (count($karyawan) > 0) {
                            $karyawanTerpakai = [];
                            $karyawanTerpakai['karyawan'] = Karyawan::find($idKaryawan);
                            $karyawanTerpakai['perawatan'] = Perawatan::find($idPerawatan);

                            $slotJam = SlotJam::whereIn('id', $daftarSlotjamFinal)->get();
                            $array = [];
                            foreach ($slotJam as $sj) {
                                array_push($array, $sj->jam);
                            }
                            $karyawanTerpakai['slotjam'] = implode(',', $array);
                            array_push($slotJamKaryawanTerpakai, $karyawanTerpakai);
                        }
                    }

                    if (count($slotJamKaryawanTerpakai) > 0) {
                        $pesanError = [];
                        foreach ($slotJamKaryawanTerpakai as $sjkt) {
                            $pesan = "Perawatan atau Paket yang mengandung " . $sjkt['perawatan']->nama . " dengan karyawan " . $sjkt['karyawan']->nama . " pada slot waktu " . $sjkt['slotjam'] . " telah terpakai.";
                            array_push($pesanError, $pesan);
                        }

                        $arrPerawatanSend = [];
                        if ($arrPerawatan != null) {
                            foreach ($arrPerawatan as $ap) {
                                $p = Perawatan::select('id', 'kode_perawatan', 'nama', 'harga', 'deskripsi', 'durasi')->where('status', 'aktif')->where('id', $ap)->first();
                                array_push($arrPerawatanSend, $p);
                            }
                        }

                        $arrPaketSend = [];
                        if ($arrPaket != null) {
                            foreach ($arrPaket as $idPaket) {
                                $paketvar = Paket::where('status', 'aktif')->where('id', $idPaket)->first();
                                array_push($arrPaketSend, $paketvar);
                            }
                        }

                        $daftarPelanggans = Pelanggan::all();
                        $varSlotJam = SlotJam::find($idSlotJam);
                        if ($tanggalPenjualan == date("Y-m-d")) {
                            $daftarSlotJam = SlotJam::where('hari', $varSlotJam->hari)->where('jam', ">=", date("H.i"))->orderBy('id')->get();
                        } else {
                            $daftarSlotJam = SlotJam::where('hari', $varSlotJam->hari)->orderBy('id')->get();
                        }
                        return redirect()->route('penjualans.admin.create')->with([
                            'idPelanggan' => $idPelanggan,
                            'daftarPelanggans' => $daftarPelanggans,
                            'idSlotJam' => $idSlotJam,
                            'daftarSlotJam' => $daftarSlotJam,
                            'tanggalPenjualan' => $tanggalPenjualan,
                            'arrPerawatan' => $arrPerawatan,
                            'arrPerawatanObject' => $arrPerawatanSend,
                            'arrPaket' => $arrPaket,
                            'arrPaketObject' => $arrPaketSend,
                            'arrKodeKeseluruhan' => $arrKodeKeseluruhan,

                        ])->withErrors($pesanError);

                    }

                    //Jika ada paket, dicek lagi jika pada paket mempunyai produk maka perlu dicek ketersediaan stok produk
                    $arrayPaket = [];
                    foreach ($arrKodeKeseluruhan as $kode) {
                        $kodePertama = substr($kode, 0, 1);
                        if ($kodePertama == "m") {
                            $paketSementara = Paket::where("kode_paket", $kode)->first();
                            array_push($arrayPaket, $paketSementara);
                        }
                    }

                    $arrayProdukDalamPaketUntukCheck = [];
                    if (count($arrayPaket) > 0) {
                        foreach ($arrayPaket as $paket) {
                            foreach ($paket->produks as $produkPaket) {
                                $produkPaketSementara = [];
                                $produkPaketSementara["id"] = $produkPaket->id;
                                $produkPaketSementara["jumlah"] = $produkPaket->pivot->jumlah;
                                array_push($arrayProdukDalamPaketUntukCheck, $produkPaketSementara);
                            }
                        }
                    }

                    $arrayFinalProdukDalamPaketUntukCheck = [];
                    if (count($arrayProdukDalamPaketUntukCheck) > 0) {
                        foreach ($arrayProdukDalamPaketUntukCheck as $varProduk) {
                            $id = $varProduk["id"];
                            $jumlah = $varProduk["jumlah"];

                            if (isset($arrayFinalProdukDalamPaketUntukCheck[$id])) {
                                $arrayFinalProdukDalamPaketUntukCheck[$id]["jumlah"] += $jumlah;
                            } else {
                                $arrayFinalProdukDalamPaketUntukCheck[$id] = ["id" => $id, "jumlah" => $jumlah];
                            }
                        }
                    }

                    if (count($arrayFinalProdukDalamPaketUntukCheck) > 0) {
                        $pesanError = [];
                        foreach ($arrayFinalProdukDalamPaketUntukCheck as $varProduk1) {
                            $objProduk = Produk::find($varProduk1["id"]);
                            if ($objProduk->stok < $varProduk1["jumlah"]) {
                                $pesan = "Total Produk " . $objProduk->nama . " dari Paket yang Anda pilih tidak mencukupi dari stok produk yang tersedia saat ini!";
                                array_push($pesanError, $pesan);
                            }
                        }

                        if (count($pesanError) > 0) {
                            $arrPerawatanSend = [];
                            if ($arrPerawatan != null) {
                                foreach ($arrPerawatan as $ap) {
                                    $p = Perawatan::select('id', 'kode_perawatan', 'nama', 'harga', 'deskripsi', 'durasi')->where('status', 'aktif')->where('id', $ap)->first();
                                    array_push($arrPerawatanSend, $p);
                                }
                            }

                            $arrPaketSend = [];
                            if ($arrPaket != null) {
                                foreach ($arrPaket as $idPaket) {
                                    $paketvar = Paket::where('status', 'aktif')->where('id', $idPaket)->first();
                                    array_push($arrPaketSend, $paketvar);
                                }
                            }

                            $daftarPelanggans = Pelanggan::all();
                            $varSlotJam = SlotJam::find($idSlotJam);

                            if ($tanggalPenjualan == date("Y-m-d")) {
                                $daftarSlotJam = SlotJam::where('hari', $varSlotJam->hari)->where('jam', ">=", date("H.i"))->orderBy('id')->get();
                            } else {
                                $daftarSlotJam = SlotJam::where('hari', $varSlotJam->hari)->orderBy('id')->get();
                            }
                            return redirect()->route('penjualans.admin.create')->with([
                                'idPelanggan' => $idPelanggan,
                                'daftarPelanggans' => $daftarPelanggans,
                                'idSlotJam' => $idSlotJam,
                                'daftarSlotJam' => $daftarSlotJam,
                                'tanggalPenjualan' => $tanggalPenjualan,
                                'arrPerawatan' => $arrPerawatan,
                                'arrPerawatanObject' => $arrPerawatanSend,
                                'arrPaket' => $arrPaket,
                                'arrPaketObject' => $arrPaketSend,
                                'arrKodeKeseluruhan' => $arrKodeKeseluruhan,

                            ])->withErrors($pesanError);
                        }
                    }
                    // Akhir dari pengecekan stok produk

                    // //Mulai Insert Data Penjualan jika ada slot kosong tersedia
                    $newPenjualan = new Penjualan();
                    $newPenjualan->nomor_nota = $idPelanggan . "/" . (count($daftarKaryawanPerawatan) + count($daftarKaryawanPerawatanKomplemen)) . "/" . date('d') . date('m') . date('Y') . "/" . date("H") . date("i") . date("s");

                    $totalHarga = 0;

                    $arrIdPaketProduk = [];
                    $arrIdPaketPerawatan = [];


                    if (count($arrayPaket) > 0) {
                        foreach ($arrayPaket as $paketTerpilih) {
                            foreach ($paketTerpilih->perawatans as $perawatanPaket) {
                                array_push($arrIdPaketPerawatan, $perawatanPaket->id);
                            }

                            foreach ($paketTerpilih->produks as $produkPaket) {
                                array_push($arrIdPaketProduk, $produkPaket->id);
                            }

                            $totalHarga += $paketTerpilih->harga;
                        }
                    }

                    foreach ($daftarKaryawanPerawatan as $karyawanPerawatan) {
                        $perawatan = Perawatan::find(explode(",", $karyawanPerawatan)[1]);
                        if (!in_array($perawatan->id, $arrIdPaketPerawatan)) {
                            $totalHarga += $perawatan->harga;
                        }
                    }

                    foreach ($daftarKaryawanPerawatanKomplemen as $karyawanPerawatan) {
                        $perawatan = Perawatan::find(explode(",", $karyawanPerawatan)[1]);
                        if (!in_array($perawatan->id, $arrIdPaketPerawatan)) {
                            $totalHarga += $perawatan->harga;
                        }
                    }


                    $newPenjualan->total_pembayaran = $totalHarga;
                    $newPenjualan->tanggal_penjualan = $tanggalPenjualan;
                    $newPenjualan->status_selesai = "belum";
                    $newPenjualan->pelanggan_id = $idPelanggan;
                    $newPenjualan->created_at = date('Y-m-d H:i:s');
                    $newPenjualan->updated_at = date('Y-m-d H:i:s');
                    $newPenjualan->save();

                    foreach ($daftarKaryawanPerawatan as $kp) {
                        //melakukan pemisahan isi dari value yang diperoleh dari form Format: idKaryawan,(arraySlotJamTerpakai)
                        //explode untuk mendapatkan idKaryawan
                        $idKaryawan = explode(",", $kp)[0];

                        //explode untuk mendapatkan idPerawatan
                        $idPerawatan = explode(",", $kp)[1];

                        //explode untuk mendapatkan arraySlotJamTerpakai dalam bentuk string, lalu menghapus karakter "(" dan ")"
                        $arrayStringSlotjam = str_replace(["(", ")"], "", explode(",", $kp)[2]);

                        $newPenjualanPerawatan = new PenjualanPerawatan();
                        $selectedPerawatan = Perawatan::find($idPerawatan);
                        $newPenjualanPerawatan->harga = $selectedPerawatan->harga;
                        $newPenjualanPerawatan->karyawan_id = $idKaryawan;
                        $newPenjualanPerawatan->perawatan_id = $idPerawatan;
                        $newPenjualanPerawatan->penjualan_id = $newPenjualan->id;
                        $newPenjualanPerawatan->created_at = date("Y-m-d H:i:s");
                        $newPenjualanPerawatan->updated_at = date("Y-m-d H:i:s");
                        $newPenjualanPerawatan->save();

                        //explode untuk mendapatkan arraySlotJamTerpakai dalam bentuk array
                        $arraySlotJam = explode(".", $arrayStringSlotjam);

                        foreach ($arraySlotJam as $sj) {
                            $newPenjualanPerawatan->slotjams()->attach($sj);
                        }
                    }

                    foreach ($daftarKaryawanPerawatanKomplemen as $kpk) {

                        //melakukan pemisahan isi dari value yang diperoleh dari form Format: idKaryawan,idPerawatan,(arraySlotJamTerpakai)
                        //explode untuk mendapatkan idKaryawan
                        $idKaryawan = explode(",", $kpk)[0];

                        //explode untuk mendapatkan idPerawatan
                        $idPerawatan = explode(",", $kpk)[1];
                        $perawatan = Perawatan::find($idPerawatan);


                        //explode untuk mendapatkan arraySlotJamTerpakai dalam bentuk string, lalu menghapus karakter "(" dan ")"
                        $arrayStringSlotjam = str_replace(["(", ")"], "", explode(",", $kpk)[2]);


                        $newPenjualanPerawatan = new PenjualanPerawatan();
                        $newPenjualanPerawatan->harga = $perawatan->harga;
                        $newPenjualanPerawatan->karyawan_id = $idKaryawan;
                        $newPenjualanPerawatan->perawatan_id = $idPerawatan;
                        $newPenjualanPerawatan->penjualan_id = $newPenjualan->id;
                        $newPenjualanPerawatan->created_at = date("Y-m-d H:i:s");
                        $newPenjualanPerawatan->updated_at = date("Y-m-d H:i:s");
                        $newPenjualanPerawatan->save();

                        //explode untuk mendapatkan arraySlotJamTerpakai dalam bentuk array
                        $arraySlotJam = explode(".", $arrayStringSlotjam);

                        $durasiPerawatan = $perawatan->durasi;

                        $slotTerpakai = ceil($durasiPerawatan / 30);

                        for ($i = 0; $i < $slotTerpakai; $i++) {
                            $newPenjualanPerawatan->slotjams()->attach($arraySlotJam[$i]);
                        }
                    }

                    //Tambah Detail penjualan produk dari paket
                    if (count($arrayFinalProdukDalamPaketUntukCheck) > 0) {
                        $totalHargaSemuaProdukDalamPaket = 0;
                        foreach ($arrayFinalProdukDalamPaketUntukCheck as $varProduk2) {
                            $produkTerpilih = Produk::find($varProduk2["id"]);
                            $produkTerpilih->stok = $produkTerpilih->stok - $varProduk2["jumlah"];
                            $produkTerpilih->updated_at = date("Y-m-d H:i:s");
                            $produkTerpilih->save();

                            $newPenjualan->produks()->attach($produkTerpilih->id, ['kuantitas' => $varProduk2["jumlah"], 'harga' => $produkTerpilih->harga_jual]);
                            // $totalHargaSemuaProdukDalamPaket += $produkTerpilih->harga_jual * $varProduk2["jumlah"];
                        }
                        // $newPenjualan->total_pembayaran = $newPenjualan->total_pembayaran + $totalHargaSemuaProdukDalamPaket;
                        // $newPenjualan->updated_at = date("Y-m-d H:i:s");
                        // $newPenjualan->save();
                    }

                    //Tambah data penjualan paket
                    if (count($arrayPaket) > 0) {
                        foreach ($arrayPaket as $paketTerpilih) {
                            $newPenjualan->pakets()->attach($paketTerpilih->id, ["harga" => $paketTerpilih->harga]);
                        }
                        $newPenjualan->updated_at = date("Y-m-d H:i:s");
                        $newPenjualan->save();
                    }

                    // $newreservasi = new Reservasi();
                    // $newreservasi->tanggal_reservasi = $tanggalPenjualan;
                    // $newreservasi->tanggal_pembuatan_reservasi = date('Y-m-d H:i:s');
                    // $newreservasi->status = 'baru';
                    // $newreservasi->penjualan_id = $newPenjualan->id;
                    // $newreservasi->created_at = date('Y-m-d H:i:s');
                    // $newreservasi->updated_at = date('Y-m-d H:i:s');
                    // $newreservasi->save();

                    //Masuk ke halaman pilih produk

                    $dataIdPenjualan = $newPenjualan->id; //nanti diganti dengan $dataIDPenjualan, ini sementara saja

                    return redirect()->route('penjualan.admin.penjualantambahproduk', $dataIdPenjualan);


                }
            }

        }
    }

    public function penjualanAdminKonfirmasi(Request $request)
    {
        date_default_timezone_set('Asia/Jakarta');
        $tanggalPenjualan = $request->get("tanggalPenjualan");
        $daftarKaryawanPerawatan = $request->get('selectkaryawan');
        $daftarKaryawanPerawatanKomplemen = $request->get('selectkaryawankomplemen');
        $idSlotJam = $request->get("slotJam");
        $arrPerawatan = $request->get("arrayperawatanid");
        $arrKodeKeseluruhan = $request->get("arraykodekeseluruhan");
        $arrPaket = $request->get("arraypaketid");
        $idPelanggan = $request->get("idPelanggan");

        if ($arrPerawatan == null) {
            $arrPerawatan = [];
        }

        if ($arrPaket == null) {
            $arrPaket = [];
        }

        if ($daftarKaryawanPerawatan == null) {
            $daftarKaryawanPerawatan = [];
        }

        if ($daftarKaryawanPerawatanKomplemen == null) {
            $daftarKaryawanPerawatanKomplemen = [];
        }

        //pengecekan sebelum insert data slot jam dan karyawan untuk memastikan tidak ada pelanggan lain yang reservasi duluan
        $slotJamKaryawanTerpakai = [];
        foreach ($daftarKaryawanPerawatan as $karyawanPerawatan) {
            $idKaryawan = explode(",", $karyawanPerawatan)[0];
            $idPerawatan = explode(",", $karyawanPerawatan)[1];


            $arrayStringSlotjam = str_replace(["(", ")"], "", explode(",", $karyawanPerawatan)[2]);
            $arraySlotJam = explode(".", $arrayStringSlotjam);

            $karyawan = Karyawan::select('karyawans.id')->distinct()
                ->join('penjualan_perawatan', 'penjualan_perawatan.karyawan_id', '=', 'karyawans.id')
                ->join('penjualans', 'penjualans.id', '=', 'penjualan_perawatan.penjualan_id')
                ->join('slot_jam_penjualan_perawatan', 'slot_jam_penjualan_perawatan.penjualan_perawatan_id', '=', 'penjualan_perawatan.id')
                ->join('slot_jams', 'slot_jams.id', '=', 'slot_jam_penjualan_perawatan.slot_jam_id')
                ->whereIn('slot_jams.id', $arraySlotJam)
                ->where('penjualans.tanggal_penjualan', $tanggalPenjualan)
                ->where('karyawans.id', $idKaryawan)
                ->get();

            if (count($karyawan) > 0) {
                $karyawanTerpakai = [];
                $karyawanTerpakai['karyawan'] = Karyawan::find($idKaryawan);
                $karyawanTerpakai['perawatan'] = Perawatan::find($idPerawatan);
                $slotJam = SlotJam::whereIn('id', $arraySlotJam)->get();
                $array = [];
                foreach ($slotJam as $sj) {
                    array_push($array, $sj->jam);
                }
                $karyawanTerpakai['slotjam'] = implode(',', $array);
                array_push($slotJamKaryawanTerpakai, $karyawanTerpakai);
            }
        }
        foreach ($daftarKaryawanPerawatanKomplemen as $karyawanPerawatanKomplemen) {
            $idKaryawan = explode(",", $karyawanPerawatanKomplemen)[0];
            $idPerawatan = explode(",", $karyawanPerawatanKomplemen)[1];
            $perawatan = Perawatan::find($idPerawatan);
            $arrayStringSlotjam = str_replace(["(", ")"], "", explode(",", $karyawanPerawatanKomplemen)[2]);

            $arraySlotJam = explode(".", $arrayStringSlotjam);

            $durasiPerawatan = $perawatan->durasi;

            $slotTerpakai = ceil($durasiPerawatan / 30);
            $daftarSlotjamFinal = [];
            for ($i = 0; $i < $slotTerpakai; $i++) {
                array_push($daftarSlotjamFinal, $arraySlotJam[$i]);
            }

            $karyawan = Karyawan::select('karyawans.id')->distinct()
                ->join('penjualan_perawatan', 'penjualan_perawatan.karyawan_id', '=', 'karyawans.id')
                ->join('penjualans', 'penjualans.id', '=', 'penjualan_perawatan.penjualan_id')
                ->join('slot_jam_penjualan_perawatan', 'slot_jam_penjualan_perawatan.penjualan_perawatan_id', '=', 'penjualan_perawatan.id')
                ->join('slot_jams', 'slot_jams.id', '=', 'slot_jam_penjualan_perawatan.slot_jam_id')
                ->whereIn('slot_jams.id', $daftarSlotjamFinal)
                ->where('penjualans.tanggal_penjualan', $tanggalPenjualan)
                ->where('karyawans.id', $idKaryawan)
                ->get();
            if (count($karyawan) > 0) {
                $karyawanTerpakai = [];
                $karyawanTerpakai['karyawan'] = Karyawan::find($idKaryawan);
                $karyawanTerpakai['perawatan'] = Perawatan::find($idPerawatan);

                $slotJam = SlotJam::whereIn('id', $daftarSlotjamFinal)->get();
                $array = [];
                foreach ($slotJam as $sj) {
                    array_push($array, $sj->jam);
                }
                $karyawanTerpakai['slotjam'] = implode(',', $array);
                array_push($slotJamKaryawanTerpakai, $karyawanTerpakai);
            }
        }


        if (count($slotJamKaryawanTerpakai) > 0) {
            $pesanError = [];
            foreach ($slotJamKaryawanTerpakai as $sjkt) {
                $pesan = "Perawatan atau Paket yang mengandung " . $sjkt['perawatan']->nama . " dengan karyawan " . $sjkt['karyawan']->nama . " pada slot waktu " . $sjkt['slotjam'] . " telah terpakai.";
                array_push($pesanError, $pesan);
            }

            $arrPerawatanSend = [];
            if ($arrPerawatan != null) {
                foreach ($arrPerawatan as $ap) {
                    $p = Perawatan::select('id', 'kode_perawatan', 'nama', 'harga', 'deskripsi', 'durasi')->where('status', 'aktif')->where('id', $ap)->first();
                    array_push($arrPerawatanSend, $p);
                }
            }

            $arrPaketSend = [];
            if ($arrPaket != null) {
                foreach ($arrPaket as $idPaket) {
                    $paketvar = Paket::where('status', 'aktif')->where('id', $idPaket)->first();
                    array_push($arrPaketSend, $paketvar);
                }
            }

            $daftarPelanggans = Pelanggan::all();
            $varSlotJam = SlotJam::find($idSlotJam);
            if ($tanggalPenjualan == date("Y-m-d")) {
                $daftarSlotJam = SlotJam::where('hari', $varSlotJam->hari)->where('jam', ">=", date("H.i"))->orderBy('id')->get();
            } else {
                $daftarSlotJam = SlotJam::where('hari', $varSlotJam->hari)->orderBy('id')->get();
            }
            return redirect()->route('penjualans.admin.create')->with([
                'idPelanggan' => $idPelanggan,
                'daftarPelanggans' => $daftarPelanggans,
                'idSlotJam' => $idSlotJam,
                'daftarSlotJam' => $daftarSlotJam,
                'tanggalPenjualan' => $tanggalPenjualan,
                'arrPerawatan' => $arrPerawatan,
                'arrPerawatanObject' => $arrPerawatanSend,
                'arrPaket' => $arrPaket,
                'arrPaketObject' => $arrPaketSend,
                'arrKodeKeseluruhan' => $arrKodeKeseluruhan,

            ])->withErrors($pesanError);
        }

        //Jika ada paket, dicek lagi jika pada paket mempunyai produk maka perlu dicek ketersediaan stok produk
        $arrayPaket = [];
        foreach ($arrKodeKeseluruhan as $kode) {
            $kodePertama = substr($kode, 0, 1);
            if ($kodePertama == "m") {
                $paketSementara = Paket::where("kode_paket", $kode)->first();
                array_push($arrayPaket, $paketSementara);
            }
        }

        $arrayProdukDalamPaketUntukCheck = [];
        if (count($arrayPaket) > 0) {
            foreach ($arrayPaket as $paket) {
                foreach ($paket->produks as $produkPaket) {
                    $produkPaketSementara = [];
                    $produkPaketSementara["id"] = $produkPaket->id;
                    $produkPaketSementara["jumlah"] = $produkPaket->pivot->jumlah;
                    array_push($arrayProdukDalamPaketUntukCheck, $produkPaketSementara);
                }
            }
        }

        $arrayFinalProdukDalamPaketUntukCheck = [];
        if (count($arrayProdukDalamPaketUntukCheck) > 0) {
            foreach ($arrayProdukDalamPaketUntukCheck as $varProduk) {
                $id = $varProduk["id"];
                $jumlah = $varProduk["jumlah"];

                if (isset($arrayFinalProdukDalamPaketUntukCheck[$id])) {
                    $arrayFinalProdukDalamPaketUntukCheck[$id]["jumlah"] += $jumlah;
                } else {
                    $arrayFinalProdukDalamPaketUntukCheck[$id] = ["id" => $id, "jumlah" => $jumlah];
                }
            }
        }

        if (count($arrayFinalProdukDalamPaketUntukCheck) > 0) {
            $pesanError = [];
            foreach ($arrayFinalProdukDalamPaketUntukCheck as $varProduk1) {
                $objProduk = Produk::find($varProduk1["id"]);
                if ($objProduk->stok < $varProduk1["jumlah"]) {
                    $pesan = "Total Produk " . $objProduk->nama . " dari Paket yang Anda pilih tidak mencukupi dari stok produk yang tersedia saat ini!";
                    array_push($pesanError, $pesan);
                }
            }

            if (count($pesanError) > 0) {
                $arrPerawatanSend = [];
                if ($arrPerawatan != null) {
                    foreach ($arrPerawatan as $ap) {
                        $p = Perawatan::select('id', 'kode_perawatan', 'nama', 'harga', 'deskripsi', 'durasi')->where('status', 'aktif')->where('id', $ap)->first();
                        array_push($arrPerawatanSend, $p);
                    }
                }

                $arrPaketSend = [];
                if ($arrPaket != null) {
                    foreach ($arrPaket as $idPaket) {
                        $paketvar = Paket::where('status', 'aktif')->where('id', $idPaket)->first();
                        array_push($arrPaketSend, $paketvar);
                    }
                }

                $daftarPelanggans = Pelanggan::all();
                $varSlotJam = SlotJam::find($idSlotJam);
                if ($tanggalPenjualan == date("Y-m-d")) {
                    $daftarSlotJam = SlotJam::where('hari', $varSlotJam->hari)->where('jam', ">=", date("H.i"))->orderBy('id')->get();
                } else {
                    $daftarSlotJam = SlotJam::where('hari', $varSlotJam->hari)->orderBy('id')->get();
                }
                return redirect()->route('penjualans.admin.create')->with([
                    'idPelanggan' => $idPelanggan,
                    'daftarPelanggans' => $daftarPelanggans,
                    'idSlotJam' => $idSlotJam,
                    'daftarSlotJam' => $daftarSlotJam,
                    'tanggalPenjualan' => $tanggalPenjualan,
                    'arrPerawatan' => $arrPerawatan,
                    'arrPerawatanObject' => $arrPerawatanSend,
                    'arrPaket' => $arrPaket,
                    'arrPaketObject' => $arrPaketSend,
                    'arrKodeKeseluruhan' => $arrKodeKeseluruhan,

                ])->withErrors($pesanError);
            }
        }
        // Akhir dari pengecekan stok produk

        //-----------------------------------------------------------

        //Mulai Insert Data Reservasi jika ada slot kosong tersedia
        $newPenjualan = new Penjualan();
        $newPenjualan->nomor_nota = $idPelanggan . "/" . (count($daftarKaryawanPerawatan) + count($daftarKaryawanPerawatanKomplemen)) . "/" . date('d') . date('m') . date('Y') . "/" . date("H") . date("i") . date("s");

        $totalHarga = 0;

        $arrIdPaketProduk = [];
        $arrIdPaketPerawatan = [];


        if (count($arrayPaket) > 0) {
            foreach ($arrayPaket as $paketTerpilih) {
                foreach ($paketTerpilih->perawatans as $perawatanPaket) {
                    array_push($arrIdPaketPerawatan, $perawatanPaket->id);
                }

                foreach ($paketTerpilih->produks as $produkPaket) {
                    array_push($arrIdPaketProduk, $produkPaket->id);
                }

                $totalHarga += $paketTerpilih->harga;
            }
        }

        foreach ($daftarKaryawanPerawatan as $karyawanPerawatan) {
            $perawatan = Perawatan::find(explode(",", $karyawanPerawatan)[1]);
            if (!in_array($perawatan->id, $arrIdPaketPerawatan)) {
                $totalHarga += $perawatan->harga;
            }
        }

        foreach ($daftarKaryawanPerawatanKomplemen as $karyawanPerawatan) {
            $perawatan = Perawatan::find(explode(",", $karyawanPerawatan)[1]);
            if (!in_array($perawatan->id, $arrIdPaketPerawatan)) {
                $totalHarga += $perawatan->harga;
            }
        }

        $newPenjualan->total_pembayaran = $totalHarga;
        $newPenjualan->tanggal_penjualan = $tanggalPenjualan;
        $newPenjualan->status_selesai = "belum";
        $newPenjualan->pelanggan_id = $idPelanggan;
        $newPenjualan->created_at = date('Y-m-d H:i:s');
        $newPenjualan->updated_at = date('Y-m-d H:i:s');
        $newPenjualan->save();


        foreach ($daftarKaryawanPerawatan as $kp) {
            //melakukan pemisahan isi dari value yang diperoleh dari form Format: idKaryawan,(arraySlotJamTerpakai)
            //explode untuk mendapatkan idKaryawan
            $idKaryawan = explode(",", $kp)[0];

            //explode untuk mendapatkan idPerawatan
            $idPerawatan = explode(",", $kp)[1];

            //explode untuk mendapatkan arraySlotJamTerpakai dalam bentuk string, lalu menghapus karakter "(" dan ")"
            $arrayStringSlotjam = str_replace(["(", ")"], "", explode(",", $kp)[2]);


            $newPenjualanPerawatan = new PenjualanPerawatan();
            $selectedPerawatan = Perawatan::find($idPerawatan);
            $newPenjualanPerawatan->harga = $selectedPerawatan->harga;
            $newPenjualanPerawatan->karyawan_id = $idKaryawan;
            $newPenjualanPerawatan->perawatan_id = $idPerawatan;
            $newPenjualanPerawatan->penjualan_id = $newPenjualan->id;
            $newPenjualanPerawatan->created_at = date("Y-m-d H:i:s");
            $newPenjualanPerawatan->updated_at = date("Y-m-d H:i:s");
            $newPenjualanPerawatan->save();

            //explode untuk mendapatkan arraySlotJamTerpakai dalam bentuk array
            $arraySlotJam = explode(".", $arrayStringSlotjam);

            foreach ($arraySlotJam as $sj) {
                $newPenjualanPerawatan->slotjams()->attach($sj);
            }
        }

        foreach ($daftarKaryawanPerawatanKomplemen as $kpk) {

            //melakukan pemisahan isi dari value yang diperoleh dari form Format: idKaryawan,idPerawatan,(arraySlotJamTerpakai)
            //explode untuk mendapatkan idKaryawan
            $idKaryawan = explode(",", $kpk)[0];

            //explode untuk mendapatkan idPerawatan
            $idPerawatan = explode(",", $kpk)[1];
            $perawatan = Perawatan::find($idPerawatan);


            //explode untuk mendapatkan arraySlotJamTerpakai dalam bentuk string, lalu menghapus karakter "(" dan ")"
            $arrayStringSlotjam = str_replace(["(", ")"], "", explode(",", $kpk)[2]);


            $newPenjualanPerawatan = new PenjualanPerawatan();
            $newPenjualanPerawatan->harga = $perawatan->harga;
            $newPenjualanPerawatan->karyawan_id = $idKaryawan;
            $newPenjualanPerawatan->perawatan_id = $idPerawatan;
            $newPenjualanPerawatan->penjualan_id = $newPenjualan->id;
            $newPenjualanPerawatan->created_at = date("Y-m-d H:i:s");
            $newPenjualanPerawatan->updated_at = date("Y-m-d H:i:s");
            $newPenjualanPerawatan->save();

            //explode untuk mendapatkan arraySlotJamTerpakai dalam bentuk array
            $arraySlotJam = explode(".", $arrayStringSlotjam);

            $durasiPerawatan = $perawatan->durasi;

            $slotTerpakai = ceil($durasiPerawatan / 30);

            for ($i = 0; $i < $slotTerpakai; $i++) {
                $newPenjualanPerawatan->slotjams()->attach($arraySlotJam[$i]);
            }
        }

        //Tambah Detail penjualan produk dari paket
        if (count($arrayFinalProdukDalamPaketUntukCheck) > 0) {
            $totalHargaSemuaProdukDalamPaket = 0;
            foreach ($arrayFinalProdukDalamPaketUntukCheck as $varProduk2) {
                $produkTerpilih = Produk::find($varProduk2["id"]);
                $produkTerpilih->stok = $produkTerpilih->stok - $varProduk2["jumlah"];
                $produkTerpilih->updated_at = date("Y-m-d H:i:s");
                $produkTerpilih->save();

                $newPenjualan->produks()->attach($produkTerpilih->id, ['kuantitas' => $varProduk2["jumlah"], 'harga' => $produkTerpilih->harga_jual]);
                // $totalHargaSemuaProdukDalamPaket += $produkTerpilih->harga_jual * $varProduk2["jumlah"];
            }
            // $newPenjualan->total_pembayaran = $newPenjualan->total_pembayaran + $totalHargaSemuaProdukDalamPaket;
            // $newPenjualan->updated_at = date("Y-m-d H:i:s");
            // $newPenjualan->save();
        }

        //Tambah data penjualan paket
        if (count($arrayPaket) > 0) {
            foreach ($arrayPaket as $paketTerpilih) {
                $newPenjualan->pakets()->attach($paketTerpilih->id, ["harga" => $paketTerpilih->harga]);
            }
            $newPenjualan->updated_at = date("Y-m-d H:i:s");
            $newPenjualan->save();
        }


        // $newreservasi = new Reservasi();
        // $newreservasi->tanggal_reservasi = $tanggalPenjualan;
        // $newreservasi->tanggal_pembuatan_reservasi = date('Y-m-d H:i:s');
        // $newreservasi->status = 'baru';
        // $newreservasi->penjualan_id = $newPenjualan->id;
        // $newreservasi->created_at = date('Y-m-d H:i:s');
        // $newreservasi->updated_at = date('Y-m-d H:i:s');
        // $newreservasi->save();

        //Masuk ke halaman pilih produk

        $dataIdPenjualan = $newPenjualan->id; //nanti diganti dengan $dataIDPenjualan, ini sementara saja

        return redirect()->route('penjualan.admin.penjualantambahproduk', $dataIdPenjualan);
    }

    public function detailPenjualan($id)
    {
        $idPenjualan = $id;
        $penjualan = Penjualan::find($idPenjualan);

        if ($penjualan == null) {
            return redirect()->route("penjualans.admin.riwayatpenjualan")->withErrors("Tidak terdapat penjualan dengan ID " . $id);
        } else {
            $penjualanPerawatan = $penjualan->penjualanperawatans->sortBy('id');
            $jamMulai = $penjualanPerawatan->first()->slotjams->sortBy('slot_jam_id')->first();
            $slotJamBerubah = $jamMulai;

            $perawatanSlotJamNonKomplemen = [];

            $perawatanNonKomplemen = [];
            $perawatanKomplemen = [];
            foreach ($penjualanPerawatan as $pp) {
                if ($pp->perawatan->status_komplemen == "tidak") {
                    array_push($perawatanNonKomplemen, $pp);
                } else {
                    array_push($perawatanKomplemen, $pp);
                }
            }

            $daftarPaket = [];
            if (count($penjualan->pakets) > 0) {
                foreach ($penjualan->pakets as $paket) {
                    array_push($daftarPaket, $paket);
                }
            }

            if (count($perawatanNonKomplemen) > 0) {
                foreach ($perawatanNonKomplemen as $penjualanPerawatanNonKomplemen) {
                    $perawatanPerSlot = [];
                    $perawatanPerSlot["penjualanperawatannonkomplemen"] = $penjualanPerawatanNonKomplemen;

                    $perawatanPerSlot["namapaket"] = "null";
                    foreach ($daftarPaket as $paket) {
                        if ($paket->perawatans->firstWhere('id', $penjualanPerawatanNonKomplemen->perawatan_id) != null) {
                            $perawatanPerSlot["namapaket"] = $paket->nama;
                            break;
                        }
                    }

                    $perawatanPerSlot["jammulai"] = $slotJamBerubah->jam;

                    $perawatanPerSlot["karyawan"] = $penjualanPerawatanNonKomplemen->karyawan;

                    $jumlahSlotTerpakai = $penjualanPerawatanNonKomplemen->slotjams->count();
                    // $jumlahSlotTerpakai = ceil($perawatan->durasi / 30);
                    $perawatanPerSlot["durasi"] = $jumlahSlotTerpakai * 30;
                    $intJamTerakhir = strtotime("+" . ($jumlahSlotTerpakai * 30) . " minutes", strtotime($slotJamBerubah->jam));

                    // $jamTerakhir = date('H.i', $intJamTerakhir);
                    // dd($jamTerakhir);

                    array_push($perawatanSlotJamNonKomplemen, $perawatanPerSlot);

                    //update slotJamBerubah ke slot selanjutnya
                    // $intJamTerakhir = strtotime("+30 minutes", strtotime($jamTerakhir));
                    $jamTerakhir = date('H.i', $intJamTerakhir);
                    $slotJamBerubah = SlotJam::where('jam', $jamTerakhir)->where('hari', $slotJamBerubah->hari)->first();
                }
            }



            $arrKomplemen = [];
            if (count($perawatanKomplemen) > 0) {
                // $idPerawatanKomplemen = [];
                $arrayPerawatanKomplemen = [];
                $totalSlotPerPerawatanKomplemen = [];
                foreach ($perawatanKomplemen as $penjualanPerawatanKomplemen) {
                    // array_push($idPerawatanKomplemen, $pk->id);
                    $array = [];
                    $array["namapaket"] = "null";
                    foreach ($daftarPaket as $paket) {
                        if ($paket->perawatans->firstWhere('id', $penjualanPerawatanKomplemen->perawatan_id) != null) {
                            $array["namapaket"] = $paket->nama;
                            break;
                        }
                    }
                    $array['penjualanperawatankomplemen'] = $penjualanPerawatanKomplemen;
                    $array["karyawan"] = $penjualanPerawatanKomplemen->karyawan;

                    array_push($arrayPerawatanKomplemen, $array);
                    array_push($totalSlotPerPerawatanKomplemen, $penjualanPerawatanKomplemen->slotjams->count());
                }
                $arrKomplemen['jammulai'] = $slotJamBerubah->jam;


                // $penjualanPerawatans = $reservasi->penjualan->penjualanperawatans;

                // $durasiTerlamaPerawatanKomplemen = Perawatan::where('status_komplemen', 'ya')->whereIn('id', $idPerawatanKomplemen)->max('durasi');

                $arrKomplemen['durasiterlama'] = max($totalSlotPerPerawatanKomplemen) * 30;
                $arrKomplemen['perawatans'] = $arrayPerawatanKomplemen;
            }


            $idDiskonUnikYangSudahPernahDipakai = Penjualan::select("diskon_id")->distinct()->where("pelanggan_id", $penjualan->pelanggan_id)->where("diskon_id", "!=", null)->get();
            $tanggalHariIni = date("Y-m-d");

            //Pencarian Diskon
            $diskonAktifBerlaku = [];
            $daftarPenjualanPaket = $penjualan->pakets;
            if (count($daftarPenjualanPaket) == 0) {
                $arrSemuaIdPaketYangAdaDiskonTertentu = Paket::where("status", "aktif")->where("diskon_id", "!=", null)->get();
                $idUnikPakets = $arrSemuaIdPaketYangAdaDiskonTertentu->pluck("diskon_id")->unique();
                $diskonAktifBerlaku = Diskon::where("status", "aktif")->whereRaw("DATE(tanggal_mulai) <= '" . $tanggalHariIni . "'")->whereRaw("DATE(tanggal_berakhir) >= '" . $tanggalHariIni . "'")->whereNotIn("id", $idDiskonUnikYangSudahPernahDipakai)->whereNotIn("id", $idUnikPakets)->where("minimal_transaksi", "<=", $penjualan->total_pembayaran)->get();
            } else {
                $arrSemuaIdPaketYangAdaDiskonTertentu = Paket::where("status", "aktif")->where("diskon_id", "!=", null)->get();
                $idUnikPakets = $arrSemuaIdPaketYangAdaDiskonTertentu->pluck("diskon_id")->unique();
                $arrDiskonAktifBerlaku = Diskon::where("status", "aktif")->whereRaw("DATE(tanggal_mulai) <= '" . $tanggalHariIni . "'")->whereRaw("DATE(tanggal_berakhir) >= '" . $tanggalHariIni . "'")->whereNotIn("id", $idDiskonUnikYangSudahPernahDipakai)->whereNotIn("id", $idUnikPakets)->where("minimal_transaksi", "<=", $penjualan->total_pembayaran)->get();
                foreach ($arrDiskonAktifBerlaku as $value) {
                    array_push($diskonAktifBerlaku, $value);
                }
                $idDiskonDariPaketsPenjualan = $daftarPenjualanPaket->where("diskon_id", "!=", null)->pluck("diskon_id")->unique();
                foreach ($idDiskonDariPaketsPenjualan as $value) {
                    $diskon = Diskon::find($value);
                    array_push($diskonAktifBerlaku, $diskon);
                }
            }

            $keteranganGantiKaryawan = false;
            $counterTrueGantiKaryawan = 0;

            $karyawansIzinSakit = [];
            $keteranganKaryawanIzinSakit = [];
            foreach ($penjualan->penjualanperawatans as $pp) {
                $karyawanTerpilih = $pp->karyawan;
                $presensiCheck = PresensiKehadiran::where("karyawan_id", $karyawanTerpilih->id)->where(function ($query) {
                    $query->where('keterangan', 'sakit')
                        ->orWhere('keterangan', 'izin');
                })->where("status", "konfirmasi")->whereRaw("DATE(tanggal_presensi) = '" . date("Y-m-d", strtotime($penjualan->tanggal_penjualan)) . "'")->first();

                if ($presensiCheck != null) {
                    $counterTrueGantiKaryawan += 1;
                    array_push($karyawansIzinSakit, $karyawanTerpilih->id);

                    $izinSakit = [];
                    $izinSakit["idKaryawan"] = $karyawanTerpilih->id;
                    $izinSakit["keterangan"] = $presensiCheck->keterangan;
                    array_push($keteranganKaryawanIzinSakit, $izinSakit);
                }
            }

            if ($counterTrueGantiKaryawan > 0) {
                $keteranganGantiKaryawan = true;
            }

            //dd($diskonAktifBerlaku);

            return view('admin.penjualan.detailpenjualan', compact('penjualan', 'jamMulai', 'arrKomplemen', 'perawatanSlotJamNonKomplemen', 'diskonAktifBerlaku', 'keteranganGantiKaryawan', 'karyawansIzinSakit', 'keteranganKaryawanIzinSakit'));
        }



    }


    public function editPilihKaryawanPenjualan(Request $request)
    {
        date_default_timezone_set('Asia/Jakarta');
        $idPenjualan = $request->get("hiddenIdPenjualan");
        $penjualan = Penjualan::find($idPenjualan);

        $tanggalPenjualan = date("Y-m-d", strtotime($penjualan->tanggal_penjualan));
        $idSlotJam = $penjualan->penjualanperawatans()->orderBy('id')->first()->slotjams()->orderBy('slot_jam_id')->first()->id;
        $arrPerawatan = [];
        $arrPerawatanTidakPerluBerubah = [];

        foreach ($penjualan->penjualanperawatans as $pp) {
            $presensiCheck = PresensiKehadiran::where("karyawan_id", $pp->karyawan_id)->where(function ($query) {
                $query->where('keterangan', 'sakit')
                    ->orWhere('keterangan', 'izin');
            })->where("status", "konfirmasi")->whereRaw("DATE(tanggal_presensi) = '" . $tanggalPenjualan . "'")->first();
            if ($presensiCheck == null) {
                array_push($arrPerawatanTidakPerluBerubah, $pp->perawatan_id);
            }
            array_push($arrPerawatan, $pp->perawatan_id);
        }

        $perawatanSlotJamNonKomplemen = [];
        $perawatanSlotJamKomplemen = [];

        //select perawatan komplemen dan non yang id nya sesuai dengan id perawtan yang dipilih dari form buatreservasiadmin
        $perawatanNonKomplemen = [];
        $perawatanKomplemen = [];

        foreach ($arrPerawatan as $idPerawatan) {
            $perawatanSementara = Perawatan::find($idPerawatan);
            if ($perawatanSementara->status_komplemen == "tidak") {
                array_push($perawatanNonKomplemen, $perawatanSementara);
            } else {
                array_push($perawatanKomplemen, $perawatanSementara);
            }
        }

        //cari slot jam mulai dari form buatreservasiadmin
        $slotJamBerubah = SlotJam::find($idSlotJam);

        //BAGIAN PERAWATAN NON KOMPLEMEN-------------------------------------------------------------------------
        //foreach $perawatanNonKomplemen untuk mencari karyawan tersedia untuk setiap perawatan
        if (count($perawatanNonKomplemen) > 0) {
            foreach ($perawatanNonKomplemen as $perawatan) {
                if (in_array($perawatan->id, $arrPerawatanTidakPerluBerubah)) {
                    $penjualanPerawatanTerpilih = $penjualan->penjualanperawatans->firstWhere("perawatan_id", $perawatan->id);
                    $perawatanPerSlot = [];
                    $perawatanPerSlot["perawatan"] = $penjualanPerawatanTerpilih->perawatan;
                    $perawatanPerSlot["jammulai"] = $penjualanPerawatanTerpilih->slotjams()->orderBy("slot_jam_id")->first()->jam;

                    $daftarSlotJam = [];
                    foreach ($penjualanPerawatanTerpilih->slotjams as $slotjam) {
                        array_push($daftarSlotJam, $slotjam->id);
                    }

                    $stringDaftarSlotJam = implode(".", $daftarSlotJam);
                    $perawatanPerSlot['idslotjam'] = $stringDaftarSlotJam;
                    $perawatanPerSlot["karyawans"] = Karyawan::where("id", $penjualanPerawatanTerpilih->karyawan_id)->first();
                    array_push($perawatanSlotJamNonKomplemen, $perawatanPerSlot);


                } else {
                    $perawatanPerSlot = [];

                    $perawatanPerSlot["perawatan"] = $perawatan;
                    $perawatanPerSlot["jammulai"] = $slotJamBerubah->jam;


                    $jumlahSlotTerpakai = $penjualan->penjualanperawatans->firstWhere("perawatan_id", $perawatan->id)->slotjams->count();
                    $intJamTerakhir = strtotime("+" . ($jumlahSlotTerpakai * 30) - 30 . " minutes", strtotime($slotJamBerubah->jam));
                    $jamTerakhir = date('H.i', $intJamTerakhir);
                    $arrIdSlotJamTerpakai = SlotJam::select('id')->where('jam', '>=', $slotJamBerubah->jam)->where('jam', '<=', $jamTerakhir)->where('hari', $slotJamBerubah->hari)->get();
                    $arrObjectSlotJamTerpakai = SlotJam::where('jam', '>=', $slotJamBerubah->jam)->where('jam', '<=', $jamTerakhir)->where('hari', $slotJamBerubah->hari)->get();
                    // dd($jumlahSlotTerpakai, count($arrIdSlotJamTerpakai), $arrIdSlotJamTerpakai);

                    $daftarSlotJam = [];
                    foreach ($arrIdSlotJamTerpakai as $sj) {
                        array_push($daftarSlotJam, $sj->id);
                    }
                    $stringDaftarSlotJam = implode(".", $daftarSlotJam);
                    $perawatanPerSlot['idslotjam'] = $stringDaftarSlotJam;

                    $arrIdKaryawanTerpakaiSlotJam = Karyawan::select('karyawans.id')->distinct()
                        ->join('karyawan_perawatan', 'karyawan_perawatan.karyawan_id', '=', 'karyawans.id')
                        ->join('penjualan_perawatan', 'penjualan_perawatan.karyawan_id', '=', 'karyawans.id')
                        ->join('penjualans', 'penjualans.id', '=', 'penjualan_perawatan.penjualan_id')
                        ->join('slot_jam_penjualan_perawatan', 'slot_jam_penjualan_perawatan.penjualan_perawatan_id', '=', 'penjualan_perawatan.id')
                        ->join('slot_jams', 'slot_jams.id', '=', 'slot_jam_penjualan_perawatan.slot_jam_id')
                        ->whereIn('slot_jams.id', $arrIdSlotJamTerpakai)
                        ->where('penjualans.tanggal_penjualan', $tanggalPenjualan)
                        ->where('penjualans.status_selesai', 'belum')
                        ->get();

                    $arrIdKaryawanIzinSakit = Karyawan::select('karyawans.id')->distinct()
                        ->join('presensi_kehadiran', 'presensi_kehadiran.karyawan_id', '=', 'karyawans.id')
                        ->where(function ($query) {
                            $query->where('presensi_kehadiran.keterangan', 'izin')->orWhere('presensi_kehadiran.keterangan', 'sakit');
                        })->whereRaw("DATE(presensi_kehadiran.tanggal_presensi) = '" . $tanggalPenjualan . "'")->where("presensi_kehadiran.status", "konfirmasi")->get();

                    // $idKaryawan
                    $karyawanTersedia = Karyawan::select('karyawans.*')->join('karyawan_perawatan', 'karyawan_perawatan.karyawan_id', '=', 'karyawans.id')
                        ->join('perawatans', 'perawatans.id', '=', 'karyawan_perawatan.perawatan_id')
                        ->whereNotIn('karyawans.id', $arrIdKaryawanTerpakaiSlotJam)->whereNotIn('karyawans.id', $arrIdKaryawanIzinSakit)->where('perawatans.id', $perawatan->id)->get();


                    $perawatanPerSlot["karyawans"] = $karyawanTersedia;
                    array_push($perawatanSlotJamNonKomplemen, $perawatanPerSlot);

                    //update slotJamBerubah ke slot selanjutnya
                    $intJamTerakhir = strtotime("+30 minutes", strtotime($jamTerakhir));
                    $jamTerakhir = date('H.i', $intJamTerakhir);
                    $slotJamBerubah = SlotJam::where('jam', $jamTerakhir)->where('hari', $slotJamBerubah->hari)->first();
                }

            }
        }


        //BAGIAN PERAWATAN KOMPLEMEN-------------------------------------------------------------------------
        $arrKomplemen = [];
        $arrKomplemen["array"] = $perawatanSlotJamKomplemen;

        if (count($perawatanKomplemen) > 0) {
            $arrKomplemen['jammulai'] = $slotJamBerubah->jam;

            $arrSlotJamPerawatanKomplemen = [];
            foreach ($perawatanKomplemen as $pk) {
                $totalSlotTerpakaiPerPerawatanKomplemen = $penjualan->penjualanperawatans->firstWhere("perawatan_id", $pk->id)->slotjams->count();
                array_push($arrSlotJamPerawatanKomplemen, $totalSlotTerpakaiPerPerawatanKomplemen);
            }

            $durasiTerlamaPerawatanKomplemen = max($arrSlotJamPerawatanKomplemen) * 30;
            $arrKomplemen['durasiterlama'] = $durasiTerlamaPerawatanKomplemen;
            $jumlahSlotTerpakai = ceil($durasiTerlamaPerawatanKomplemen / 30);
            $intJamTerakhir = strtotime("+" . ($jumlahSlotTerpakai * 30) - 30 . " minutes", strtotime($slotJamBerubah->jam));
            $jamTerakhir = date('H.i', $intJamTerakhir);

            $arrIdSlotJamTerpakai = SlotJam::select('id')->where('jam', '>=', $slotJamBerubah->jam)->where('jam', '<=', $jamTerakhir)->where('hari', $slotJamBerubah->hari)->get();
            $arrObjectSlotJamTerpakai = SlotJam::where('jam', '>=', $slotJamBerubah->jam)->where('jam', '<=', $jamTerakhir)->where('hari', $slotJamBerubah->hari)->get();

            $daftarSlotJam = [];
            foreach ($arrIdSlotJamTerpakai as $sj) {
                array_push($daftarSlotJam, $sj->id);
            }
            $stringDaftarSlotJam = implode(".", $daftarSlotJam);
            $arrKomplemen['idslotjam'] = $stringDaftarSlotJam;

            $arrIdKaryawanTerpakaiSlotJam = Karyawan::select('karyawans.id')->distinct()
                ->join('karyawan_perawatan', 'karyawan_perawatan.karyawan_id', '=', 'karyawans.id')
                ->join('penjualan_perawatan', 'penjualan_perawatan.karyawan_id', '=', 'karyawans.id')
                ->join('penjualans', 'penjualans.id', '=', 'penjualan_perawatan.penjualan_id')
                ->join('slot_jam_penjualan_perawatan', 'slot_jam_penjualan_perawatan.penjualan_perawatan_id', '=', 'penjualan_perawatan.id')
                ->join('slot_jams', 'slot_jams.id', '=', 'slot_jam_penjualan_perawatan.slot_jam_id')
                ->whereIn('slot_jams.id', $arrIdSlotJamTerpakai)
                ->where('penjualans.tanggal_penjualan', $tanggalPenjualan)
                ->where('penjualans.status_selesai', 'belum')
                ->get();

            $arrIdKaryawanIzinSakit = Karyawan::select('karyawans.id')->distinct()
                ->join('presensi_kehadiran', 'presensi_kehadiran.karyawan_id', '=', 'karyawans.id')
                ->where(function ($query) {
                    $query->where('presensi_kehadiran.keterangan', 'izin')->orWhere('presensi_kehadiran.keterangan', 'sakit');
                })->whereRaw("DATE(presensi_kehadiran.tanggal_presensi) = '" . $tanggalPenjualan . "'")->where("presensi_kehadiran.status", "konfirmasi")->get();


            foreach ($perawatanKomplemen as $perawatanK) {
                if (in_array($perawatanK->id, $arrPerawatanTidakPerluBerubah)) {

                    $penjualanPerawatanTerpilih = $penjualan->penjualanperawatans->firstWhere("perawatan_id", $perawatanK->id);

                    $perawatanPerSlot = [];
                    $perawatanPerSlot["perawatan"] = $perawatanK;
                    $karyawanTersedia = Karyawan::select('karyawans.*')->join('karyawan_perawatan', 'karyawan_perawatan.karyawan_id', '=', 'karyawans.id')
                        ->join('perawatans', 'perawatans.id', '=', 'karyawan_perawatan.perawatan_id')
                        ->whereNotIn('karyawans.id', $arrIdKaryawanTerpakaiSlotJam)->whereNotIn('karyawans.id', $arrIdKaryawanIzinSakit)->where('perawatans.id', $perawatanK->id)->get();


                    $perawatanPerSlot["karyawans"] = Karyawan::where("id", $penjualanPerawatanTerpilih->karyawan_id)->get();
                    array_push($perawatanSlotJamKomplemen, $perawatanPerSlot);
                } else {
                    $karyawanPerawatanIni = [];
                    foreach ($perawatanK->karyawans as $k) {
                        array_push($karyawanPerawatanIni, $k->id);
                    }

                    $idSlotJam = [];
                    $daftarPenjualanPerawatanKomplemen = $penjualan->penjualanperawatans->filter(function ($penjualanPerawatan) {
                        return $penjualanPerawatan->perawatan->status_komplemen == "ya";
                    });
                    foreach ($daftarPenjualanPerawatanKomplemen as $dpk) {
                        $minSlotJam = $dpk->slotjams()->orderBy('slot_jam_id')->first()->id;
                        array_push($idSlotJam, $minSlotJam);
                    }

                    $minIdSlotJam = min($idSlotJam);
                    $arrayKaryawanIzinSakit = [];
                    foreach ($arrIdKaryawanIzinSakit as $idKaryawan) {
                        array_push($arrayKaryawanIzinSakit, $idKaryawan->id);
                    }

                    $karyawanYangTetapBisaDipilihLagi = [];
                    foreach ($karyawanPerawatanIni as $kpi) {
                        $penjualanPerawatanIni = $daftarPenjualanPerawatanKomplemen->firstWhere("karyawan_id", $kpi);

                        if (!in_array($kpi, $arrayKaryawanIzinSakit) && $penjualanPerawatanIni != null && $penjualanPerawatanIni->slotjams()->orderBy('slot_jam_id')->first()->id == $minIdSlotJam) {
                            array_push($karyawanYangTetapBisaDipilihLagi, $kpi);
                        }
                    }

                    $arrIdKaryawanTerpakaiSlotJamSementara = $arrIdKaryawanTerpakaiSlotJam;

                    foreach ($arrIdKaryawanTerpakaiSlotJamSementara as $key => $idTerpakai) {
                        if (in_array($idTerpakai->id, $karyawanYangTetapBisaDipilihLagi)) {
                            unset($arrIdKaryawanTerpakaiSlotJamSementara[$key]);
                        }
                    }

                    $perawatanPerSlot = [];
                    $perawatanPerSlot["perawatan"] = $perawatanK;
                    $karyawanTersedia = Karyawan::select('karyawans.*')->join('karyawan_perawatan', 'karyawan_perawatan.karyawan_id', '=', 'karyawans.id')
                        ->join('perawatans', 'perawatans.id', '=', 'karyawan_perawatan.perawatan_id')
                        ->whereNotIn('karyawans.id', $arrIdKaryawanTerpakaiSlotJamSementara)->whereNotIn('karyawans.id', $arrIdKaryawanIzinSakit)->where('perawatans.id', $perawatanK->id)->get();


                    $perawatanPerSlot["karyawans"] = $karyawanTersedia;
                    array_push($perawatanSlotJamKomplemen, $perawatanPerSlot);
                }

            }
            $arrKomplemen['array'] = $perawatanSlotJamKomplemen;
        }


        $keteranganNull = 0;

        foreach ($perawatanSlotJamNonKomplemen as $ps) {
            if (count($ps['karyawans']) == 0) {
                $keteranganNull += 1;
            }
        }
        foreach ($arrKomplemen['array'] as $ps) {
            if (count($ps['karyawans']) == 0) {
                $keteranganNull += 1;
            }
        }
        if ($keteranganNull > 0) {
            $pesanError = "Terdapat perawatan yang memiliki karyawan yang tidak tersedia pada jam tersebut. Silahkan menghubungi pihak Admin Salon!";
            return view('admin.penjualan.editpilihkaryawan', compact('perawatanSlotJamNonKomplemen', 'arrKomplemen', 'idPenjualan', 'arrPerawatanTidakPerluBerubah', 'pesanError'));
        } else {
            return view('admin.penjualan.editpilihkaryawan', compact('perawatanSlotJamNonKomplemen', 'arrKomplemen', 'arrPerawatanTidakPerluBerubah', 'idPenjualan'));
        }
    }

    public function konfirmasiEditPilihKaryawanPenjualan(Request $request)
    {
        date_default_timezone_set('Asia/Jakarta');
        $idPenjualan = $request->get("hiddenIdPenjualan");
        $penjualan = Penjualan::find($idPenjualan);
        $daftarKaryawanPerawatan = $request->get('selectkaryawan');
        $daftarKaryawanPerawatanKomplemen = $request->get('selectkaryawankomplemen');

        if ($daftarKaryawanPerawatan == null) {
            $daftarKaryawanPerawatan = [];
        }

        if ($daftarKaryawanPerawatanKomplemen == null) {
            $daftarKaryawanPerawatanKomplemen = [];
        }

        $slotJamKaryawanTerpakai = [];
        foreach ($daftarKaryawanPerawatan as $karyawanPerawatan) {
            $idKaryawan = explode(",", $karyawanPerawatan)[0];
            $idPerawatan = explode(",", $karyawanPerawatan)[1];


            $arrayStringSlotjam = str_replace(["(", ")"], "", explode(",", $karyawanPerawatan)[2]);
            $arraySlotJam = explode(".", $arrayStringSlotjam);

            $karyawan = Karyawan::select('karyawans.id')->distinct()
                ->join('penjualan_perawatan', 'penjualan_perawatan.karyawan_id', '=', 'karyawans.id')
                ->join('penjualans', 'penjualans.id', '=', 'penjualan_perawatan.penjualan_id')
                ->join('slot_jam_penjualan_perawatan', 'slot_jam_penjualan_perawatan.penjualan_perawatan_id', '=', 'penjualan_perawatan.id')
                ->join('slot_jams', 'slot_jams.id', '=', 'slot_jam_penjualan_perawatan.slot_jam_id')
                ->whereIn('slot_jams.id', $arraySlotJam)
                ->where('penjualans.tanggal_penjualan', $penjualan->tanggal_penjualan)
                ->where('karyawans.id', $idKaryawan)
                ->get();

            if (count($karyawan) > 0) {
                $karyawanTerpakai = [];
                $karyawanTerpakai['karyawan'] = Karyawan::find($idKaryawan);
                $karyawanTerpakai['perawatan'] = Perawatan::find($idPerawatan);
                $slotJam = SlotJam::whereIn('id', $arraySlotJam)->get();
                $array = [];
                foreach ($slotJam as $sj) {
                    array_push($array, $sj->jam);
                }
                $karyawanTerpakai['slotjam'] = implode(',', $array);
                array_push($slotJamKaryawanTerpakai, $karyawanTerpakai);
            }
        }

        foreach ($daftarKaryawanPerawatanKomplemen as $karyawanPerawatanKomplemen) {
            $idKaryawan = explode(",", $karyawanPerawatanKomplemen)[0];
            $idPerawatan = explode(",", $karyawanPerawatanKomplemen)[1];
            $perawatan = Perawatan::find($idPerawatan);
            $arrayStringSlotjam = str_replace(["(", ")"], "", explode(",", $karyawanPerawatanKomplemen)[2]);

            $arraySlotJam = explode(".", $arrayStringSlotjam);

            $durasiPerawatan = $perawatan->durasi;

            $slotTerpakai = ceil($durasiPerawatan / 30);
            $daftarSlotjamFinal = [];
            for ($i = 0; $i < $slotTerpakai; $i++) {
                array_push($daftarSlotjamFinal, $arraySlotJam[$i]);
            }

            $karyawan = Karyawan::select('karyawans.id')->distinct()
                ->join('penjualan_perawatan', 'penjualan_perawatan.karyawan_id', '=', 'karyawans.id')
                ->join('penjualans', 'penjualans.id', '=', 'penjualan_perawatan.penjualan_id')
                ->join('slot_jam_penjualan_perawatan', 'slot_jam_penjualan_perawatan.penjualan_perawatan_id', '=', 'penjualan_perawatan.id')
                ->join('slot_jams', 'slot_jams.id', '=', 'slot_jam_penjualan_perawatan.slot_jam_id')
                ->whereIn('slot_jams.id', $daftarSlotjamFinal)
                ->where('penjualans.tanggal_penjualan', $penjualan->tanggal_penjualan)
                ->where('karyawans.id', $idKaryawan)
                ->get();

            // $karyawanPerawatanIni = [];
            // foreach ($perawatanK->karyawans as $k) {
            //     array_push($karyawanPerawatanIni, $k->id);
            // }

            $idSlotJam = [];
            $daftarPenjualanPerawatanKomplemen = $penjualan->penjualanperawatans->filter(function ($penjualanPerawatan) {
                return $penjualanPerawatan->perawatan->status_komplemen == "ya";
            });
            foreach ($daftarPenjualanPerawatanKomplemen as $dpk) {
                $minSlotJam = $dpk->slotjams()->orderBy('slot_jam_id')->first()->id;
                array_push($idSlotJam, $minSlotJam);
            }

            $minIdSlotJam = min($idSlotJam);


            $karyawanYangTetapBisaDipilihLagi = [];
            $penjualanPerawatanIni = $daftarPenjualanPerawatanKomplemen->firstWhere("karyawan_id", $idKaryawan);

            if ($penjualanPerawatanIni != null && $penjualanPerawatanIni->slotjams()->orderBy('slot_jam_id')->first()->id == $minIdSlotJam) {
                $karyawan = [];
            }

            if (count($karyawan) > 0) {
                $karyawanTerpakai = [];
                $karyawanTerpakai['karyawan'] = Karyawan::find($idKaryawan);
                $karyawanTerpakai['perawatan'] = Perawatan::find($idPerawatan);

                $slotJam = SlotJam::whereIn('id', $daftarSlotjamFinal)->get();
                $array = [];
                foreach ($slotJam as $sj) {
                    array_push($array, $sj->jam);
                }
                $karyawanTerpakai['slotjam'] = implode(',', $array);
                array_push($slotJamKaryawanTerpakai, $karyawanTerpakai);
            }
        }


        if (count($slotJamKaryawanTerpakai) > 0) {
            $pesanError = [];
            foreach ($slotJamKaryawanTerpakai as $sjkt) {
                $pesan = "Perawatan atau Paket yang mengandung " . $sjkt['perawatan']->nama . " dengan karyawan " . $sjkt['karyawan']->nama . " pada slot waktu " . $sjkt['slotjam'] . " telah terpakai.";
                array_push($pesanError, $pesan);
            }

            return redirect()->route('penjualans.admin.detailpenjualan', $penjualan->id)->withErrors($pesanError);
        }

        //Selesai pengecekan tidak ada yang reservasi slot itu duluan//---------------------------


        foreach ($daftarKaryawanPerawatan as $kp) {
            //melakukan pemisahan isi dari value yang diperoleh dari form Format: idKaryawan,(arraySlotJamTerpakai)
            //explode untuk mendapatkan idKaryawan
            $idKaryawan = explode(",", $kp)[0];

            //explode untuk mendapatkan idPerawatan
            $idPerawatan = explode(",", $kp)[1];

            $penjualanPerawatanTerpilih = $penjualan->penjualanperawatans->where("perawatan_id", $idPerawatan)->first();

            $penjualanPerawatanTerbaru = PenjualanPerawatan::find($penjualanPerawatanTerpilih->id);

            $penjualanPerawatanTerbaru->karyawan_id = $idKaryawan;
            $penjualanPerawatanTerbaru->updated_at = date("Y-m-d H:i:s");
            $penjualanPerawatanTerbaru->save();

        }

        foreach ($daftarKaryawanPerawatanKomplemen as $kpk) {

            //melakukan pemisahan isi dari value yang diperoleh dari form Format: idKaryawan,idPerawatan,(arraySlotJamTerpakai)
            //explode untuk mendapatkan idKaryawan
            $idKaryawan = explode(",", $kpk)[0];

            //explode untuk mendapatkan idPerawatan
            $idPerawatan = explode(",", $kpk)[1];

            $penjualanPerawatanTerpilih = $penjualan->penjualanperawatans->where("perawatan_id", $idPerawatan)->first();

            $penjualanPerawatanTerbaru = PenjualanPerawatan::find($penjualanPerawatanTerpilih->id);

            $penjualanPerawatanTerbaru->karyawan_id = $idKaryawan;
            $penjualanPerawatanTerbaru->updated_at = date("Y-m-d H:i:s");
            $penjualanPerawatanTerbaru->save();
        }

        return redirect()->route('penjualans.admin.detailpenjualan', $penjualan->id)->with('status', 'Berhasil mengedit pemilihan karyawan!');
    }


    public function riwayatPenjualan()
    {
        date_default_timezone_set('Asia/Jakarta');
        $hariIndonesia = array('Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu');
        $tanggalPenjualan = Penjualan::selectRaw("DISTINCT DATE(tanggal_penjualan) as tanggal_penjualan")->whereRaw(" DATE(tanggal_penjualan) < '" . date("Y-m-d") . "'")->orderByRaw('DATE(tanggal_penjualan) desc')->get();

        $arrDaftarRiwayatPenjualan = [];
        foreach ($tanggalPenjualan as $tr) {


            $arrPenjualanSementara = Penjualan::whereRaw(" DATE(tanggal_penjualan) = '" . $tr->tanggal_penjualan . "'")->get();
            $arrPenjualan = $arrPenjualanSementara->filter(function ($penjualan) {
                return $penjualan->reservasi == null;
            });

            if (count($arrPenjualan) == 0) {
                continue;
            } else {
                $objectRiwayat = [];
                $nomorHariDalamMingguan = date("w", strtotime($tr->tanggal_penjualan));
                $tanggal = $hariIndonesia[$nomorHariDalamMingguan] . ", " . date('d-m-Y', strtotime($tr->tanggal_penjualan));
                $objectRiwayat["tanggalpenjualan"] = $tanggal;

                $objectRiwayat["tanggal"] = $tr->tanggal_penjualan;

                $objectRiwayat["penjualans"] = $arrPenjualan;

                $objectRiwayat["jumlahpenjualan"] = count($arrPenjualan);

                $totalPenjualanPerawatan = 0;
                $totalPenjualanProduk = 0;
                $totalPotonganDiskon = 0;
                $totalPenjualanPaket = 0;
                foreach ($arrPenjualan as $penjualan) {
                    if ($penjualan->status_selesai == "selesai") {

                        $idPaketPerawatan = [];
                        $idPaketProduk = [];

                        if (count($penjualan->pakets) > 0) {
                            foreach ($penjualan->pakets as $paket) {
                                $totalPenjualanPaket += $paket->pivot->harga;
                                foreach ($paket->perawatans as $perawatan) {
                                    array_push($idPaketPerawatan, $perawatan->id);
                                }
                                foreach ($paket->produks as $produk) {
                                    array_push($idPaketProduk, $produk->id);
                                }
                            }
                        }


                        foreach ($penjualan->penjualanperawatans as $p) {
                            if (!in_array($p->perawatan_id, $idPaketPerawatan)) {
                                $totalPenjualanPerawatan += $p->harga;
                            }

                        }

                        foreach ($penjualan->produks as $p) {
                            if (!in_array($p->id, $idPaketProduk)) {
                                $totalPenjualanProduk += $p->pivot->kuantitas * $p->pivot->harga;
                            } else {
                                $totalKeseluruhan = $p->pivot->kuantitas;
                                $totalDariPaket = 0;
                                foreach ($penjualan->pakets as $paket) {
                                    if ($paket->produks->firstWhere("id", $p->id) != null) {
                                        $totalDariPaket += $paket->produks->firstWhere("id", $p->id)->pivot->jumlah;
                                    }
                                }

                                if ($totalKeseluruhan > $totalDariPaket) {
                                    $totalHargaSisaDiluarPaket = ($totalKeseluruhan - $totalDariPaket) * $p->pivot->harga;
                                    $totalPenjualanProduk += $totalHargaSisaDiluarPaket;
                                }
                            }
                        }

                        $totalHargaPerawatanPerPenjualan = 0;
                        $totalHargaProdukPerPenjualan = 0;
                        $totalHargaPaketPerPenjualan = 0;

                        foreach ($penjualan->penjualanperawatans as $pper) {
                            if (!in_array($pper->perawatan_id, $idPaketPerawatan)) {
                                $totalHargaPerawatanPerPenjualan += $pper->harga;
                            }
                        }

                        foreach ($penjualan->produks as $ppro) {
                            if (!in_array($ppro->id, $idPaketProduk)) {
                                $totalHargaProdukPerPenjualan += $ppro->pivot->kuantitas * $ppro->pivot->harga;
                            }

                        }

                        if (count($penjualan->pakets) > 0) {
                            foreach ($penjualan->pakets as $paket) {
                                $totalHargaPaketPerPenjualan += $paket->pivot->harga;
                            }
                        }

                        //Hitung Total potogan Diskon

                        if ($penjualan->diskon_id != null) {
                            $objDiskon = $penjualan->diskon;
                            $totalPotonganDiskonPerPenjualan = (($totalHargaPerawatanPerPenjualan + $totalHargaProdukPerPenjualan + $totalHargaPaketPerPenjualan) * $objDiskon->jumlah_potongan) / 100;

                            if ($totalPotonganDiskonPerPenjualan > $objDiskon->maksimum_potongan) {
                                $totalPotonganDiskonPerPenjualan = $objDiskon->maksimum_potongan;
                            }

                            $totalPotonganDiskon += $totalPotonganDiskonPerPenjualan;
                        }
                    }

                }

                $objectRiwayat["totalpenjualanproduk"] = $totalPenjualanProduk;

                $objectRiwayat["totalpenjualanperawatan"] = $totalPenjualanPerawatan;

                $objectRiwayat["totalpenjualanpaket"] = $totalPenjualanPaket;

                $objectRiwayat["totalpotongandiskon"] = $totalPotonganDiskon;

                $objectRiwayat["totalpembayaran"] = $totalPenjualanProduk + $totalPenjualanPerawatan + $totalPenjualanPaket - $totalPotonganDiskon;

                array_push($arrDaftarRiwayatPenjualan, $objectRiwayat);
            }

        }

        return view("admin.penjualan.riwayatpenjualan", compact("arrDaftarRiwayatPenjualan"));

    }

    public function getDetailRiwayatPenjualan()
    {
        $tanggal = $_POST['tanggal'];
        $arrPenjualanSementara = Penjualan::whereRaw(" DATE(tanggal_penjualan) = '" . $tanggal . "'")->get();
        $riwayatPenjualans = $arrPenjualanSementara->filter(function ($penjualan) {
            return $penjualan->reservasi == null;
        });
        return response()->json(array('msg' => view('admin.penjualan.detailriwayatpenjualan', compact('riwayatPenjualans'))->render()), 200);
    }

    public function adminBatalkanPenjualan(Request $request)
    {
        date_default_timezone_set('Asia/Jakarta');
        $idPenjualan = $request->get('idPenjualanBatal');
        $penjualanTerpilih = Penjualan::find($idPenjualan);

        if (count($penjualanTerpilih->produks) > 0) {
            foreach ($penjualanTerpilih->produks as $produk) {
                $produkTerpilih = Produk::find($produk->id);
                $produkTerpilih->stok = $produkTerpilih->stok + $produk->pivot->kuantitas;
                $produkTerpilih->updated_at = date("Y-m-d H:i:s");
                $produkTerpilih->save();
                // $penjualanTerpilih->produks()->detach($produk);
            }
        }

        $penjualanTerpilih->status_selesai = "batal";
        $penjualanTerpilih->updated_at = date("Y-m-d H:i:s");
        $penjualanTerpilih->save();

        $emailPelanggan = $penjualanTerpilih->pelanggan->user->email;
        $namaPelanggan = $penjualanTerpilih->pelanggan->nama;
        $pesanEmail = "Mohon maaf untuk penjualan Anda kami batalkan.";
        $nomorNota = $penjualanTerpilih->nomor_nota;

        MailController::mailBatalReservasiAdmin($emailPelanggan, $namaPelanggan, $pesanEmail, $nomorNota);

        return redirect()->route('penjualans.admin.detailpenjualan', $penjualanTerpilih->id)->with('status', 'Berhasil membatalkan penjualan!');
    }

    public function adminSelesaiPenjualan(Request $request)
    {
        date_default_timezone_set('Asia/Jakarta');
        $idPenjualan = $request->get('idPenjualanSelesai');
        $selectedPenjualan = Penjualan::find($idPenjualan);

        $selectedPenjualan->status_selesai = "selesai";
        $selectedPenjualan->updated_at = date("Y-m-d H:i:s");
        $selectedPenjualan->save();

        return redirect()->route('penjualans.admin.detailpenjualan', $selectedPenjualan->id)->with('status', 'Berhasil menyelesaikan penjualan!');
    }

    public function daftarPenjualanKeseluruhan()
    {
        date_default_timezone_set('Asia/Jakarta');
        $hariIndonesia = array('Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu');
        $tanggalPenjualan = Penjualan::selectRaw("DISTINCT DATE(tanggal_penjualan) as tanggal_penjualan")->whereRaw(" DATE(tanggal_penjualan) <= '" . date("Y-m-d") . "'")->orderByRaw('DATE(tanggal_penjualan) desc')->get();

        $arrDaftarRiwayatPenjualan = [];
        foreach ($tanggalPenjualan as $tr) {
            $arrPenjualan = Penjualan::whereRaw(" DATE(tanggal_penjualan) = '" . $tr->tanggal_penjualan . "'")->get();
            // $arrPenjualan = $arrPenjualanSementara->filter(function ($penjualan) {
            //     return $penjualan->reservasi == null;
            // });

            $objectRiwayat = [];
            $nomorHariDalamMingguan = date("w", strtotime($tr->tanggal_penjualan));
            $tanggal = $hariIndonesia[$nomorHariDalamMingguan] . ", " . date('d-m-Y', strtotime($tr->tanggal_penjualan));
            $objectRiwayat["tanggalpenjualan"] = $tanggal;

            $objectRiwayat["tanggal"] = $tr->tanggal_penjualan;

            $objectRiwayat["penjualans"] = $arrPenjualan;

            $objectRiwayat["jumlahpenjualan"] = count($arrPenjualan);

            $objectRiwayat["jumlahpenjualanselesai"] = $arrPenjualan->where("status_selesai", "selesai")->count();

            $totalPenjualanPerawatan = 0;
            $totalPenjualanProduk = 0;
            $totalPotonganDiskon = 0;
            $totalPenjualanPaket = 0;
            foreach ($arrPenjualan as $penjualan) {
                if ($penjualan->status_selesai == "selesai") {

                    $idPaketPerawatan = [];
                    $idPaketProduk = [];

                    if (count($penjualan->pakets) > 0) {
                        foreach ($penjualan->pakets as $paket) {
                            $totalPenjualanPaket += $paket->pivot->harga;
                            foreach ($paket->perawatans as $perawatan) {
                                array_push($idPaketPerawatan, $perawatan->id);
                            }
                            foreach ($paket->produks as $produk) {
                                array_push($idPaketProduk, $produk->id);
                            }
                        }
                    }


                    foreach ($penjualan->penjualanperawatans as $p) {
                        if (!in_array($p->perawatan_id, $idPaketPerawatan)) {
                            $totalPenjualanPerawatan += $p->harga;
                        }

                    }

                    foreach ($penjualan->produks as $p) {
                        if (!in_array($p->id, $idPaketProduk)) {
                            $totalPenjualanProduk += $p->pivot->kuantitas * $p->pivot->harga;
                        } else {
                            $totalKeseluruhan = $p->pivot->kuantitas;
                            $totalDariPaket = 0;
                            foreach ($penjualan->pakets as $paket) {
                                if ($paket->produks->firstWhere("id", $p->id) != null) {
                                    $totalDariPaket += $paket->produks->firstWhere("id", $p->id)->pivot->jumlah;
                                }
                            }

                            if ($totalKeseluruhan > $totalDariPaket) {
                                $totalHargaSisaDiluarPaket = ($totalKeseluruhan - $totalDariPaket) * $p->pivot->harga;
                                $totalPenjualanProduk += $totalHargaSisaDiluarPaket;
                            }
                        }
                    }

                    $totalHargaPerawatanPerPenjualan = 0;
                    $totalHargaProdukPerPenjualan = 0;
                    $totalHargaPaketPerPenjualan = 0;

                    foreach ($penjualan->penjualanperawatans as $pper) {
                        if (!in_array($pper->perawatan_id, $idPaketPerawatan)) {
                            $totalHargaPerawatanPerPenjualan += $pper->harga;
                        }
                    }

                    foreach ($penjualan->produks as $ppro) {
                        if (!in_array($ppro->id, $idPaketProduk)) {
                            $totalHargaProdukPerPenjualan += $ppro->pivot->kuantitas * $ppro->pivot->harga;
                        }

                    }

                    if (count($penjualan->pakets) > 0) {
                        foreach ($penjualan->pakets as $paket) {
                            $totalHargaPaketPerPenjualan += $paket->pivot->harga;
                        }
                    }

                    //Hitung Total potogan Diskon

                    if ($penjualan->diskon_id != null) {
                        $objDiskon = $penjualan->diskon;
                        $totalPotonganDiskonPerPenjualan = (($totalHargaPerawatanPerPenjualan + $totalHargaProdukPerPenjualan + $totalHargaPaketPerPenjualan) * $objDiskon->jumlah_potongan) / 100;

                        if ($totalPotonganDiskonPerPenjualan > $objDiskon->maksimum_potongan) {
                            $totalPotonganDiskonPerPenjualan = $objDiskon->maksimum_potongan;
                        }

                        $totalPotonganDiskon += $totalPotonganDiskonPerPenjualan;
                    }
                }

            }

            $objectRiwayat["totalpenjualanproduk"] = $totalPenjualanProduk;

            $objectRiwayat["totalpenjualanperawatan"] = $totalPenjualanPerawatan;

            $objectRiwayat["totalpenjualanpaket"] = $totalPenjualanPaket;

            $objectRiwayat["totalpotongandiskon"] = $totalPotonganDiskon;

            $objectRiwayat["totalpembayaran"] = $totalPenjualanProduk + $totalPenjualanPerawatan + $totalPenjualanPaket - $totalPotonganDiskon;

            array_push($arrDaftarRiwayatPenjualan, $objectRiwayat);

        }

        return view("admin.penjualan.riwayatpenjualankeseluruhan", compact("arrDaftarRiwayatPenjualan"));
    }

    public function detailPenjualanKeseluruhan()
    {
        $tanggal = $_POST['tanggal'];
        $riwayatPenjualans = Penjualan::whereRaw(" DATE(tanggal_penjualan) = '" . $tanggal . "'")->get();
        // $riwayatPenjualans = $arrPenjualanSementara->filter(function ($penjualan) {
        //     return $penjualan->reservasi == null;
        // });
        return response()->json(array('msg' => view('admin.penjualan.detailriwayatkeseluruhan', compact('riwayatPenjualans'))->render()), 200);
    }

    public function detailNotaReservasiPenjualan($idReservasi)
    {
        $reservasi = Reservasi::find($idReservasi);
        if ($reservasi == null) {
            $pesan = "Tidak terdapat reservasi pelanggan dengan ID " . $idReservasi;
            return redirect()->route("riwayatreservasis.index")->withErrors($pesan);
        } else {
            $penjualanPerawatan = $reservasi->penjualan->penjualanperawatans->sortBy('id');
            $jamMulai = $penjualanPerawatan->first()->slotjams->sortBy('slot_jam_id')->first();

            $arrPaket = [];
            $arrProduk = [];
            $arrPerawatan = [];

            $idPaketPerawatan = [];
            $idPaketProduk = [];



            if (count($reservasi->penjualan->pakets) > 0) {
                foreach ($reservasi->penjualan->pakets as $paket) {
                    array_push($arrPaket, $paket);
                    foreach ($paket->perawatans as $perawatan) {
                        array_push($idPaketPerawatan, $perawatan->id);
                    }
                    foreach ($paket->produks as $produk) {
                        if (!in_array($produk->id, $idPaketProduk)) {
                            array_push($idPaketProduk, $produk->id);
                        }

                    }
                }
            }

            foreach ($reservasi->penjualan->penjualanperawatans as $p) {
                if (!in_array($p->perawatan_id, $idPaketPerawatan)) {
                    array_push($arrPerawatan, $p);
                }
            }

            foreach ($reservasi->penjualan->produks as $p) {
                if (!in_array($p->id, $idPaketProduk)) {
                    $produkSementara = [];
                    $produkSementara["object"] = $p;
                    $produkSementara["kuantitas"] = $p->pivot->kuantitas;
                    $produkSementara["harga"] = $p->pivot->harga;
                    $produkSementara["subtotal"] = $p->pivot->kuantitas * $p->pivot->harga;
                    array_push($arrProduk, $produkSementara);
                } else {
                    $totalKeseluruhan = $p->pivot->kuantitas;
                    $totalDariPaket = 0;
                    foreach ($reservasi->penjualan->pakets as $paket) {
                        if ($paket->produks->firstWhere("id", $p->id) != null) {
                            $totalDariPaket += $paket->produks->firstWhere("id", $p->id)->pivot->jumlah;
                        }
                    }

                    if ($totalKeseluruhan > $totalDariPaket) {
                        $jumlahSisaDiluarPaket = $totalKeseluruhan - $totalDariPaket;
                        $produkSementara = [];
                        $produkSementara["object"] = $p;
                        $produkSementara["kuantitas"] = $jumlahSisaDiluarPaket;
                        $produkSementara["harga"] = $p->pivot->harga;
                        $produkSementara["subtotal"] = $jumlahSisaDiluarPaket * $p->pivot->harga;
                        array_push($arrProduk, $produkSementara);
                    }
                }
            }

            return view("admin.penjualan.detailnotareservasi", compact("reservasi", "jamMulai", "arrPaket", "arrProduk", "arrPerawatan"));
        }


    }

    public function detailNotaPenjualan($idPenjualan)
    {
        $penjualan = Penjualan::find($idPenjualan);

        if ($penjualan == null) {
            return redirect()->route("penjualans.admin.riwayatpenjualan")->withErrors("Tidak terdapat penjualan dengan ID " . $idPenjualan);
        } else {
            $penjualanPerawatan = $penjualan->penjualanperawatans->sortBy('id');
            $jamMulai = $penjualanPerawatan->first()->slotjams->sortBy('slot_jam_id')->first();

            $arrPaket = [];
            $arrProduk = [];
            $arrPerawatan = [];

            $idPaketPerawatan = [];
            $idPaketProduk = [];



            if (count($penjualan->pakets) > 0) {
                foreach ($penjualan->pakets as $paket) {
                    array_push($arrPaket, $paket);
                    foreach ($paket->perawatans as $perawatan) {
                        array_push($idPaketPerawatan, $perawatan->id);
                    }
                    foreach ($paket->produks as $produk) {
                        array_push($idPaketProduk, $produk->id);
                    }
                }
            }

            foreach ($penjualan->penjualanperawatans as $p) {
                if (!in_array($p->perawatan_id, $idPaketPerawatan)) {
                    array_push($arrPerawatan, $p);
                }
            }

            foreach ($penjualan->produks as $p) {
                if (!in_array($p->id, $idPaketProduk)) {
                    $produkSementara = [];
                    $produkSementara["object"] = $p;
                    $produkSementara["kuantitas"] = $p->pivot->kuantitas;
                    $produkSementara["harga"] = $p->pivot->harga;
                    $produkSementara["subtotal"] = $p->pivot->kuantitas * $p->pivot->harga;
                    array_push($arrProduk, $produkSementara);
                } else {
                    $totalKeseluruhan = $p->pivot->kuantitas;
                    $totalDariPaket = 0;
                    foreach ($penjualan->pakets as $paket) {
                        if ($paket->produks->firstWhere("id", $p->id) != null) {
                            $totalDariPaket += $paket->produks->firstWhere("id", $p->id)->pivot->jumlah;
                        }
                    }

                    if ($totalKeseluruhan > $totalDariPaket) {
                        $jumlahSisaDiluarPaket = $totalKeseluruhan - $totalDariPaket;
                        $produkSementara = [];
                        $produkSementara["object"] = $p;
                        $produkSementara["kuantitas"] = $jumlahSisaDiluarPaket;
                        $produkSementara["harga"] = $p->pivot->harga;
                        $produkSementara["subtotal"] = $jumlahSisaDiluarPaket * $p->pivot->harga;
                        array_push($arrProduk, $produkSementara);
                    }
                }
            }

            return view("admin.penjualan.detailnotapenjualan", compact("penjualan", "jamMulai", "arrPaket", "arrProduk", "arrPerawatan"));
        }


    }

    //PELANGGAN
    public function detailNotaReservasiPenjualanPelanggan($idReservasi)
    {

        $reservasi = Reservasi::find($idReservasi);

        if ($reservasi == null) {
            return redirect()->route('pelanggans.index');
        } else {
            if ($reservasi->penjualan->pelanggan->id != Auth::user()->pelanggan->id) {
                return redirect()->route('pelanggans.index');
            } else {
                $penjualanPerawatan = $reservasi->penjualan->penjualanperawatans->sortBy('id');
                $jamMulai = $penjualanPerawatan->first()->slotjams->sortBy('slot_jam_id')->first();

                $arrPaket = [];
                $arrProduk = [];
                $arrPerawatan = [];

                $idPaketPerawatan = [];
                $idPaketProduk = [];



                if (count($reservasi->penjualan->pakets) > 0) {
                    foreach ($reservasi->penjualan->pakets as $paket) {
                        array_push($arrPaket, $paket);
                        foreach ($paket->perawatans as $perawatan) {
                            array_push($idPaketPerawatan, $perawatan->id);
                        }
                        foreach ($paket->produks as $produk) {
                            if (!in_array($produk->id, $idPaketProduk)) {
                                array_push($idPaketProduk, $produk->id);
                            }

                        }
                    }
                }

                foreach ($reservasi->penjualan->penjualanperawatans as $p) {
                    if (!in_array($p->perawatan_id, $idPaketPerawatan)) {
                        array_push($arrPerawatan, $p);
                    }
                }

                foreach ($reservasi->penjualan->produks as $p) {
                    if (!in_array($p->id, $idPaketProduk)) {
                        $produkSementara = [];
                        $produkSementara["object"] = $p;
                        $produkSementara["kuantitas"] = $p->pivot->kuantitas;
                        $produkSementara["harga"] = $p->pivot->harga;
                        $produkSementara["subtotal"] = $p->pivot->kuantitas * $p->pivot->harga;
                        array_push($arrProduk, $produkSementara);
                    } else {
                        $totalKeseluruhan = $p->pivot->kuantitas;
                        $totalDariPaket = 0;
                        foreach ($reservasi->penjualan->pakets as $paket) {
                            if ($paket->produks->firstWhere("id", $p->id) != null) {
                                $totalDariPaket += $paket->produks->firstWhere("id", $p->id)->pivot->jumlah;
                            }
                        }

                        if ($totalKeseluruhan > $totalDariPaket) {
                            $jumlahSisaDiluarPaket = $totalKeseluruhan - $totalDariPaket;
                            $produkSementara = [];
                            $produkSementara["object"] = $p;
                            $produkSementara["kuantitas"] = $jumlahSisaDiluarPaket;
                            $produkSementara["harga"] = $p->pivot->harga;
                            $produkSementara["subtotal"] = $jumlahSisaDiluarPaket * $p->pivot->harga;
                            array_push($arrProduk, $produkSementara);
                        }
                    }
                }

                return view("pelanggan.reservasi.detailnotareservasi", compact("reservasi", "jamMulai", "arrPaket", "arrProduk", "arrPerawatan"));
            }
        }


    }

}