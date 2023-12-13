<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use App\Models\Penjualan;
use App\Models\PenjualanPerawatan;
use App\Models\Perawatan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

        if ($request->get('radioJenisKaryawan') == "admin") {
            $validatedData = $request->validate(
                [
                    'namaKaryawan' => 'required|max:255',
                    'emailKaryawan' => ['required', 'email:rfc,dns', Rule::unique('users', 'email')->where(function ($query) {
                        $query->whereIn('id', function ($subquery) {
                            $subquery->select('user_id')
                                ->from('karyawans')
                                ->whereNull('deleted_at');
                        });
                    })],
                    'gajiKaryawan' => 'required|numeric|min:1',
                    'nomor_telepon' => 'required|numeric|unique:karyawans',
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
                ]
            );
        } else {
            $validatedData = $request->validate(
                [
                    'namaKaryawan' => 'required|max:255',
                    'emailKaryawan' => ['required', 'email:rfc,dns', Rule::unique('users', 'email')->where('role', 'karyawan')->where(function ($query) {
                        $query->whereIn('id', function ($subquery) {
                            $subquery->select('user_id')
                                ->from('karyawans')
                                ->whereNull('deleted_at');
                        });
                    })],
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
        }



        $namaKaryawan = $request->get("namaKaryawan");
        $emailKaryawan = $request->get('emailKaryawan');
        $gajiKaryawan = $request->get('gajiKaryawan');
        $nomor_telepon = $request->get('nomor_telepon');
        $arrayperawatanid = $request->get('arrayperawatanid');
        $jenisKaryawan = $request->get('radioJenisKaryawan');
        $genderKaryawan = $request->get('radioGenderKaryawan');
        $tanggalLahirKaryawan = $request->get('tanggalLahir');


        $usernameUnik = str_replace(" ", "", strtolower($namaKaryawan)) . date("d") . date("H") . date("i") . date("s");
        $checkUsernameKaryawanUnik = false;
        while ($checkUsernameKaryawanUnik == false) {
            $cariUsername = User::where("username", $usernameUnik)->where("role", "karyawan")->first();
            if ($cariUsername == null) {
                $checkUsernameKaryawanUnik = true;
            } else {
                $usernameUnik = str_replace(" ", "", strtolower($namaKaryawan)) . date("d") . date("H") . date("i") . date("s");
            }
        }

        $passwordKaryawan = "";
        for ($i = 0; $i < 9; $i++) {
            $passwordKaryawan .= random_int(1, 9);
        }
        $newUser = User::create([
            'username' => $usernameUnik,
            'email' => $emailKaryawan,
            'password' => Hash::make($passwordKaryawan),
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

        if ($request->get('radioJenisKaryawan') != "admin") {
            foreach ($arrayperawatanid as $idPerawatan) {
                $newKaryawan->perawatans()->attach($idPerawatan);
            }
        }


        MailController::mailUsernamePasswordTambahKaryawan($emailKaryawan, $namaKaryawan, $usernameUnik, $passwordKaryawan);

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
    public function edit($idKaryawan)
    {
        $karyawan = Karyawan::find($idKaryawan);
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
    public function update(Request $request, $idKaryawan)
    {
        date_default_timezone_set("Asia/Jakarta");

        $karyawan = Karyawan::find($idKaryawan);

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
                //Mengecek apakah ada pergantian email dan nomor telepon
                if ($request->get('nomor_telepon') != $karyawan->nomor_telepon) {

                    if ($jenisKaryawan == "admin") {
                        $validatedData = $request->validate(
                            [
                                'namaKaryawan' => 'required|max:255',
                                'emailKaryawan' => ['required', 'email:rfc,dns', Rule::unique('users', 'email')->where('role', 'karyawan')->where(function ($query) {
                                    $query->whereIn('id', function ($subquery) {
                                        $subquery->select('user_id')
                                            ->from('karyawans')
                                            ->whereNull('deleted_at');
                                    });
                                })],
                                'gajiKaryawan' => 'required|numeric|min:1',
                                'nomor_telepon' => 'required|numeric|unique:karyawans',

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

                            ]
                        );
                    } else {
                        $validatedData = $request->validate(
                            [
                                'namaKaryawan' => 'required|max:255',
                                'emailKaryawan' => ['required', 'email:rfc,dns', Rule::unique('users', 'email')->where('role', 'karyawan')->where(function ($query) {
                                    $query->whereIn('id', function ($subquery) {
                                        $subquery->select('user_id')
                                            ->from('karyawans')
                                            ->whereNull('deleted_at');
                                    });
                                })],
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
                    }

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

                    if ($jenisKaryawan != "admin") {
                        foreach ($arrayperawatanid as $idPerawatan) {
                            $karyawan->perawatans()->attach($idPerawatan);
                        }
                    }
                    return redirect()->route('karyawans.index')->with('status', 'Berhasil mengedit karyawan ' . $namaKaryawan . '!');
                } else {
                    //Ada pergantian email tapi tidak ada pergantian nomor telepon
                    if ($jenisKaryawan == "admin") {
                        $validatedData = $request->validate(
                            [
                                'namaKaryawan' => 'required|max:255',
                                'emailKaryawan' => ['required', 'email:rfc,dns', Rule::unique('users', 'email')->where('role', 'karyawan')->where(function ($query) {
                                    $query->whereIn('id', function ($subquery) {
                                        $subquery->select('user_id')
                                            ->from('karyawans')
                                            ->whereNull('deleted_at');
                                    });
                                })],
                                'gajiKaryawan' => 'required|numeric|min:1',
                            ],
                            [
                                'namaKaryawan.required' => 'Nama karyawan tidak boleh kosong!',
                                'emailKaryawan.required' => 'Email karyawan tidak boleh kosong!',
                                'emailKaryawan.email' => 'Mohon masukkan email dengan format yang benar!',
                                'emailKaryawan.unique' => 'Email karyawan ' . $request->get('emailKaryawan') . ' sudah pernah dipakai, mohon gunakan email yang lain!',
                                'gajiKaryawan.required' => 'Gaji Pokok Karyawan tidak boleh kosong!',
                                'gajiKaryawan.numeric' => 'Gaji Karyawan harus berupa angka!',
                                'gajiKaryawan.min' => 'Gaji karyawan harus lebih dari Rp. 0!',
                            ]
                        );

                    } else {
                        $validatedData = $request->validate(
                            [
                                'namaKaryawan' => 'required|max:255',
                                'emailKaryawan' => ['required', 'email:rfc,dns', Rule::unique('users', 'email')->where('role', 'karyawan')->where(function ($query) {
                                    $query->whereIn('id', function ($subquery) {
                                        $subquery->select('user_id')
                                            ->from('karyawans')
                                            ->whereNull('deleted_at');
                                    });
                                })],
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
                    }

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

                    if ($jenisKaryawan != "admin") {
                        foreach ($arrayperawatanid as $idPerawatan) {
                            $karyawan->perawatans()->attach($idPerawatan);
                        }
                    }

                    return redirect()->route('karyawans.index')->with('status', 'Berhasil mengedit karyawan ' . $namaKaryawan . '!');
                }
            }

            if ($request->get('nomor_telepon') != $karyawan->nomor_telepon) {
                //Menegecek apakah ada pergantian nomor telepon dan email
                if ($request->get('emailKaryawan') != $karyawan->user->email) {

                    if ($jenisKaryawan == "admin") {
                        $validatedData = $request->validate(
                            [
                                'namaKaryawan' => 'required|max:255',
                                'emailKaryawan' => ['required', 'email:rfc,dns', Rule::unique('users', 'email')->where('role', 'karyawan')->where(function ($query) {
                                    $query->whereIn('id', function ($subquery) {
                                        $subquery->select('user_id')
                                            ->from('karyawans')
                                            ->whereNull('deleted_at');
                                    });
                                })],
                                'gajiKaryawan' => 'required|numeric|min:1',
                                'nomor_telepon' => 'required|numeric|unique:karyawans',
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
                            ]
                        );
                    } else {
                        $validatedData = $request->validate(
                            [
                                'namaKaryawan' => 'required|max:255',
                                'emailKaryawan' => ['required', 'email:rfc,dns', Rule::unique('users', 'email')->where('role', 'karyawan')->where(function ($query) {
                                    $query->whereIn('id', function ($subquery) {
                                        $subquery->select('user_id')
                                            ->from('karyawans')
                                            ->whereNull('deleted_at');
                                    });
                                })],
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
                    }

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

                    if ($jenisKaryawan != "admin") {
                        foreach ($arrayperawatanid as $idPerawatan) {
                            $karyawan->perawatans()->attach($idPerawatan);
                        }
                    }

                    return redirect()->route('karyawans.index')->with('status', 'Berhasil mengedit karyawan ' . $namaKaryawan . '!');
                } else {
                    //Ada  pergantian nomor telepon tetapi tidak ada pergantian email
                    if ($jenisKaryawan == "admin") {
                        $validatedData = $request->validate(
                            [
                                'namaKaryawan' => 'required|max:255',
                                'gajiKaryawan' => 'required|numeric|min:1',
                                'nomor_telepon' => 'required|numeric|unique:karyawans',

                            ],
                            [
                                'nomor_telepon.required' => 'Nomor telepon karyawan tidak boleh kosong!',
                                'nomor_telepon.numeric' => 'Nomor telepon karyawan harus berupa angka!',
                                'nomor_telepon.unique' => 'Karyawan dengan nomor telepon ' . $request->get('nomor_telepon') . ' sudah pernah dipakai, mohon gunakan nomor telepon yang lain!',
                                'namaKaryawan.required' => 'Nama karyawan tidak boleh kosong!',
                                'gajiKaryawan.required' => 'Gaji Pokok Karyawan tidak boleh kosong!',
                                'gajiKaryawan.numeric' => 'Gaji Karyawan harus berupa angka!',
                                'gajiKaryawan.min' => 'Gaji karyawan harus lebih dari Rp. 0!',

                            ]
                        );
                    } else {
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
                    }

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

                    if ($jenisKaryawan != "admin") {
                        foreach ($arrayperawatanid as $idPerawatan) {
                            $karyawan->perawatans()->attach($idPerawatan);
                        }
                    }

                    return redirect()->route('karyawans.index')->with('status', 'Berhasil mengedit karyawan ' . $namaKaryawan . '!');
                }
            }
        } else {
            if ($jenisKaryawan == "admin") {
                $validatedData = $request->validate(
                    [
                        'namaKaryawan' => 'required|max:255',
                        'gajiKaryawan' => 'required|numeric|min:1',
                    ],
                    [
                        'namaKaryawan.required' => 'Nama karyawan tidak boleh kosong!',
                        'gajiKaryawan.required' => 'Gaji Pokok Karyawan tidak boleh kosong!',
                        'gajiKaryawan.numeric' => 'Gaji Karyawan harus berupa angka!',
                        'gajiKaryawan.min' => 'Gaji karyawan harus lebih dari Rp. 0!',
                    ]
                );
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
            }


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

            if ($jenisKaryawan != "admin") {
                foreach ($arrayperawatanid as $idPerawatan) {
                    $karyawan->perawatans()->attach($idPerawatan);
                }
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
    public function destroy($idKaryawan)
    {

        $karyawan = Karyawan::find($idKaryawan);
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

    public function indexKomisiKaryawan()
    {
        $karyawans = Karyawan::select("id", "nama")->get();
        $distinctTahun = Penjualan::selectRaw("year(tanggal_penjualan) as tahun")->distinct()->where('status_selesai', 'selesai')->orderByRaw("tahun desc")->get();
        $bulans = [
            ["id" => "01", "nama" => "Januari"],
            ["id" => "02", "nama" => "Februari"],
            ["id" => "03", "nama" => "Maret"],
            ["id" => "04", "nama" => "April"],
            ["id" => "05", "nama" => "Mei"],
            ["id" => "06", "nama" => "Juni"],
            ["id" => "07", "nama" => "Juli"],
            ["id" => "08", "nama" => "Agustus"],
            ["id" => "09", "nama" => "September"],
            ["id" => "10", "nama" => "Oktober"],
            ["id" => "11", "nama" => "November"],
            ["id" => "12", "nama" => "Desember"],
        ];

        //$idUnikPenjualan = Penjualan::select("id")->distinct()->where("status_selesai", "selesai")->get();

        $komisiPerKaryawanKeseluruhan = [];
        foreach ($karyawans as $karyawan) {
            $karyawanKomisi = [];
            $karyawanKomisi["karyawan"] = $karyawan;
            $daftarPenjualan = PenjualanPerawatan::where("penjualan_perawatan.karyawan_id", $karyawan->id)->where("penjualans.status_selesai", "selesai")->join("penjualans", "penjualans.id", "=", "penjualan_perawatan.penjualan_id")->get();
            $penjualanIdUnik = $daftarPenjualan->pluck('penjualan_id')->unique();
            $karyawanKomisi["penjualan"] = $daftarPenjualan;
            $karyawanKomisi["tanggal_awal"] = $penjualan = Penjualan::whereIn("id", $penjualanIdUnik)->where('status_selesai', 'selesai')->min("tanggal_penjualan");
            $karyawanKomisi["tanggal_akhir"] = $penjualan = Penjualan::whereIn("id", $penjualanIdUnik)->where('status_selesai', 'selesai')->max("tanggal_penjualan");
            $karyawanKomisi["jumlah_pelayanan"] = $daftarPenjualan->count();
            $karyawanKomisi["total_komisi"] = $daftarPenjualan->sum(function ($penjualan) {
                return ($penjualan->harga * $penjualan->perawatan->komisi) / 100;
            });
            array_push($komisiPerKaryawanKeseluruhan, $karyawanKomisi);
        }

        return view("admin.karyawan.komisikaryawan.index", compact("distinctTahun", "bulans", "komisiPerKaryawanKeseluruhan"));
    }

    public function prosesKomisiKaryawan()
    {
        $tahunPenjualan = $_POST["tahunPenjualan"];
        $bulanPenjualan = $_POST["bulanPenjualan"];
        //dd($tahunPenjualan, $bulanPenjualan);
        $karyawans = Karyawan::select("id", "nama")->get();

        $distinctTahun = Penjualan::selectRaw("year(tanggal_penjualan) as tahun")->distinct()->orderByRaw("tahun desc")->get();
        $bulans = [
            ["id" => "01", "nama" => "Januari"],
            ["id" => "02", "nama" => "Februari"],
            ["id" => "03", "nama" => "Maret"],
            ["id" => "04", "nama" => "April"],
            ["id" => "05", "nama" => "Mei"],
            ["id" => "06", "nama" => "Juni"],
            ["id" => "07", "nama" => "Juli"],
            ["id" => "08", "nama" => "Agustus"],
            ["id" => "09", "nama" => "September"],
            ["id" => "10", "nama" => "Oktober"],
            ["id" => "11", "nama" => "November"],
            ["id" => "12", "nama" => "Desember"],
        ];
        $komisiPerKaryawan = [];
        if ($tahunPenjualan == "semuaTahun" || $bulanPenjualan == "semuaBulan") {
            if ($tahunPenjualan == "semuaTahun" && $bulanPenjualan != "semuaBulan") {
                $idUnikPenjualan = Penjualan::select("id")->distinct()->whereRaw("MONTH(tanggal_penjualan) = '" . $bulanPenjualan . "'")->where("status_selesai", "selesai")->get();

                foreach ($karyawans as $karyawan) {
                    $karyawanKomisi = [];
                    $karyawanKomisi["karyawan"] = $karyawan;
                    $daftarPenjualan = PenjualanPerawatan::where("karyawan_id", $karyawan->id)->whereIn("penjualan_id", $idUnikPenjualan)->get();
                    $karyawanKomisi["penjualan"] = $daftarPenjualan;
                    $karyawanKomisi["tanggal_awal"] = $penjualan = Penjualan::whereIn("penjualans.id", $idUnikPenjualan)->where("penjualan_perawatan.karyawan_id", $karyawan->id)->where("penjualans.status_selesai", "selesai")->join("penjualan_perawatan", "penjualan_perawatan.penjualan_id", "=", "penjualans.id")->min("penjualans.tanggal_penjualan");
                    $karyawanKomisi["tanggal_akhir"] = $penjualan = Penjualan::whereIn("penjualans.id", $idUnikPenjualan)->where("penjualan_perawatan.karyawan_id", $karyawan->id)->where("penjualans.status_selesai", "selesai")->join("penjualan_perawatan", "penjualan_perawatan.penjualan_id", "=", "penjualans.id")->max("penjualans.tanggal_penjualan");
                    $karyawanKomisi["jumlah_pelayanan"] = $daftarPenjualan->count();
                    $karyawanKomisi["total_komisi"] = $daftarPenjualan->sum(function ($penjualan) {
                        return ($penjualan->harga * $penjualan->perawatan->komisi) / 100;
                    });
                    array_push($komisiPerKaryawan, $karyawanKomisi);
                }
            } else if ($tahunPenjualan != "semuaTahun" && $bulanPenjualan == "semuaBulan") {
                $idUnikPenjualan = Penjualan::select("id")->distinct()->whereRaw("YEAR(tanggal_penjualan) = '" . $tahunPenjualan . "'")->where("status_selesai", "selesai")->get();

                foreach ($karyawans as $karyawan) {
                    $karyawanKomisi = [];
                    $karyawanKomisi["karyawan"] = $karyawan;
                    $daftarPenjualan = PenjualanPerawatan::where("karyawan_id", $karyawan->id)->whereIn("penjualan_id", $idUnikPenjualan)->get();
                    $karyawanKomisi["penjualan"] = $daftarPenjualan;
                    $karyawanKomisi["tanggal_awal"] = $penjualan = Penjualan::whereIn("penjualans.id", $idUnikPenjualan)->where("penjualan_perawatan.karyawan_id", $karyawan->id)->where("penjualans.status_selesai", "selesai")->join("penjualan_perawatan", "penjualan_perawatan.penjualan_id", "=", "penjualans.id")->min("penjualans.tanggal_penjualan");
                    $karyawanKomisi["tanggal_akhir"] = $penjualan = Penjualan::whereIn("penjualans.id", $idUnikPenjualan)->where("penjualan_perawatan.karyawan_id", $karyawan->id)->where("penjualans.status_selesai", "selesai")->join("penjualan_perawatan", "penjualan_perawatan.penjualan_id", "=", "penjualans.id")->max("penjualans.tanggal_penjualan");
                    $karyawanKomisi["jumlah_pelayanan"] = $daftarPenjualan->count();
                    $karyawanKomisi["total_komisi"] = $daftarPenjualan->sum(function ($penjualan) {
                        return ($penjualan->harga * $penjualan->perawatan->komisi) / 100;
                    });
                    array_push($komisiPerKaryawan, $karyawanKomisi);
                }
            } else {
                foreach ($karyawans as $karyawan) {
                    $karyawanKomisi = [];
                    $karyawanKomisi["karyawan"] = $karyawan;
                    $daftarPenjualan = PenjualanPerawatan::where("penjualan_perawatan.karyawan_id", $karyawan->id)->where("penjualans.status_selesai", "selesai")->join("penjualans", "penjualans.id", "=", "penjualan_perawatan.penjualan_id")->get();
                    $penjualanIdUnik = $daftarPenjualan->pluck('penjualan_id')->unique();
                    $karyawanKomisi["penjualan"] = $daftarPenjualan;
                    $karyawanKomisi["tanggal_awal"] = $penjualan = Penjualan::whereIn("id", $penjualanIdUnik)->where('status_selesai', 'selesai')->min("tanggal_penjualan");
                    $karyawanKomisi["tanggal_akhir"] = $penjualan = Penjualan::whereIn("id", $penjualanIdUnik)->where('status_selesai', 'selesai')->max("tanggal_penjualan");
                    $karyawanKomisi["jumlah_pelayanan"] = $daftarPenjualan->count();
                    $karyawanKomisi["total_komisi"] = $daftarPenjualan->sum(function ($penjualan) {
                        return ($penjualan->harga * $penjualan->perawatan->komisi) / 100;
                    });
                    array_push($komisiPerKaryawan, $karyawanKomisi);
                }
            }
        } else {
            $idUnikPenjualan = Penjualan::select("id")->distinct()->whereRaw("YEAR(tanggal_penjualan) = '" . $tahunPenjualan . "' and MONTH(tanggal_penjualan) = '" . $bulanPenjualan . "'")->where("status_selesai", "selesai")->get();

            foreach ($karyawans as $karyawan) {
                $karyawanKomisi = [];
                $karyawanKomisi["karyawan"] = $karyawan;
                $daftarPenjualan = PenjualanPerawatan::where("karyawan_id", $karyawan->id)->whereIn("penjualan_id", $idUnikPenjualan)->get();
                $karyawanKomisi["penjualan"] = $daftarPenjualan;
                $karyawanKomisi["tanggal_awal"] = $penjualan = Penjualan::whereIn("penjualans.id", $idUnikPenjualan)->where("penjualan_perawatan.karyawan_id", $karyawan->id)->where("penjualans.status_selesai", "selesai")->join("penjualan_perawatan", "penjualan_perawatan.penjualan_id", "=", "penjualans.id")->min("penjualans.tanggal_penjualan");
                $karyawanKomisi["tanggal_akhir"] = $penjualan = Penjualan::whereIn("penjualans.id", $idUnikPenjualan)->where("penjualan_perawatan.karyawan_id", $karyawan->id)->where("penjualans.status_selesai", "selesai")->join("penjualan_perawatan", "penjualan_perawatan.penjualan_id", "=", "penjualans.id")->max("penjualans.tanggal_penjualan");
                $karyawanKomisi["jumlah_pelayanan"] = $daftarPenjualan->count();
                $karyawanKomisi["total_komisi"] = $daftarPenjualan->sum(function ($penjualan) {
                    return ($penjualan->harga * $penjualan->perawatan->komisi) / 100;
                });
                array_push($komisiPerKaryawan, $karyawanKomisi);
            }
        }

        return response()->json(array('msg' => view('admin.karyawan.komisikaryawan.tabledaftarkomisikaryawan', compact('komisiPerKaryawan'))->render()), 200);
    }

    public function getDetailKomisiKaryawan()
    {
        $tahunPenjualan = $_POST["tahunPenjualan"];
        $bulanPenjualan = $_POST["bulanPenjualan"];
        $idKaryawan = $_POST["idKaryawan"];

        $daftarPenjualan = [];
        if ($tahunPenjualan == "semuaTahun" || $bulanPenjualan == "semuaBulan") {
            if ($tahunPenjualan == "semuaTahun" && $bulanPenjualan != "semuaBulan") {
                $idUnikPenjualan = Penjualan::select("id")->distinct()->whereRaw("MONTH(tanggal_penjualan) = '" . $bulanPenjualan . "'")->where("status_selesai", "selesai")->get();
                $daftarPenjualan = PenjualanPerawatan::where("karyawan_id", $idKaryawan)->whereIn("penjualan_id", $idUnikPenjualan)->get();

            } else if ($tahunPenjualan != "semuaTahun" && $bulanPenjualan == "semuaBulan") {
                $idUnikPenjualan = Penjualan::select("id")->distinct()->whereRaw("YEAR(tanggal_penjualan) = '" . $tahunPenjualan . "'")->where("status_selesai", "selesai")->get();
                $daftarPenjualan = PenjualanPerawatan::where("karyawan_id", $idKaryawan)->whereIn("penjualan_id", $idUnikPenjualan)->get();

            } else {
                $daftarPenjualan = PenjualanPerawatan::where("penjualan_perawatan.karyawan_id", $idKaryawan)->where("penjualans.status_selesai", "selesai")->join("penjualans", "penjualans.id", "=", "penjualan_perawatan.penjualan_id")->get();
            }
        } else {
            $idUnikPenjualan = Penjualan::select("id")->distinct()->whereRaw("YEAR(tanggal_penjualan) = '" . $tahunPenjualan . "' and MONTH(tanggal_penjualan) = '" . $bulanPenjualan . "'")->where("status_selesai", "selesai")->get();
            $daftarPenjualan = PenjualanPerawatan::where("karyawan_id", $idKaryawan)->whereIn("penjualan_id", $idUnikPenjualan)->get();
        }

        return response()->json(array('msg' => view('admin.karyawan.komisikaryawan.detailkomisikaryawan', compact('daftarPenjualan'))->render()), 200);

    }

    public function indexKomisiKaryawanSalon()
    {
        $karyawan = Auth::user()->karyawan;
        $distinctTahun = Penjualan::selectRaw("year(tanggal_penjualan) as tahun")->distinct()->where('status_selesai', 'selesai')->orderByRaw("tahun desc")->get();
        $bulans = [
            ["id" => "01", "nama" => "Januari"],
            ["id" => "02", "nama" => "Februari"],
            ["id" => "03", "nama" => "Maret"],
            ["id" => "04", "nama" => "April"],
            ["id" => "05", "nama" => "Mei"],
            ["id" => "06", "nama" => "Juni"],
            ["id" => "07", "nama" => "Juli"],
            ["id" => "08", "nama" => "Agustus"],
            ["id" => "09", "nama" => "September"],
            ["id" => "10", "nama" => "Oktober"],
            ["id" => "11", "nama" => "November"],
            ["id" => "12", "nama" => "Desember"],
        ];


        $komisiKaryawan = [];
        $komisiKaryawan["karyawan"] = $karyawan;
        $daftarPenjualan = PenjualanPerawatan::where("penjualan_perawatan.karyawan_id", $karyawan->id)->where("penjualans.status_selesai", "selesai")->join("penjualans", "penjualans.id", "=", "penjualan_perawatan.penjualan_id")->get();
        $penjualanIdUnik = $daftarPenjualan->pluck('penjualan_id')->unique();
        $komisiKaryawan["penjualan"] = $daftarPenjualan;
        $komisiKaryawan["tanggal_awal"] = $penjualan = Penjualan::whereIn("id", $penjualanIdUnik)->where('status_selesai', 'selesai')->min("tanggal_penjualan");
        $komisiKaryawan["tanggal_akhir"] = $penjualan = Penjualan::whereIn("id", $penjualanIdUnik)->where('status_selesai', 'selesai')->max("tanggal_penjualan");
        $komisiKaryawan["jumlah_pelayanan"] = $daftarPenjualan->count();
        $komisiKaryawan["total_komisi"] = $daftarPenjualan->sum(function ($penjualan) {
            return ($penjualan->harga * $penjualan->perawatan->komisi) / 100;
        });

        return view("karyawansalon.komisikaryawan.komisikaryawansalon", compact("distinctTahun", "bulans", "komisiKaryawan"));
    }

    public function prosesKomisiKaryawanSalon()
    {
        $tahunPenjualan = $_POST["tahunPenjualan"];
        $bulanPenjualan = $_POST["bulanPenjualan"];
        //dd($tahunPenjualan, $bulanPenjualan);
        $karyawan = Auth::user()->karyawan;

        $distinctTahun = Penjualan::selectRaw("year(tanggal_penjualan) as tahun")->distinct()->orderByRaw("tahun desc")->get();
        $bulans = [
            ["id" => "01", "nama" => "Januari"],
            ["id" => "02", "nama" => "Februari"],
            ["id" => "03", "nama" => "Maret"],
            ["id" => "04", "nama" => "April"],
            ["id" => "05", "nama" => "Mei"],
            ["id" => "06", "nama" => "Juni"],
            ["id" => "07", "nama" => "Juli"],
            ["id" => "08", "nama" => "Agustus"],
            ["id" => "09", "nama" => "September"],
            ["id" => "10", "nama" => "Oktober"],
            ["id" => "11", "nama" => "November"],
            ["id" => "12", "nama" => "Desember"],
        ];

        if ($tahunPenjualan == "semuaTahun" || $bulanPenjualan == "semuaBulan") {
            if ($tahunPenjualan == "semuaTahun" && $bulanPenjualan != "semuaBulan") {
                $idUnikPenjualan = Penjualan::select("id")->distinct()->whereRaw("MONTH(tanggal_penjualan) = '" . $bulanPenjualan . "'")->where("status_selesai", "selesai")->get();

                $komisiKaryawan = [];
                $komisiKaryawan["karyawan"] = $karyawan;
                $daftarPenjualan = PenjualanPerawatan::where("karyawan_id", $karyawan->id)->whereIn("penjualan_id", $idUnikPenjualan)->get();
                $komisiKaryawan["penjualan"] = $daftarPenjualan;
                $komisiKaryawan["tanggal_awal"] = $penjualan = Penjualan::whereIn("penjualans.id", $idUnikPenjualan)->where("penjualan_perawatan.karyawan_id", $karyawan->id)->where("penjualans.status_selesai", "selesai")->join("penjualan_perawatan", "penjualan_perawatan.penjualan_id", "=", "penjualans.id")->min("penjualans.tanggal_penjualan");
                $komisiKaryawan["tanggal_akhir"] = $penjualan = Penjualan::whereIn("penjualans.id", $idUnikPenjualan)->where("penjualan_perawatan.karyawan_id", $karyawan->id)->where("penjualans.status_selesai", "selesai")->join("penjualan_perawatan", "penjualan_perawatan.penjualan_id", "=", "penjualans.id")->max("penjualans.tanggal_penjualan");
                $komisiKaryawan["jumlah_pelayanan"] = $daftarPenjualan->count();
                $komisiKaryawan["total_komisi"] = $daftarPenjualan->sum(function ($penjualan) {
                    return ($penjualan->harga * $penjualan->perawatan->komisi) / 100;
                });

            } else if ($tahunPenjualan != "semuaTahun" && $bulanPenjualan == "semuaBulan") {
                $idUnikPenjualan = Penjualan::select("id")->distinct()->whereRaw("YEAR(tanggal_penjualan) = '" . $tahunPenjualan . "'")->where("status_selesai", "selesai")->get();

                $komisiKaryawan = [];
                $komisiKaryawan["karyawan"] = $karyawan;
                $daftarPenjualan = PenjualanPerawatan::where("karyawan_id", $karyawan->id)->whereIn("penjualan_id", $idUnikPenjualan)->get();
                $komisiKaryawan["penjualan"] = $daftarPenjualan;
                $komisiKaryawan["tanggal_awal"] = $penjualan = Penjualan::whereIn("penjualans.id", $idUnikPenjualan)->where("penjualan_perawatan.karyawan_id", $karyawan->id)->where("penjualans.status_selesai", "selesai")->join("penjualan_perawatan", "penjualan_perawatan.penjualan_id", "=", "penjualans.id")->min("penjualans.tanggal_penjualan");
                $komisiKaryawan["tanggal_akhir"] = $penjualan = Penjualan::whereIn("penjualans.id", $idUnikPenjualan)->where("penjualan_perawatan.karyawan_id", $karyawan->id)->where("penjualans.status_selesai", "selesai")->join("penjualan_perawatan", "penjualan_perawatan.penjualan_id", "=", "penjualans.id")->max("penjualans.tanggal_penjualan");
                $komisiKaryawan["jumlah_pelayanan"] = $daftarPenjualan->count();
                $komisiKaryawan["total_komisi"] = $daftarPenjualan->sum(function ($penjualan) {
                    return ($penjualan->harga * $penjualan->perawatan->komisi) / 100;
                });
            } else {
                $komisiKaryawan = [];
                $komisiKaryawan["karyawan"] = $karyawan;
                $daftarPenjualan = PenjualanPerawatan::where("penjualan_perawatan.karyawan_id", $karyawan->id)->where("penjualans.status_selesai", "selesai")->join("penjualans", "penjualans.id", "=", "penjualan_perawatan.penjualan_id")->get();
                $penjualanIdUnik = $daftarPenjualan->pluck('penjualan_id')->unique();
                $komisiKaryawan["penjualan"] = $daftarPenjualan;
                $komisiKaryawan["tanggal_awal"] = $penjualan = Penjualan::whereIn("id", $penjualanIdUnik)->where('status_selesai', 'selesai')->min("tanggal_penjualan");
                $komisiKaryawan["tanggal_akhir"] = $penjualan = Penjualan::whereIn("id", $penjualanIdUnik)->where('status_selesai', 'selesai')->max("tanggal_penjualan");
                $komisiKaryawan["jumlah_pelayanan"] = $daftarPenjualan->count();
                $komisiKaryawan["total_komisi"] = $daftarPenjualan->sum(function ($penjualan) {
                    return ($penjualan->harga * $penjualan->perawatan->komisi) / 100;
                });
            }
        } else {
            $idUnikPenjualan = Penjualan::select("id")->distinct()->whereRaw("YEAR(tanggal_penjualan) = '" . $tahunPenjualan . "' and MONTH(tanggal_penjualan) = '" . $bulanPenjualan . "'")->where("status_selesai", "selesai")->get();

            $komisiKaryawan = [];
            $komisiKaryawan["karyawan"] = $karyawan;
            $daftarPenjualan = PenjualanPerawatan::where("karyawan_id", $karyawan->id)->whereIn("penjualan_id", $idUnikPenjualan)->get();
            $komisiKaryawan["penjualan"] = $daftarPenjualan;
            $komisiKaryawan["tanggal_awal"] = $penjualan = Penjualan::whereIn("penjualans.id", $idUnikPenjualan)->where("penjualan_perawatan.karyawan_id", $karyawan->id)->where("penjualans.status_selesai", "selesai")->join("penjualan_perawatan", "penjualan_perawatan.penjualan_id", "=", "penjualans.id")->min("penjualans.tanggal_penjualan");
            $komisiKaryawan["tanggal_akhir"] = $penjualan = Penjualan::whereIn("penjualans.id", $idUnikPenjualan)->where("penjualan_perawatan.karyawan_id", $karyawan->id)->where("penjualans.status_selesai", "selesai")->join("penjualan_perawatan", "penjualan_perawatan.penjualan_id", "=", "penjualans.id")->max("penjualans.tanggal_penjualan");
            $komisiKaryawan["jumlah_pelayanan"] = $daftarPenjualan->count();
            $komisiKaryawan["total_komisi"] = $daftarPenjualan->sum(function ($penjualan) {
                return ($penjualan->harga * $penjualan->perawatan->komisi) / 100;
            });
        }

        return response()->json(array('msg' => view('karyawansalon.komisikaryawan.tablekomisikaryawansalon', compact('komisiKaryawan'))->render()), 200);
    }

    public function getDetailKomisiKaryawanSalon()
    {
        $tahunPenjualan = $_POST["tahunPenjualan"];
        $bulanPenjualan = $_POST["bulanPenjualan"];
        $karyawan = Auth::user()->karyawan;

        $daftarPenjualan = [];
        if ($tahunPenjualan == "semuaTahun" || $bulanPenjualan == "semuaBulan") {
            if ($tahunPenjualan == "semuaTahun" && $bulanPenjualan != "semuaBulan") {
                $idUnikPenjualan = Penjualan::select("id")->distinct()->whereRaw("MONTH(tanggal_penjualan) = '" . $bulanPenjualan . "'")->where("status_selesai", "selesai")->get();
                $daftarPenjualan = PenjualanPerawatan::where("karyawan_id", $karyawan->id)->whereIn("penjualan_id", $idUnikPenjualan)->get();

            } else if ($tahunPenjualan != "semuaTahun" && $bulanPenjualan == "semuaBulan") {
                $idUnikPenjualan = Penjualan::select("id")->distinct()->whereRaw("YEAR(tanggal_penjualan) = '" . $tahunPenjualan . "'")->where("status_selesai", "selesai")->get();
                $daftarPenjualan = PenjualanPerawatan::where("karyawan_id", $karyawan->id)->whereIn("penjualan_id", $idUnikPenjualan)->get();

            } else {
                $daftarPenjualan = PenjualanPerawatan::where("penjualan_perawatan.karyawan_id", $karyawan->id)->where("penjualans.status_selesai", "selesai")->join("penjualans", "penjualans.id", "=", "penjualan_perawatan.penjualan_id")->get();
            }
        } else {
            $idUnikPenjualan = Penjualan::select("id")->distinct()->whereRaw("YEAR(tanggal_penjualan) = '" . $tahunPenjualan . "' and MONTH(tanggal_penjualan) = '" . $bulanPenjualan . "'")->where("status_selesai", "selesai")->get();
            $daftarPenjualan = PenjualanPerawatan::where("karyawan_id", $karyawan->id)->whereIn("penjualan_id", $idUnikPenjualan)->get();
        }

        return response()->json(array('msg' => view('karyawansalon.komisikaryawan.detailkomisikaryawansalon', compact('daftarPenjualan'))->render()), 200);

    }

}
