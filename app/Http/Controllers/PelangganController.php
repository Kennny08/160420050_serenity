<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Paket;
use App\Models\Pelanggan;
use App\Models\Penjualan;
use App\Models\PenjualanPerawatan;
use App\Models\Perawatan;
use App\Models\Produk;
use App\Models\Ulasan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PelangganController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        date_default_timezone_set('Asia/Jakarta');
        $hariIndonesia = array('Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu');
        $nomorHariDalamMingguan = date("w");
        $tanggalHariIni = $hariIndonesia[$nomorHariDalamMingguan] . ", " . date('d-m-Y');
        $pelanggan = Auth::user()->pelanggan;

        $totalReservasi = 0;
        $totalPerawatan = 0;
        $totalProduk = 0;

        $penjualans = Penjualan::where("pelanggan_id", $pelanggan->id)->where("status_selesai", "selesai")->get();
        foreach ($penjualans as $penjualan) {
            if ($penjualan->reservasi != null) {
                $totalReservasi += 1;
            }

            foreach ($penjualan->produks as $produk) {
                $totalProduk += $produk->pivot->kuantitas;
            }

            $totalPerawatan += $penjualan->penjualanperawatans->count();
        }

        $perawatans = Perawatan::where("status", "aktif")->inRandomOrder()->limit(4)->get();
        $penjualanPerawatans = PenjualanPerawatan::select("penjualan_perawatan.perawatan_id")->join("penjualans", "penjualans.id", "=", "penjualan_perawatan.penjualan_id")->where("penjualans.status_selesai", "selesai")->get();

        $produks = [];
        $kategoris = Kategori::all()->shuffle();
        $daftarKategori = [];
        foreach ($kategoris as $k) {
            if ($k->produks->where("status_jual", "aktif")->count() > 0) {
                array_push($daftarKategori, $k);
            }
        }

        $batas = 0;
        if (count($daftarKategori) >= 4) {
            $batas = 4;
        }else{
            $batas = count($daftarKategori);
        }

        for ($i = 0; $i < $batas; $i++) {
            $produkSementara = [];
            $produkSementara["kategori"] = $daftarKategori[$i];
            $produkSementara["produks"] = Produk::where("status", "aktif")->where("status_jual", "aktif")->where("kategori_id", $daftarKategori[$i]->id)->inRandomOrder()->limit(4)->get();
            array_push($produks, $produkSementara);
        }
        $penjualansSelesai = Penjualan::where("status_selesai", "selesai")->get();

        $ulasans = Ulasan::where("status", "aktif")->inRandomOrder()->limit(3)->get();

        $pakets = Paket::where("status", "aktif")->inRandomOrder()->limit(4)->get();


        return view("pelanggan.index", compact('pelanggan', 'tanggalHariIni', 'totalReservasi', 'totalPerawatan', 'totalProduk', 'perawatans', 'penjualanPerawatans', 'produks', 'penjualansSelesai', 'ulasans', 'pakets'));
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
     * @param  \App\Models\Pelanggan  $pelanggan
     * @return \Illuminate\Http\Response
     */
    public function show(Pelanggan $pelanggan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Pelanggan  $pelanggan
     * @return \Illuminate\Http\Response
     */
    public function edit(Pelanggan $pelanggan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Pelanggan  $pelanggan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Pelanggan $pelanggan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Pelanggan  $pelanggan
     * @return \Illuminate\Http\Response
     */
    public function destroy(Pelanggan $pelanggan)
    {
        //
    }
}
