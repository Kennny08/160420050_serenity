<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use App\Models\Produk;
use App\Models\RiwayatPengambilanProduk;
use Illuminate\Http\Request;

class RiwayatPengambilanProdukController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        date_default_timezone_set("Asia/Jakarta");
        $hariIndonesia = array('Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu');
        $tanggalUnik = RiwayatPengambilanProduk::selectRaw("DATE(tanggal_pengambilan) as tanggal_pengambilan")->distinct()->get();
        $arrPengambilanProdukPerTanggal = [];
        foreach ($tanggalUnik as $tanggal) {
            $riwayat = [];
            $daftarPengambilanProduk = RiwayatPengambilanProduk::whereRaw("DATE(tanggal_pengambilan) = '" . $tanggal->tanggal_pengambilan . "'")->get();
            $riwayat["tanggal_pengambilan"] = $tanggal->tanggal_pengambilan;
            $riwayat["totalriwayat"] = $daftarPengambilanProduk->count();
            $riwayat["totalproduk"] = $daftarPengambilanProduk->pluck("produk_id")->unique()->count();
            $riwayat["jumlahkaryawan"] = $daftarPengambilanProduk->pluck("karyawan_id")->unique()->count();

            $nomorHariDalamMingguan = date("w", strtotime($tanggal->tanggal_pengambilan));
            $tanggalRiwayatTeks = $hariIndonesia[$nomorHariDalamMingguan] . ", " . date('d-m-Y', strtotime($tanggal->tanggal_pengambilan));
            $riwayat["tanggal_pengambilan_teks"] = $tanggalRiwayatTeks;

            array_push($arrPengambilanProdukPerTanggal, $riwayat);
        }
        return view("admin.produk.riwayatpengambilanproduk.index", compact("arrPengambilanProdukPerTanggal"));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        date_default_timezone_set('Asia/Jakarta');
        $produks = Produk::select("id", "kode_produk", "nama", "stok")->where("status", "aktif")->where("status_jual", "tidak")->get();
        $karyawanPekerjaSalon = Karyawan::select("id", "nama")->get();

        $hariIndonesia = array('Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu');
        $nomorHariDalamMingguan = date("w");
        $tanggalHariIni = $hariIndonesia[$nomorHariDalamMingguan] . ", " . date('d-m-Y');

        return view("admin.produk.riwayatpengambilanproduk.tambahriwayatpengambilanproduk", compact("produks", "karyawanPekerjaSalon", "tanggalHariIni"));
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
        $idProduk = $request->get("produk");
        $idKaryawan = $request->get("karyawan");
        $kuantitas = $request->get("kuantitas");
        $keterangan = $request->get("keterangan");
        $tanggalHariIni = date("d-m-Y");
        $produk = Produk::find($idProduk);
        $karyawan = Karyawan::find($idKaryawan);

        if ($produk->stok >= $kuantitas) {
            $produk->stok = $produk->stok - $kuantitas;
            $produk->updated_at = date("Y-m-d H:i:s");
            $produk->save();

            $newPengambilanProduk = new RiwayatPengambilanProduk();
            $newPengambilanProduk->tanggal_pengambilan = date("Y-m-d H:i:s");
            $newPengambilanProduk->produk_id = $idProduk;
            $newPengambilanProduk->karyawan_id = $idKaryawan;
            $newPengambilanProduk->kuantitas = $kuantitas;
            $newPengambilanProduk->keterangan = $keterangan;
            $newPengambilanProduk->created_at = date("Y-m-d H:i:s");
            $newPengambilanProduk->updated_at = date("Y-m-d H:i:s");
            $newPengambilanProduk->save();
            return redirect()->route("riwayatpengambilanproduks.index")->with("status", "Berhasil menambahkan data pengambilan produk " . $produk->nama . "oleh karyawan " . $karyawan->nama . " pada tanggal " . $tanggalHariIni . "!");
        } else {
            return redirect()->back()->withErrors("Kuantitas yang mau diambil dari produk " . $produk->nama . " memiliki stok yang tidak mencukupi, stok tersisa " . $produk->stok . " !")->withInput();
        }



    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\RiwayatPengambilanProduk  $riwayatPengambilanProduk
     * @return \Illuminate\Http\Response
     */
    public function show(RiwayatPengambilanProduk $riwayatPengambilanProduk)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\RiwayatPengambilanProduk  $riwayatPengambilanProduk
     * @return \Illuminate\Http\Response
     */
    public function edit(RiwayatPengambilanProduk $riwayatPengambilanProduk)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\RiwayatPengambilanProduk  $riwayatPengambilanProduk
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, RiwayatPengambilanProduk $riwayatPengambilanProduk)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\RiwayatPengambilanProduk  $riwayatPengambilanProduk
     * @return \Illuminate\Http\Response
     */
    public function destroy(RiwayatPengambilanProduk $riwayatPengambilanProduk)
    {
        //
    }

    public function getDetailRiwayatPengambilanProduk()
    {
        $tanggalRiwayat = $_POST["tanggalRiwayat"];
        $daftarPengambilanProduk = RiwayatPengambilanProduk::whereRaw("DATE(tanggal_pengambilan) = '" . $tanggalRiwayat . "'")->orderBy("id", "desc")->get();
        return response()->json(array('msg' => view('admin.produk.riwayatpengambilanproduk.detailriwayatpengambilanproduk', compact('daftarPengambilanProduk'))->render()), 200);
    }
}
