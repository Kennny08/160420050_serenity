<?php

namespace App\Http\Controllers;

use App\Models\Pembelian;
use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $suppliers = Supplier::all();
        return view("admin.supplier.index", compact("suppliers"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("admin.supplier.tambahsupplier");
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
                'namaSupplier' => 'required|max:255',
                'nomor_telepon' => 'required|numeric|digits_between:12,13|unique:suppliers',
                'alamatSupplier' => 'required|max:255',
                'email' => 'required|email:rfc,dns|unique:suppliers',
            ],
            [
                'namaSupplier.required' => 'Nama supplier tidak boleh kosong!',
                'nomor_telepon.required' => 'Nomor telepon supplier tidak boleh kosong!',
                'nomor_telepon.numeric' => 'Mohon masukkan email dengan format yang benar!',
                'nomor_telepon.digits_between' => 'Nomor telepon supplier minimal 12 atau 13 digit!',
                'nomor_telepon.unique' => 'Nomor telepon supplier ' . $request->get('nomor_telepon') . ' sudah pernah dipakai, mohon gunakan nomor telepon yang lain!',
                'email.unique' => 'Email supplier ' . $request->get('email') . ' sudah pernah dipakai, mohon gunakan email yang lain!',
                'email.required' => 'Email supplier tidak boleh kosong!',
                'email.email' => 'Mohon masukkan format email yang benar untuk email supplier!',
                'alamatSupplier.required' => 'Alamat supplier tidak boleh kosong!',

            ]
        );

        $namaSupplier = $request->get('namaSupplier');
        $emailSupplier = $request->get('email');
        $nomorTeleponSupplier = $request->get('nomor_telepon');
        $alamatSupplier = $request->get('alamatSupplier');

        $newSupplier = new Supplier();
        $newSupplier->nama = $namaSupplier;
        $newSupplier->email = $emailSupplier;
        $newSupplier->nomor_telepon = $nomorTeleponSupplier;
        $newSupplier->alamat = $alamatSupplier;
        $newSupplier->created_at = date('Y-m-d H:i:s');
        $newSupplier->updated_at = date('Y-m-d H:i:s');
        $newSupplier->save();

        return redirect()->route('suppliers.index')->with('status', 'Berhasil menambah supplier ' . $namaSupplier . '!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function show(Supplier $supplier)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function edit(Supplier $supplier)
    {
        return view("admin.supplier.editsupplier", compact("supplier"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Supplier $supplier)
    {
        date_default_timezone_set("Asia/Jakarta");

        $namaSupplier = $request->get('namaSupplier');
        $emailSupplier = $request->get('email');
        $nomorTeleponSupplier = $request->get('nomor_telepon');
        $alamatSupplier = $request->get('alamatSupplier');

        if ($emailSupplier != $supplier->email || $nomorTeleponSupplier != $supplier->nomor_telepon) {
            if ($emailSupplier != $supplier->email) {
                if ($nomorTeleponSupplier != $supplier->nomor_telepon) {
                    $validatedData = $request->validate(
                        [
                            'namaSupplier' => 'required|max:255',
                            'nomor_telepon' => 'required|numeric|digits_between:12,13|unique:suppliers',
                            'alamatSupplier' => 'required|max:255',
                            'email' => 'required|email:rfc,dns|unique:suppliers',
                        ],
                        [
                            'namaSupplier.required' => 'Nama supplier tidak boleh kosong!',
                            'nomor_telepon.required' => 'Nomor telepon supplier tidak boleh kosong!',
                            'nomor_telepon.numeric' => 'Mohon masukkan email dengan format yang benar!',
                            'nomor_telepon.digits_between' => 'Nomor telepon supplier minimal 12 atau 13 digit!',
                            'nomor_telepon.unique' => 'Nomor telepon supplier ' . $request->get('nomor_telepon') . ' sudah pernah dipakai, mohon gunakan nomor telepon yang lain!',
                            'email.unique' => 'Email supplier ' . $request->get('email') . ' sudah pernah dipakai, mohon gunakan email yang lain!',
                            'email.required' => 'Email supplier tidak boleh kosong!',
                            'email.email' => 'Mohon masukkan format email yang benar untuk email supplier!',
                            'alamatSupplier.required' => 'Alamat supplier tidak boleh kosong!',

                        ]
                    );
                    $supplier->nama = $namaSupplier;
                    $supplier->nomor_telepon = $nomorTeleponSupplier;
                    $supplier->alamat = $alamatSupplier;
                    $supplier->email = $emailSupplier;
                    $supplier->updated_at = date('Y-m-d H:i:s');
                    $supplier->save();

                    return redirect()->route('suppliers.index')->with('status', 'Berhasil mengedit supplier ' . $namaSupplier . '!');
                } else {
                    $validatedData = $request->validate(
                        [
                            'namaSupplier' => 'required|max:255',
                            'alamatSupplier' => 'required|max:255',
                            'email' => 'required|email:rfc,dns|unique:suppliers',
                        ],
                        [
                            'namaSupplier.required' => 'Nama supplier tidak boleh kosong!',
                            'email.unique' => 'Email supplier ' . $request->get('email') . ' sudah pernah dipakai, mohon gunakan email yang lain!',
                            'email.required' => 'Email supplier tidak boleh kosong!',
                            'email.email' => 'Mohon masukkan format email yang benar untuk email supplier!',
                            'alamatSupplier.required' => 'Alamat supplier tidak boleh kosong!',

                        ]
                    );

                    $supplier->nama = $namaSupplier;
                    $supplier->alamat = $alamatSupplier;
                    $supplier->email = $emailSupplier;
                    $supplier->updated_at = date('Y-m-d H:i:s');
                    $supplier->save();

                    return redirect()->route('suppliers.index')->with('status', 'Berhasil mengedit supplier ' . $namaSupplier . '!');
                }
            }

            if ($nomorTeleponSupplier != $supplier->nomor_telepon) {
                if ($emailSupplier != $supplier->email) {
                    $validatedData = $request->validate(
                        [
                            'namaSupplier' => 'required|max:255',
                            'nomor_telepon' => 'required|numeric|digits_between:12,13|unique:suppliers',
                            'alamatSupplier' => 'required|max:255',
                            'email' => 'required|email:rfc,dns|unique:suppliers',
                        ],
                        [
                            'namaSupplier.required' => 'Nama supplier tidak boleh kosong!',
                            'nomor_telepon.required' => 'Nomor telepon supplier tidak boleh kosong!',
                            'nomor_telepon.numeric' => 'Mohon masukkan email dengan format yang benar!',
                            'nomor_telepon.digits_between' => 'Nomor telepon supplier minimal 12 atau 13 digit!',
                            'nomor_telepon.unique' => 'Nomor telepon supplier ' . $request->get('nomor_telepon') . ' sudah pernah dipakai, mohon gunakan nomor telepon yang lain!',
                            'email.unique' => 'Email supplier ' . $request->get('email') . ' sudah pernah dipakai, mohon gunakan email yang lain!',
                            'email.required' => 'Email supplier tidak boleh kosong!',
                            'email.email' => 'Mohon masukkan format email yang benar untuk email supplier!',
                            'alamatSupplier.required' => 'Alamat supplier tidak boleh kosong!',

                        ]
                    );

                    $supplier->nama = $namaSupplier;
                    $supplier->nomor_telepon = $nomorTeleponSupplier;
                    $supplier->alamat = $alamatSupplier;
                    $supplier->email = $emailSupplier;
                    $supplier->updated_at = date('Y-m-d H:i:s');
                    $supplier->save();

                    return redirect()->route('suppliers.index')->with('status', 'Berhasil mengedit supplier ' . $namaSupplier . '!');
                } else {
                    $validatedData = $request->validate(
                        [
                            'namaSupplier' => 'required|max:255',
                            'nomor_telepon' => 'required|numeric|digits_between:12,13|unique:suppliers',
                            'alamatSupplier' => 'required|max:255',
                        ],
                        [
                            'namaSupplier.required' => 'Nama supplier tidak boleh kosong!',
                            'nomor_telepon.required' => 'Nomor telepon supplier tidak boleh kosong!',
                            'nomor_telepon.numeric' => 'Mohon masukkan email dengan format yang benar!',
                            'nomor_telepon.digits_between' => 'Nomor telepon supplier minimal 12 atau 13 digit!',
                            'nomor_telepon.unique' => 'Nomor telepon supplier ' . $request->get('nomor_telepon') . ' sudah pernah dipakai, mohon gunakan nomor telepon yang lain!',
                            'alamatSupplier.required' => 'Alamat supplier tidak boleh kosong!',

                        ]
                    );
                    $supplier->nama = $namaSupplier;
                    $supplier->nomor_telepon = $nomorTeleponSupplier;
                    $supplier->alamat = $alamatSupplier;
                    $supplier->updated_at = date('Y-m-d H:i:s');
                    $supplier->save();

                    return redirect()->route('suppliers.index')->with('status', 'Berhasil mengedit supplier ' . $namaSupplier . '!');
                }
            }
        } else {
            $validatedData = $request->validate(
                [
                    'namaSupplier' => 'required|max:255',
                    'alamatSupplier' => 'required|max:255',

                ],
                [
                    'namaSupplier.required' => 'Nama supplier tidak boleh kosong!',
                    'alamatSupplier.required' => 'Alamat supplier tidak boleh kosong!',

                ]
            );

            $supplier->nama = $namaSupplier;
            $supplier->alamat = $alamatSupplier;
            $supplier->updated_at = date('Y-m-d H:i:s');
            $supplier->save();

            return redirect()->route('suppliers.index')->with('status', 'Berhasil mengedit supplier ' . $namaSupplier . '!');


        }


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function destroy(Supplier $supplier)
    {
        $objSupplier = $supplier;
        try {
            $objSupplier->delete();
            return redirect()->route('suppliers.index')->with('status', 'Supplier ' . $objSupplier->nama . ' telah berhasil dihapus');
        } catch (\PDOException $ex) {
            $msg = "Data Gagal dihapus. Pastikan kembali tidak ada data yang berelasi sebelum dihapus";
            return redirect()->route('suppliers.index')->with('status', $msg);
        }
    }

    public function getDetailPembelianSupplier()
    {
        $idSupplier = $_POST["idSupplier"];
        $supplier = Supplier::find($idSupplier);
        $pembelianDariSupplier = Pembelian::where("supplier_id", $idSupplier)->get();
        return response()->json(array('msg' => view('admin.supplier.detailpembeliansupplier', compact('pembelianDariSupplier', 'supplier'))->render()), 200);
    }
}
