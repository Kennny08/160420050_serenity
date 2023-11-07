<?php

namespace App\Http\Controllers;

use App\Models\SlotJam;
use Illuminate\Http\Request;

class SlotJamController extends Controller
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
     * @param  \App\Models\SlotJam  $slotJam
     * @return \Illuminate\Http\Response
     */
    public function show(SlotJam $slotJam)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SlotJam  $slotJam
     * @return \Illuminate\Http\Response
     */
    public function edit(SlotJam $slotJam)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SlotJam  $slotJam
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SlotJam $slotJam)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SlotJam  $slotJam
     * @return \Illuminate\Http\Response
     */
    public function destroy(SlotJam $slotJam)
    {
        //
    }

    public function getSlotJamAktif()
    {
        $tanggal = $_POST['tanggal'];
        $hariIndonesia = array('Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu');

        $nomorHariDalamMingguan = date("w", strtotime($tanggal));

        $slotJams = SlotJam::where('hari', $hariIndonesia[$nomorHariDalamMingguan])->get();

        $status = "";
        if (count($slotJams) == 0) {
            $status = "no";
        } else {
            $status = "ok";
        }
        return response()->json(array('status' => $status, 'msg' => view('admin.reservasi.optionslotjam', compact('slotJams'))->render()), 200);
    }
}