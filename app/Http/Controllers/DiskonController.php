<?php

namespace App\Http\Controllers;

use App\Models\Diskon;
use App\Models\Penjualan;
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
                'maksimumPotongan' => 'required|numeric|min:1',
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
                'jumlahPotongan.max' => 'Jumlah Potongan Diskon maksimum 100%! ',
                'minimalTransaksi.required' => 'Minimal Transaksi tidak boleh kosong!',
                'minimalTransaksi.numeric' => 'Minimal Transaksi harus berupa angka!',
                'minimalTransaksi.min' => 'Minimal Transaksi minimal Rp 1!',

                'maksimumPotongan.required' => 'Maksimum Potongan tidak boleh kosong!',
                'maksimumPotongan.numeric' => 'Maskimal Potongan harus berupa angka!',
                'maksimumPotongan.min' => 'Maskimal potongan diskon minimal Rp 1! ',
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
        $maksimumPotongan = $request->get("maksimumPotongan");
        $tanggalMulai = $request->get("tanggalMulai");
        $tanggalBerakhir = $request->get("tanggalBerakhir");

        $newDiskon = new Diskon();
        $newDiskon->nama = $namaDiskon;
        $newDiskon->kode_diskon = $kodeDiskon;
        $newDiskon->jumlah_potongan = $jumlahPotongan;
        $newDiskon->minimal_transaksi = $minimalTransaksi;
        $newDiskon->maksimum_potongan = $maksimumPotongan;
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
        return view("admin.diskon.editdiskon", compact("diskon"));
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
        date_default_timezone_set("Asia/Jakarta");

        $validatedData = $request->validate(
            [
                'namaDiskon' => 'required|max:255',
                'tanggalBerakhir' => 'required|date',
            ],
            [
                'namaDiskon.required' => 'Nama diskon tidak boleh kosong!',
                'tanggalBerakhir.required' => 'Tanggal berakhirnya Diskon tidak boleh kosong!',
                'tanggalBerakhir.date' => 'Tanggal Berakhir harus dalam bentuk tanggal!',

            ]
        );

        $namaDiskon = $request->get("namaDiskon");
        $status = $request->get("radioStatusDiskon");
        $tanggalBerakhir = $request->get("tanggalBerakhir");

        $diskon->nama = $namaDiskon;
        $diskon->status = $status;
        $diskon->tanggal_berakhir = $tanggalBerakhir;
        $diskon->updated_at = date("Y-m-d H:i:s");
        $diskon->save();

        return redirect()->route("diskons.index")->with("status", "Berhasil mengedit data diskon " . $namaDiskon);

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

    public function pilihDiskon($idPenjualan)
    {
        $penjualan = Penjualan::find($idPenjualan);

        $idDiskonUnikYangSudahPernahDipakai = Penjualan::select("diskon_id")->distinct()->where("pelanggan_id", $penjualan->pelanggan_id)->where("diskon_id", "!=", null)->get();
        $tanggalHariIni = date("Y-m-d");
        $diskonAktifBerlaku = Diskon::where("status", "aktif")->whereRaw("DATE(tanggal_mulai) <= '" . $tanggalHariIni . "'")->whereRaw("DATE(tanggal_berakhir) >= '" . $tanggalHariIni . "'")->where("minimal_transaksi", "<=", $penjualan->total_pembayaran)->whereNotIn("id", $idDiskonUnikYangSudahPernahDipakai)->get();

        return view("admin.diskon.pilihdiskon", compact("diskonAktifBerlaku", "penjualan"));
    }

    public function prosesPemakaianDiskon(Request $request)
    {
        date_default_timezone_set("Asia/Jakarta");
        $idPenjualan = $request->get("idPenjualan");
        $idDiskon = $request->get("idDiskon");

        $penjualan = Penjualan::find($idPenjualan);
        $diskon = Diskon::find($idDiskon);

        $jumlahPotongan = ($penjualan->total_pembayaran * $diskon->jumlah_potongan) / 100;

        if ($jumlahPotongan >= $diskon->maksimum_potongan) {
            $jumlahPotongan = $diskon->maksimum_potongan;
        }

        $penjualanHargaBaru = Penjualan::find($idPenjualan);
        $penjualanHargaBaru->total_pembayaran = $penjualan->total_pembayaran - $jumlahPotongan;
        $penjualanHargaBaru->diskon_id = $diskon->id;
        $penjualanHargaBaru->updated_at = date("Y-m-d H:i:s");
        $penjualanHargaBaru->save();

        if ($penjualanHargaBaru->reservasi != null) {
            return redirect()->route('reservasi.admin.detailreservasi', $penjualanHargaBaru->reservasi->id)->with('status', 'Berhasil meggunakan diskon ' . $diskon->nama . '!');
        } else {
            //
        }
    }

    public function getDetailDiskon()
    {
        $idDiskon = $_POST["idDiskon"];

        $diskon = Diskon::find($idDiskon);

        $penjualanDiskon = $diskon->penjualans;

        $penjualanDiskons = [];
        foreach ($penjualanDiskon as $penjualan) {
            $penjualanSementara = [];

            $penjualanSementara["penjualan"] = $penjualan;

            $totalHargaPenjualanPerawatan = $penjualan->penjualanperawatans->sum("harga");
        
            
            $totalHargaPenjualanProduk = $penjualan->produks->sum(function ($penjualanProduk) {
                return ($penjualanProduk->pivot->harga * $penjualanProduk->pivot->kuantitas);
            });
            $penjualanSementara["totalHarga"] = $totalHargaPenjualanProduk + $totalHargaPenjualanPerawatan;

            $penjualanSementara["totalPotongan"] = $totalHargaPenjualanProduk + $totalHargaPenjualanPerawatan - $penjualan->total_pembayaran;

            $penjualanSementara["totalPembayaran"] = $penjualan->total_pembayaran;

            array_push($penjualanDiskons, $penjualanSementara);

        }

        return response()->json(array('msg' => view('admin.diskon.detaildiskon', compact('penjualanDiskons'))->render()), 200);
    }

}
