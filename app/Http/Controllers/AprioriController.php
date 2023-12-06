<?php

namespace App\Http\Controllers;

use App\Library\Apriori;
use App\Models\Penjualan;
use Illuminate\Http\Request;

class AprioriController extends Controller
{
    public function settingApriori()
    {
        $tanggalPenjualanAwal = Penjualan::where('status_selesai', 'selesai')->min('tanggal_penjualan');
        $tanggalPenjualanAkhir = Penjualan::where('status_selesai', 'selesai')->max('tanggal_penjualan');
        $minSupport = 1;
        $minConfidence = 1;
        if ($tanggalPenjualanAwal != null && $tanggalPenjualanAkhir != null) {
            $jumlahPenjualan = Penjualan::where('tanggal_penjualan', '>=', $tanggalPenjualanAwal)->where('tanggal_penjualan', '<=', $tanggalPenjualanAkhir)->where('status_selesai', 'selesai')->get();
            if (count($jumlahPenjualan) > 0) {
                $keterangan = "Berhasil";
                $batasTanggalMulai = date('Y-m-d', strtotime($tanggalPenjualanAwal));
                $batasTanggalAkhir = date('Y-m-d', strtotime($tanggalPenjualanAkhir));
                $tanggalMulai = date('Y-m-d', strtotime($tanggalPenjualanAwal));
                $tanggalAkhir = date('Y-m-d', strtotime($tanggalPenjualanAkhir));
                return view("admin.rekomendasiproduk.settingrekomendasiproduk", compact('tanggalMulai', 'tanggalAkhir', 'keterangan', 'minSupport', 'minConfidence', 'batasTanggalMulai', 'batasTanggalAkhir'));
            } else {
                $keterangan = "Gagal";
                return view("admin.rekomendasiproduk.settingrekomendasiproduk", compact('keterangan', 'minSupport', 'minConfidence'));
            }
        } else {
            $keterangan = "Gagal";
            return view("admin.rekomendasiproduk.settingrekomendasiproduk", compact('keterangan', 'minSupport', 'minConfidence'));
        }


    }
    public function prosesApriori(Request $request)
    {
        // $aprioriConf = new \CodedHeartInside\DataMining\Apriori\Configuration();
        // $aprioriConf->setDisplayDebugInformation();
        // $aprioriConf->setMinimumSupport(0.6)->setMinimumConfidence(1);

        // $dataInput = new \CodedHeartInside\DataMining\Apriori\Data\Input($aprioriConf);
        // $dataInput->flushDataSet()
        //     ->addDataSet($dataSet);

        // $aprioriClass = new \CodedHeartInside\DataMining\Apriori\Apriori($aprioriConf);
        // $aprioriClass->run();

        // $hasilSupport = $aprioriClass->getSupportRecords();
        // $hasilConfidence = $aprioriClass->getConfidenceRecords();

        $tanggalMulai = $request->get('tanggalMulai');
        $tanggalAkhir = $request->get('tanggalAkhir');
        $minSupport = $request->get('minSupport');
        $minConfidence = $request->get('minConfidence');
        $titleTabel = "Rekomendasi Perawatan atau Produk berdasarkan Riwayat Penjualan pada tanggal " . date('d-m-Y', strtotime($tanggalMulai)) . " hingga " . date('d-m-Y', strtotime($tanggalAkhir));
        $dataSet = [];
        $penjualans = Penjualan::where("tanggal_penjualan", ">=", $tanggalMulai)->where("tanggal_penjualan", "<=", $tanggalAkhir)->where("status_selesai", "selesai")->get();


        foreach ($penjualans as $penjualan) {
            $item = [];

            $idPaketPerawatan = [];
            $idPaketProduk = [];

            if (count($penjualan->pakets) > 0) {
                foreach ($penjualan->pakets as $paket) {
                    array_push($item, $paket->nama);
                    foreach ($paket->perawatans as $perawatan) {
                        array_push($idPaketPerawatan, $perawatan->id);
                    }
                    foreach ($paket->produks as $produk) {
                        array_push($idPaketProduk, $produk->id);
                    }
                }
            }

            foreach ($penjualan->penjualanperawatans as $pp) {
                if (!in_array($pp->perawatan->id, $idPaketPerawatan)) {
                    array_push($item, $pp->perawatan->nama);
                }

            }
            if (count($penjualan->produks) > 0) {
                foreach ($penjualan->produks as $produk) {
                    if (!in_array($produk->id, $idPaketProduk)) {
                        array_push($item, $produk->nama);
                    }
                }
            }


            $itemText = implode(",", $item);
            array_push($dataSet, $itemText);
        }

        // dd($dataSet);
        // $dataSet = [
        //     "Cuci,Gunting,Creambath",
        //     "Cuci,Gunting,Blow",
        //     "Cuci,Gunting,Masker",
        //     "Gunting,Creambath",
        //     "Gunting,Catok,Masker",
        //     "Cuci,Gunting,Blow",
        //     "Gunting,Smoothing",
        //     "Gunting,Facial",
        //     "Gunting,Creambath",
        //     "Cuci,Gunting,Blow",
        //     "Cuci,Gunting,Blow",
        //     "Cuci,Catok,Masker",
        //     "Catok,Masker",
        //     "Gunting,Masker,Cat",
        //     "Cuci,Masker",
        //     "Gunting,Facial",
        //     "Cuci,Gunting,Masker",
        //     "Gunting,Masker,Cat",
        //     "Cuci,Gunting,Blow",
        //     "Gunting,Creambath",
        //     "Cuci,Masker",
        //     "Gunting,Facial",
        //     "Gunting,Cat,Smoothing",
        //     "Gunting,Masker,Cat",
        //     "Cuci,Catok,Masker",
        //     "Cuci,Gunting,Creambath",
        //     "Cuci,Blow",
        //     "Cuci,Blow",
        //     "Cuci,Gunting,Creambath",
        //     "Creambath,Facial",
        // ];


        $Apriori = new Apriori();

        $Apriori->setMaxScan(50); //Scan 2, 3, ...
        $Apriori->setMinSup($minSupport); //Minimum support 1, 2, 3, ...
        $Apriori->setMinConf($minConfidence); //Minimum confidence - Percent 1, 2, ..., 100
        $Apriori->setDelimiter(','); //Delimiter

        $Apriori->process($dataSet);
        $freqItemSets = $Apriori->printFreqItemsets();
        $assocRules = $Apriori->getAssociationRules();
        $keterangan = "Berhasil";

        $batasTanggalMulai = Penjualan::where('status_selesai', 'selesai')->min('tanggal_penjualan');
        $batasTanggalAkhir = Penjualan::where('status_selesai', 'selesai')->max('tanggal_penjualan');
        $batasTanggalMulai = date('Y-m-d', strtotime($batasTanggalMulai));
        $batasTanggalAkhir = date('Y-m-d', strtotime($batasTanggalAkhir));
        $tanggalMulai = date('Y-m-d', strtotime($tanggalMulai));
        $tanggalAkhir = date('Y-m-d', strtotime($tanggalAkhir));

        
        return view('admin.rekomendasiproduk.settingrekomendasiproduk', compact('freqItemSets', 'assocRules', 'keterangan', 'tanggalMulai', 'tanggalAkhir', 'titleTabel', 'minSupport', 'minConfidence', 'batasTanggalMulai', 'batasTanggalAkhir'));
    }

    public function getDetailPenjualan()
    {

        $tanggalMulai = $_POST["tanggalMulai"];
        $tanggalAkhir = $_POST["tanggalAkhir"];
        $dataSet = [];
        $penjualans = Penjualan::where("tanggal_penjualan", ">=", $tanggalMulai)->where("tanggal_penjualan", "<=", $tanggalAkhir)->where("status_selesai", "selesai")->get();


        foreach ($penjualans as $penjualan) {
            $itemPenjualan = [];
            $itemPenjualan["tanggalPenjualan"] = date("d-m-Y", strtotime($penjualan->tanggal_penjualan));
            $itemPenjualan["tanggal"] = date("Y-m-d", strtotime($penjualan->tanggal_penjualan));

            $item = [];
            $idPaketPerawatan = [];
            $idPaketProduk = [];

            if (count($penjualan->pakets) > 0) {
                foreach ($penjualan->pakets as $paket) {
                    array_push($item, $paket->nama);
                    foreach ($paket->perawatans as $perawatan) {
                        array_push($idPaketPerawatan, $perawatan->id);
                    }
                    foreach ($paket->produks as $produk) {
                        array_push($idPaketProduk, $produk->id);
                    }
                }
            }

            foreach ($penjualan->penjualanperawatans as $pp) {
                if (!in_array($pp->perawatan->id, $idPaketPerawatan)) {
                    array_push($item, $pp->perawatan->nama);
                }

            }
            if (count($penjualan->produks) > 0) {
                foreach ($penjualan->produks as $produk) {
                    if (!in_array($produk->id, $idPaketProduk)) {
                        array_push($item, $produk->nama);
                    }
                }
            }

            $itemPenjualan["detail"] = $item;
            array_push($dataSet, $itemPenjualan);
        }

        // dd($dataSet);

        return response()->json(array('msg' => view('admin.rekomendasiproduk.detailpenjualan', compact('dataSet'))->render()), 200);
    }

}
