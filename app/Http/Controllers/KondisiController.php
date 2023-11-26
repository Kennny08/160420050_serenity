<?php

namespace App\Http\Controllers;

use App\Models\Kondisi;
use Illuminate\Http\Request;

class KondisiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $kondisis = Kondisi::all();
        return view("admin.produk.kondisiproduk.index", compact("kondisis"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.produk.kondisiproduk.tambahkondisi');
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
        $keteranganKondisi = $request->get('keteranganKondisi');
        $kondisi = new Kondisi();
        $kondisi->keterangan = $keteranganKondisi;
        $kondisi->created_at = date('Y-m-d H:i:s');
        $kondisi->updated_at = date('Y-m-d H:i:s');
        $kondisi->save();
        return redirect()->route('kondisis.index')->with('status', 'Kondisi ' . $kondisi->keterangan . ' telah berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Kondisi  $kondisi
     * @return \Illuminate\Http\Response
     */
    public function show(Kondisi $kondisi)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Kondisi  $kondisi
     * @return \Illuminate\Http\Response
     */
    public function edit($idKondisi)
    {
        $objKondisi = Kondisi::find($idKondisi);
        return view('admin.produk.kondisiproduk.editkondisi', compact('objKondisi'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Kondisi  $kondisi
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $idKondisi)
    {
        date_default_timezone_set('Asia/Jakarta');
        $kondisi = Kondisi::find($idKondisi);
        $kondisi->keterangan = $request->get('keteranganKondisi');
        $kondisi->updated_at = date('Y-m-d H:i:s');
        $kondisi->save();
        return redirect()->route('kondisis.index')->with('status', 'Kondisi ' . $kondisi->keterangan . ' telah berhasil diedit');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Kondisi  $kondisi
     * @return \Illuminate\Http\Response
     */
    public function destroy($idKondisi)
    {
        $objKondisi = Kondisi::find($idKondisi);
        try {
            $objKondisi->delete();
            return redirect()->route('kondisis.index')->with('status', 'Kondisi ' . $objKondisi->keterangan . ' telah berhasil dihapus');
        } catch (\PDOException $ex) {
            $msg = "Data Gagal dihapus. Pastikan kembali tidak ada data yang berelasi sebelum dihapus";
            return redirect()->route('kondisis.index')->with('status', $msg);
        }
    }

    public function getDaftarProdukKondisi()
    {
        $idKondisi = $_POST["idKondisi"];
        $kondisi = Kondisi::find($idKondisi);
        $daftarProdukKondisi = $kondisi->produks;

        return response()->json(array('msg' => view('admin.produk.kondisiproduk.daftarprodukkondisi', compact('daftarProdukKondisi'))->render()), 200);

    }
}
