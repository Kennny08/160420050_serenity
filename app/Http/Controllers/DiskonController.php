<?php

namespace App\Http\Controllers;

use App\Models\Diskon;
use Illuminate\Http\Request;

class DiskonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tanggalHariIni = date("Y-m-d");
        $diskonAktif = Diskon::where("status", "aktif")->get();
        $diskonNonaktif = Diskon::where("status", "nonaktif")->get();

        return view("admin.diskon.index", compact("diskonAktif", "diskonNonaktif"));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("admin.diskon.tambahdiskon");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        date_default_timezone_set("Asia/Jakarta");

        $validatedData = $request->validate(
            [
                'namaDiskon' => 'required|max:255',
                'kode_diskon' => 'required|unique:diskons',
                'jumlahPotongan' => 'required|numeric|min:1|max:100',
                'minimalTransaksi' => 'required|numeric|min:1',
                'maksimalPotongan' => 'required|numeric|min:1',
                'tanggalMulai' => 'required|date|before_or_equal:tanggalBerakhir',
                'tanggalBerakhir' => 'required|date|after_or_equal:tanggalMulai',
            ],
            [
                'namaDiskon.required' => 'Nama diskon tidak boleh kosong!',
                'kode_diskon.required' => 'Kode Diskon tidak boleh kosong!',
                'kode_diskon.unique' => 'Kode diskon sudah pernah dipakai, mohon masukkan kode diskon lainnya!',
                'jumlahPotongan.required' => 'Jumlah Potongan tidak boleh kosong!',
                'jumlahPotongan.numeric' => 'Jumlah Potongan harus berupa angka!',

                'jumlahPotongan.min' => 'Jumlah Potongan Diskon minimal 1%!',
                'jumlahPotongan.max' => 'Jumlah Potongan Diskon maksimal 100%! ',
                'minimalTransaksi.required' => 'Minimal Transaksi tidak boleh kosong!',
                'minimalTransaksi.numeric' => 'Minimal Transaksi harus berupa angka!',
                'minimalTransaksi.min' => 'Minimal Transaksi minimal Rp 1!',

                'maksimalPotongan.required' => 'Maksimal Potongan tidak boleh kosong!',
                'maksimalPotongan.numeric' => 'Maskimal Potongan harus berupa angka!',
                'maksimalPotongan.min' => 'Maskimal potongan diskon minimal Rp 1! ',
                'tanggalMulai.required' => 'Tanggal Mulai Berlakunya Diskon tidak boleh kosong!',
                'tanggalMulai.date' => 'Tanggal Mulai harus dalam bentuk tanggal!',

                'tanggalMulai.before_or_equal' => 'Tanggal Mulai harus kurang dari atau sama dengan tanggal Berakhir',
                'tanggalBerakhir.required' => 'Tanggal berakhirnya Diskon tidak boleh kosong!',
                'tanggalBerakhir.date' => 'Tanggal Berakhir harus dalam bentuk tanggal!',
                'tanggalBerakhir.after_or_equal' => 'Tanggal Berakhir harus lebih besar atau sama dengan Tanggal Mulai',
            ]
        );

        $namaDiskon = $request->get("namaDiskon");
        $kodeDiskon = $request->get("kode_diskon");
        $jumlahPotongan = $request->get("jumlahPotongan");
        $minimalTransaksi = $request->get("minimalTransaksi");
        $maksimalPotongan = $request->get("maksimalPotongan");
        $tanggalMulai = $request->get("tanggalMulai");
        $tanggalBerakhir = $request->get("tanggalBerakhir");

        $newDiskon = new Diskon();
        $newDiskon->nama = $namaDiskon;
        $newDiskon->kode_diskon = $kodeDiskon;
        $newDiskon->jumlah_potongan = $jumlahPotongan;
        $newDiskon->minimal_transaksi = $minimalTransaksi;
        $newDiskon->maksimum_potongan = $maksimalPotongan;
        $newDiskon->tanggal_mulai = $tanggalMulai;
        $newDiskon->tanggal_berakhir = $tanggalBerakhir;
        $newDiskon->created_at = date("Y-m-d H:i:i");
        $newDiskon->updated_at = date("Y-m-d H:i:i");
        $newDiskon->save();

        return redirect()->route("diskons.index")->with("status", "Berhasil menambahkan data diskon " . $namaDiskon);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Diskon  $diskon
     * @return \Illuminate\Http\Response
     */
    public function show(Diskon $diskon)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Diskon  $diskon
     * @return \Illuminate\Http\Response
     */
    public function edit(Diskon $diskon)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Diskon  $diskon
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Diskon $diskon)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Diskon  $diskon
     * @return \Illuminate\Http\Response
     */
    public function destroy(Diskon $diskon)
    {
        //
    }

    public function daftarDiskonBerlaku()
    {
        $tanggalHariIni = date("Y-m-d");
        $diskonAktifBerlaku = Diskon::where("status", "aktif")->whereRaw("DATE(tanggal_mulai) <= '" . $tanggalHariIni . "'")->whereRaw("DATE(tanggal_berakhir) >= '" . $tanggalHariIni . "'")->get();

        return view("admin.diskon.daftardiskonsedangberlangsung", compact("diskonAktifBerlaku"));
    }

    public function daftarDiskonSelesai()
    {
        $tanggalHariIni = date("Y-m-d");
        $diskonAktifSudahSelesai = Diskon::where("status", "aktif")->whereRaw("DATE(tanggal_berakhir) < '" . $tanggalHariIni . "'")->get();

        return view("admin.diskon.daftardiskonselesai", compact("diskonAktifSudahSelesai"));
    }

}
