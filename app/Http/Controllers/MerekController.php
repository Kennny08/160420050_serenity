<?php

namespace App\Http\Controllers;

use App\Models\Merek;
use Illuminate\Http\Request;

class MerekController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $mereks = Merek::all();
        return view("admin.produk.merekproduk.index", compact("mereks"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.produk.merekproduk.tambahmerek');
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
        $namaMerek = $request->get('namaMerek');
        $merek = new Merek();
        $merek->nama = $namaMerek;
        $merek->created_at = date('Y-m-d H:i:s');
        $merek->updated_at = date('Y-m-d H:i:s');
        $merek->save();
        return redirect()->route('mereks.index')->with('status', 'Merek ' . $merek->nama . ' telah berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Merek  $merek
     * @return \Illuminate\Http\Response
     */
    public function show(Merek $merek)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Merek  $merek
     * @return \Illuminate\Http\Response
     */
    public function edit($idMerek)
    {
        $objMerek = Merek::find($idMerek);
        return view('admin.produk.merekproduk.editmerek', compact('objMerek'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Merek  $merek
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $idMerek)
    {
        date_default_timezone_set('Asia/Jakarta');
        $merek = Merek::find($idMerek);
        $merek->nama = $request->get('namaMerek');
        $merek->updated_at = date('Y-m-d H:i:s');
        $merek->save();
        return redirect()->route('mereks.index')->with('status', 'Merek ' . $merek->nama . ' telah berhasil diedit');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Merek  $merek
     * @return \Illuminate\Http\Response
     */
    public function destroy($idMerek)
    {
        $objMerek = Merek::find($idMerek);
        try {
            $objMerek->delete();
            return redirect()->route('mereks.index')->with('status', 'Merek ' . $objMerek->nama . ' telah berhasil dihapus');
        } catch (\PDOException $ex) {
            $msg = "Data Gagal dihapus. Pastikan kembali tidak ada data yang berelasi sebelum dihapus";
            return redirect()->route('mereks.index')->with('status', $msg);
        }
    }

    public function getDaftarProdukMerek()
    {
        $idMerek = $_POST["idMerek"];
        $merek = Merek::find($idMerek);
        $daftarProdukMerek = $merek->produks;

        return response()->json(array('msg' => view('admin.produk.merekproduk.daftarprodukmerek', compact('daftarProdukMerek'))->render()), 200);

    }
}
