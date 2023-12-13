<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Paket;
use App\Models\Penjualan;
use App\Models\PenjualanPerawatan;
use App\Models\Perawatan;
use App\Models\Produk;
use App\Models\Reservasi;
use App\Models\Ulasan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function halamanUtama()
    {

        if (Auth::check()) {
            if (Auth::user()->role == "pelanggan") {
                return redirect()->route("pelanggans.index");
            } else {
                date_default_timezone_set('Asia/Jakarta');
                $hariIndonesia = array('Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu');
                $nomorHariDalamMingguan = date("w");
                $tanggalHariIni = $hariIndonesia[$nomorHariDalamMingguan] . ", " . date('d-m-Y');

                $totalReservasi = Reservasi::where("status", "selesai")->count();
                $totalPerawatan = Perawatan::where("status", "aktif")->count();
                $totalProduk = Produk::where("status", "aktif")->count();



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
                } else {
                    $batas = count($daftarKategori);
                }

                for ($i = 0; $i < $batas; $i++) {
                    $produkSementara = [];
                    $produkSementara["kategori"] = $daftarKategori[$i];
                    $produkSementara["produks"] = Produk::where("status", "aktif")->where("status_jual", "aktif")->where("kategori_id", $daftarKategori[$i]->id)->inRandomOrder()->limit(4)->get();
                    array_push($produks, $produkSementara);
                }
                $penjualansSelesai = Penjualan::where("status_selesai", "selesai")->get();

                $ulasans = Ulasan::where("status", "aktif")->inRandomOrder()->limit(10)->get();

                $pakets = Paket::where("status", "aktif")->inRandomOrder()->limit(4)->get();


                return view("alluser.index", compact('tanggalHariIni', 'totalReservasi', 'totalPerawatan', 'totalProduk', 'perawatans', 'penjualanPerawatans', 'produks', 'penjualansSelesai', 'ulasans', 'pakets'));
            }

        } else {
            date_default_timezone_set('Asia/Jakarta');
            $hariIndonesia = array('Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu');
            $nomorHariDalamMingguan = date("w");
            $tanggalHariIni = $hariIndonesia[$nomorHariDalamMingguan] . ", " . date('d-m-Y');

            $totalReservasi = Reservasi::where("status", "selesai")->count();
            $totalPerawatan = Perawatan::where("status", "aktif")->count();
            $totalProduk = Produk::where("status", "aktif")->count();



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
            } else {
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


            return view("alluser.index", compact('tanggalHariIni', 'totalReservasi', 'totalPerawatan', 'totalProduk', 'perawatans', 'penjualanPerawatans', 'produks', 'penjualansSelesai', 'ulasans', 'pakets'));
        }

    }
}
