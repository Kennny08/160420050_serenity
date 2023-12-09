<?php

namespace App\Http\Controllers;

use App\Models\Reservasi;
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
        $tanggalMingguIni = date("Y-m-d", strtotime('last sunday'));
        $tanggalSabtuIni = date('Y-m-d', strtotime('this saturday'));
        $jams = SlotJam::selectRaw('distinct(jam)')->orderBy('jam', 'asc')->get();
        $daftarSlotJams = [];
        foreach ($jams as $j) {
            $slotJams = SlotJam::select('hari', 'jam', 'id', 'status')->where('jam', $j->jam)->get();
            array_push($daftarSlotJams, $slotJams);
        }
        return view('admin.slotjam.slotjamreservasi', compact('daftarSlotJams', 'tanggalMingguIni', 'tanggalSabtuIni'));
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
        date_default_timezone_set('Asia/Jakarta');
        $tanggal = $_POST['tanggal'];
        $hariIndonesia = array('Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu');

        $nomorHariDalamMingguan = date("w", strtotime($tanggal));
        if ($tanggal == date("Y-m-d")) {
            $slotJams = SlotJam::where('hari', $hariIndonesia[$nomorHariDalamMingguan])->where('jam', ">=", date("H.i"))->get();
        } else {
            $slotJams = SlotJam::where('hari', $hariIndonesia[$nomorHariDalamMingguan])->get();
        }


        $status = "";
        if (count($slotJams) == 0) {
            $status = "no";
        } else {
            $status = "ok";
        }
        return response()->json(array('status' => $status, 'msg' => view('admin.reservasi.optionslotjam', compact('slotJams'))->render()), 200);
    }

    public function getSlotJamAktifPelanggan()
    {
        date_default_timezone_set('Asia/Jakarta');
        $tanggal = $_POST['tanggal'];
        $hariIndonesia = array('Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu');

        $nomorHariDalamMingguan = date("w", strtotime($tanggal));
        if ($tanggal == date("Y-m-d")) {
            $slotJams = SlotJam::where('hari', $hariIndonesia[$nomorHariDalamMingguan])->where('jam', ">=", date("H.i"))->get();
        } else {
            $slotJams = SlotJam::where('hari', $hariIndonesia[$nomorHariDalamMingguan])->get();
        }


        $status = "";
        if (count($slotJams) == 0) {
            $status = "no";
        } else {
            $status = "ok";
        }
        return response()->json(array('status' => $status, 'msg' => view('pelanggan.reservasi.optionslotjam', compact('slotJams'))->render()), 200);
    }

    public function editStatusSlotJam()
    {
        date_default_timezone_set('Asia/Jakarta');
        $idSlotjam = $_POST['idSlotJam'];
        $statusUbah = $_POST['status'];

        $slotJamDipilih = SlotJam::find($idSlotjam);
        if ($statusUbah != $slotJamDipilih->status) {
            if ($statusUbah == "nonaktif") {
                $tanggalMingguIni = date("Y-m-d", strtotime('last sunday'));
                $tanggalSabtuIni = date('Y-m-d', strtotime('this saturday'));
                $daftarReservasiBaru = Reservasi::where("status", "baru")->where("tanggal_reservasi", ">=", $tanggalMingguIni)->where("tanggal_reservasi", "<=", $tanggalSabtuIni)->get();
                // dd($daftarReservasiBaru);
                $daftarReservasiFix = [];
                foreach ($daftarReservasiBaru as $reservasi) {
                    $penjualanPerawatan = $reservasi->penjualan->penjualanperawatans;
                    foreach ($penjualanPerawatan as $pp) {
                        $daftarSlotJamPenjualanPerawatan = $pp->slotjams;
                        if ($daftarSlotJamPenjualanPerawatan->where('id', $idSlotjam)->count() > 0) {
                            array_push($daftarReservasiFix, $reservasi);
                            break;
                        }
                    }
                }
                if (count($daftarReservasiFix) != 0) {
                    return response()->json(array('status' => "gagal", 'msg' => view('admin.slotjam.modalslotjadwal', compact('daftarReservasiFix'))->render()), 200);
                } else {
                    $slotJamDipilih->status = "nonaktif";
                    $slotJamDipilih->updated_at = date("Y-m-d H:i:s");
                    $slotJamDipilih->save();
                    return response()->json(array('status' => "berhasil"), 200);
                }
            } else {
                $slotJamDipilih->status = "aktif";
                $slotJamDipilih->updated_at = date("Y-m-d H:i:s");
                $slotJamDipilih->save();
                return response()->json(array('status' => "berhasil"), 200);
            }
        } else {
            return response()->json(array('status' => "berhasil"), 200);
        }
    }
}