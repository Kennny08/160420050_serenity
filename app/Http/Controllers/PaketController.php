<?php

namespace App\Http\Controllers;

use App\Models\Paket;
use App\Models\Perawatan;
use App\Models\Produk;
use Illuminate\Http\Request;

class PaketController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $paketsAktif = Paket::where("status", "aktif")->get();
        $paketsNonaktif = Paket::where("status", "nonaktif")->get();

        return view("admin.paket.index", compact("paketsAktif", "paketsNonaktif"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $perawatanAktif = Perawatan::where("status", "aktif")->orderBy("nama")->get();
        $produkAktif = Produk::where("status", "aktif")->where("status_jual", "aktif")->orderBy("nama")->get();

        return view("admin.paket.tambahpaket", compact("perawatanAktif", "produkAktif"));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
        date_default_timezone_set('Asia/Jakarta');
        $validatedData = $request->validate(
            [
                'namaPaket' => 'required|max:255',
                'kode_paket' => 'required|unique:pakets',
                'hargaPaket' => 'required|numeric|min:1',
                'arrayperawatanid' => 'required|array|min:2',
            ],
            [
                'namaPaket.required' => 'Nama paket tidak boleh kosong!',
                'kode_paket.required' => 'Kode Paket tidak boleh kosong!',
                'kode_paket.unique' => 'Kode paket sudah pernah dipakai, mohon masukkan kode paket lainnya!',
                'hargaPaket.required' => 'Harga Paket tidak boleh kosong!',
                'hargaPaket.numeric' => 'Harga Paket harus berupa angka!',

                'hargaPaket.min' => 'Harga Paket Paket minimal Rp. 1!',
                'arrayperawatanid.required' => 'Perawatan Paket tidak boleh kosong!',
                'arrayperawatanid.min' => 'Minimal terdapat 2 perawatan dalam satu Paket!'
            ]
        );

        $namaPaket = $request->get("namaPaket");
        $kodePaket = $request->get("kode_paket");
        $hargaPaket = $request->get("hargaPaket");
        $arrPerawatanId = $request->get("arrayperawatanid");
        $arrProdukId = $request->get("arrayprodukid");
        $arrProdukKuantitas = $request->get("arrayprodukkuantitas");
        $statusPaket = $request->get("radioStatusPaket");
        $deskripsiPaket = $request->get("deskripsiPaket");

        $newPaket = new Paket();
        $newPaket->nama = $namaPaket;
        $newPaket->kode_paket = $kodePaket;
        $newPaket->harga = $hargaPaket;
        if ($deskripsiPaket != null) {
            $newPaket->deskripsi = $deskripsiPaket;
        }
        $newPaket->status = $statusPaket;
        $newPaket->created_at = date("Y-m-d H:i:s");
        $newPaket->updated_at = date("Y-m-d H:i:s");
        $newPaket->save();

        foreach ($arrPerawatanId as $idPerawatan) {
            $newPaket->perawatans()->attach($idPerawatan);
        }

        if ($arrProdukId != null) {
            for ($i = 0; $i < count($arrProdukId); $i++) {
                $newPaket->produks()->attach($arrProdukId[$i], ['jumlah' => $arrProdukKuantitas[$i]]);
            }

        }

        return redirect()->route("pakets.index")->with("status", "Berhasil menambahkan data paket " . $newPaket->nama . "!");



    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Paket  $paket
     * @return \Illuminate\Http\Response
     */
    public function show(Paket $paket)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Paket  $paket
     * @return \Illuminate\Http\Response
     */
    public function edit($idPaket)
    {
        $paket = Paket::find($idPaket);
        $perawatanAktif = Perawatan::where("status", "aktif")->orderBy("nama")->get();
        $produkAktif = Produk::where("status", "aktif")->where("status_jual", "aktif")->orderBy("nama")->get();

        $arrPerawatanId = [];
        foreach ($paket->perawatans as $perawatan) {
            array_push($arrPerawatanId, $perawatan->id);
        }

        $arrProdukId = [];
        if (count($paket->produks) != 0) {
            foreach ($paket->produks as $produk) {
                array_push($arrProdukId, $produk->id);
            }
        }

        return view("admin.paket.editpaket", compact("perawatanAktif", "produkAktif", "paket", "arrPerawatanId", "arrProdukId"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Paket  $paket
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $idPaket)
    {
        date_default_timezone_set('Asia/Jakarta');
        $paket = Paket::find($idPaket);
        $validatedData = $request->validate(
            [
                'namaPaket' => 'required|max:255',
                'hargaPaket' => 'required|numeric|min:1',
                // 'arrayperawatanid' => 'required|array|min:2',
            ],
            [
                'namaPaket.required' => 'Nama paket tidak boleh kosong!',
                'hargaPaket.required' => 'Harga Paket tidak boleh kosong!',
                'hargaPaket.numeric' => 'Harga Paket harus berupa angka!',

                'hargaPaket.min' => 'Harga Paket Paket minimal Rp. 1!',
                // 'arrayperawatanid.required' => 'Perawatan Paket tidak boleh kosong!',
                // 'arrayperawatanid.min' => 'Minimal terdapat 2 perawatan dalam satu Paket!'
            ]
        );

        $namaPaket = $request->get("namaPaket");
        $hargaPaket = $request->get("hargaPaket");
        // $arrPerawatanId = $request->get("arrayperawatanid");
        // $arrProdukId = $request->get("arrayprodukid");
        // $arrProdukKuantitas = $request->get("arrayprodukkuantitas");
        $statusPaket = $request->get("radioStatusPaket");
        $deskripsiPaket = $request->get("deskripsiPaket");

        $paket->nama = $namaPaket;
        $paket->harga = $hargaPaket;
        if ($deskripsiPaket != null) {
            $paket->deskripsi = $deskripsiPaket;
        }
        $paket->status = $statusPaket;
        $paket->updated_at = date("Y-m-d H:i:s");
        $paket->save();

        // foreach ($paket->perawatans as $perawatan) {
        //     $paket->perawatans()->detach($perawatan);
        // }
        // foreach ($arrPerawatanId as $idPerawatan) {
        //     $paket->perawatans()->attach($idPerawatan);
        // }

        // if ($arrProdukId != null) {
        //     foreach ($paket->produks as $produk) {
        //         $paket->produks()->detach($produk);
        //     }

        //     for ($i = 0; $i < count($arrProdukId); $i++) {
        //         $paket->produks()->attach($arrProdukId[$i], ['jumlah' => $arrProdukKuantitas[$i]]);
        //     }
        // } else {
        //     foreach ($paket->produks as $produk) {
        //         $paket->produks()->detach($produk);
        //     }
        // }

        return redirect()->route("pakets.index")->with("status", "Berhasil mengedit data paket " . $paket->nama . "!");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Paket  $paket
     * @return \Illuminate\Http\Response
     */
    public function destroy($idPaket)
    {
        $objPaket = Paket::find($idPaket);
        try {
            $objPaket->delete();
            return redirect()->route('pakets.index')->with('status', 'Paket ' . $objPaket->nama . ' telah berhasil dihapus');
        } catch (\PDOException $ex) {
            $msg = "Data Gagal dihapus. Pastikan kembali tidak ada data yang berelasi sebelum dihapus";
            return redirect()->route('pakets.index')->with('status', $msg);
        }
    }

    public function getDetailPaket()
    {
        $idPaket = $_POST["idPaket"];
        $paket = Paket::find($idPaket);

        $jmlhReservasi = 0;
        $jmlTanpaReservasi = 0;
        foreach ($paket->penjualans as $pp) {
            if ($pp->reservasi != null) {
                if ($pp->reservasi->status == 'selesai') {
                    $jmlhReservasi++;
                }
            } else {
                if ($pp->status_selesai == 'selesai') {
                    $jmlTanpaReservasi++;
                }
            }
        }
        $paket['jmlh_reservasi'] = $jmlhReservasi;
        $paket['jmlh_tanpa_reservasi'] = $jmlTanpaReservasi;

        return response()->json(array('msg' => view('admin.paket.detailpaket', compact('paket'))->render()), 200);
    }

    public function getDetailPaketReservasi()
    {
        $idPaket = $_POST['idPaket'];
        $paket = Paket::find($idPaket);
        return response()->json(array('msg' => view('admin.reservasi.detailpaket', compact('paket'))->render()), 200);
    }

    public function addPaketToListReservasi()
    {
        $idPaket = $_POST['idPaket'];
        $paket = Paket::find($idPaket);
        $perawatans = $paket->perawatans;
        return response()->json(array('msg' => view('admin.reservasi.adddetailpaket', compact('paket'))->render(), "perawatans" => $perawatans), 200);
    }

    public function updatePerawatanAfterDeletePaket()
    {
        $idPaket = $_POST['idPaket'];
        $paket = Paket::find($idPaket);
        $perawatans = $paket->perawatans;
        return response()->json(array("perawatans" => $perawatans), 200);
    }

    public function checkPaketIsiSama()
    {
        $idPaket = $_POST["idPaket"];
        $daftarPaket = explode(",", $_POST["daftarPaketDiambil"]);



        $arrIdPerawatanYangSudahAda = [];
        $arrDaftarPaket = Paket::whereIn("kode_paket", $daftarPaket)->get();
        foreach ($arrDaftarPaket as $paketTertambah) {
            foreach ($paketTertambah->perawatans as $perawatanPaketTertambah) {
                array_push($arrIdPerawatanYangSudahAda, $perawatanPaketTertambah->id);
            }
        }

        $objPaket = Paket::find($idPaket);
        $arrPerawatanObjPaket = [];

        foreach ($objPaket->perawatans as $perawatan) {
            if (in_array($perawatan->id, $arrIdPerawatanYangSudahAda)) {
                array_push($arrPerawatanObjPaket, $perawatan);
            }
        }

        return response()->json(array("arrPerawatanObjPaket" => $arrPerawatanObjPaket), 200);

    }

}
