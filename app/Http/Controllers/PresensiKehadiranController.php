<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use App\Models\Pelanggan;
use App\Models\Penjualan;
use App\Models\PenjualanPerawatan;
use App\Models\PresensiKehadiran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $jumlahIzinKehadiran = PresensiKehadiran::where(function ($query) {
            $query->where('keterangan', 'sakit')
                ->orWhere('keterangan', 'izin');
        })->whereRaw("DATE(tanggal_presensi) >= '" . $tanggalHariIni . "'")->where("status", "belum")->count();
        $presensiIzinKehadiranHariIni = PresensiKehadiran::where(function ($query) {
            $query->where('keterangan', 'sakit')
                ->orWhere('keterangan', 'izin');
        })->whereRaw("DATE(tanggal_presensi) = '" . $tanggalHariIni . "'")->get();
        $jumlahKaryawan = Karyawan::count("id");
        $idKaryawanUnikIzin = PresensiKehadiran::selectRaw("DISTINCT karyawan_id ")->where(function ($query) {
            $query->where('keterangan', 'sakit')
                ->orWhere('keterangan', 'izin');
        })->whereRaw("DATE(tanggal_presensi) = '" . $tanggalHariIni . "'")->get();
        $objectPertamaYangtanpaIzin = PresensiKehadiran::whereRaw("DATE(tanggal_presensi) = '" . $tanggalHariIni . "'")->whereNotIn("keterangan", ["izin", "sakit"])->first();

        return view("admin.karyawan.presensikehadiran.index", compact("tanggalHariIni", "daftarRiwayatPresensi", "presensisHariIni", "jumlahIzinKehadiran", "presensiIzinKehadiranHariIni", "jumlahKaryawan", "idKaryawanUnikIzin", "objectPertamaYangtanpaIzin"));
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

        $idMaxPresensiHariIniPerKaryawan = PresensiKehadiran::selectRaw("max(id) as id")->whereRaw("DATE(tanggal_presensi) = '" . $tanggalHariIni . "'")->whereNotIn("keterangan", ["izin", "sakit"])->groupBy("karyawan_id")->get();

        $idPresensiKaryawanYangSudahPresensiHariIni = [];
        foreach ($idMaxPresensiHariIniPerKaryawan as $presensi) {
            array_push($idPresensiKaryawanYangSudahPresensiHariIni, $presensi->id);
        }

        $arrObjectPresensiHariIniTanpaIzin = PresensiKehadiran::whereIn("id", $idPresensiKaryawanYangSudahPresensiHariIni)->get();
        // dd($arrObjectPresensiHariIniTanpaIzin);

        $daftarIzinPresensiHariIni = PresensiKehadiran::whereRaw("DATE(tanggal_presensi) = '" . $tanggalHariIni . "'")->where(function ($query) {
            $query->where('keterangan', 'sakit')
                ->orWhere('keterangan', 'izin');
        })->get();
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


        $karyawanYangIzinSakitDikonfirmasi = [];
        for ($i = 0; $i < count($daftarNamaKaryawan); $i++) {
            if ($keteranganPresensi[$i] == "izin" || $keteranganPresensi[$i] == "sakit") {
                $presensiIzinTerpilih = PresensiKehadiran::where('karyawan_id', $daftarNamaKaryawan[$i])->where('status', 'belum')->whereRaw("DATE(tanggal_presensi) = '" . $tanggalHariIni . "'")->first();
                if ($presensiIzinTerpilih != null) {
                    $presensiIzinTerpilih->status = $statusPresensi[$i];
                    $presensiIzinTerpilih->updated_at = date("Y-m-d H:i:s");
                    $presensiIzinTerpilih->save();

                    if ($statusPresensi[$i] == "konfirmasi") {
                        if (!in_array($daftarNamaKaryawan[$i], $karyawanYangIzinSakitDikonfirmasi)) {
                            array_push($karyawanYangIzinSakitDikonfirmasi, $daftarNamaKaryawan[$i]);
                        }
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

                    if ($statusPresensi[$i] == "konfirmasi") {
                        if (!in_array($daftarNamaKaryawan[$i], $karyawanYangIzinSakitDikonfirmasi)) {
                            array_push($karyawanYangIzinSakitDikonfirmasi, $daftarNamaKaryawan[$i]);
                        }
                    }
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


        if (count($karyawanYangIzinSakitDikonfirmasi) > 0) {
            $daftarPenjualanBelumHariIni = Penjualan::where("status_selesai", "belum")->whereRaw("DATE(tanggal_penjualan) = '" . $tanggalHariIni . "'")->whereHas("penjualanperawatans", function ($query) use ($karyawanYangIzinSakitDikonfirmasi) {
                $query->whereIn("karyawan_id", $karyawanYangIzinSakitDikonfirmasi);
            })->get();

            if (count($daftarPenjualanBelumHariIni) > 0) {

                $idPelangganUnik = $daftarPenjualanBelumHariIni->pluck("pelanggan_id")->unique();

                foreach ($idPelangganUnik as $idPelanggan) {
                    $pelanggan = Pelanggan::find($idPelanggan);

                    $nomorNota = [];

                    $penjualanPelangganTerpilih = Penjualan::where("pelanggan_id", $idPelanggan)->where("status_selesai", "belum")->whereRaw("DATE(tanggal_penjualan) = '" . $tanggalHariIni . "'")->whereHas("penjualanperawatans", function ($query) use ($karyawanYangIzinSakitDikonfirmasi) {
                        $query->whereIn("karyawan_id", $karyawanYangIzinSakitDikonfirmasi);
                    })->get();

                    $penjualanReservasiPelangganTerpilih = [];
                    foreach ($penjualanPelangganTerpilih as $penjualanTerpilih) {
                        if ($penjualanTerpilih->reservasi != null) {
                            array_push($penjualanReservasiPelangganTerpilih, $penjualanTerpilih);
                        }
                    }


                    foreach ($penjualanReservasiPelangganTerpilih as $penjualanPelanggan) {

                        array_push($nomorNota, $penjualanPelanggan->nomor_nota);
                    }

                    $stringNomorNota = implode(", ", $nomorNota);

                    MailController::mailPemberitahuanKaryawanIzinSakit($pelanggan->user->email, $stringNomorNota);
                }
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
        $idMaxPresensiPerKaryawanIzinDitolak = PresensiKehadiran::selectRaw("max(id) as id")->whereRaw("DATE(tanggal_presensi) = '" . $tanggalPresensi . "'")->where(function ($query) {
            $query->where('keterangan', 'sakit')
                ->orWhere('keterangan', 'izin');
        })->where("status", "tolak")->groupBy("karyawan_id")->get();
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

        $karyawanYangIzinSakitDikonfirmasi = [];
        for ($i = 0; $i < count($daftarIdKaryawan); $i++) {
            $idMaxPresensiKaryawan = PresensiKehadiran::whereRaw("DATE(tanggal_presensi) = '" . $tanggalPresensi . "'")->where("karyawan_id", $daftarIdKaryawan[$i])->max("id");
            $presensiKaryawanMax = PresensiKehadiran::find($idMaxPresensiKaryawan);

            if ($keteranganPresensi[$i] != $presensiKaryawanMax->keterangan) {
                if ($statusPresensi[$i] != $presensiKaryawanMax->status) {
                    if (($presensiKaryawanMax->keterangan == "izin" || $presensiKaryawanMax->keterangan == "sakit") && $presensiKaryawanMax->status == "tolak") {
                        $newPresensi = new PresensiKehadiran();
                        $newPresensi->keterangan = $keteranganPresensi[$i];
                        $newPresensi->status = $statusPresensi[$i];
                        $newPresensi->karyawan_id = $daftarIdKaryawan[$i];
                        $newPresensi->tanggal_presensi = date("Y-m-d H:i:s", strtotime($presensiKaryawanMax->created_at));
                        $newPresensi->created_at = date("Y-m-d H:i:s", strtotime($presensiKaryawanMax->created_at));
                        $newPresensi->updated_at = date("Y-m-d H:i:s");
                        $newPresensi->save();

                        if ($statusPresensi[$i] == "konfirmasi" && ($keteranganPresensi[$i] == "izin" || $keteranganPresensi[$i] == "sakit")) {
                            if (!in_array($daftarIdKaryawan[$i], $karyawanYangIzinSakitDikonfirmasi)) {
                                array_push($karyawanYangIzinSakitDikonfirmasi, $daftarIdKaryawan[$i]);
                            }
                        }
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

                        if ($statusPresensi[$i] == "konfirmasi" && ($keteranganPresensi[$i] == "izin" || $keteranganPresensi[$i] == "sakit")) {
                            if (!in_array($daftarIdKaryawan[$i], $karyawanYangIzinSakitDikonfirmasi)) {
                                array_push($karyawanYangIzinSakitDikonfirmasi, $daftarIdKaryawan[$i]);
                            }
                        }
                    }


                } else {
                    $presensiKaryawanMax->keterangan = $keteranganPresensi[$i];
                    $presensiKaryawanMax->updated_at = date("Y-m-d H:i:s", strtotime($presensiKaryawanMax->updated_at));
                    $presensiKaryawanMax->save();

                    if ($statusPresensi[$i] == "konfirmasi" && ($keteranganPresensi[$i] == "izin" || $keteranganPresensi[$i] == "sakit")) {
                        if (!in_array($daftarIdKaryawan[$i], $karyawanYangIzinSakitDikonfirmasi)) {
                            array_push($karyawanYangIzinSakitDikonfirmasi, $daftarIdKaryawan[$i]);
                        }
                    }
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

                    if ($statusPresensi[$i] == "konfirmasi" && ($keteranganPresensi[$i] == "izin" || $keteranganPresensi[$i] == "sakit")) {
                        if (!in_array($daftarIdKaryawan[$i], $karyawanYangIzinSakitDikonfirmasi)) {
                            array_push($karyawanYangIzinSakitDikonfirmasi, $daftarIdKaryawan[$i]);
                        }
                    }
                }
            }
        }

        if (count($karyawanYangIzinSakitDikonfirmasi) > 0) {
            $strtotimeTanggalHariIni = strtotime(date("Y-m-d"));
            $strtotimeTanggalEditPresensi = strtotime($tanggalPresensi);

            if ($strtotimeTanggalEditPresensi >= $strtotimeTanggalHariIni) {
                $daftarPenjualanBelumHariIni = Penjualan::where("status_selesai", "belum")->whereRaw("DATE(tanggal_penjualan) = '" . $tanggalPresensi . "'")->whereHas("penjualanperawatans", function ($query) use ($karyawanYangIzinSakitDikonfirmasi) {
                    $query->whereIn("karyawan_id", $karyawanYangIzinSakitDikonfirmasi);
                })->get();

                if (count($daftarPenjualanBelumHariIni) > 0) {

                    $idPelangganUnik = $daftarPenjualanBelumHariIni->pluck("pelanggan_id")->unique();

                    foreach ($idPelangganUnik as $idPelanggan) {
                        $pelanggan = Pelanggan::find($idPelanggan);

                        $nomorNota = [];

                        $penjualanPelangganTerpilih = Penjualan::where("pelanggan_id", $idPelanggan)->where("status_selesai", "belum")->whereRaw("DATE(tanggal_penjualan) = '" . $tanggalPresensi . "'")->whereHas("penjualanperawatans", function ($query) use ($karyawanYangIzinSakitDikonfirmasi) {
                            $query->whereIn("karyawan_id", $karyawanYangIzinSakitDikonfirmasi);
                        })->get();

                        $penjualanReservasiPelangganTerpilih = [];
                        foreach ($penjualanPelangganTerpilih as $penjualanTerpilih) {
                            if ($penjualanTerpilih->reservasi != null) {
                                array_push($penjualanReservasiPelangganTerpilih, $penjualanTerpilih);
                            }
                        }


                        foreach ($penjualanReservasiPelangganTerpilih as $penjualanPelanggan) {

                            array_push($nomorNota, $penjualanPelanggan->nomor_nota);
                        }

                        $stringNomorNota = implode(", ", $nomorNota);

                        MailController::mailPemberitahuanKaryawanIzinSakit($pelanggan->user->email, $stringNomorNota);
                    }
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

    public function konfirmasiCheckPresensi(Request $request)
    {
        date_default_timezone_set("Asia/Jakarta");
        $arrayPresensiKonfirmasi = $request->get("checkKonfirmasi");
        $tanggalHariIni = date("Y-m-d");

        $karyawanYangIzinSakitDikonfirmasi = [];
        foreach ($arrayPresensiKonfirmasi as $idPresensi) {
            $presensiTerpilih = PresensiKehadiran::find($idPresensi);
            $presensiTerpilih->status = "konfirmasi";
            $presensiTerpilih->updated_at = date("Y-m-d H:i:s");
            $presensiTerpilih->save();

            if (($presensiTerpilih->keterangan == "izin" || $presensiTerpilih->keterangan == "sakit")) {
                if (!in_array($presensiTerpilih->karyawan->id, $karyawanYangIzinSakitDikonfirmasi)) {
                    array_push($karyawanYangIzinSakitDikonfirmasi, $presensiTerpilih->karyawan->id);
                }
            }
        }

        $daftarPenjualanBelumHariIni = Penjualan::where("status_selesai", "belum")->whereRaw("DATE(tanggal_penjualan) = '" . $tanggalHariIni . "'")->whereHas("penjualanperawatans", function ($query) use ($karyawanYangIzinSakitDikonfirmasi) {
            $query->whereIn("karyawan_id", $karyawanYangIzinSakitDikonfirmasi);
        })->get();

        if (count($daftarPenjualanBelumHariIni) > 0) {

            $idPelangganUnik = $daftarPenjualanBelumHariIni->pluck("pelanggan_id")->unique();

            foreach ($idPelangganUnik as $idPelanggan) {
                $pelanggan = Pelanggan::find($idPelanggan);

                $nomorNota = [];

                $penjualanPelangganTerpilih = Penjualan::where("pelanggan_id", $idPelanggan)->where("status_selesai", "belum")->whereRaw("DATE(tanggal_penjualan) = '" . $tanggalHariIni . "'")->whereHas("penjualanperawatans", function ($query) use ($karyawanYangIzinSakitDikonfirmasi) {
                    $query->whereIn("karyawan_id", $karyawanYangIzinSakitDikonfirmasi);
                })->get();

                $penjualanReservasiPelangganTerpilih = [];
                foreach ($penjualanPelangganTerpilih as $penjualanTerpilih) {
                    if ($penjualanTerpilih->reservasi != null) {
                        array_push($penjualanReservasiPelangganTerpilih, $penjualanTerpilih);
                    }
                }


                foreach ($penjualanReservasiPelangganTerpilih as $penjualanPelanggan) {

                    array_push($nomorNota, $penjualanPelanggan->nomor_nota);
                }

                $stringNomorNota = implode(", ", $nomorNota);

                MailController::mailPemberitahuanKaryawanIzinSakit($pelanggan->user->email, $stringNomorNota);
            }
        }

        return redirect()->route("presensikehadirans.index")->with("status", "Berhasil mengkonfirmasi presensi untuk hari ini pada tanggal " . date("d-m-Y"));

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

    public function riwayatIzinKehadiran()
    {
        date_default_timezone_set("Asia/Jakarta");
        $tanggalHariIni = date("Y-m-d");

        $tanggalUnikIzinSebelumnya = PresensiKehadiran::selectRaw("distinct DATE(tanggal_presensi) as tanggal_presensi")->whereRaw("DATE(tanggal_presensi) < '" . $tanggalHariIni . "'")->where(function ($query) {
            $query->where('keterangan', 'sakit')
                ->orWhere('keterangan', 'izin');
        })->orderBy("tanggal_presensi", "asc")->get();
        $tanggalUnikIzinHariIniKedepan = PresensiKehadiran::selectRaw("distinct DATE(tanggal_presensi) as tanggal_presensi")->whereRaw("DATE(tanggal_presensi) >= '" . $tanggalHariIni . "'")->where(function ($query) {
            $query->where('keterangan', 'sakit')
                ->orWhere('keterangan', 'izin');
        })->orderBy("tanggal_presensi", "desc")->get();

        $daftarIzinPresensiHariSebelumnya = [];
        $daftarIzinPresensiHariIniKedepan = [];
        foreach ($tanggalUnikIzinSebelumnya as $tanggalIzin) {
            $objIzinSementara = [];

            $hariIndonesia = array('Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu');
            $nomorHariDalamMingguan = date("w", strtotime($tanggalIzin->tanggal_presensi));
            $tanggalIzinTeks = $hariIndonesia[$nomorHariDalamMingguan] . ", " . date('d-m-Y', strtotime($tanggalIzin->tanggal_presensi));


            $objIzinSementara["daftarIzin"] = PresensiKehadiran::whereRaw("DATE(tanggal_presensi) = '" . $tanggalIzin->tanggal_presensi . "'")->where(function ($query) {
                $query->where('keterangan', 'sakit')
                    ->orWhere('keterangan', 'izin');
            })->orderBy("tanggal_presensi", "asc")->get();
            $objIzinSementara["jumlahKaryawan"] = PresensiKehadiran::selectRaw("DISTINCT karyawan_id")->whereRaw("DATE(tanggal_presensi) = '" . $tanggalIzin->tanggal_presensi . "'")->where(function ($query) {
                $query->where('keterangan', 'sakit')
                    ->orWhere('keterangan', 'izin');
            })->count();
            $objIzinSementara["tanggalIzin"] = $tanggalIzin->tanggal_presensi;
            $objIzinSementara["tanggalIzinHari"] = $tanggalIzinTeks;
            array_push($daftarIzinPresensiHariSebelumnya, $objIzinSementara);
        }

        foreach ($tanggalUnikIzinHariIniKedepan as $tanggalIzin) {
            $objIzinSementara = [];

            $hariIndonesia = array('Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu');
            $nomorHariDalamMingguan = date("w", strtotime($tanggalIzin->tanggal_presensi));
            $tanggalIzinTeks = $hariIndonesia[$nomorHariDalamMingguan] . ", " . date('d-m-Y', strtotime($tanggalIzin->tanggal_presensi));

            $objIzinSementara["daftarIzin"] = PresensiKehadiran::whereRaw("DATE(tanggal_presensi) = '" . $tanggalIzin->tanggal_presensi . "'")->where(function ($query) {
                $query->where('keterangan', 'sakit')
                    ->orWhere('keterangan', 'izin');
            })->orderBy("tanggal_presensi", "asc")->get();
            $objIzinSementara["jumlahKaryawan"] = PresensiKehadiran::selectRaw("DISTINCT karyawan_id")->whereRaw("DATE(tanggal_presensi) = '" . $tanggalIzin->tanggal_presensi . "'")->where(function ($query) {
                $query->where('keterangan', 'sakit')
                    ->orWhere('keterangan', 'izin');
            })->count();
            $objIzinSementara["tanggalIzin"] = $tanggalIzin->tanggal_presensi;
            $objIzinSementara["tanggalIzinHari"] = $tanggalIzinTeks;
            array_push($daftarIzinPresensiHariIniKedepan, $objIzinSementara);
        }

        //dd($daftarIzinPresensiHariIniKedepan, $daftarIzinPresensiHariSebelumnya);
        return view("admin.karyawan.presensikehadiran.daftarizinkehadiran", compact("daftarIzinPresensiHariSebelumnya", "tanggalHariIni", "daftarIzinPresensiHariIniKedepan"));
    }

    public function getDetailIzinKehadiran()
    {
        $tanggalIzin = $_POST['tanggalIzin'];
        $idMaxIzinPerKaryawan = PresensiKehadiran::selectRaw("max(id) as id")->whereRaw("DATE(tanggal_presensi) = '" . $tanggalIzin . "'")->where(function ($query) {
            $query->where('keterangan', 'sakit')
                ->orWhere('keterangan', 'izin');
        })->groupBy("karyawan_id")->get();
        $daftarPresensiIzin = PresensiKehadiran::whereIn("id", $idMaxIzinPerKaryawan)->get();
        return response()->json(array('msg' => view('admin.karyawan.presensikehadiran.detailizinkehadiran', compact('daftarPresensiIzin'))->render()), 200);
    }

    public function updateStatusIzin()
    {
        date_default_timezone_set("Asia/Jakarta");
        $tanggalHariIni = date("Y-m-d");
        $idPresensi = $_POST['idPresensi'];
        $keteranganKonfirmasi = $_POST['keteranganKonfirmasi'];

        $objPresensi = PresensiKehadiran::find($idPresensi);
        $objPresensi->status = $keteranganKonfirmasi;
        $objPresensi->updated_at = date('Y-m-d H:i:s');
        $objPresensi->save();

        $tanggalPresensiYangDiupdate = date('Y-m-d', strtotime($objPresensi->tanggal_presensi));

        if (strtotime($tanggalPresensiYangDiupdate) >= strtotime($tanggalHariIni)) {



            if ($keteranganKonfirmasi == "konfirmasi") {
                //email pemberitahuan ke pelanggan yang karyawannya diizinkan untu izin tertentu
                $daftarPenjualanBelumHariIni = Penjualan::where("status_selesai", "belum")->whereRaw("DATE(tanggal_penjualan) = '" . $tanggalPresensiYangDiupdate . "'")->whereHas("penjualanperawatans", function ($query) use ($objPresensi) {
                    $query->where("karyawan_id", $objPresensi->karyawan->id);
                })->get();

                if (count($daftarPenjualanBelumHariIni) > 0) {

                    $idPelangganUnik = $daftarPenjualanBelumHariIni->pluck("pelanggan_id")->unique();

                    foreach ($idPelangganUnik as $idPelanggan) {
                        $pelanggan = Pelanggan::find($idPelanggan);

                        $nomorNota = [];

                        $penjualanPelangganTerpilih = Penjualan::where("pelanggan_id", $idPelanggan)->where("status_selesai", "belum")->whereRaw("DATE(tanggal_penjualan) = '" . $tanggalPresensiYangDiupdate . "'")->whereHas("penjualanperawatans", function ($query) use ($objPresensi) {
                            $query->where("karyawan_id", $objPresensi->karyawan->id);
                        })->get();

                        $penjualanReservasiPelangganTerpilih = [];
                        foreach ($penjualanPelangganTerpilih as $penjualanTerpilih) {
                            if ($penjualanTerpilih->reservasi != null) {
                                array_push($penjualanReservasiPelangganTerpilih, $penjualanTerpilih);
                            }
                        }


                        foreach ($penjualanReservasiPelangganTerpilih as $penjualanPelanggan) {

                            array_push($nomorNota, $penjualanPelanggan->nomor_nota);
                        }

                        $stringNomorNota = implode(", ", $nomorNota);

                        MailController::mailPemberitahuanKaryawanIzinSakit($pelanggan->user->email, $stringNomorNota);
                    }
                }
            }


            $tanggalUnikIzinHariIniKedepan = PresensiKehadiran::selectRaw("distinct DATE(tanggal_presensi) as tanggal_presensi")->whereRaw("DATE(tanggal_presensi) >= '" . $tanggalHariIni . "'")->where(function ($query) {
                $query->where('keterangan', 'sakit')
                    ->orWhere('keterangan', 'izin');
            })->orderBy("tanggal_presensi", "asc")->get();
            $daftarIzinPresensiHariIniKedepan = [];
            foreach ($tanggalUnikIzinHariIniKedepan as $tanggalIzin) {
                $objIzinSementara = [];

                $hariIndonesia = array('Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu');
                $nomorHariDalamMingguan = date("w", strtotime($tanggalIzin->tanggal_presensi));
                $tanggalIzinTeks = $hariIndonesia[$nomorHariDalamMingguan] . ", " . date('d-m-Y', strtotime($tanggalIzin->tanggal_presensi));

                $objIzinSementara["daftarIzin"] = PresensiKehadiran::whereRaw("DATE(tanggal_presensi) = '" . $tanggalIzin->tanggal_presensi . "'")->where(function ($query) {
                    $query->where('keterangan', 'sakit')
                        ->orWhere('keterangan', 'izin');
                })->orderBy("tanggal_presensi", "asc")->get();
                $objIzinSementara["jumlahKaryawan"] = PresensiKehadiran::selectRaw("DISTINCT karyawan_id")->whereRaw("DATE(tanggal_presensi) = '" . $tanggalIzin->tanggal_presensi . "'")->where(function ($query) {
                    $query->where('keterangan', 'sakit')
                        ->orWhere('keterangan', 'izin');
                })->count("id");
                $objIzinSementara["tanggalIzin"] = $tanggalIzin->tanggal_presensi;
                $objIzinSementara["tanggalIzinHari"] = $tanggalIzinTeks;
                array_push($daftarIzinPresensiHariIniKedepan, $objIzinSementara);

            }
            return response()->json(array('updated_at' => date("d-m-Y H:i", strtotime($objPresensi->updated_at)), 'msg' => view('admin.karyawan.presensikehadiran.daftarizinhariinikedepan', compact('daftarIzinPresensiHariIniKedepan'))->render(), "waktu" => "hariinikedepan"), 200);
        } else {
            $tanggalUnikIzinSebelumnya = PresensiKehadiran::selectRaw("distinct DATE(tanggal_presensi) as tanggal_presensi")->whereRaw("DATE(tanggal_presensi) < '" . $tanggalHariIni . "'")->where(function ($query) {
                $query->where('keterangan', 'sakit')
                    ->orWhere('keterangan', 'izin');
            })->orderBy("tanggal_presensi", "desc")->get();

            $daftarIzinPresensiHariSebelumnya = [];
            foreach ($tanggalUnikIzinSebelumnya as $tanggalIzin) {
                $objIzinSementara = [];

                $hariIndonesia = array('Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu');
                $nomorHariDalamMingguan = date("w", strtotime($tanggalIzin->tanggal_presensi));
                $tanggalIzinTeks = $hariIndonesia[$nomorHariDalamMingguan] . ", " . date('d-m-Y', strtotime($tanggalIzin->tanggal_presensi));

                $objIzinSementara["daftarIzin"] = PresensiKehadiran::whereRaw("DATE(tanggal_presensi) = '" . $tanggalIzin->tanggal_presensi . "'")->where(function ($query) {
                    $query->where('keterangan', 'sakit')
                        ->orWhere('keterangan', 'izin');
                })->orderBy("tanggal_presensi", "asc")->get();
                $objIzinSementara["jumlahKaryawan"] = PresensiKehadiran::selectRaw("DISTINCT karyawan_id")->whereRaw("DATE(tanggal_presensi) = '" . $tanggalIzin->tanggal_presensi . "'")->where(function ($query) {
                    $query->where('keterangan', 'sakit')
                        ->orWhere('keterangan', 'izin');
                })->count("id");
                $objIzinSementara["tanggalIzin"] = $tanggalIzin->tanggal_presensi;
                $objIzinSementara["tanggalIzinHari"] = $tanggalIzinTeks;
                array_push($daftarIzinPresensiHariSebelumnya, $objIzinSementara);

            }
            return response()->json(array('updated_at' => date("d-m-Y H:i", strtotime($objPresensi->updated_at)), 'msg' => view('admin.karyawan.presensikehadiran.daftarizinsebelumnya', compact('daftarIzinPresensiHariSebelumnya'))->render(), "waktu" => "sebelumnya"), 200);
        }

    }

    public function presensiHariIniKaryawanSalon()
    {
        date_default_timezone_set("Asia/Jakarta");
        $tanggalHariIni = date("Y-m-d");
        $karyawan = Auth::user()->karyawan;

        $idPresensiKaryawanMax = PresensiKehadiran::whereRaw("DATE(tanggal_presensi) = '" . $tanggalHariIni . "'")->where("karyawan_id", $karyawan->id)->max("id");
        $presensiKaryawan = PresensiKehadiran::find($idPresensiKaryawanMax);
        $idMaxPresensiHariIniPerKaryawan = PresensiKehadiran::selectRaw("max(id) as id")->whereRaw("DATE(tanggal_presensi) = '" . $tanggalHariIni . "'")->groupBy("karyawan_id")->get();
        $presensisHariIni = PresensiKehadiran::whereIn("id", $idMaxPresensiHariIniPerKaryawan)->get();
        $jumlahKaryawan = Karyawan::count("id");
        $idKaryawanUnikIzin = PresensiKehadiran::selectRaw("DISTINCT karyawan_id ")->where(function ($query) {
            $query->where('keterangan', 'sakit')
                ->orWhere('keterangan', 'izin');
        })->whereRaw("DATE(tanggal_presensi) = '" . $tanggalHariIni . "'")->get();

        $objectPertamaYangtanpaIzin = PresensiKehadiran::whereRaw("DATE(tanggal_presensi) = '" . $tanggalHariIni . "'")->whereNotIn("keterangan", ["izin", "sakit"])->first();

        return view("karyawansalon.presensikaryawan.presensihariini", compact("presensiKaryawan", "presensisHariIni", "idKaryawanUnikIzin", "jumlahKaryawan", "objectPertamaYangtanpaIzin"));
    }

    public function prosesPresensiHariIniKaryawanSalon(Request $request)
    {

        date_default_timezone_set("Asia/Jakarta");
        $idPresensi = $request->get("idPresensiKaryawan");
        $keteranganPresensi = $request->get("radioKeteranganPresensi");

        $presensi = PresensiKehadiran::find($idPresensi);
        $presensi->keterangan = $keteranganPresensi;
        $presensi->tanggal_presensi = date("Y-m-d H:i:s");
        $presensi->save();

        return redirect()->route("karyawans.presensihariinikaryawansalon")->with("status", "Berhasil melakukan presensi untuk tanggal " . date("d-m-Y") . "!");

    }

    public function riwayatPresensiKaryawanSalon()
    {
        date_default_timezone_set("Asia/Jakarta");
        $tanggalHariIni = date("Y-m-d");
        $karyawan = Auth::user()->karyawan;

        $daftarIdRiwayatPresensi = PresensiKehadiran::selectRaw("max(id) as id")->whereRaw("DATE(tanggal_presensi) < '" . $tanggalHariIni . "'")->where("karyawan_id", $karyawan->id)->groupByRaw("DATE(tanggal_presensi)")->orderByRaw("DATE(tanggal_presensi) asc")->get();
        $daftarRiwayatPresensis = PresensiKehadiran::whereIn("id", $daftarIdRiwayatPresensi)->orderBy("id", "desc")->get();
        return view("karyawansalon.presensikaryawan.riwayatpresensi", compact("daftarRiwayatPresensis"));
    }

    public function daftarIzinKaryawanSalon()
    {
        date_default_timezone_set("Asia/Jakarta");
        $tanggalHariIni = date("Y-m-d");
        $karyawan = Auth::user()->karyawan;
        $daftarRiwayatIzinKaryawanHriIniKedepan = PresensiKehadiran::where(function ($query) {
            $query->where('keterangan', 'sakit')
                ->orWhere('keterangan', 'izin');
        })->whereRaw("DATE(tanggal_presensi) >= '" . $tanggalHariIni . "'")->where("karyawan_id", $karyawan->id)->orderByRaw("DATE(tanggal_presensi) desc")->get();
        $daftarRiwayatIzinKaryawanSebelumnya = PresensiKehadiran::where(function ($query) {
            $query->where('keterangan', 'sakit')
                ->orWhere('keterangan', 'izin');
        })->whereRaw("DATE(tanggal_presensi) < '" . $tanggalHariIni . "'")->where("karyawan_id", $karyawan->id)->orderByRaw("DATE(tanggal_presensi) desc")->get();

        return view("karyawansalon.presensikaryawan.daftarizinkaryawan", compact("daftarRiwayatIzinKaryawanHriIniKedepan", "daftarRiwayatIzinKaryawanSebelumnya"));
    }

    public function prosesIzinKaryawanSalon(Request $request)
    {


        if ($request->get("deskripsiIzin") == null) {
            return redirect()->back()->withInput()->withErrors("Deskripsi Keterangan Izin tidak boleh kosong ketika ingin mengajukan Izin!");
        }

        date_default_timezone_set("Asia/Jakarta");
        $tanggalIzin = $request->get("tanggalIzin");
        $keteranganizin = $request->get("radioKeteranganPresensi");
        $deskripsiIzin = $request->get("deskripsiIzin");
        $fileTambahan = $request->file("fileTambahan");
        $karyawan = Auth::user()->karyawan;

        $idMaxPresensiHariTersebut = PresensiKehadiran::whereRaw("DATE(tanggal_presensi) = '" . $tanggalIzin . "'")->where("karyawan_id", $karyawan->id)->max("id");
        // $checkIzin = PresensiKehadiran::where("status", "izin")->whereRaw("DATE(tanggal_presensi) = '" . $tanggalIzin . "'")->where("karyawan_id", $karyawan->id)->first();
        $checkIzin = PresensiKehadiran::find($idMaxPresensiHariTersebut);


        if ($checkIzin == null) {
            $newIzinKaryawan = new PresensiKehadiran();
            $newIzinKaryawan->karyawan_id = $karyawan->id;
            $newIzinKaryawan->karyawan_id = $karyawan->id;
            $newIzinKaryawan->tanggal_presensi = $tanggalIzin;

            if ($keteranganizin == "izin") {
                $newIzinKaryawan->keterangan = "izin";
            } else {
                $newIzinKaryawan->keterangan = "sakit";
            }

            $newIzinKaryawan->deskripsi = $deskripsiIzin;


            $newIzinKaryawan->status = "belum";
            $newIzinKaryawan->updated_at = date("Y-m-d H:i:s");
            $newIzinKaryawan->created_at = date("Y-m-d H:i:s");
            $newIzinKaryawan->save();

            if ($fileTambahan != null) {
                $extensionImage = $fileTambahan->getClientOriginalExtension();
                $namaImage = "keterangan-" . $keteranganizin . "-" . $newIzinKaryawan->id . "." . $extensionImage;
                $fileTambahan->move(public_path('assets_admin/images/izin_sakit_karyawan'), $namaImage);
                $newIzinKaryawan->file_tambahan = $namaImage;
                $newIzinKaryawan->updated_at = date("Y-m-d H:i:s");
                $newIzinKaryawan->save();
            }

            return redirect()->route("karyawans.daftarizinkaryawansalon")->with("status", "Berhasil mengajukan izin kehadiran untuk tanggal " . date("d-m-Y", strtotime($tanggalIzin)) . "!");
        } else {
            if ($checkIzin->keterangan == "izin" || $checkIzin->keterangan == "sakit") {
                return redirect()->route("karyawans.daftarizinkaryawansalon")->withErrors("Anda sudah pernah mengajukan izin untuk tanggal " . date("d-m-Y", strtotime($tanggalIzin)) . "!");
            } else {
                return redirect()->route("karyawans.daftarizinkaryawansalon")->withErrors("Anda tidak dapat mengajukan izin untuk tanggal " . date("d-m-Y", strtotime($tanggalIzin)) . ", karena sudah terdapat presensi untuk Anda pada tanggal tersebut!");
            }

        }
    }


}
