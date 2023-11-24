<?php

namespace App\Http\Controllers;

use App\Models\Diskon;
use App\Models\Karyawan;
use App\Models\Paket;
use App\Models\Pelanggan;
use App\Models\Penjualan;
use App\Models\PenjualanPerawatan;
use App\Models\Perawatan;
use App\Models\Produk;
use App\Models\Reservasi;
use App\Models\SlotJam;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ReservasiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        date_default_timezone_set('Asia/Jakarta');
        $tanggalHariIni = date('Y-m-d');
        $reservasis = Reservasi::orderBy('status', 'asc')->whereRaw("DATE(tanggal_reservasi) = '" . $tanggalHariIni . "'")->orderBy("tanggal_reservasi", "asc")->get();


        $reservasisAkanDatang = Reservasi::orderBy('status', 'asc')->whereRaw("DATE(tanggal_reservasi) > '" . $tanggalHariIni . "'")->orderBy("tanggal_reservasi", "asc")->get();


        return view('admin.reservasi.index', compact('reservasis', 'reservasisAkanDatang'));
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
     * @param  \App\Models\Reservasi  $reservasi
     * @return \Illuminate\Http\Response
     */
    public function show(Reservasi $reservasi)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Reservasi  $reservasi
     * @return \Illuminate\Http\Response
     */
    public function edit(Reservasi $reservasi)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Reservasi  $reservasi
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Reservasi $reservasi)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Reservasi  $reservasi
     * @return \Illuminate\Http\Response
     */
    public function destroy(Reservasi $reservasi)
    {
        //
    }

    public function reservasiAdminCreate()
    {
        date_default_timezone_set('Asia/Jakarta');
        $hariIndonesia = array('Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu');
        $perawatans = Perawatan::select('id', 'kode_perawatan', 'nama', 'harga', 'deskripsi', 'durasi')->where('status', 'aktif')->orderBy('nama')->get();
        $pakets = Paket::where('status', 'aktif')->orderBy("nama")->get();

        $tanggal = date('d-m-Y');
        $tanggalPertamaDalamMinggu = date('Y-m-d');

        $tanggalTerakhirDalamMinggu = date('Y-m-d', strtotime('this saturday'));

        $nomorHariDalamMingguan = date("w");
        $tanggalHariIni = $hariIndonesia[$nomorHariDalamMingguan] . ", " . date('d-m-Y');

        $slotJams = SlotJam::where('hari', $hariIndonesia[$nomorHariDalamMingguan])->where('jam', ">=", date("H.i"))->get();
        $daftarPelanggans = Pelanggan::all();
        return view('admin.reservasi.buatreservasiadmin', compact('perawatans', 'slotJams', 'tanggalHariIni', 'tanggalPertamaDalamMinggu', 'tanggalTerakhirDalamMinggu', 'daftarPelanggans', 'pakets'));
    }

    public function reservasiAdminPilihKaryawan(Request $request)
    {
        $tanggalReservasi = $request->get("tanggalReservasi");
        $idSlotJam = $request->get("slotJam");
        $arrPerawatan = $request->get("arrayperawatanid");
        $idPelanggan = $request->get("idPelanggan");
        $arrKodeKeseluruhan = $request->get("arraykodekeseluruhan");
        $arrPaket = $request->get("arraypaketid");
        $keteranganPilihKaryawan = $request->get("keteranganPilihKaryawan");

        $pesanError = [];
        if ($idSlotJam == null || ($arrPerawatan == null && $arrPaket == null) || $idPelanggan == null) {
            if ($idSlotJam == null) {
                array_push($pesanError, "Slot Jam tidak boleh kosong!");
            }
            if ($arrPerawatan == null && $arrPaket == null) {
                array_push($pesanError, "Harap memilih setidaknya satu perawatan atau satu paket!");
            }
            if ($idPelanggan == null) {
                array_push($pesanError, "Nama Pelanggan tidak boleh kosong!");
            }
            return redirect()->back()->withErrors($pesanError);
        }

        $arrPerawatanSend = [];
        if ($arrPerawatan != null) {
            foreach ($arrPerawatan as $ap) {
                $p = Perawatan::select('id', 'kode_perawatan', 'nama', 'harga', 'deskripsi', 'durasi')->where('status', 'aktif')->where('id', $ap)->first();
                array_push($arrPerawatanSend, $p);
            }
        }

        $arrPaketSend = [];
        if ($arrPaket != null) {
            foreach ($arrPaket as $idPaket) {
                $paketvar = Paket::where('status', 'aktif')->where('id', $idPaket)->first();
                array_push($arrPaketSend, $paketvar);
            }
        }

        $daftarPelanggans = Pelanggan::all();
        $varSlotJam = SlotJam::find($idSlotJam);
        $daftarSlotJam = SlotJam::where('hari', $varSlotJam->hari)->where('status', 'aktif')->orderBy('id')->get();
        return redirect()->route('reservasi.admin.create')->with([
            'idPelanggan' => $idPelanggan,
            'daftarPelanggans' => $daftarPelanggans,
            'idSlotJam' => $idSlotJam,
            'daftarSlotJam' => $daftarSlotJam,
            'tanggalReservasi' => $tanggalReservasi,
            'arrPerawatan' => $arrPerawatan,
            'arrPerawatanObject' => $arrPerawatanSend,
            'arrPaket' => $arrPaket,
            'arrPaketObject' => $arrPaketSend,
            'arrKodeKeseluruhan' => $arrKodeKeseluruhan,

        ])->withErrors('Perawatan yang dipilih telah melewati batas jam buka pada hari tersebut!');

        dd($request->all());
        //ambil data dari form buatreservasiadmin
        date_default_timezone_set('Asia/Jakarta');
        $tanggalReservasi = $request->get("tanggalReservasi");
        $idSlotJam = $request->get("slotJam");
        $arrPerawatan = $request->get("arrayperawatanid");
        $idPelanggan = $request->get("idPelanggan");
        $keteranganPilihKaryawan = $request->get("keteranganPilihKaryawan");

        $pesanError = [];
        if ($idSlotJam == null || $arrPerawatan == null || $idPelanggan == null) {
            if ($idSlotJam == null) {
                array_push($pesanError, "Slot Jam tidak boleh kosong!");
            }
            if ($arrPerawatan == null || $arrPaket == null) {
                array_push($pesanError, "Harap memilih setidaknya satu perawatan atau satu paket!");
            }
            if ($idPelanggan == null) {
                array_push($pesanError, "Nama Pelanggan tidak boleh kosong!");
            }
            return redirect()->back()->withErrors($pesanError);
        }

        //buat array kosongan untuk simpan perawatan slot jam perawatan komplemen dan nonkomplemen
        $perawatanSlotJamNonKomplemen = [];
        $perawatanSlotJamKomplemen = [];

        //select perawatan komplemen dan non yang id nya sesuai dengan id perawtan yang dipilih dari form buatreservasiadmin
        $perawatanNonKomplemen = [];
        $perawatanKomplemen = [];
        //$arrNonKomplemenSementara = Perawatan::select('id')->where('status_komplemen', 'tidak')->whereIn('id', $arrPerawatan)->get();
        //$arrKomplemenSementara = Perawatan::select('id')->where('status_komplemen', 'ya')->whereIn('id', $arrPerawatan)->get();
        foreach ($arrPerawatan as $p) {
            $produkSementara = Perawatan::find($p);
            if ($produkSementara->status_komplemen == "tidak") {
                array_push($perawatanNonKomplemen, $produkSementara);
            } else {
                array_push($perawatanKomplemen, $produkSementara);
            }

        }

        //Pengecekan perawatan yang dipilih tidak boleh melebihi jam tutup
        $penghitungJam = SlotJam::find($idSlotJam);
        $jamPengubah = strtotime($penghitungJam->jam);
        $jamTutup = SlotJam::where('hari', $penghitungJam->hari)->where('status', 'aktif')->max('jam');
        $jamTutup = strtotime("+30 minutes", strtotime($jamTutup));

        foreach ($perawatanNonKomplemen as $perawatan) {
            $jumlahSlotTerpakai = ceil($perawatan->durasi / 30);
            $jamPengubah = strtotime("+" . ($jumlahSlotTerpakai * 30) . " minutes", $jamPengubah);
        }

        $maxDurasiPerawatanKomplemen = Perawatan::where('status_komplemen', 'ya')->whereIn('id', $arrPerawatan)->max('durasi');
        $jumlahSlotTerpakaiMax = ceil($maxDurasiPerawatanKomplemen / 30);
        $jamPengubah = strtotime("+" . ($jumlahSlotTerpakaiMax * 30) . " minutes", $jamPengubah);
        // $jamJamSekarang = date('H.i', $jamPengubah);
        // $jamJamTutup = date('H.i', $jamTutup);

        // dd($jamJamSekarang, $jamJamTutup);
        if ($jamPengubah > $jamTutup) {
            $arrPerawatanSend = [];
            foreach ($arrPerawatan as $ap) {
                $p = Perawatan::select('id', 'kode_perawatan', 'nama', 'harga', 'deskripsi', 'durasi')->where('status', 'aktif')->where('id', $ap)->first();
                array_push($arrPerawatanSend, $p);
            }
            $daftarPelanggans = Pelanggan::all();
            $varSlotJam = SlotJam::find($idSlotJam);
            $daftarSlotJam = SlotJam::where('hari', $varSlotJam->hari)->where('status', 'aktif')->orderBy('id')->get();
            return redirect()->route('reservasi.admin.create')->with([
                'idPelanggan' => $idPelanggan,
                'daftarPelanggans' => $daftarPelanggans,
                'idSlotJam' => $idSlotJam,
                'daftarSlotJam' => $daftarSlotJam,
                'tanggalReservasi' => $tanggalReservasi,
                'arrPerawatan' => $arrPerawatan,
                'arrPerawatanObject' => $arrPerawatanSend,
            ])->withErrors('Perawatan yang dipilih telah melewati batas jam buka pada hari tersebut!');

        } else {
            if ($keteranganPilihKaryawan == "ya") {
                //cari slot jam mulai dari form buatreservasiadmin
                $slotJamBerubah = SlotJam::find($idSlotJam);

                //BAGIAN PERAWATAN NON KOMPLEMEN-------------------------------------------------------------------------
                //foreach $perawatanNonKomplemen untuk mencari karyawan tersedia untuk setiap perawatan
                foreach ($perawatanNonKomplemen as $perawatan) {
                    $perawatanPerSlot = [];
                    $perawatanPerSlot["perawatan"] = $perawatan;
                    $perawatanPerSlot["jammulai"] = $slotJamBerubah->jam;

                    $jumlahSlotTerpakai = ceil($perawatan->durasi / 30);
                    $intJamTerakhir = strtotime("+" . ($jumlahSlotTerpakai * 30) - 30 . " minutes", strtotime($slotJamBerubah->jam));
                    $jamTerakhir = date('H.i', $intJamTerakhir);
                    $arrIdSlotJamTerpakai = SlotJam::select('id')->where('jam', '>=', $slotJamBerubah->jam)->where('jam', '<=', $jamTerakhir)->where('hari', $slotJamBerubah->hari)->get();
                    $arrObjectSlotJamTerpakai = SlotJam::where('jam', '>=', $slotJamBerubah->jam)->where('jam', '<=', $jamTerakhir)->where('hari', $slotJamBerubah->hari)->get();
                    // dd($jumlahSlotTerpakai, count($arrIdSlotJamTerpakai), $arrIdSlotJamTerpakai);

                    //mengecek apakah perawatan yang dipilih pelanggan memuat slot jam yang sedang ditutup atau tidak aktif
                    $slotJamTutup = [];
                    foreach ($arrObjectSlotJamTerpakai as $slotJam) {
                        if ($slotJam->status == 'nonaktif') {
                            array_push($slotJamTutup, $slotJam->jam);
                        }
                    }
                    if (count($slotJamTutup) > 0) {
                        $stringArrJamTutup = implode(', ', $slotJamTutup);
                        $arrPerawatanSend = [];
                        foreach ($arrPerawatan as $ap) {
                            $p = Perawatan::select('id', 'kode_perawatan', 'nama', 'harga', 'deskripsi', 'durasi')->where('status', 'aktif')->where('id', $ap)->first();
                            array_push($arrPerawatanSend, $p);
                        }
                        $daftarPelanggans = Pelanggan::all();
                        $varSlotJam = SlotJam::find($idSlotJam);
                        $daftarSlotJam = SlotJam::where('hari', $varSlotJam->hari)->where('status', 'aktif')->orderBy('id')->get();
                        return redirect()->route('reservasi.admin.create')->with([
                            'idPelanggan' => $idPelanggan,
                            'daftarPelanggans' => $daftarPelanggans,
                            'idSlotJam' => $idSlotJam,
                            'daftarSlotJam' => $daftarSlotJam,
                            'tanggalReservasi' => $tanggalReservasi,
                            'arrPerawatan' => $arrPerawatan,
                            'arrPerawatanObject' => $arrPerawatanSend,
                        ])->withErrors('Perawatan yang dipilih memuat jam waktu tutup salon yaitu pada jam ' . $stringArrJamTutup);
                    }

                    $daftarSlotJam = [];
                    foreach ($arrIdSlotJamTerpakai as $sj) {
                        array_push($daftarSlotJam, $sj->id);
                    }
                    $stringDaftarSlotJam = implode(".", $daftarSlotJam);
                    $perawatanPerSlot['idslotjam'] = $stringDaftarSlotJam;

                    $arrIdKaryawanTerpakaiSlotJam = Karyawan::select('karyawans.id')->distinct()
                        ->join('karyawan_perawatan', 'karyawan_perawatan.karyawan_id', '=', 'karyawans.id')
                        ->join('penjualan_perawatan', 'penjualan_perawatan.karyawan_id', '=', 'karyawans.id')
                        ->join('penjualans', 'penjualans.id', '=', 'penjualan_perawatan.penjualan_id')
                        ->join('slot_jam_penjualan_perawatan', 'slot_jam_penjualan_perawatan.penjualan_perawatan_id', '=', 'penjualan_perawatan.id')
                        ->join('slot_jams', 'slot_jams.id', '=', 'slot_jam_penjualan_perawatan.slot_jam_id')
                        ->whereIn('slot_jams.id', $arrIdSlotJamTerpakai)
                        ->where('penjualans.tanggal_penjualan', $tanggalReservasi)
                        ->where('penjualans.status_selesai', 'belum')
                        ->get();

                    $arrIdKaryawanIzinSakit = Karyawan::select('karyawans.id')->distinct()
                        ->join('presensi_kehadiran', 'presensi_kehadiran.karyawan_id', '=', 'karyawans.id')
                        ->where(function ($query) {
                            $query->where('presensi_kehadiran.keterangan', 'izin')->orWhere('presensi_kehadiran.keterangan', 'sakit');
                        })->whereRaw("DATE(presensi_kehadiran.tanggal_presensi) = '" . $tanggalReservasi . "'")->where("presensi_kehadiran.status", "konfirmasi")->get();

                    // $idKaryawan
                    $karyawanTersedia = Karyawan::select('karyawans.*')->join('karyawan_perawatan', 'karyawan_perawatan.karyawan_id', '=', 'karyawans.id')
                        ->join('perawatans', 'perawatans.id', '=', 'karyawan_perawatan.perawatan_id')
                        ->whereNotIn('karyawans.id', $arrIdKaryawanTerpakaiSlotJam)->whereNotIn('karyawans.id', $arrIdKaryawanIzinSakit)->where('perawatans.id', $perawatan->id)->get();


                    $perawatanPerSlot["karyawans"] = $karyawanTersedia;
                    array_push($perawatanSlotJamNonKomplemen, $perawatanPerSlot);

                    //update slotJamBerubah ke slot selanjutnya
                    $intJamTerakhir = strtotime("+30 minutes", strtotime($jamTerakhir));
                    $jamTerakhir = date('H.i', $intJamTerakhir);
                    $slotJamBerubah = SlotJam::where('jam', $jamTerakhir)->where('hari', $slotJamBerubah->hari)->first();
                }

                //BAGIAN PERAWATAN KOMPLEMEN-------------------------------------------------------------------------
                $arrKomplemen = [];
                $arrKomplemen['jammulai'] = $slotJamBerubah->jam;

                $durasiTerlamaPerawatanKomplemen = Perawatan::where('status_komplemen', 'ya')->whereIn('id', $arrPerawatan)->max('durasi');
                $arrKomplemen['durasiterlama'] = $durasiTerlamaPerawatanKomplemen;
                $jumlahSlotTerpakai = ceil($durasiTerlamaPerawatanKomplemen / 30);
                $intJamTerakhir = strtotime("+" . ($jumlahSlotTerpakai * 30) - 30 . " minutes", strtotime($slotJamBerubah->jam));
                $jamTerakhir = date('H.i', $intJamTerakhir);

                $arrIdSlotJamTerpakai = SlotJam::select('id')->where('jam', '>=', $slotJamBerubah->jam)->where('jam', '<=', $jamTerakhir)->where('hari', $slotJamBerubah->hari)->get();
                $arrObjectSlotJamTerpakai = SlotJam::where('jam', '>=', $slotJamBerubah->jam)->where('jam', '<=', $jamTerakhir)->where('hari', $slotJamBerubah->hari)->get();
                //mengecek apakah perawatan yang dipilih pelanggan memuat slot jam yan sedang ditutup atau tidak aktif
                $slotJamTutup = [];
                foreach ($arrObjectSlotJamTerpakai as $slotJam) {
                    if ($slotJam->status == 'nonaktif') {
                        array_push($slotJamTutup, $slotJam->jam);
                    }
                }
                if (count($slotJamTutup) > 0) {
                    $stringArrJamTutup = implode(', ', $slotJamTutup);
                    $arrPerawatanSend = [];
                    foreach ($arrPerawatan as $ap) {
                        $p = Perawatan::select('id', 'kode_perawatan', 'nama', 'harga', 'deskripsi', 'durasi')->where('status', 'aktif')->where('id', $ap)->first();
                        array_push($arrPerawatanSend, $p);
                    }
                    $daftarPelanggans = Pelanggan::all();
                    $varSlotJam = SlotJam::find($idSlotJam);
                    $daftarSlotJam = SlotJam::where('hari', $varSlotJam->hari)->where('status', 'aktif')->orderBy('id')->get();
                    return redirect()->route('reservasi.admin.create')->with([
                        'idPelanggan' => $idPelanggan,
                        'daftarPelanggans' => $daftarPelanggans,
                        'idSlotJam' => $idSlotJam,
                        'daftarSlotJam' => $daftarSlotJam,
                        'tanggalReservasi' => $tanggalReservasi,
                        'arrPerawatan' => $arrPerawatan,
                        'arrPerawatanObject' => $arrPerawatanSend,
                    ])->withErrors('Perawatan yang dipilih memuat jam waktu tutup salon yaitu pada jam ' . $stringArrJamTutup);
                }

                $daftarSlotJam = [];
                foreach ($arrIdSlotJamTerpakai as $sj) {
                    array_push($daftarSlotJam, $sj->id);
                }
                $stringDaftarSlotJam = implode(".", $daftarSlotJam);
                $arrKomplemen['idslotjam'] = $stringDaftarSlotJam;

                $arrIdKaryawanTerpakaiSlotJam = Karyawan::select('karyawans.id')->distinct()
                    ->join('karyawan_perawatan', 'karyawan_perawatan.karyawan_id', '=', 'karyawans.id')
                    ->join('penjualan_perawatan', 'penjualan_perawatan.karyawan_id', '=', 'karyawans.id')
                    ->join('penjualans', 'penjualans.id', '=', 'penjualan_perawatan.penjualan_id')
                    ->join('slot_jam_penjualan_perawatan', 'slot_jam_penjualan_perawatan.penjualan_perawatan_id', '=', 'penjualan_perawatan.id')
                    ->join('slot_jams', 'slot_jams.id', '=', 'slot_jam_penjualan_perawatan.slot_jam_id')
                    ->whereIn('slot_jams.id', $arrIdSlotJamTerpakai)
                    ->where('penjualans.tanggal_penjualan', $tanggalReservasi)
                    ->where('penjualans.status_selesai', 'belum')
                    ->get();

                $arrIdKaryawanIzinSakit = Karyawan::select('karyawans.id')->distinct()
                    ->join('presensi_kehadiran', 'presensi_kehadiran.karyawan_id', '=', 'karyawans.id')
                    ->where(function ($query) {
                        $query->where('presensi_kehadiran.keterangan', 'izin')->orWhere('presensi_kehadiran.keterangan', 'sakit');
                    })->whereRaw("DATE(presensi_kehadiran.tanggal_presensi) = '" . $tanggalReservasi . "'")->where("presensi_kehadiran.status", "konfirmasi")->get();


                foreach ($perawatanKomplemen as $perawatanK) {
                    $perawatanPerSlot = [];
                    $perawatanPerSlot["perawatan"] = $perawatanK;
                    $karyawanTersedia = Karyawan::select('karyawans.*')->join('karyawan_perawatan', 'karyawan_perawatan.karyawan_id', '=', 'karyawans.id')
                        ->join('perawatans', 'perawatans.id', '=', 'karyawan_perawatan.perawatan_id')
                        ->whereNotIn('karyawans.id', $arrIdKaryawanTerpakaiSlotJam)->whereNotIn('karyawans.id', $arrIdKaryawanIzinSakit)->where('perawatans.id', $perawatanK->id)->get();


                    $perawatanPerSlot["karyawans"] = $karyawanTersedia;
                    array_push($perawatanSlotJamKomplemen, $perawatanPerSlot);
                }
                $arrKomplemen['array'] = $perawatanSlotJamKomplemen;

                $keteranganNull = 0;

                foreach ($perawatanSlotJamNonKomplemen as $ps) {
                    if (count($ps['karyawans']) == 0) {
                        $keteranganNull += 1;
                    }
                }
                foreach ($arrKomplemen['array'] as $ps) {
                    if (count($ps['karyawans']) == 0) {
                        $keteranganNull += 1;
                    }
                }
                if ($keteranganNull > 0) {
                    $pesanError = "Terdapat perawatan yang memiliki karyawan yang tidak tersedia pada jam tersebut, silahkan mengatur ulang jam mulai, urutan perawatan Anda, atau hari reservasi lainnya!";
                    return view('admin.reservasi.pilihkaryawan', compact('perawatanSlotJamNonKomplemen', 'arrKomplemen', 'tanggalReservasi', 'idSlotJam', 'arrPerawatan', 'idPelanggan', 'pesanError'));
                } else {
                    return view('admin.reservasi.pilihkaryawan', compact('perawatanSlotJamNonKomplemen', 'arrKomplemen', 'tanggalReservasi', 'idSlotJam', 'arrPerawatan', 'idPelanggan'));
                }

            } else {
                //cari slot jam mulai dari form buatreservasiadmin
                $slotJamBerubah = SlotJam::find($idSlotJam);

                //BAGIAN PERAWATAN NON KOMPLEMEN-------------------------------------------------------------------------
                //foreach $perawatanNonKomplemen untuk mencari karyawan tersedia untuk setiap perawatan
                foreach ($perawatanNonKomplemen as $perawatan) {
                    $perawatanPerSlot = [];
                    $perawatanPerSlot["perawatan"] = $perawatan;
                    $perawatanPerSlot["jammulai"] = $slotJamBerubah->jam;

                    $jumlahSlotTerpakai = ceil($perawatan->durasi / 30);
                    $intJamTerakhir = strtotime("+" . ($jumlahSlotTerpakai * 30) - 30 . " minutes", strtotime($slotJamBerubah->jam));
                    $jamTerakhir = date('H.i', $intJamTerakhir);
                    $arrIdSlotJamTerpakai = SlotJam::select('id')->where('jam', '>=', $slotJamBerubah->jam)->where('jam', '<=', $jamTerakhir)->where('hari', $slotJamBerubah->hari)->get();
                    $arrObjectSlotJamTerpakai = SlotJam::where('jam', '>=', $slotJamBerubah->jam)->where('jam', '<=', $jamTerakhir)->where('hari', $slotJamBerubah->hari)->get();
                    // dd($jumlahSlotTerpakai, count($arrIdSlotJamTerpakai), $arrIdSlotJamTerpakai);

                    //mengecek apakah perawatan yang dipilih pelanggan memuat slot jam yang sedang ditutup atau tidak aktif
                    $slotJamTutup = [];
                    foreach ($arrObjectSlotJamTerpakai as $slotJam) {
                        if ($slotJam->status == 'nonaktif') {
                            array_push($slotJamTutup, $slotJam->jam);
                        }
                    }
                    if (count($slotJamTutup) > 0) {
                        $stringArrJamTutup = implode(', ', $slotJamTutup);
                        $arrPerawatanSend = [];
                        foreach ($arrPerawatan as $ap) {
                            $p = Perawatan::select('id', 'kode_perawatan', 'nama', 'harga', 'deskripsi', 'durasi')->where('status', 'aktif')->where('id', $ap)->first();
                            array_push($arrPerawatanSend, $p);
                        }
                        $daftarPelanggans = Pelanggan::all();
                        $varSlotJam = SlotJam::find($idSlotJam);
                        $daftarSlotJam = SlotJam::where('hari', $varSlotJam->hari)->where('status', 'aktif')->orderBy('id')->get();
                        return redirect()->route('reservasi.admin.create')->with([
                            'idPelanggan' => $idPelanggan,
                            'daftarPelanggans' => $daftarPelanggans,
                            'idSlotJam' => $idSlotJam,
                            'daftarSlotJam' => $daftarSlotJam,
                            'tanggalReservasi' => $tanggalReservasi,
                            'arrPerawatan' => $arrPerawatan,
                            'arrPerawatanObject' => $arrPerawatanSend,
                        ])->withErrors('Perawatan yang dipilih memuat jam waktu tutup salon yaitu pada jam ' . $stringArrJamTutup);
                    }

                    $daftarSlotJam = [];
                    foreach ($arrIdSlotJamTerpakai as $sj) {
                        array_push($daftarSlotJam, $sj->id);
                    }
                    $stringDaftarSlotJam = implode(".", $daftarSlotJam);
                    $perawatanPerSlot['idslotjam'] = $stringDaftarSlotJam;

                    $arrIdKaryawanTerpakaiSlotJam = Karyawan::select('karyawans.id')->distinct()
                        ->join('karyawan_perawatan', 'karyawan_perawatan.karyawan_id', '=', 'karyawans.id')
                        ->join('penjualan_perawatan', 'penjualan_perawatan.karyawan_id', '=', 'karyawans.id')
                        ->join('penjualans', 'penjualans.id', '=', 'penjualan_perawatan.penjualan_id')
                        ->join('slot_jam_penjualan_perawatan', 'slot_jam_penjualan_perawatan.penjualan_perawatan_id', '=', 'penjualan_perawatan.id')
                        ->join('slot_jams', 'slot_jams.id', '=', 'slot_jam_penjualan_perawatan.slot_jam_id')
                        ->whereIn('slot_jams.id', $arrIdSlotJamTerpakai)
                        ->where('penjualans.tanggal_penjualan', $tanggalReservasi)
                        ->where('penjualans.status_selesai', 'belum')
                        ->get();

                    $arrIdKaryawanIzinSakit = Karyawan::select('karyawans.id')->distinct()
                        ->join('presensi_kehadiran', 'presensi_kehadiran.karyawan_id', '=', 'karyawans.id')
                        ->where(function ($query) {
                            $query->where('presensi_kehadiran.keterangan', 'izin')->orWhere('presensi_kehadiran.keterangan', 'sakit');
                        })->whereRaw("DATE(presensi_kehadiran.tanggal_presensi) = '" . $tanggalReservasi . "'")->where("presensi_kehadiran.status", "konfirmasi")->get();

                    // $idKaryawan
                    $arrKaryawanTersedia = [];
                    $karyawanTersedia = Karyawan::select('karyawans.*')->join('karyawan_perawatan', 'karyawan_perawatan.karyawan_id', '=', 'karyawans.id')
                        ->join('perawatans', 'perawatans.id', '=', 'karyawan_perawatan.perawatan_id')
                        ->whereNotIn('karyawans.id', $arrIdKaryawanTerpakaiSlotJam)->whereNotIn('karyawans.id', $arrIdKaryawanIzinSakit)->where('perawatans.id', $perawatan->id)->first();

                    if ($karyawanTersedia != null) {
                        array_push($arrKaryawanTersedia, $karyawanTersedia);
                    }


                    $perawatanPerSlot["karyawans"] = $arrKaryawanTersedia;
                    array_push($perawatanSlotJamNonKomplemen, $perawatanPerSlot);

                    //update slotJamBerubah ke slot selanjutnya
                    $intJamTerakhir = strtotime("+30 minutes", strtotime($jamTerakhir));
                    $jamTerakhir = date('H.i', $intJamTerakhir);
                    $slotJamBerubah = SlotJam::where('jam', $jamTerakhir)->where('hari', $slotJamBerubah->hari)->first();
                }

                //BAGIAN PERAWATAN KOMPLEMEN-------------------------------------------------------------------------
                $arrKomplemen = [];
                $arrKomplemen['jammulai'] = $slotJamBerubah->jam;

                $durasiTerlamaPerawatanKomplemen = Perawatan::where('status_komplemen', 'ya')->whereIn('id', $arrPerawatan)->max('durasi');
                $arrKomplemen['durasiterlama'] = $durasiTerlamaPerawatanKomplemen;
                $jumlahSlotTerpakai = ceil($durasiTerlamaPerawatanKomplemen / 30);
                $intJamTerakhir = strtotime("+" . ($jumlahSlotTerpakai * 30) - 30 . " minutes", strtotime($slotJamBerubah->jam));
                $jamTerakhir = date('H.i', $intJamTerakhir);

                $arrIdSlotJamTerpakai = SlotJam::select('id')->where('jam', '>=', $slotJamBerubah->jam)->where('jam', '<=', $jamTerakhir)->where('hari', $slotJamBerubah->hari)->get();
                $arrObjectSlotJamTerpakai = SlotJam::where('jam', '>=', $slotJamBerubah->jam)->where('jam', '<=', $jamTerakhir)->where('hari', $slotJamBerubah->hari)->get();
                //mengecek apakah perawatan yang dipilih pelanggan memuat slot jam yan sedang ditutup atau tidak aktif
                $slotJamTutup = [];
                foreach ($arrObjectSlotJamTerpakai as $slotJam) {
                    if ($slotJam->status == 'nonaktif') {
                        array_push($slotJamTutup, $slotJam->jam);
                    }
                }
                if (count($slotJamTutup) > 0) {
                    $stringArrJamTutup = implode(', ', $slotJamTutup);
                    $arrPerawatanSend = [];
                    foreach ($arrPerawatan as $ap) {
                        $p = Perawatan::select('id', 'kode_perawatan', 'nama', 'harga', 'deskripsi', 'durasi')->where('status', 'aktif')->where('id', $ap)->first();
                        array_push($arrPerawatanSend, $p);
                    }
                    $daftarPelanggans = Pelanggan::all();
                    $varSlotJam = SlotJam::find($idSlotJam);
                    $daftarSlotJam = SlotJam::where('hari', $varSlotJam->hari)->where('status', 'aktif')->orderBy('id')->get();
                    return redirect()->route('reservasi.admin.create')->with([
                        'idPelanggan' => $idPelanggan,
                        'daftarPelanggans' => $daftarPelanggans,
                        'idSlotJam' => $idSlotJam,
                        'daftarSlotJam' => $daftarSlotJam,
                        'tanggalReservasi' => $tanggalReservasi,
                        'arrPerawatan' => $arrPerawatan,
                        'arrPerawatanObject' => $arrPerawatanSend,
                    ])->withErrors('Perawatan yang dipilih memuat jam waktu tutup salon yaitu pada jam ' . $stringArrJamTutup);
                }

                $daftarSlotJam = [];
                foreach ($arrIdSlotJamTerpakai as $sj) {
                    array_push($daftarSlotJam, $sj->id);
                }
                $stringDaftarSlotJam = implode(".", $daftarSlotJam);
                $arrKomplemen['idslotjam'] = $stringDaftarSlotJam;

                $arrIdKaryawanTerpakaiSlotJam = Karyawan::select('karyawans.id')->distinct()
                    ->join('karyawan_perawatan', 'karyawan_perawatan.karyawan_id', '=', 'karyawans.id')
                    ->join('penjualan_perawatan', 'penjualan_perawatan.karyawan_id', '=', 'karyawans.id')
                    ->join('penjualans', 'penjualans.id', '=', 'penjualan_perawatan.penjualan_id')
                    ->join('slot_jam_penjualan_perawatan', 'slot_jam_penjualan_perawatan.penjualan_perawatan_id', '=', 'penjualan_perawatan.id')
                    ->join('slot_jams', 'slot_jams.id', '=', 'slot_jam_penjualan_perawatan.slot_jam_id')
                    ->whereIn('slot_jams.id', $arrIdSlotJamTerpakai)
                    ->where('penjualans.tanggal_penjualan', $tanggalReservasi)
                    ->where('penjualans.status_selesai', 'belum')
                    ->get();

                $arrIdKaryawanIzinSakit = Karyawan::select('karyawans.id')->distinct()
                    ->join('presensi_kehadiran', 'presensi_kehadiran.karyawan_id', '=', 'karyawans.id')
                    ->where(function ($query) {
                        $query->where('presensi_kehadiran.keterangan', 'izin')->orWhere('presensi_kehadiran.keterangan', 'sakit');
                    })->whereRaw("DATE(presensi_kehadiran.tanggal_presensi) = '" . $tanggalReservasi . "'")->where("presensi_kehadiran.status", "konfirmasi")->get();


                foreach ($perawatanKomplemen as $perawatanK) {
                    $perawatanPerSlot = [];
                    $perawatanPerSlot["perawatan"] = $perawatanK;

                    $arrKaryawanTersedia = [];
                    $karyawanTersedia = Karyawan::select('karyawans.*')->join('karyawan_perawatan', 'karyawan_perawatan.karyawan_id', '=', 'karyawans.id')
                        ->join('perawatans', 'perawatans.id', '=', 'karyawan_perawatan.perawatan_id')
                        ->whereNotIn('karyawans.id', $arrIdKaryawanTerpakaiSlotJam)->whereNotIn('karyawans.id', $arrIdKaryawanIzinSakit)->where('perawatans.id', $perawatanK->id)->first();

                    if ($karyawanTersedia != null) {
                        array_push($arrKaryawanTersedia, $karyawanTersedia);
                    }

                    $perawatanPerSlot["karyawans"] = $arrKaryawanTersedia;
                    array_push($perawatanSlotJamKomplemen, $perawatanPerSlot);
                }
                $arrKomplemen['array'] = $perawatanSlotJamKomplemen;

                $keteranganNull = 0;
                $arrPerawatanTidakAdaKaryawan = [];
                foreach ($perawatanSlotJamNonKomplemen as $ps) {
                    if (count($ps['karyawans']) == 0) {
                        $keteranganNull += 1;
                        array_push($arrPerawatanTidakAdaKaryawan, $ps["perawatan"]->nama);
                    }
                }
                foreach ($arrKomplemen['array'] as $ps) {
                    if (count($ps['karyawans']) == 0) {
                        $keteranganNull += 1;
                        array_push($arrPerawatanTidakAdaKaryawan, $ps["perawatan"]->nama);
                    }
                }
                if ($keteranganNull > 0) {
                    $stringPerawatanTidakAdaKaryawan = implode(",", $arrPerawatanTidakAdaKaryawan);
                    $pesanError = "Terdapat perawatan seperti " . $stringPerawatanTidakAdaKaryawan . " yang memiliki karyawan yang tidak tersedia pada jam kerja, silahkan mengatur ulang jam mulai, urutan perawatan Anda, atau hari reservasi lainnya!";
                    $arrPerawatanSend = [];
                    foreach ($arrPerawatan as $ap) {
                        $p = Perawatan::select('id', 'kode_perawatan', 'nama', 'harga', 'deskripsi', 'durasi')->where('status', 'aktif')->where('id', $ap)->first();
                        array_push($arrPerawatanSend, $p);
                    }
                    $daftarPelanggans = Pelanggan::all();
                    $varSlotJam = SlotJam::find($idSlotJam);
                    $daftarSlotJam = SlotJam::where('hari', $varSlotJam->hari)->where('status', 'aktif')->orderBy('id')->get();
                    return redirect()->route('reservasi.admin.create')->with([
                        'idPelanggan' => $idPelanggan,
                        'daftarPelanggans' => $daftarPelanggans,
                        'idSlotJam' => $idSlotJam,
                        'daftarSlotJam' => $daftarSlotJam,
                        'tanggalReservasi' => $tanggalReservasi,
                        'arrPerawatan' => $arrPerawatan,
                        'arrPerawatanObject' => $arrPerawatanSend,
                    ])->withErrors($pesanError);

                } else {
                    // return view('admin.reservasi.pilihkaryawan', compact('perawatanSlotJamNonKomplemen', 'arrKomplemen', 'tanggalReservasi', 'idSlotJam', 'arrPerawatan', 'idPelanggan'));
                    $daftarKaryawanPerawatan = [];
                    $daftarKaryawanPerawatanKomplemen = [];

                    foreach ($perawatanSlotJamNonKomplemen as $ps) {
                        $stringSlot = "";
                        foreach ($ps['karyawans'] as $k) {
                            $stringSlot .= $k->id . "," . $ps['perawatan']->id . ",(" . $ps['idslotjam'] . ")";
                        }
                        array_push($daftarKaryawanPerawatan, $stringSlot);
                    }

                    foreach ($arrKomplemen['array'] as $ps) {
                        $stringSlot = "";
                        foreach ($ps['karyawans'] as $k) {
                            $stringSlot .= $k->id . "," . $ps['perawatan']->id . ",(" . $arrKomplemen['idslotjam'] . ")";
                        }
                        array_push($daftarKaryawanPerawatanKomplemen, $stringSlot);
                    }

                    $slotJamKaryawanTerpakai = [];
                    foreach ($daftarKaryawanPerawatan as $karyawanPerawatan) {
                        $idKaryawan = explode(",", $karyawanPerawatan)[0];
                        $idPerawatan = explode(",", $karyawanPerawatan)[1];


                        $arrayStringSlotjam = str_replace(["(", ")"], "", explode(",", $karyawanPerawatan)[2]);
                        $arraySlotJam = explode(".", $arrayStringSlotjam);

                        $karyawan = Karyawan::select('karyawans.id')->distinct()
                            ->join('penjualan_perawatan', 'penjualan_perawatan.karyawan_id', '=', 'karyawans.id')
                            ->join('penjualans', 'penjualans.id', '=', 'penjualan_perawatan.penjualan_id')
                            ->join('slot_jam_penjualan_perawatan', 'slot_jam_penjualan_perawatan.penjualan_perawatan_id', '=', 'penjualan_perawatan.id')
                            ->join('slot_jams', 'slot_jams.id', '=', 'slot_jam_penjualan_perawatan.slot_jam_id')
                            ->whereIn('slot_jams.id', $arraySlotJam)
                            ->where('penjualans.tanggal_penjualan', $tanggalReservasi)
                            ->where('karyawans.id', $idKaryawan)
                            ->get();

                        if (count($karyawan) > 0) {
                            $karyawanTerpakai = [];
                            $karyawanTerpakai['karyawan'] = Karyawan::find($idKaryawan);
                            $karyawanTerpakai['perawatan'] = Perawatan::find($idPerawatan);
                            $slotJam = SlotJam::whereIn('id', $arraySlotJam)->get();
                            $array = [];
                            foreach ($slotJam as $sj) {
                                array_push($array, $sj->jam);
                            }
                            $karyawanTerpakai['slotjam'] = implode(',', $array);
                            array_push($slotJamKaryawanTerpakai, $karyawanTerpakai);
                        }
                    }
                    foreach ($daftarKaryawanPerawatanKomplemen as $karyawanPerawatanKomplemen) {
                        $idKaryawan = explode(",", $karyawanPerawatanKomplemen)[0];
                        $idPerawatan = explode(",", $karyawanPerawatanKomplemen)[1];
                        $perawatan = Perawatan::find($idPerawatan);
                        $arrayStringSlotjam = str_replace(["(", ")"], "", explode(",", $karyawanPerawatanKomplemen)[2]);

                        $arraySlotJam = explode(".", $arrayStringSlotjam);

                        $durasiPerawatan = $perawatan->durasi;

                        $slotTerpakai = ceil($durasiPerawatan / 30);
                        $daftarSlotjamFinal = [];
                        for ($i = 0; $i < $slotTerpakai; $i++) {
                            array_push($daftarSlotjamFinal, $arraySlotJam[$i]);
                        }

                        $karyawan = Karyawan::select('karyawans.id')->distinct()
                            ->join('penjualan_perawatan', 'penjualan_perawatan.karyawan_id', '=', 'karyawans.id')
                            ->join('penjualans', 'penjualans.id', '=', 'penjualan_perawatan.penjualan_id')
                            ->join('slot_jam_penjualan_perawatan', 'slot_jam_penjualan_perawatan.penjualan_perawatan_id', '=', 'penjualan_perawatan.id')
                            ->join('slot_jams', 'slot_jams.id', '=', 'slot_jam_penjualan_perawatan.slot_jam_id')
                            ->whereIn('slot_jams.id', $daftarSlotjamFinal)
                            ->where('penjualans.tanggal_penjualan', $tanggalReservasi)
                            ->where('karyawans.id', $idKaryawan)
                            ->get();
                        if (count($karyawan) > 0) {
                            $karyawanTerpakai = [];
                            $karyawanTerpakai['karyawan'] = Karyawan::find($idKaryawan);
                            $karyawanTerpakai['perawatan'] = Perawatan::find($idPerawatan);

                            $slotJam = SlotJam::whereIn('id', $daftarSlotjamFinal)->get();
                            $array = [];
                            foreach ($slotJam as $sj) {
                                array_push($array, $sj->jam);
                            }
                            $karyawanTerpakai['slotjam'] = implode(',', $array);
                            array_push($slotJamKaryawanTerpakai, $karyawanTerpakai);
                        }
                    }
                    if (count($slotJamKaryawanTerpakai) > 0) {
                        $pesanError = [];
                        foreach ($slotJamKaryawanTerpakai as $sjkt) {
                            $pesan = "Perawatan " . $sjkt['perawatan']->nama . " dengan karyawan " . $sjkt['karyawan']->nama . " pada slot waktu " . $sjkt['slotjam'] . " telah terpakai.";
                            array_push($pesanError, $pesan);
                        }
                        $arrPerawatanSend = [];
                        foreach ($arrPerawatan as $ap) {
                            $p = Perawatan::select('id', 'kode_perawatan', 'nama', 'harga', 'deskripsi', 'durasi')->where('status', 'aktif')->where('id', $ap)->first();
                            array_push($arrPerawatanSend, $p);
                        }
                        $daftarPelanggans = Pelanggan::all();
                        $varSlotJam = SlotJam::find($idSlotJam);
                        $daftarSlotJam = SlotJam::where('hari', $varSlotJam->hari)->where('status', 'aktif')->orderBy('id')->get();
                        return redirect()->route('reservasi.admin.create')->with([
                            'idPelanggan' => $idPelanggan,
                            'daftarPelanggans' => $daftarPelanggans,
                            'idSlotJam' => $idSlotJam,
                            'daftarSlotJam' => $daftarSlotJam,
                            'tanggalReservasi' => $tanggalReservasi,
                            'arrPerawatan' => $arrPerawatan,
                            'arrPerawatanObject' => $arrPerawatanSend,
                        ])->withErrors($pesanError);
                    }

                    //-----------------------------------------------------------

                    //Mulai Insert Data Reservasi jika ada slot kosong tersedia

                    $newPenjualan = new Penjualan();
                    $newPenjualan->nomor_nota = $idPelanggan . "/" . (count($daftarKaryawanPerawatan) + count($daftarKaryawanPerawatanKomplemen)) . "/" . date('d') . date('m') . date('Y');

                    $totalHarga = 0;
                    foreach ($daftarKaryawanPerawatan as $karyawanPerawatan) {
                        $perawatan = Perawatan::find(explode(",", $karyawanPerawatan)[1]);
                        $totalHarga += $perawatan->harga;
                    }
                    foreach ($daftarKaryawanPerawatanKomplemen as $karyawanPerawatan) {
                        $perawatan = Perawatan::find(explode(",", $karyawanPerawatan)[1]);
                        $totalHarga += $perawatan->harga;
                    }
                    $newPenjualan->total_pembayaran = $totalHarga;
                    $newPenjualan->tanggal_penjualan = $tanggalReservasi;
                    $newPenjualan->status_selesai = "belum";
                    $newPenjualan->pelanggan_id = $idPelanggan;
                    $newPenjualan->created_at = date('Y-m-d H:i:s');
                    $newPenjualan->updated_at = date('Y-m-d H:i:s');
                    $newPenjualan->save();


                    foreach ($daftarKaryawanPerawatan as $kp) {
                        //melakukan pemisahan isi dari value yang diperoleh dari form Format: idKaryawan,(arraySlotJamTerpakai)
                        //explode untuk mendapatkan idKaryawan
                        $idKaryawan = explode(",", $kp)[0];

                        //explode untuk mendapatkan idPerawatan
                        $idPerawatan = explode(",", $kp)[1];

                        //explode untuk mendapatkan arraySlotJamTerpakai dalam bentuk string, lalu menghapus karakter "(" dan ")"
                        $arrayStringSlotjam = str_replace(["(", ")"], "", explode(",", $kp)[2]);


                        $newPenjualanPerawatan = new PenjualanPerawatan();
                        $selectedPerawatan = Perawatan::find($idPerawatan);
                        $newPenjualanPerawatan->harga = $selectedPerawatan->harga;
                        $newPenjualanPerawatan->karyawan_id = $idKaryawan;
                        $newPenjualanPerawatan->perawatan_id = $idPerawatan;
                        $newPenjualanPerawatan->penjualan_id = $newPenjualan->id;
                        $newPenjualanPerawatan->created_at = date("Y-m-d H:i:s");
                        $newPenjualanPerawatan->updated_at = date("Y-m-d H:i:s");
                        $newPenjualanPerawatan->save();

                        //explode untuk mendapatkan arraySlotJamTerpakai dalam bentuk array
                        $arraySlotJam = explode(".", $arrayStringSlotjam);

                        foreach ($arraySlotJam as $sj) {
                            $newPenjualanPerawatan->slotjams()->attach($sj);
                        }
                    }

                    foreach ($daftarKaryawanPerawatanKomplemen as $kpk) {

                        //melakukan pemisahan isi dari value yang diperoleh dari form Format: idKaryawan,idPerawatan,(arraySlotJamTerpakai)
                        //explode untuk mendapatkan idKaryawan
                        $idKaryawan = explode(",", $kpk)[0];

                        //explode untuk mendapatkan idPerawatan
                        $idPerawatan = explode(",", $kpk)[1];
                        $perawatan = Perawatan::find($idPerawatan);


                        //explode untuk mendapatkan arraySlotJamTerpakai dalam bentuk string, lalu menghapus karakter "(" dan ")"
                        $arrayStringSlotjam = str_replace(["(", ")"], "", explode(",", $kpk)[2]);


                        $newPenjualanPerawatan = new PenjualanPerawatan();
                        $newPenjualanPerawatan->harga = $perawatan->harga;
                        $newPenjualanPerawatan->karyawan_id = $idKaryawan;
                        $newPenjualanPerawatan->perawatan_id = $idPerawatan;
                        $newPenjualanPerawatan->penjualan_id = $newPenjualan->id;
                        $newPenjualanPerawatan->created_at = date("Y-m-d H:i:s");
                        $newPenjualanPerawatan->updated_at = date("Y-m-d H:i:s");
                        $newPenjualanPerawatan->save();

                        //explode untuk mendapatkan arraySlotJamTerpakai dalam bentuk array
                        $arraySlotJam = explode(".", $arrayStringSlotjam);

                        $durasiPerawatan = $perawatan->durasi;

                        $slotTerpakai = ceil($durasiPerawatan / 30);

                        for ($i = 0; $i < $slotTerpakai; $i++) {
                            $newPenjualanPerawatan->slotjams()->attach($arraySlotJam[$i]);
                        }
                    }

                    $newreservasi = new Reservasi();
                    $newreservasi->tanggal_reservasi = $tanggalReservasi;
                    $newreservasi->tanggal_pembuatan_reservasi = date('Y-m-d H:i:s');
                    $newreservasi->status = 'baru';
                    $newreservasi->penjualan_id = $newPenjualan->id;
                    $newreservasi->created_at = date('Y-m-d H:i:s');
                    $newreservasi->updated_at = date('Y-m-d H:i:s');
                    $newreservasi->save();

                    //Masuk ke halaman pilih produk

                    $dataIdPenjualan = $newPenjualan->id; //nanti diganti dengan $dataIDPenjualan, ini sementara saja

                    return redirect()->route('reservasi.admin.reservasitambahproduk', $dataIdPenjualan);


                }
            }

        }
    }

    public function reservasiAdminKonfirmasi(Request $request)
    {
        date_default_timezone_set('Asia/Jakarta');
        $tanggalReservasi = $request->get("tanggalReservasi");
        $daftarKaryawanPerawatan = $request->get('selectkaryawan');
        $daftarKaryawanPerawatanKomplemen = $request->get('selectkaryawankomplemen');
        $idSlotJam = $request->get("slotJam");
        $arrPerawatan = $request->get("arrayperawatanid");
        $idPelanggan = $request->get("idPelanggan");

        //pengecekan sebelum insert data slot jam dan karyawan untuk memastikan tidak ada pelanggan lain yang reservasi duluan
        $slotJamKaryawanTerpakai = [];
        foreach ($daftarKaryawanPerawatan as $karyawanPerawatan) {
            $idKaryawan = explode(",", $karyawanPerawatan)[0];
            $idPerawatan = explode(",", $karyawanPerawatan)[1];


            $arrayStringSlotjam = str_replace(["(", ")"], "", explode(",", $karyawanPerawatan)[2]);
            $arraySlotJam = explode(".", $arrayStringSlotjam);

            $karyawan = Karyawan::select('karyawans.id')->distinct()
                ->join('penjualan_perawatan', 'penjualan_perawatan.karyawan_id', '=', 'karyawans.id')
                ->join('penjualans', 'penjualans.id', '=', 'penjualan_perawatan.penjualan_id')
                ->join('slot_jam_penjualan_perawatan', 'slot_jam_penjualan_perawatan.penjualan_perawatan_id', '=', 'penjualan_perawatan.id')
                ->join('slot_jams', 'slot_jams.id', '=', 'slot_jam_penjualan_perawatan.slot_jam_id')
                ->whereIn('slot_jams.id', $arraySlotJam)
                ->where('penjualans.tanggal_penjualan', $tanggalReservasi)
                ->where('karyawans.id', $idKaryawan)
                ->get();

            if (count($karyawan) > 0) {
                $karyawanTerpakai = [];
                $karyawanTerpakai['karyawan'] = Karyawan::find($idKaryawan);
                $karyawanTerpakai['perawatan'] = Perawatan::find($idPerawatan);
                $slotJam = SlotJam::whereIn('id', $arraySlotJam)->get();
                $array = [];
                foreach ($slotJam as $sj) {
                    array_push($array, $sj->jam);
                }
                $karyawanTerpakai['slotjam'] = implode(',', $array);
                array_push($slotJamKaryawanTerpakai, $karyawanTerpakai);
            }
        }
        foreach ($daftarKaryawanPerawatanKomplemen as $karyawanPerawatanKomplemen) {
            $idKaryawan = explode(",", $karyawanPerawatanKomplemen)[0];
            $idPerawatan = explode(",", $karyawanPerawatanKomplemen)[1];
            $perawatan = Perawatan::find($idPerawatan);
            $arrayStringSlotjam = str_replace(["(", ")"], "", explode(",", $karyawanPerawatanKomplemen)[2]);

            $arraySlotJam = explode(".", $arrayStringSlotjam);

            $durasiPerawatan = $perawatan->durasi;

            $slotTerpakai = ceil($durasiPerawatan / 30);
            $daftarSlotjamFinal = [];
            for ($i = 0; $i < $slotTerpakai; $i++) {
                array_push($daftarSlotjamFinal, $arraySlotJam[$i]);
            }

            $karyawan = Karyawan::select('karyawans.id')->distinct()
                ->join('penjualan_perawatan', 'penjualan_perawatan.karyawan_id', '=', 'karyawans.id')
                ->join('penjualans', 'penjualans.id', '=', 'penjualan_perawatan.penjualan_id')
                ->join('slot_jam_penjualan_perawatan', 'slot_jam_penjualan_perawatan.penjualan_perawatan_id', '=', 'penjualan_perawatan.id')
                ->join('slot_jams', 'slot_jams.id', '=', 'slot_jam_penjualan_perawatan.slot_jam_id')
                ->whereIn('slot_jams.id', $daftarSlotjamFinal)
                ->where('penjualans.tanggal_penjualan', $tanggalReservasi)
                ->where('karyawans.id', $idKaryawan)
                ->get();
            if (count($karyawan) > 0) {
                $karyawanTerpakai = [];
                $karyawanTerpakai['karyawan'] = Karyawan::find($idKaryawan);
                $karyawanTerpakai['perawatan'] = Perawatan::find($idPerawatan);

                $slotJam = SlotJam::whereIn('id', $daftarSlotjamFinal)->get();
                $array = [];
                foreach ($slotJam as $sj) {
                    array_push($array, $sj->jam);
                }
                $karyawanTerpakai['slotjam'] = implode(',', $array);
                array_push($slotJamKaryawanTerpakai, $karyawanTerpakai);
            }
        }


        if (count($slotJamKaryawanTerpakai) > 0) {
            $pesanError = [];
            foreach ($slotJamKaryawanTerpakai as $sjkt) {
                $pesan = "Perawatan " . $sjkt['perawatan']->nama . " dengan karyawan " . $sjkt['karyawan']->nama . " pada slot waktu " . $sjkt['slotjam'] . " telah terpakai.";
                array_push($pesanError, $pesan);
            }
            $arrPerawatanSend = [];
            foreach ($arrPerawatan as $ap) {
                $p = Perawatan::select('id', 'kode_perawatan', 'nama', 'harga', 'deskripsi', 'durasi')->where('status', 'aktif')->where('id', $ap)->first();
                array_push($arrPerawatanSend, $p);
            }
            $daftarPelanggans = Pelanggan::all();
            $varSlotJam = SlotJam::find($idSlotJam);
            $daftarSlotJam = SlotJam::where('hari', $varSlotJam->hari)->where('status', 'aktif')->orderBy('id')->get();
            return redirect()->route('reservasi.admin.create')->with([
                'idPelanggan' => $idPelanggan,
                'daftarPelanggans' => $daftarPelanggans,
                'idSlotJam' => $idSlotJam,
                'daftarSlotJam' => $daftarSlotJam,
                'tanggalReservasi' => $tanggalReservasi,
                'arrPerawatan' => $arrPerawatan,
                'arrPerawatanObject' => $arrPerawatanSend,
            ])->withErrors($pesanError);
        }

        //-----------------------------------------------------------

        //Mulai Insert Data Reservasi jika ada slot kosong tersedia
        $newPenjualan = new Penjualan();
        $newPenjualan->nomor_nota = $idPelanggan . "/" . (count($daftarKaryawanPerawatan) + count($daftarKaryawanPerawatanKomplemen)) . "/" . date('d') . date('m') . date('Y');

        $totalHarga = 0;
        foreach ($daftarKaryawanPerawatan as $karyawanPerawatan) {
            $perawatan = Perawatan::find(explode(",", $karyawanPerawatan)[1]);
            $totalHarga += $perawatan->harga;
        }
        foreach ($daftarKaryawanPerawatanKomplemen as $karyawanPerawatan) {
            $perawatan = Perawatan::find(explode(",", $karyawanPerawatan)[1]);
            $totalHarga += $perawatan->harga;
        }
        $newPenjualan->total_pembayaran = $totalHarga;
        $newPenjualan->tanggal_penjualan = $tanggalReservasi;
        $newPenjualan->status_selesai = "belum";
        $newPenjualan->pelanggan_id = $idPelanggan;
        $newPenjualan->created_at = date('Y-m-d H:i:s');
        $newPenjualan->updated_at = date('Y-m-d H:i:s');
        $newPenjualan->save();


        foreach ($daftarKaryawanPerawatan as $kp) {
            //melakukan pemisahan isi dari value yang diperoleh dari form Format: idKaryawan,(arraySlotJamTerpakai)
            //explode untuk mendapatkan idKaryawan
            $idKaryawan = explode(",", $kp)[0];

            //explode untuk mendapatkan idPerawatan
            $idPerawatan = explode(",", $kp)[1];

            //explode untuk mendapatkan arraySlotJamTerpakai dalam bentuk string, lalu menghapus karakter "(" dan ")"
            $arrayStringSlotjam = str_replace(["(", ")"], "", explode(",", $kp)[2]);


            $newPenjualanPerawatan = new PenjualanPerawatan();
            $selectedPerawatan = Perawatan::find($idPerawatan);
            $newPenjualanPerawatan->harga = $selectedPerawatan->harga;
            $newPenjualanPerawatan->karyawan_id = $idKaryawan;
            $newPenjualanPerawatan->perawatan_id = $idPerawatan;
            $newPenjualanPerawatan->penjualan_id = $newPenjualan->id;
            $newPenjualanPerawatan->created_at = date("Y-m-d H:i:s");
            $newPenjualanPerawatan->updated_at = date("Y-m-d H:i:s");
            $newPenjualanPerawatan->save();

            //explode untuk mendapatkan arraySlotJamTerpakai dalam bentuk array
            $arraySlotJam = explode(".", $arrayStringSlotjam);

            foreach ($arraySlotJam as $sj) {
                $newPenjualanPerawatan->slotjams()->attach($sj);
            }
        }

        foreach ($daftarKaryawanPerawatanKomplemen as $kpk) {

            //melakukan pemisahan isi dari value yang diperoleh dari form Format: idKaryawan,idPerawatan,(arraySlotJamTerpakai)
            //explode untuk mendapatkan idKaryawan
            $idKaryawan = explode(",", $kpk)[0];

            //explode untuk mendapatkan idPerawatan
            $idPerawatan = explode(",", $kpk)[1];
            $perawatan = Perawatan::find($idPerawatan);


            //explode untuk mendapatkan arraySlotJamTerpakai dalam bentuk string, lalu menghapus karakter "(" dan ")"
            $arrayStringSlotjam = str_replace(["(", ")"], "", explode(",", $kpk)[2]);


            $newPenjualanPerawatan = new PenjualanPerawatan();
            $newPenjualanPerawatan->harga = $perawatan->harga;
            $newPenjualanPerawatan->karyawan_id = $idKaryawan;
            $newPenjualanPerawatan->perawatan_id = $idPerawatan;
            $newPenjualanPerawatan->penjualan_id = $newPenjualan->id;
            $newPenjualanPerawatan->created_at = date("Y-m-d H:i:s");
            $newPenjualanPerawatan->updated_at = date("Y-m-d H:i:s");
            $newPenjualanPerawatan->save();

            //explode untuk mendapatkan arraySlotJamTerpakai dalam bentuk array
            $arraySlotJam = explode(".", $arrayStringSlotjam);

            $durasiPerawatan = $perawatan->durasi;

            $slotTerpakai = ceil($durasiPerawatan / 30);

            for ($i = 0; $i < $slotTerpakai; $i++) {
                $newPenjualanPerawatan->slotjams()->attach($arraySlotJam[$i]);
            }
        }

        $newreservasi = new Reservasi();
        $newreservasi->tanggal_reservasi = $tanggalReservasi;
        $newreservasi->tanggal_pembuatan_reservasi = date('Y-m-d H:i:s');
        $newreservasi->status = 'baru';
        $newreservasi->penjualan_id = $newPenjualan->id;
        $newreservasi->created_at = date('Y-m-d H:i:s');
        $newreservasi->updated_at = date('Y-m-d H:i:s');
        $newreservasi->save();

        //Masuk ke halaman pilih produk

        $dataIdPenjualan = $newPenjualan->id; //nanti diganti dengan $dataIDPenjualan, ini sementara saja

        return redirect()->route('reservasi.admin.reservasitambahproduk', $dataIdPenjualan);
    }

    public function detailReservasi($id)
    {
        $idReservasi = $id;
        $reservasi = Reservasi::find($idReservasi);

        $penjualanPerawatan = $reservasi->penjualan->penjualanperawatans->sortBy('id');
        $jamMulai = $penjualanPerawatan->first()->slotjams->sortBy('slot_jam_id')->first();
        $slotJamBerubah = $jamMulai;

        $perawatanSlotJamNonKomplemen = [];


        $perawatanNonKomplemen = [];
        $perawatanKomplemen = [];
        foreach ($penjualanPerawatan as $pp) {
            if ($pp->perawatan->status_komplemen == "tidak") {
                array_push($perawatanNonKomplemen, $pp->perawatan);
            } else {
                array_push($perawatanKomplemen, $pp->perawatan);
            }
        }

        foreach ($perawatanNonKomplemen as $perawatan) {
            $perawatanPerSlot = [];
            $perawatanPerSlot["perawatan"] = $perawatan;
            $perawatanPerSlot["jammulai"] = $slotJamBerubah->jam;

            $perawatanPerSlot["karyawan"] = $reservasi->penjualan->penjualanperawatans->where('perawatan_id', $perawatan->id)->first()->karyawan;

            $jumlahSlotTerpakai = ceil($perawatan->durasi / 30);
            $intJamTerakhir = strtotime("+" . ($jumlahSlotTerpakai * 30) - 30 . " minutes", strtotime($slotJamBerubah->jam));
            $jamTerakhir = date('H.i', $intJamTerakhir);

            array_push($perawatanSlotJamNonKomplemen, $perawatanPerSlot);

            //update slotJamBerubah ke slot selanjutnya
            $intJamTerakhir = strtotime("+30 minutes", strtotime($jamTerakhir));
            $jamTerakhir = date('H.i', $intJamTerakhir);
            $slotJamBerubah = SlotJam::where('jam', $jamTerakhir)->where('hari', $slotJamBerubah->hari)->first();
        }

        $arrKomplemen = [];
        $idPerawatanKomplemen = [];
        $arrayPerawatanKomplemen = [];
        foreach ($perawatanKomplemen as $pk) {
            array_push($idPerawatanKomplemen, $pk->id);
            $array = [];
            $array['perawatan'] = $pk;
            $array["karyawan"] = $reservasi->penjualan->penjualanperawatans->where('perawatan_id', $pk->id)->first()->karyawan;
            array_push($arrayPerawatanKomplemen, $array);
        }
        $arrKomplemen['jammulai'] = $slotJamBerubah->jam;
        $durasiTerlamaPerawatanKomplemen = Perawatan::where('status_komplemen', 'ya')->whereIn('id', $idPerawatanKomplemen)->max('durasi');
        $arrKomplemen['durasiterlama'] = $durasiTerlamaPerawatanKomplemen;
        $arrKomplemen['perawatans'] = $arrayPerawatanKomplemen;

        $idDiskonUnikYangSudahPernahDipakai = Penjualan::select("diskon_id")->distinct()->where("pelanggan_id", $reservasi->penjualan->pelanggan_id)->where("diskon_id", "!=", null)->get();
        $tanggalHariIni = date("Y-m-d");
        $diskonAktifBerlaku = Diskon::where("status", "aktif")->whereRaw("DATE(tanggal_mulai) <= '" . $tanggalHariIni . "'")->whereRaw("DATE(tanggal_berakhir) >= '" . $tanggalHariIni . "'")->whereNotIn("id", $idDiskonUnikYangSudahPernahDipakai)->where("minimal_transaksi", "<=", $reservasi->penjualan->total_pembayaran)->get();


        return view('admin.reservasi.detailreservasi', compact('reservasi', 'jamMulai', 'arrKomplemen', 'perawatanSlotJamNonKomplemen', 'diskonAktifBerlaku'));

    }

    public function adminBatalkanReservasi(Request $request)
    {
        $idReservasi = $request->get('idReservasiBatal');
        $selectedReservasi = Reservasi::find($idReservasi);
        $penjualanTerpilih = Penjualan::find($selectedReservasi->penjualan->id);

        if (count($selectedReservasi->penjualan->produks) > 0) {
            foreach ($selectedReservasi->penjualan->produks as $produk) {
                $produkTerpilih = Produk::find($produk->id);
                $produkTerpilih->stok = $produkTerpilih->stok + $produk->pivot->kuantitas;
                $produkTerpilih->save();
                // $penjualanTerpilih->produks()->detach($produk);
            }
        }

        $selectedReservasi->status = "dibatalkan salon";
        $selectedReservasi->save();

        $penjualanTerpilih->status_selesai = "batal";
        $penjualanTerpilih->save();

        $emailPelanggan = $selectedReservasi->penjualan->pelanggan->user->email;
        $namaPelanggan = $selectedReservasi->penjualan->pelanggan->nama;
        $pesanEmail = "Mohon maaf untuk reservasi Anda kami batalkan.";
        $nomorNota = $selectedReservasi->penjualan->nomor_nota;

        MailController::mailBatalReservasiAdmin($emailPelanggan, $namaPelanggan, $pesanEmail, $nomorNota);

        return redirect()->route('reservasi.admin.detailreservasi', $selectedReservasi->id)->with('status', 'Berhasil membatalkan reservasi!');
    }

    public function adminSelesaiReservasi(Request $request)
    {
        $idReservasi = $request->get('idReservasiSelesai');
        $selectedReservasi = Reservasi::find($idReservasi);
        $selectedReservasi->status = "selesai";
        $selectedReservasi->save();

        $idPenjualan = $selectedReservasi->penjualan->id;
        $selectedPenjualan = Penjualan::find($idPenjualan);
        $selectedPenjualan->status_selesai = "selesai";
        $selectedPenjualan->save();

        return redirect()->route('reservasi.admin.detailreservasi', $selectedReservasi->id)->with('status', 'Berhasil menyelesaikan reservasi!');
    }

    public function riwayatReservasiPerawatanAdmin()
    {
        date_default_timezone_set('Asia/Jakarta');
        $hariIndonesia = array('Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu');
        $tanggalReservasi = Reservasi::selectRaw("DISTINCT DATE(tanggal_reservasi) as tanggal_reservasi")->whereRaw(" DATE(tanggal_reservasi) < '" . date("Y-m-d") . "'")->orderBy('tanggal_reservasi', "desc")->get();

        $arrDaftarRiwayatReservasi = [];
        foreach ($tanggalReservasi as $tr) {
            $objectRiwayat = [];
            $reservasi = Reservasi::whereRaw(" DATE(tanggal_reservasi) = '" . $tr->tanggal_reservasi . "'")->get();

            $nomorHariDalamMingguan = date("w", strtotime($tr->tanggal_reservasi));
            $tanggal = $hariIndonesia[$nomorHariDalamMingguan] . ", " . date('d-m-Y', strtotime($tr->tanggal_reservasi));
            $objectRiwayat["tanggalreservasi"] = $tanggal;

            $objectRiwayat["tanggal"] = $tr->tanggal_reservasi;

            $objectRiwayat["reservasis"] = $reservasi;

            $objectRiwayat["jumlahreservasi"] = count($reservasi);

            $totalPenjualanPerawatan = 0;
            $totalPenjualanProduk = 0;
            foreach ($reservasi as $r) {
                if ($r->status == "selesai") {
                    $totalPenjualanPerawatan += $r->penjualan->total_pembayaran;

                    foreach ($r->penjualan->produks as $p) {
                        $totalPenjualanProduk += $p->pivot->kuantitas * $p->pivot->harga;
                    }
                }

            }

            $objectRiwayat["totalpenjualanproduk"] = $totalPenjualanProduk;

            $objectRiwayat["totalpenjualanperawatan"] = $totalPenjualanPerawatan;

            $objectRiwayat["totalpembayaran"] = $totalPenjualanProduk + $totalPenjualanPerawatan;

            array_push($arrDaftarRiwayatReservasi, $objectRiwayat);
        }

        return view("admin.reservasi.riwayatreservasiperawatan", compact("arrDaftarRiwayatReservasi"));

    }
    public function getDetailRiwayatReservasiPerawatan()
    {
        $tanggal = $_POST['tanggal'];
        $riwayatReservasis = Reservasi::whereRaw("DATE(tanggal_reservasi) = '" . $tanggal . "'")->orderBy("id", "desc")->get();
        return response()->json(array('msg' => view('admin.reservasi.detailreservasiperawatan', compact('riwayatReservasis'))->render()), 200);
    }
}