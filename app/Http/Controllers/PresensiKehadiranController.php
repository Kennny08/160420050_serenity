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
        $karyawans = Karyawan::orderBy("nama")->get();

        $idMaxPresensiHariIniPerKaryawan = PresensiKehadiran::selectRaw("max(id) as id")->whereRaw("DATE(tanggal_presensi) = '" . $tanggalHariIni . "'")->where("keterangan", "!=", "izin")->groupBy("karyawan_id")->get();

        $idPresensiKaryawanYangSudahPresensiHariIni = [];
        foreach ($idMaxPresensiHariIniPerKaryawan as $presensi) {
            array_push($idPresensiKaryawanYangSudahPresensiHariIni, $presensi->id);
        }

        $arrObjectPresensiHariIniTanpaIzin = PresensiKehadiran::whereIn("id", $idPresensiKaryawanYangSudahPresensiHariIni)->get();
        // dd($arrObjectPresensiHariIniTanpaIzin);

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

        return view('admin.karyawan.presensikehadiran.bukapresensi1', compact('karyawans', 'daftarKaryawanIzinHariIni', 'tanggalHariIniTeks', 'idKaryawansIzin', 'daftarIzinPresensiHariIni', 'arrObjectPresensiHariIniTanpaIzin'));

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
        $waktuBukaPresensi = $request->get("waktuBukaPresensi");
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
                    $newPresensiKehadiran->tanggal_presensi = date("Y-m-d H:i:s", strtotime($tanggalHariIni . " " . $waktuBukaPresensi . ":00"));
                    $newPresensiKehadiran->created_at = date("Y-m-d H:i:s", strtotime($tanggalHariIni . " " . $waktuBukaPresensi . ":00"));
                    $newPresensiKehadiran->updated_at = date("Y-m-d H:i:s");
                    $newPresensiKehadiran->save();
                }
            } else {
                $newPresensiKehadiran = new PresensiKehadiran();
                $newPresensiKehadiran->keterangan = $keteranganPresensi[$i];
                $newPresensiKehadiran->status = $statusPresensi[$i];
                $newPresensiKehadiran->karyawan_id = $daftarNamaKaryawan[$i];
                $newPresensiKehadiran->tanggal_presensi = date("Y-m-d H:i:s", strtotime($tanggalHariIni . " " . $waktuBukaPresensi . ":00"));
                $newPresensiKehadiran->created_at = date("Y-m-d H:i:s", strtotime($tanggalHariIni . " " . $waktuBukaPresensi . ":00"));
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

    public function editPresensiKehadiran($tanggalPresensi)
    {
        date_default_timezone_set("Asia/Jakarta");
        $idMaxPresensiPerKaryawan = PresensiKehadiran::selectRaw("max(id) as id")->whereRaw("DATE(tanggal_presensi) = '" . $tanggalPresensi . "'")->groupBy("karyawan_id")->get();
        $idMaxPresensiPerKaryawanIzinDitolak = PresensiKehadiran::selectRaw("max(id) as id")->whereRaw("DATE(tanggal_presensi) = '" . $tanggalPresensi . "'")->where("keterangan", "izin")->where("status", "tolak")->groupBy("karyawan_id")->get();
        $objectUnikKaryawanYangIzinDitolak = PresensiKehadiran::selectRaw("DISTINCT karyawan_id")->whereIn("id", $idMaxPresensiPerKaryawanIzinDitolak)->get();
        $idUnikKaryawanYangIzinDitolak = [];
        foreach ($objectUnikKaryawanYangIzinDitolak as $presensiIzinDitolak) {
            array_push($idUnikKaryawanYangIzinDitolak, $presensiIzinDitolak->karyawan_id);
        }

        $idPresensiKaryawan = [];
        foreach ($idMaxPresensiPerKaryawan as $presensi) {
            array_push($idPresensiKaryawan, $presensi->id);
        }

        $daftarObjectPresensiKehadiran = PresensiKehadiran::whereIn("id", $idPresensiKaryawan)->get();
        $arrObjectPresensiKehadiran = [];

        foreach ($daftarObjectPresensiKehadiran as $objectPresensiKehadiran) {
            array_push($arrObjectPresensiKehadiran, $objectPresensiKehadiran);
        }

        usort($arrObjectPresensiKehadiran, [$this, "urutkanNamaKaryawan"]);

        $hariIndonesia = array('Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu');
        $nomorHariDalamMingguan = date("w", strtotime($tanggalPresensi));
        $tanggalPresensiTeks = $hariIndonesia[$nomorHariDalamMingguan] . ", " . date('d-m-Y', strtotime($tanggalPresensi));

        return view('admin.karyawan.presensikehadiran.editpresensi', compact('arrObjectPresensiKehadiran', 'tanggalPresensi', 'tanggalPresensiTeks', 'idUnikKaryawanYangIzinDitolak'));
    }

    protected function urutkanNamaKaryawan($presensiA, $presensiB)
    {
        return strcmp($presensiA->karyawan->nama, $presensiB->karyawan->nama);
    }

    public function updatePresensiKehadiran(Request $request)
    {
        //dd($request->all());
        date_default_timezone_set("Asia/Jakarta");
        $tanggalHariIni = date("Y-m-d");
        $tanggalPresensi = $request->get("tanggalPresensi");
        $daftarIdKaryawan = $request->get("daftarNamaKaryawan");
        $keteranganPresensi = $request->get("keteranganPresensi");
        $statusPresensi = $request->get("statusPresensi");
        for ($i = 0; $i < count($daftarIdKaryawan); $i++) {
            $idMaxPresensiKaryawan = PresensiKehadiran::whereRaw("DATE(tanggal_presensi) = '" . $tanggalPresensi . "'")->where("karyawan_id", $daftarIdKaryawan[$i])->max("id");
            $presensiKaryawanMax = PresensiKehadiran::find($idMaxPresensiKaryawan);

            if ($keteranganPresensi[$i] != $presensiKaryawanMax->keterangan) {
                if ($statusPresensi[$i] != $presensiKaryawanMax->status) {
                    if ($presensiKaryawanMax->keterangan == "izin" && $presensiKaryawanMax->status == "tolak") {
                        $newPresensi = new PresensiKehadiran();
                        $newPresensi->keterangan = $keteranganPresensi[$i];
                        $newPresensi->status = $statusPresensi[$i];
                        $newPresensi->karyawan_id = $daftarIdKaryawan[$i];
                        $newPresensi->tanggal_presensi = date("Y-m-d H:i:s", strtotime($presensiKaryawanMax->created_at));
                        $newPresensi->created_at = date("Y-m-d H:i:s", strtotime($presensiKaryawanMax->created_at));
                        $newPresensi->updated_at = date("Y-m-d H:i:s");
                        $newPresensi->save();
                    } else {
                        $presensiKaryawanMax->keterangan = $keteranganPresensi[$i];
                        $presensiKaryawanMax->status = $statusPresensi[$i];
                        if ($tanggalPresensi == $tanggalHariIni) {
                            $presensiKaryawanMax->updated_at = date("Y-m-d H:i:s");
                        } else {
                            $tanggalSementara = date("Y-m-d H:i:s", strtotime($presensiKaryawanMax->updated_at));
                            $presensiKaryawanMax->updated_at = $tanggalSementara;
                        }
                        $presensiKaryawanMax->save();
                    }


                } else {
                    $presensiKaryawanMax->keterangan = $keteranganPresensi[$i];
                    $presensiKaryawanMax->updated_at = date("Y-m-d H:i:s", strtotime($presensiKaryawanMax->updated_at));
                    $presensiKaryawanMax->save();
                }
            } else {
                if ($statusPresensi[$i] != $presensiKaryawanMax->status) {
                    $presensiKaryawanMax->status = $statusPresensi[$i];
                    if ($tanggalPresensi == $tanggalHariIni) {
                        $presensiKaryawanMax->updated_at = date("Y-m-d H:i:s");
                    } else {
                        $presensiKaryawanMax->updated_at = date("Y-m-d H:i:s", strtotime($presensiKaryawanMax->updated_at));
                    }
                    $presensiKaryawanMax->save();
                }
            }
        }
        $hariIndonesia = array('Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu');

        if ($tanggalPresensi == $tanggalHariIni) {
            $nomorHariDalamMingguan = date("w");
            $tanggalPresensiTeks = $hariIndonesia[$nomorHariDalamMingguan] . ", " . date('d-m-Y');
            return redirect()->route("presensikehadirans.index")->with("status", "Berhasil mengedit presensi untuk hari ini pada tanggal " . $tanggalPresensiTeks);
        } else {
            $nomorHariDalamMingguan = date("w", strtotime($tanggalPresensi));
            $tanggalPresensiTeks = $hariIndonesia[$nomorHariDalamMingguan] . ", " . date('d-m-Y', strtotime($tanggalPresensi));
            return redirect()->route("admin.presensikehadirans.riwayatpresensi")->with("status", "Berhasil mengedit presensi untuk tanggal " . $tanggalPresensiTeks);
        }


    }

    public function riwayatPresensiKaryawan()
    {
        date_default_timezone_set("Asia/Jakarta");
        $tanggalHariIni = date("Y-m-d");
        $tanggalPresensi = PresensiKehadiran::selectRaw("distinct DATE(tanggal_presensi) as tanggal_presensi")->whereRaw("DATE(tanggal_presensi) < '" . $tanggalHariIni . "'")->orderBy("tanggal_presensi", "desc")->get();
        $daftarRiwayatPresensi = [];
        foreach ($tanggalPresensi as $tanggal) {
            $riwayatPresensi = [];
            $daftarRiwayat = PresensiKehadiran::whereRaw("DATE(tanggal_presensi) = '" . $tanggal->tanggal_presensi . "'")->get();

            $hariIndonesia = array('Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu');
            $nomorHariDalamMingguan = date("w", strtotime($tanggal->tanggal_presensi));
            $tanggalPresensiTeks = $hariIndonesia[$nomorHariDalamMingguan] . ", " . date('d-m-Y', strtotime($tanggal->tanggal_presensi));
            $riwayatPresensi["tanggalPresensi"] = $tanggal->tanggal_presensi;
            $riwayatPresensi["tanggalPresensiDenganHari"] = $tanggalPresensiTeks;
            $riwayatPresensi["daftarPresensi"] = $daftarRiwayat;
            $riwayatPresensi["objectPresensiPertamaTanpaIzin"] = $daftarRiwayat->firstWhere("keterangan", "!=", "izin");
            array_push($daftarRiwayatPresensi, $riwayatPresensi);
        }
        return view("admin.karyawan.presensikehadiran.riwayatpresensi", compact("daftarRiwayatPresensi"));
    }

    public function getDetailRiwayatPresensi()
    {
        $tanggalPresensi = $_POST['tanggalPresensi'];
        $idMaxPresensiPerKaryawan = PresensiKehadiran::selectRaw("max(id) as id")->whereRaw("DATE(tanggal_presensi) = '" . $tanggalPresensi . "'")->groupBy("karyawan_id")->get();
        $presensis = PresensiKehadiran::whereIn("id", $idMaxPresensiPerKaryawan)->get();


        return response()->json(array('msg' => view('admin.karyawan.presensikehadiran.detailriwayatpresensi', compact('presensis'))->render()), 200);
    }
}
