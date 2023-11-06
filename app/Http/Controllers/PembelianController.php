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
        //
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
