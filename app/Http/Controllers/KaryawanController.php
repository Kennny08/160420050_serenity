<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use App\Models\Perawatan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class KaryawanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $karyawans = Karyawan::all();

        return view("admin.karyawan.index", compact("karyawans"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $perawatans = Perawatan::where('status', 'aktif')->orderBy('nama')->get();
        return view('admin.karyawan.tambahkaryawan', compact('perawatans'));
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
                'namaKaryawan' => 'required|max:255',
                'emailKaryawan' => ['required', 'email:rfc,dns', Rule::unique('users', 'email')->where('role', 'karyawan')],
                'gajiKaryawan' => 'required|numeric|min:1',
                'nomor_telepon' => 'required|numeric|unique:karyawans',
                'arrayperawatanid' => 'required|min:1'
            ],
            [
                'namaKaryawan.required' => 'Nama karyawan tidak boleh kosong!',
                'emailKaryawan.required' => 'Email karyawan tidak boleh kosong!',
                'emailKaryawan.email' => 'Mohon masukkan email dengan format yang benar!',
                'emailKaryawan.unique' => 'Email karyawan ' . $request->get('emailKaryawan') . ' sudah pernah dipakai, mohon gunakan email yang lain!',
                'gajiKaryawan.required' => 'Gaji Pokok Karyawan tidak boleh kosong!',
                'gajiKaryawan.numeric' => 'Gaji Karyawan harus berupa angka!',
                'gajiKaryawan.min' => 'Gaji karyawan harus lebih dari Rp. 0!',
                'nomor_telepon.required' => 'Nomor telepon karyawan tidak boleh kosong!',
                'nomor_telepon.numeric' => 'Nomor telepon karyawan harus berupa angka!',
                'nomor_telepon.unique' => 'Karyawan dengan nomor telepon ' . $request->get('nomor_telepon') . ' sudah pernah dipakai, mohon gunakan nomor telepon yang lain!',
                'arrayperawatanid.required' => 'Perawatan tidak boleh kosong, minimal pilih satu perawatan!',
            ]
        );

        $namaKaryawan = $request->get("namaKaryawan");
        $emailKaryawan = $request->get('emailKaryawan');
        $gajiKaryawan = $request->get('gajiKaryawan');
        $nomor_telepon = $request->get('nomor_telepon');
        $arrayperawatanid = $request->get('arrayperawatanid');
        $jenisKaryawan = $request->get('radioJenisKaryawan');
        $genderKaryawan = $request->get('radioGenderKaryawan');
        $tanggalLahirKaryawan = $request->get('tanggalLahir');


        $newUser = User::create([
            'username' => str_replace(" ", "", $namaKaryawan) . "12345",
            'email' => $emailKaryawan,
            'password' => Hash::make("12345678"),
            'role' => 'karyawan',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        $newKaryawan = new Karyawan();
        $newKaryawan->nama = $namaKaryawan;
        $newKaryawan->tanggal_lahir = $tanggalLahirKaryawan;
        $newKaryawan->gaji = $gajiKaryawan;
        $newKaryawan->user_id = $newUser->id;
        $newKaryawan->gender = $genderKaryawan;
        $newKaryawan->nomor_telepon = $nomor_telepon;
        $newKaryawan->jenis_karyawan = $jenisKaryawan;
        $newKaryawan->created_at = date('Y-m-d H:i:s');
        $newKaryawan->updated_at = date('Y-m-d H:i:s');
        $newKaryawan->save();

        foreach ($arrayperawatanid as $idPerawatan) {
            $newKaryawan->perawatans()->attach($idPerawatan);
        }

        return redirect()->route('karyawans.index')->with('status', 'Berhasil menambah karyawan ' . $namaKaryawan . '!');


    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Karyawan  $karyawan
     * @return \Illuminate\Http\Response
     */
    public function show(Karyawan $karyawan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Karyawan  $karyawan
     * @return \Illuminate\Http\Response
     */
    public function edit(Karyawan $karyawan)
    {
        $perawatans = Perawatan::where('status', 'aktif')->orderBy('nama')->get();
        return view('admin.karyawan.editkaryawan', compact('perawatans', 'karyawan'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Karyawan  $karyawan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Karyawan $karyawan)
    {
        date_default_timezone_set("Asia/Jakarta");
        $namaKaryawan = $request->get("namaKaryawan");
        $emailKaryawan = $request->get('emailKaryawan');
        $gajiKaryawan = $request->get('gajiKaryawan');
        $nomor_telepon = $request->get('nomor_telepon');
        $arrayperawatanid = $request->get('arrayperawatanid');
        $jenisKaryawan = $request->get('radioJenisKaryawan');
        $genderKaryawan = $request->get('radioGenderKaryawan');
        $tanggalLahirKaryawan = $request->get('tanggalLahir');
        //Mengecek apakah ada pergantian email atau nomor telepon
        if ($request->get('emailKaryawan') != $karyawan->user->email || $request->get('nomor_telepon') != $karyawan->nomor_telepon) {
            //Mengecek apakah ada pergantian email
            if ($request->get('emailKaryawan') != $karyawan->user->email) {
                //Menegecek apakah ada pergantian email dan nomor telepon
                if ($request->get('nomor_telepon') != $karyawan->nomor_telepon) {
                    $validatedData = $request->validate(
                        [
                            'namaKaryawan' => 'required|max:255',
                            'emailKaryawan' => ['required', 'email:rfc,dns', Rule::unique('users', 'email')->where('role', 'karyawan')],
                            'gajiKaryawan' => 'required|numeric|min:1',
                            'nomor_telepon' => 'required|numeric|unique:karyawans',
                            'arrayperawatanid' => 'required|min:1'
                        ],
                        [
                            'namaKaryawan.required' => 'Nama karyawan tidak boleh kosong!',
                            'emailKaryawan.required' => 'Email karyawan tidak boleh kosong!',
                            'emailKaryawan.email' => 'Mohon masukkan email dengan format yang benar!',
                            'emailKaryawan.unique' => 'Email karyawan ' . $request->get('emailKaryawan') . ' sudah pernah dipakai, mohon gunakan email yang lain!',
                            'gajiKaryawan.required' => 'Gaji Pokok Karyawan tidak boleh kosong!',
                            'gajiKaryawan.numeric' => 'Gaji Karyawan harus berupa angka!',
                            'gajiKaryawan.min' => 'Gaji karyawan harus lebih dari Rp. 0!',
                            'nomor_telepon.required' => 'Nomor telepon karyawan tidak boleh kosong!',
                            'nomor_telepon.numeric' => 'Nomor telepon karyawan harus berupa angka!',
                            'nomor_telepon.unique' => 'Karyawan dengan nomor telepon ' . $request->get('nomor_telepon') . ' sudah pernah dipakai, mohon gunakan nomor telepon yang lain!',
                            'arrayperawatanid.required' => 'Perawatan tidak boleh kosong, minimal pilih satu perawatan!',
                        ]
                    );
                    $karyawan->nama = $namaKaryawan;
                    $karyawan->tanggal_lahir = $tanggalLahirKaryawan;
                    $karyawan->gaji = $gajiKaryawan;
                    $karyawan->gender = $genderKaryawan;
                    $karyawan->nomor_telepon = $nomor_telepon;
                    $karyawan->jenis_karyawan = $jenisKaryawan;
                    $karyawan->updated_at = date('Y-m-d H:i:s');

                    $userKaryawan = $karyawan->user;
                    $userKaryawan->email = $emailKaryawan;
                    $userKaryawan->save();

                    $karyawan->save();

                    foreach ($karyawan->perawatans as $perawatan) {
                        $karyawan->perawatans()->detach($perawatan);
                    }

                    foreach ($arrayperawatanid as $idPerawatan) {
                        $karyawan->perawatans()->attach($idPerawatan);
                    }

                    return redirect()->route('karyawans.index')->with('status', 'Berhasil mengedit karyawan ' . $namaKaryawan . '!');
                } else {
                    //Ada pergantian email tapi tidak ada pergantian nomor telepon
                    $validatedData = $request->validate(
                        [
                            'namaKaryawan' => 'required|max:255',
                            'emailKaryawan' => ['required', 'email:rfc,dns', Rule::unique('users', 'email')->where('role', 'karyawan')],
                            'gajiKaryawan' => 'required|numeric|min:1',
                            'arrayperawatanid' => 'required|min:1'
                        ],
                        [
                            'namaKaryawan.required' => 'Nama karyawan tidak boleh kosong!',
                            'emailKaryawan.required' => 'Email karyawan tidak boleh kosong!',
                            'emailKaryawan.email' => 'Mohon masukkan email dengan format yang benar!',
                            'emailKaryawan.unique' => 'Email karyawan ' . $request->get('emailKaryawan') . ' sudah pernah dipakai, mohon gunakan email yang lain!',
                            'gajiKaryawan.required' => 'Gaji Pokok Karyawan tidak boleh kosong!',
                            'gajiKaryawan.numeric' => 'Gaji Karyawan harus berupa angka!',
                            'gajiKaryawan.min' => 'Gaji karyawan harus lebih dari Rp. 0!',
                            'arrayperawatanid.required' => 'Perawatan tidak boleh kosong, minimal pilih satu perawatan!',
                        ]
                    );
                    $karyawan->nama = $namaKaryawan;
                    $karyawan->tanggal_lahir = $tanggalLahirKaryawan;
                    $karyawan->gaji = $gajiKaryawan;
                    $karyawan->gender = $genderKaryawan;
                    $karyawan->jenis_karyawan = $jenisKaryawan;
                    $karyawan->updated_at = date('Y-m-d H:i:s');

                    $userKaryawan = $karyawan->user;
                    $userKaryawan->email = $emailKaryawan;
                    $userKaryawan->save();

                    $karyawan->save();

                    foreach ($karyawan->perawatans as $perawatan) {
                        $karyawan->perawatans()->detach($perawatan);
                    }

                    foreach ($arrayperawatanid as $idPerawatan) {
                        $karyawan->perawatans()->attach($idPerawatan);
                    }

                    return redirect()->route('karyawans.index')->with('status', 'Berhasil mengedit karyawan ' . $namaKaryawan . '!');
                }
            }

            if ($request->get('nomor_telepon') != $karyawan->nomor_telepon) {
                //Menegecek apakah ada pergantian nomor telepon dan email
                if ($request->get('emailKaryawan') != $karyawan->user->email) {
                    $validatedData = $request->validate(
                        [
                            'namaKaryawan' => 'required|max:255',
                            'emailKaryawan' => ['required', 'email:rfc,dns', Rule::unique('users', 'email')->where('role', 'karyawan')],
                            'gajiKaryawan' => 'required|numeric|min:1',
                            'nomor_telepon' => 'required|numeric|unique:karyawans',
                            'arrayperawatanid' => 'required|min:1'
                        ],
                        [
                            'namaKaryawan.required' => 'Nama karyawan tidak boleh kosong!',
                            'emailKaryawan.required' => 'Email karyawan tidak boleh kosong!',
                            'emailKaryawan.email' => 'Mohon masukkan email dengan format yang benar!',
                            'emailKaryawan.unique' => 'Email karyawan ' . $request->get('emailKaryawan') . ' sudah pernah dipakai, mohon gunakan email yang lain!',
                            'gajiKaryawan.required' => 'Gaji Pokok Karyawan tidak boleh kosong!',
                            'gajiKaryawan.numeric' => 'Gaji Karyawan harus berupa angka!',
                            'gajiKaryawan.min' => 'Gaji karyawan harus lebih dari Rp. 0!',
                            'nomor_telepon.required' => 'Nomor telepon karyawan tidak boleh kosong!',
                            'nomor_telepon.numeric' => 'Nomor telepon karyawan harus berupa angka!',
                            'nomor_telepon.unique' => 'Karyawan dengan nomor telepon ' . $request->get('nomor_telepon') . ' sudah pernah dipakai, mohon gunakan nomor telepon yang lain!',
                            'arrayperawatanid.required' => 'Perawatan tidak boleh kosong, minimal pilih satu perawatan!',
                        ]
                    );
                    $karyawan->nama = $namaKaryawan;
                    $karyawan->tanggal_lahir = $tanggalLahirKaryawan;
                    $karyawan->gaji = $gajiKaryawan;
                    $karyawan->gender = $genderKaryawan;
                    $karyawan->nomor_telepon = $nomor_telepon;
                    $karyawan->jenis_karyawan = $jenisKaryawan;
                    $karyawan->updated_at = date('Y-m-d H:i:s');

                    $userKaryawan = $karyawan->user;
                    $userKaryawan->email = $emailKaryawan;
                    $userKaryawan->save();

                    $karyawan->save();

                    foreach ($karyawan->perawatans as $perawatan) {
                        $karyawan->perawatans()->detach($perawatan);
                    }

                    foreach ($arrayperawatanid as $idPerawatan) {
                        $karyawan->perawatans()->attach($idPerawatan);
                    }

                    return redirect()->route('karyawans.index')->with('status', 'Berhasil mengedit karyawan ' . $namaKaryawan . '!');
                } else {
                    //Ada  pergantian nomor telepon tetapi tidak ada pergantian email
                    $validatedData = $request->validate(
                        [
                            'namaKaryawan' => 'required|max:255',
                            'gajiKaryawan' => 'required|numeric|min:1',
                            'nomor_telepon' => 'required|numeric|unique:karyawans',
                            'arrayperawatanid' => 'required|min:1'
                        ],
                        [
                            'nomor_telepon.required' => 'Nomor telepon karyawan tidak boleh kosong!',
                            'nomor_telepon.numeric' => 'Nomor telepon karyawan harus berupa angka!',
                            'nomor_telepon.unique' => 'Karyawan dengan nomor telepon ' . $request->get('nomor_telepon') . ' sudah pernah dipakai, mohon gunakan nomor telepon yang lain!',
                            'namaKaryawan.required' => 'Nama karyawan tidak boleh kosong!',
                            'gajiKaryawan.required' => 'Gaji Pokok Karyawan tidak boleh kosong!',
                            'gajiKaryawan.numeric' => 'Gaji Karyawan harus berupa angka!',
                            'gajiKaryawan.min' => 'Gaji karyawan harus lebih dari Rp. 0!',
                            'arrayperawatanid.required' => 'Perawatan tidak boleh kosong, minimal pilih satu perawatan!',
                        ]
                    );
                    $karyawan->nama = $namaKaryawan;
                    $karyawan->tanggal_lahir = $tanggalLahirKaryawan;
                    $karyawan->gaji = $gajiKaryawan;
                    $karyawan->gender = $genderKaryawan;
                    $karyawan->jenis_karyawan = $jenisKaryawan;
                    $karyawan->nomor_telepon = $nomor_telepon;
                    $karyawan->updated_at = date('Y-m-d H:i:s');

                    $karyawan->save();

                    foreach ($karyawan->perawatans as $perawatan) {
                        $karyawan->perawatans()->detach($perawatan);
                    }

                    foreach ($arrayperawatanid as $idPerawatan) {
                        $karyawan->perawatans()->attach($idPerawatan);
                    }

                    return redirect()->route('karyawans.index')->with('status', 'Berhasil mengedit karyawan ' . $namaKaryawan . '!');
                }
            }
        } else {
            $validatedData = $request->validate(
                [
                    'namaKaryawan' => 'required|max:255',
                    'gajiKaryawan' => 'required|numeric|min:1',
                    'arrayperawatanid' => 'required|min:1'
                ],
                [
                    'namaKaryawan.required' => 'Nama karyawan tidak boleh kosong!',
                    'gajiKaryawan.required' => 'Gaji Pokok Karyawan tidak boleh kosong!',
                    'gajiKaryawan.numeric' => 'Gaji Karyawan harus berupa angka!',
                    'gajiKaryawan.min' => 'Gaji karyawan harus lebih dari Rp. 0!',
                    'arrayperawatanid.required' => 'Perawatan tidak boleh kosong, minimal pilih satu perawatan!',
                ]
            );

            $karyawan->nama = $namaKaryawan;
            $karyawan->tanggal_lahir = $tanggalLahirKaryawan;
            $karyawan->gaji = $gajiKaryawan;
            $karyawan->gender = $genderKaryawan;
            $karyawan->jenis_karyawan = $jenisKaryawan;
            $karyawan->updated_at = date('Y-m-d H:i:s');
            $karyawan->save();

            foreach ($karyawan->perawatans as $perawatan) {
                $karyawan->perawatans()->detach($perawatan);
            }

            foreach ($arrayperawatanid as $idPerawatan) {
                $karyawan->perawatans()->attach($idPerawatan);
            }

            return redirect()->route('karyawans.index')->with('status', 'Berhasil mengedit karyawan ' . $namaKaryawan . '!');
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Karyawan  $karyawan
     * @return \Illuminate\Http\Response
     */
    public function destroy(Karyawan $karyawan)
    {
        
        $objKaryawan = $karyawan;
        try {
            $objKaryawan->delete();
            return redirect()->route('karyawans.index')->with('status', 'Karyawan ' . $objKaryawan->nama . ' telah berhasil dihapus');
        } catch (\PDOException $ex) {
            $msg = "Data Gagal dihapus. Pastikan kembali tidak ada data yang berelasi sebelum dihapus";
            return redirect()->route('karyawans.index')->with('status', $msg);
        }
    }

    public function getDetailKaryawan()
    {
        $idKaryawan = $_POST['idKaryawan'];
        $karyawan = Karyawan::find($idKaryawan);
        return response()->json(array('msg' => view('admin.karyawan.detailkaryawan', compact('karyawan'))->render()), 200);
    }
}
