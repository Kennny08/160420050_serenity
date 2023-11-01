<?php

namespace App\Http\Controllers;

use App\Models\Perawatan;
use Illuminate\Http\Request;

class PerawatanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $perawatansAktif = Perawatan::where('status', 'aktif')->get();
        $perawatansNonaktif = Perawatan::where('status', 'nonaktif')->get();
        return view('admin.perawatan.index', compact('perawatansAktif', 'perawatansNonaktif'));
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
     * @param  \App\Models\Perawatan  $perawatan
     * @return \Illuminate\Http\Response
     */
    public function show(Perawatan $perawatan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Perawatan  $perawatan
     * @return \Illuminate\Http\Response
     */
    public function edit(Perawatan $perawatan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Perawatan  $perawatan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Perawatan $perawatan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Perawatan  $perawatan
     * @return \Illuminate\Http\Response
     */
    public function destroy(Perawatan $perawatan)
    {
        //
    }

    public function getDetailPerawatan()
    {
        $idPerawatan = $_POST['idPerawatan'];
        $perawatan = Perawatan::find($idPerawatan);
        return response()->json(array('msg' => view('admin.reservasi.detailperawatan', compact('perawatan'))->render()), 200);
    }

    public function getDetailPerawatanList()
    {

        $idPerawatan = $_POST['idPerawatan'];
        $perawatan = Perawatan::find($idPerawatan);
        $jmlhReservasi = 0;
        $jmlTanpaReservasi = 0;
        foreach ($perawatan->penjualanperawatans as $pp) {
            if ($pp->penjualan->reservasi != null) {
                if ($pp->penjualan->reservasi->status == 'selesai') {
                    $jmlhReservasi++;
                }
            } else {
                if ($pp->penjualan->status_selesai == 'selesai') {
                    $jmlTanpaReservasi++;
                }
            }
        }
        $perawatan['jmlh_reservasi'] = $jmlhReservasi;
        $perawatan['jmlh_tanpa_reservasi'] = $jmlTanpaReservasi;
        return response()->json(array('msg' => view('admin.perawatan.detailperawatan', compact('perawatan'))->render()), 200);
    }
}