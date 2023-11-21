<?php

namespace App\Http\Controllers;

use App\Models\Paket;
use App\Models\Perawatan;
use App\Models\Produk;
use Illuminate\Http\Request;

class PaketController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $paketsAktif = Paket::where("status", "aktif")->get();
        $paketsNonaktif = Paket::where("status", "nonaktif")->get();

        return view("admin.paket.index", compact("paketsAktif", "paketsNonaktif"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $perawatanAktif = Perawatan::where("status", "aktif")->get();
        $produkAktif = Produk::where("status", "aktif")->where("status_jual", "aktif")->get();

        return view("admin.paket.tambahpaket", compact("perawatanAktif", "produkAktif"));
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
     * @param  \App\Models\Paket  $paket
     * @return \Illuminate\Http\Response
     */
    public function show(Paket $paket)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Paket  $paket
     * @return \Illuminate\Http\Response
     */
    public function edit(Paket $paket)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Paket  $paket
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Paket $paket)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Paket  $paket
     * @return \Illuminate\Http\Response
     */
    public function destroy(Paket $paket)
    {
        //
    }
}
