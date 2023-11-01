<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $kategoris = Kategori::all();
        return view("admin.produk.kategoriproduk.index", compact("kategoris"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.produk.kategoriproduk.tambahkategori');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        date_default_timezone_set('Asia/Jakarta');
        $namaKategori = $request->get('namaKategori');
        $kategori = new Kategori();
        $kategori->nama = $namaKategori;
        $kategori->created_at = date('Y-m-d H:i:s');
        $kategori->updated_at = date('Y-m-d H:i:s');
        $kategori->save();
        return redirect()->route('kategoris.index')->with('status', 'Kategori ' . $kategori->nama . ' telah berhasil ditambahkan');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Kategori  $kategori
     * @return \Illuminate\Http\Response
     */
    public function show(Kategori $kategori)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Kategori  $kategori
     * @return \Illuminate\Http\Response
     */
    public function edit(Kategori $kategori)
    {
        $objKategori = $kategori;
        return view('admin.produk.kategoriproduk.editkategori', compact('objKategori'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Kategori  $kategori
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Kategori $kategori)
    {
        date_default_timezone_set('Asia/Jakarta');
        $namaKategori = $request->get('namaKategori');
        $kategori->nama = $namaKategori;
        $kategori->updated_at = date('Y-m-d H:i:s');
        $kategori->save();
        return redirect()->route('kategoris.index')->with('status', 'Kategori ' . $kategori->nama . ' telah berhasil diedit');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Kategori  $kategori
     * @return \Illuminate\Http\Response
     */
    public function destroy(Kategori $kategori)
    {
        $objCategory = $kategori;
        try {

            $objCategory->delete();
            return redirect()->route('kategoris.index')->with('status', 'Kategori ' . $objCategory->nama . ' telah berhasil dihapus');
        } catch (\PDOException $ex) {
            $msg = "Data Gagal dihapus. Pastikan kembali tidak ada data yang berelasi sebelum dihapus";
            return redirect()->route('kategoris.index')->with('status', $msg);
        }
    }
}
