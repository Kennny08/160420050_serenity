<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Paket;
use App\Models\Pelanggan;
use App\Models\Penjualan;
use App\Models\PenjualanPerawatan;
use App\Models\Perawatan;
use App\Models\Produk;
use App\Models\Ulasan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class PelangganController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        date_default_timezone_set('Asia/Jakarta');
        $hariIndonesia = array('Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu');
        $nomorHariDalamMingguan = date("w");
        $tanggalHariIni = $hariIndonesia[$nomorHariDalamMingguan] . ", " . date('d-m-Y');
        $pelanggan = Auth::user()->pelanggan;

        $totalReservasi = 0;
        $totalPerawatan = 0;
        $totalProduk = 0;

        $penjualans = Penjualan::where("pelanggan_id", $pelanggan->id)->where("status_selesai", "selesai")->get();
        foreach ($penjualans as $penjualan) {
            if ($penjualan->reservasi != null) {
                $totalReservasi += 1;
            }

            foreach ($penjualan->produks as $produk) {
                $totalProduk += $produk->pivot->kuantitas;
            }

            $totalPerawatan += $penjualan->penjualanperawatans->count();
        }

        $perawatans = Perawatan::where("status", "aktif")->inRandomOrder()->limit(4)->get();
        $penjualanPerawatans = PenjualanPerawatan::select("penjualan_perawatan.perawatan_id")->join("penjualans", "penjualans.id", "=", "penjualan_perawatan.penjualan_id")->where("penjualans.status_selesai", "selesai")->get();

        $produks = [];
        $kategoris = Kategori::all()->shuffle();
        $daftarKategori = [];
        foreach ($kategoris as $k) {
            if ($k->produks->where("status_jual", "aktif")->count() > 0) {
                array_push($daftarKategori, $k);
            }
        }

        $batas = 0;
        if (count($daftarKategori) >= 4) {
            $batas = 4;
        }else{
            $batas = count($daftarKategori);
        }

        for ($i = 0; $i < $batas; $i++) {
            $produkSementara = [];
            $produkSementara["kategori"] = $daftarKategori[$i];
            $produkSementara["produks"] = Produk::where("status", "aktif")->where("status_jual", "aktif")->where("kategori_id", $daftarKategori[$i]->id)->inRandomOrder()->limit(4)->get();
            array_push($produks, $produkSementara);
        }
        $penjualansSelesai = Penjualan::where("status_selesai", "selesai")->get();

        $ulasans = Ulasan::where("status", "aktif")->inRandomOrder()->limit(3)->get();

        $pakets = Paket::where("status", "aktif")->inRandomOrder()->limit(4)->get();


        return view("pelanggan.index", compact('pelanggan', 'tanggalHariIni', 'totalReservasi', 'totalPerawatan', 'totalProduk', 'perawatans', 'penjualanPerawatans', 'produks', 'penjualansSelesai', 'ulasans', 'pakets'));
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
     * @param  \App\Models\Pelanggan  $pelanggan
     * @return \Illuminate\Http\Response
     */
    public function show(Pelanggan $pelanggan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Pelanggan  $pelanggan
     * @return \Illuminate\Http\Response
     */
    public function edit(Pelanggan $pelanggan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Pelanggan  $pelanggan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Pelanggan $pelanggan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Pelanggan  $pelanggan
     * @return \Illuminate\Http\Response
     */
    public function destroy(Pelanggan $pelanggan)
    {
        //
    }

    public function bukaRegisterAkun(){
        return view("auth.register");
    }

    public function registerAkun(Request $request){
        

        date_default_timezone_set("Asia/Jakarta");
        $validatedData = $request->validate(
            [
                'nama' => 'required|max:255',
                'email' => ['required', 'email:rfc,dns', Rule::unique('users', 'email')->where('role', 'pelanggan')],
                'tanggallahir' => 'required',
                'gender' => 'required',
                'alamat' => 'required',
                'password' => 'required|min:8',
                'username' => ['required', Rule::unique('users', 'username')],
                'nomor_telepon' => "required|unique:pelanggans|min:11",
            ],
            [
                'nama.required' => 'Nama karyawan tidak boleh kosong!',
                'email.required' => 'Email tidak boleh kosong!',
                'email.email' => 'Mohon masukkan email dengan format yang benar!',
                'emailKaryawan.unique' => 'Email karyawan ' . $request->get('emailKaryawan') . ' sudah pernah dipakai, mohon gunakan email yang lain!',
                'tanggallahir.required' => 'Tanggal lahir tidak boleh kosong!',
                'gender.required' => 'Harap pilih gender Anda!',
                'alamat.required' => 'Alamat tidak boleh kosong!',
                'username.required' => 'Username tidak boleh kosong',
                'nomor_telepon.required' => 'Nomor telepon tidak boleh kosong',
                'nomor_telepon.min' => 'Minimal nomor telepon ada 11 digit',
                'email.unique' => 'Email telah terpakai, mohon gunakan email Anda yang lain!',
                'username.unique' => 'Username telah terpakai, mohon gunakan username Anda yang lain!',
                'nomor_telepon.unique' => 'Nomor telepon telah terpakai, mohon gunakan nomor telepon Anda yang lain!',
                "password.required" => "Password tidak boleh kosong",
                "password.min" => "Minimal password ada 8 digit",

            ]
        );

        $namaPelanggan = $request->get("nama");
        $email = $request->get("email");
        $tanggalLahir = $request->get("tanggallahir");
        $gender = $request->get("gender");
        $alamat = $request->get("alamat");
        $password = $request->get("password");
        $username = $request->get("username");
        $nomorTelepon = $request->get("nomor_telepon");

        $newUser = User::create([
            'username' => $username,
            'email' => $email,
            'password' => Hash::make($password),
            'role' => 'pelanggan',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        $newPelanggan = new Pelanggan();
        $newPelanggan->user_id = $newUser->id;
        $newPelanggan->nama = $namaPelanggan;
        $newPelanggan->tanggal_lahir = $tanggalLahir;
        $newPelanggan->nomor_telepon = $nomorTelepon;
        $newPelanggan->gender = $gender;
        $newPelanggan->alamat = $alamat;
        $newPelanggan->created_at = date("Y-m-d H:i:s");
        $newPelanggan->updated_at = date("Y-m-d H:i:s");
        $newPelanggan->save();

        return redirect()->route("login")->with("status", "Berhasil melakukan regitrasi akun. Silahkan melakukan Login!");
    }
}
