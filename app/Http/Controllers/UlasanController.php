<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use App\Models\Ulasan;
use Illuminate\Http\Request;

class UlasanController extends Controller
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
     * @param  \App\Models\Ulasan  $ulasan
     * @return \Illuminate\Http\Response
     */
    public function show(Ulasan $ulasan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Ulasan  $ulasan
     * @return \Illuminate\Http\Response
     */
    public function edit(Ulasan $ulasan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Ulasan  $ulasan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Ulasan $ulasan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Ulasan  $ulasan
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ulasan $ulasan)
    {
        //
    }

    public function pelangganSimpanUlasan(Request $request)
    {
        date_default_timezone_set('Asia/Jakarta');
        $idPenjualan = $request->get("hiddenIdPenjualanReservasi");
        $penjualan = Penjualan::find($idPenjualan);
        $ulasan = $request->get("ulasan");

        if (trim($ulasan) == "") {
            return redirect()->route('reservasis.pelanggan.detailreservasi', $penjualan->reservasi->id)->withErrors("Mohon untuk tidak mengosongkan kotak ulasan jika ingin memberikan ulasan!");
        } else {
            $newUlasan = new Ulasan();
            $newUlasan->penjualan_id = $penjualan->id;
            $newUlasan->ulasan = $ulasan;
            $newUlasan->created_at = date("Y-m-d H:i:s");
            $newUlasan->updated_at = date("Y-m-d H:i:s");
            $newUlasan->save();

            return redirect()->route('reservasis.pelanggan.detailreservasi', $penjualan->reservasi->id)->with("status", "Berhasil memberikan ulasan untuk reservasi ini!");
        }
    }
}
