<?php

namespace App\Http\Controllers;

use App\Models\Perawatan;
use App\Models\Produk;
use Illuminate\Http\Request;

class PerawatanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $perawatansAktif = Perawatan::where('status', 'aktif')->get();
        $perawatansNonaktif = Perawatan::where('status', 'nonaktif')->get();
        return view('admin.perawatan.index', compact('perawatansAktif', 'perawatansNonaktif'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $produks = Produk::where('status', 'aktif')->where('status_jual', 'tidak')->orderBy('nama')->get();
        return view('admin.perawatan.tambahperawatan', compact('produks'));
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
                'namaPerawatan' => 'required|max:255',
                'kode_perawatan' => 'required|unique:perawatans',
                'hargaPerawatan' => 'required|numeric|min:1',
                'durasi' => 'required|numeric|min:30|multiple_of:30',
                'komisiKaryawan' => 'required|numeric|min:1|max:100',
            ],
            [
                'namaPerawatan.required' => 'Nama perawatan tidak boleh kosong!',
                'kode_perawatan.required' => 'Kode perawatan tidak boleh kosong!',
                'kode_perawatan.unique' => 'Kode perawatan sudah pernah dipakai, mohon gunakan kode yang lain!',
                'hargaPerawatan.required' => 'Harga perawatan tidak boleh kosong!',
                'hargaPerawatan.numeric' => 'Harga perawatan harus berupa angka!',
                'hargaPerawatan.min' => 'Harga perawatan harus lebih dari Rp. 0!',
                'durasi.required' => 'Durasi perawatan produk tidak boleh kosong',
                'durasi.numeric' => 'Harga Beli produk harus berupa angka',
                'durasi.min' => 'Durasi perawatan minimal 30 menit!',
                'durasi.multiple_of' => 'Durasi perawatan harus kelipatan 30 menit!',
                'komisiKaryawan.required' => 'Stok produk tidak boleh kosong',
                'komisiKaryawan.numeric' => 'Stok produk harus berupa angka',
                'komisiKaryawan.min' => 'Minimal komisi karyawan adalah 1%!',
                'komisiKaryawan.max' => 'Maksimal komisi karyawan adalah 100%!',
            ]
        );

        $namaPerawatan = $request->get('namaPerawatan');
        $kodePerawatan = $request->get('kode_perawatan');
        $hargaPerawatan = $request->get('hargaPerawatan');
        $durasiPerawatan = $request->get('durasi');
        $komisiKaryawan = $request->get('komisiKaryawan');
        $deskripsiPerawatan = $request->get('deskripsiPerawatan');
        $statusKeaktifan = $request->get('radioStatusPerawatan');
        $statusKomplemen = $request->get('radioStatusKomplemenPerawatan');
        $arrayIdProduk = $request->get('arrayprodukid');

        $newPerawatan = new Perawatan();
        $newPerawatan->nama = $namaPerawatan;
        $newPerawatan->kode_perawatan = $kodePerawatan;
        $newPerawatan->harga = $hargaPerawatan;
        $newPerawatan->durasi = $durasiPerawatan;
        $newPerawatan->deskripsi = $deskripsiPerawatan;
        $newPerawatan->status = $statusKeaktifan;
        $newPerawatan->status_komplemen = $statusKomplemen;
        $newPerawatan->komisi = $komisiKaryawan;
        $newPerawatan->created_at = date('Y-m-d H:i:s');
        $newPerawatan->updated_at = date('Y-m-d H:i:s');
        $newPerawatan->save();

        if ($arrayIdProduk != null) {
            foreach ($arrayIdProduk as $idProduk) {
                $newPerawatan->produks()->attach($idProduk);
            }
        }

        return redirect()->route('perawatans.index')->with('status', 'Berhasil menambah perawatan ' . $namaPerawatan . '!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Perawatan  $perawatan
     * @return \Illuminate\Http\Response
     */
    public function show(Perawatan $perawatan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Perawatan  $perawatan
     * @return \Illuminate\Http\Response
     */
    public function edit(Perawatan $perawatan)
    {
        $produksDigunakan = Produk::where('status', 'aktif')->where('status_jual', 'tidak')->orderBy('nama')->get();
        return view('admin.perawatan.editperawatan', compact('produksDigunakan', 'perawatan'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Perawatan  $perawatan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Perawatan $perawatan)
    {
        date_default_timezone_set("Asia/Jakarta");
        $namaPerawatan = $request->get('namaPerawatan');
        $kodePerawatan = $request->get('kode_perawatan');
        $hargaPerawatan = $request->get('hargaPerawatan');
        $durasiPerawatan = $request->get('durasi');
        $komisiKaryawan = $request->get('komisiKaryawan');
        $deskripsiPerawatan = $request->get('deskripsiPerawatan');
        $statusKeaktifan = $request->get('radioStatusPerawatan');
        $statusKomplemen = $request->get('radioStatusKomplemenPerawatan');
        $arrayIdProduk = $request->get('arrayprodukid');
        if ($kodePerawatan != $perawatan->kode_perawatan) {
            $validatedData = $request->validate(
                [
                    'namaPerawatan' => 'required|max:255',
                    'kode_perawatan' => 'required|unique:perawatans',
                    'hargaPerawatan' => 'required|numeric|min:1',
                    'durasi' => 'required|numeric|min:30|multiple_of:30',
                    'komisiKaryawan' => 'required|numeric|min:1|max:100',
                ],
                [
                    'namaPerawatan.required' => 'Nama perawatan tidak boleh kosong!',
                    'kode_perawatan.required' => 'Kode perawatan tidak boleh kosong!',
                    'kode_perawatan.unique' => 'Kode perawatan sudah pernah dipakai, mohon gunakan kode yang lain!',
                    'hargaPerawatan.required' => 'Harga perawatan tidak boleh kosong!',
                    'hargaPerawatan.numeric' => 'Harga perawatan harus berupa angka!',
                    'hargaPerawatan.min' => 'Harga perawatan harus lebih dari Rp. 0!',
                    'durasi.required' => 'Durasi perawatan produk tidak boleh kosong',
                    'durasi.numeric' => 'Harga Beli produk harus berupa angka',
                    'durasi.min' => 'Durasi perawatan minimal 30 menit!',
                    'durasi.multiple_of' => 'Durasi perawatan harus kelipatan 30 menit!',
                    'komisiKaryawan.required' => 'Stok produk tidak boleh kosong',
                    'komisiKaryawan.numeric' => 'Stok produk harus berupa angka',
                    'komisiKaryawan.min' => 'Minimal komisi karyawan adalah 1%!',
                    'komisiKaryawan.max' => 'Maksimal komisi karyawan adalah 100%!',
                ]
            );
            $perawatan->nama = $namaPerawatan;
            $perawatan->kode_perawatan = $kodePerawatan;
            $perawatan->harga = $hargaPerawatan;
            $perawatan->durasi = $durasiPerawatan;
            $perawatan->deskripsi = $deskripsiPerawatan;
            $perawatan->status = $statusKeaktifan;
            $perawatan->status_komplemen = $statusKomplemen;
            $perawatan->komisi = $komisiKaryawan;
            $perawatan->updated_at = date('Y-m-d H:i:s');
            $perawatan->save();

            if ($arrayIdProduk != null) {
                foreach ($perawatan->produks as $produk) {
                    $perawatan->produks()->detach($produk);
                }

                foreach ($arrayIdProduk as $idProduk) {
                    $perawatan->produks()->attach($idProduk);
                }
            } else {
                foreach ($perawatan->produks as $produk) {
                    $perawatan->produks()->detach($produk);
                }
            }

            return redirect()->route('perawatans.index')->with('status', 'Berhasil mengedit perawatan ' . $namaPerawatan . '!');
        } else {
            $validatedData = $request->validate(
                [
                    'namaPerawatan' => 'required|max:255',
                    'hargaPerawatan' => 'required|numeric|min:1',
                    'durasi' => 'required|numeric|min:30|multiple_of:30',
                    'komisiKaryawan' => 'required|numeric|min:1|max:100',
                ],
                [
                    'namaPerawatan.required' => 'Nama perawatan tidak boleh kosong!',
                    'hargaPerawatan.required' => 'Harga perawatan tidak boleh kosong!',
                    'hargaPerawatan.numeric' => 'Harga perawatan harus berupa angka!',
                    'hargaPerawatan.min' => 'Harga perawatan harus lebih dari Rp. 0!',
                    'durasi.required' => 'Durasi perawatan produk tidak boleh kosong',
                    'durasi.numeric' => 'Harga Beli produk harus berupa angka',
                    'durasi.min' => 'Durasi perawatan minimal 30 menit!',
                    'durasi.multiple_of' => 'Durasi perawatan harus kelipatan 30 menit!',
                    'komisiKaryawan.required' => 'Stok produk tidak boleh kosong',
                    'komisiKaryawan.numeric' => 'Stok produk harus berupa angka',
                    'komisiKaryawan.min' => 'Minimal komisi karyawan adalah 1%!',
                    'komisiKaryawan.max' => 'Maksimal komisi karyawan adalah 100%!',
                ]
            );

            $perawatan->nama = $namaPerawatan;
            $perawatan->harga = $hargaPerawatan;
            $perawatan->durasi = $durasiPerawatan;
            $perawatan->deskripsi = $deskripsiPerawatan;
            $perawatan->status = $statusKeaktifan;
            $perawatan->status_komplemen = $statusKomplemen;
            $perawatan->komisi = $komisiKaryawan;
            $perawatan->updated_at = date('Y-m-d H:i:s');
            $perawatan->save();

            if ($arrayIdProduk != null) {
                foreach ($perawatan->produks as $produk) {
                    $perawatan->produks()->detach($produk);
                }

                foreach ($arrayIdProduk as $idProduk) {
                    $perawatan->produks()->attach($idProduk);
                }
            } else {
                foreach ($perawatan->produks as $produk) {
                    $perawatan->produks()->detach($produk);
                }
            }

            return redirect()->route('perawatans.index')->with('status', 'Berhasil mengedit perawatan ' . $namaPerawatan . '!');
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Perawatan  $perawatan
     * @return \Illuminate\Http\Response
     */
    public function destroy(Perawatan $perawatan)
    {
        $objPerawatan = $perawatan;
        try {
            $objPerawatan->delete();
            return redirect()->route('perawatans.index')->with('status', 'Perawatan ' . $objPerawatan->nama . ' telah berhasil dihapus');
        } catch (\PDOException $ex) {
            $msg = "Data Gagal dihapus. Pastikan kembali tidak ada data yang berelasi sebelum dihapus";
            return redirect()->route('perawatans.index')->with('status', $msg);
        }
    }

    public function getDetailPerawatan()
    {
        $idPerawatan = $_POST['idPerawatan'];
        $perawatan = Perawatan::find($idPerawatan);
        return response()->json(array('msg' => view('admin.reservasi.detailperawatan', compact('perawatan'))->render()), 200);
    }

    public function getDetailPerawatanList()
    {

        $idPerawatan = $_POST['idPerawatan'];
        $perawatan = Perawatan::find($idPerawatan);
        $jmlhReservasi = 0;
        $jmlTanpaReservasi = 0;
        foreach ($perawatan->penjualanperawatans as $pp) {
            if ($pp->penjualan->reservasi != null) {
                if ($pp->penjualan->reservasi->status == 'selesai') {
                    $jmlhReservasi++;
                }
            } else {
                if ($pp->penjualan->status_selesai == 'selesai') {
                    $jmlTanpaReservasi++;
                }
            }
        }
        $perawatan['jmlh_reservasi'] = $jmlhReservasi;
        $perawatan['jmlh_tanpa_reservasi'] = $jmlTanpaReservasi;
        return response()->json(array('msg' => view('admin.perawatan.detailperawatan', compact('perawatan'))->render()), 200);
    }
}