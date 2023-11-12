<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use App\Models\PresensiKehadiran;
use Illuminate\Http\Request;

class PresensiKehadiranController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        date_default_timezone_set("Asia/Jakarta");
        $tanggalHariIni = date("Y-m-d");
        $tanggalPresensi = PresensiKehadiran::selectRaw("distinct DATE(tanggal_presensi) as tanggal_presensi")->whereRaw("DATE(tanggal_presensi) < '" . $tanggalHariIni . "'")->get();
        $daftarRiwayatPresensi = [];
        foreach ($tanggalPresensi as $tanggal) {
            $daftarRiwayat = PresensiKehadiran::whereRaw("DATE(tanggal_presensi) = '" . $tanggal->tanggal_presensi . "'")->get();
            array_push($daftarRiwayatPresensi, $daftarRiwayat);
        }
        $idMaxPresensiHariIniPerKaryawan = PresensiKehadiran::selectRaw("max(id) as id")->whereRaw("DATE(tanggal_presensi) = '" . $tanggalHariIni . "'")->groupBy("karyawan_id")->get();
        $presensisHariIni = PresensiKehadiran::whereIn("id", $idMaxPresensiHariIniPerKaryawan)->get();
        $jumlahIzinKehadiran = PresensiKehadiran::where("keterangan", "izin")->whereRaw("DATE(tanggal_presensi) >= '" . $tanggalHariIni . "'")->where("status", "belum")->count();
        $presensiIzinKehadiranHariIni = PresensiKehadiran::where("keterangan", "izin")->whereRaw("DATE(tanggal_presensi) = '" . $tanggalHariIni . "'")->get();
        $jumlahKaryawan = Karyawan::count("id");
        $idKaryawanUnikIzin = PresensiKehadiran::selectRaw("DISTINCT karyawan_id ")->where("keterangan", "izin")->whereRaw("DATE(tanggal_presensi) = '" . $tanggalHariIni . "'")->get();

        return view("admin.karyawan.presensikehadiran.index", compact("tanggalHariIni", "daftarRiwayatPresensi", "presensisHariIni", "jumlahIzinKehadiran", "presensiIzinKehadiranHariIni", "jumlahKaryawan", "idKaryawanUnikIzin"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        date_default_timezone_set("Asia/Jakarta");
        $tanggalHariIni = date("Y-m-d");
        $karyawans = Karyawan::all();



        $daftarIzinPresensiHariIni = PresensiKehadiran::whereRaw("DATE(tanggal_presensi) = '" . $tanggalHariIni . "'")->where("keterangan", "izin")->get();
        $daftarKaryawanIzinHariIni = [];
        if (count($daftarIzinPresensiHariIni) > 0) {
            foreach ($karyawans as $karyawan) {
                if ($daftarIzinPresensiHariIni->where("karyawan_id", $karyawan->id)->count() > 0) {
                    array_push($daftarKaryawanIzinHariIni, $karyawan);
                }
            }
        }

        $idKaryawansIzin = array_map(function ($karyawan) {
            return $karyawan->id;
        }, $daftarKaryawanIzinHariIni);

        // foreach ($karyawans as $k) {
        //     if (!in_array($k->id, $idKaryawansIzin)) {
        //         $newPresensiKaryawan = new PresensiKehadiran();
        //         $newPresensiKaryawan->keterangan = "absen";
        //     }
        // }

        $hariIndonesia = array('Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu');
        $nomorHariDalamMingguan = date("w");
        $tanggalHariIniTeks = $hariIndonesia[$nomorHariDalamMingguan] . ", " . date('d-m-Y');

        return view('admin.karyawan.presensikehadiran.bukapresensi', compact('karyawans', 'daftarKaryawanIzinHariIni', 'tanggalHariIniTeks', 'idKaryawansIzin', 'daftarIzinPresensiHariIni'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //dd($request->all());
        date_default_timezone_set("Asia/Jakarta");
        $tanggalHariIni = date("Y-m-d");
        $daftarNamaKaryawan = $request->get("daftarNamaKaryawan");
        $keteranganPresensi = $request->get("keteranganPresensi");
        $statusPresensi = $request->get("statusPresensi");
        $karyawans = Karyawan::all();
        if ($daftarNamaKaryawan == null) {

            return redirect()->route("presensikehadirans.index")->with("status", "Presensi hari ini telah berhasil dibuka!")->withInput();
        }

        foreach ($keteranganPresensi as $kp) {
            if ($kp == "null") {

                return redirect()->back()->withErrors("Mohon pilih keterangan presensi untuk setiap karyawan yang tersedia!")->withInput();
            }
        }

        foreach ($statusPresensi as $sp) {
            if ($sp == "null") {
                return redirect()->back()->withErrors("Mohon pilih status dari keterangan presensi untuk setiap karyawan yang tersedia!")->withInput();
            }
        }

        for ($i = 0; $i < count($daftarNamaKaryawan); $i++) {

            if ($keteranganPresensi[$i] == "izin") {
                $presensiIzinTerpilih = PresensiKehadiran::where('karyawan_id', $daftarNamaKaryawan[$i])->where('status', 'belum')->whereRaw("DATE(tanggal_presensi) = '" . $tanggalHariIni . "'")->first();
                if ($presensiIzinTerpilih != null) {
                    $presensiIzinTerpilih->status = $statusPresensi[$i];
                    $presensiIzinTerpilih->updated_at = date("Y-m-d H:i:s");
                    $presensiIzinTerpilih->save();
                } else {
                    $newPresensiKehadiran = new PresensiKehadiran();
                    $newPresensiKehadiran->keterangan = $keteranganPresensi[$i];
                    $newPresensiKehadiran->status = $statusPresensi[$i];
                    $newPresensiKehadiran->karyawan_id = $daftarNamaKaryawan[$i];
                    $newPresensiKehadiran->tanggal_presensi = date("Y-m-d H:i:s");
                    $newPresensiKehadiran->created_at = date("Y-m-d H:i:s");
                    $newPresensiKehadiran->updated_at = date("Y-m-d H:i:s");
                    $newPresensiKehadiran->save();
                }
            } else {
                $newPresensiKehadiran = new PresensiKehadiran();
                $newPresensiKehadiran->keterangan = $keteranganPresensi[$i];
                $newPresensiKehadiran->status = $statusPresensi[$i];
                $newPresensiKehadiran->karyawan_id = $daftarNamaKaryawan[$i];
                $newPresensiKehadiran->tanggal_presensi = date("Y-m-d H:i:s");
                $newPresensiKehadiran->created_at = date("Y-m-d H:i:s");
                $newPresensiKehadiran->updated_at = date("Y-m-d H:i:s");
                $newPresensiKehadiran->save();
            }

        }
        return redirect()->route('presensikehadirans.index')->with('status', 'Berhasil membuka presensi untuk hari ini');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PresensiKehadiran  $presensiKehadiran
     * @return \Illuminate\Http\Response
     */
    public function show(PresensiKehadiran $presensiKehadiran)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PresensiKehadiran  $presensiKehadiran
     * @return \Illuminate\Http\Response
     */
    public function edit(PresensiKehadiran $presensiKehadiran)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PresensiKehadiran  $presensiKehadiran
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PresensiKehadiran $presensiKehadiran)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PresensiKehadiran  $presensiKehadiran
     * @return \Illuminate\Http\Response
     */
    public function destroy(PresensiKehadiran $presensiKehadiran)
    {
        //
    }

    public function editPresensiKehadiran(){

    }
}
