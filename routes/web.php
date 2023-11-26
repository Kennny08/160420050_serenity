<?php

use App\Http\Controllers\AprioriController;
use App\Http\Controllers\DiskonController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\KondisiController;
use App\Http\Controllers\MerekController;
use App\Http\Controllers\PaketController;
use App\Http\Controllers\PembelianController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\PerawatanController;
use App\Http\Controllers\PresensiKehadiranController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\ReservasiController;
use App\Http\Controllers\RiwayatPengambilanProdukController;
use App\Http\Controllers\SlotJamController;
use App\Http\Controllers\SupplierController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

Route::get('/', function () {
    if (!Auth::check()) {
        return redirect()->route('login');
    } else {
        if (Auth::user()->role === 'admin' || Auth::user()->role === 'karyawan') {

            if (Auth::user()->role === 'admin' || Auth::user()->karyawan->jenis_karyawan === 'admin') {
                return redirect()->route('reservasis.index');
            } else {
                //Route untuk ke halaman reservasi untuk karyawan salon tersebut saja
                // return redirect()->route('reservasis.index');
            }

        }
        // else if (Auth::user()->role === 'pelanggan') {
        //     return redirect()->route('pelanggan.reservasis.index');
        // }
    }
});

//ADMIN
Route::middleware(['auth', 'salon'])->group(function () {

    //Penjualan
    Route::get('/serenity/penjualan/errorpage', [PenjualanController::class, "errorPageNullPenjualan"])->name("penjualan.null.errorpage");

    //Produk
    Route::get('/salon/produk/index', [ProdukController::class, "index"])->name("produks.index");

    //Kategori
    Route::get('/salon/kategori/index', [KategoriController::class, "index"])->name("kategoris.index");
    Route::post('/salon/kategori/getdaftarprodukkategori', [KategoriController::class, "getDaftarProdukKategori"])->name("admin.kategoris.getdaftarprodukkategori");

    //Merek
    Route::get('/salon/merek/index', [MerekController::class, "index"])->name("mereks.index");
    Route::post('/salon/merek/getdaftarprodukmerek', [MerekController::class, "getDaftarProdukMerek"])->name("admin.mereks.getdaftarprodukmerek");

    //Kondisi
    Route::get('/salon/kondisi/index', [KondisiController::class, "index"])->name("kondisis.index");
    Route::post('/salon/kondisi/getdaftarprodukkondisi', [KondisiController::class, "getDaftarProdukKondisi"])->name("admin.kondisis.getdaftarprodukkondisi");

    //Perawatan
    Route::get('/salon/perawatan/index', [PerawatanController::class, "index"])->name("perawatans.index");
    Route::post('/salon/perawatan/getDetailPerawatan', [PerawatanController::class, "getDetailPerawatanList"])->name("admin.getdetailperawatan");


    //Paket
    Route::get('/salon/paket/index', [PaketController::class, "index"])->name("pakets.index");
    Route::post('/salon/diskon/getdetailpaket', [PaketController::class, 'getDetailPaket'])->name('admin.pakets.getdetailpaket');


    //Diskon
    Route::get('/salon/diskon/index', [DiskonController::class, "index"])->name("diskons.index");
    Route::get('/salon/diskon/daftardiskonberlaku', [DiskonController::class, "daftarDiskonBerlaku"])->name("diskons.daftardiskonberlaku");
    Route::get('/salon/diskon/daftardiskonselesai', [DiskonController::class, "daftarDiskonSelesai"])->name("diskons.daftardiskonselesai");
    Route::post('/salon/diskon/getdetaildiskon', [DiskonController::class, 'getDetailDiskon'])->name('admin.diskons.getdetaildiskons');

    Route::middleware(['karyawansalon'])->group(function () {

        //Reservasi hari ini dan riwayatnya

        //Absen

        //Komisi Karyawan Tersebut

    });


    Route::middleware(['karyawanadmin'])->group(function () {

        //After Login
        Route::get('/salon', [ReservasiController::class, "index"])->name("reservasis.index");


        //Reservasi
        Route::resource('reservasis', ReservasiController::class);
        Route::get('/reservasi/admin/create', [ReservasiController::class, "reservasiAdminCreate"])->name("reservasi.admin.create");
        Route::get('/reservasi/admin/riwayatreservasiperawatan', [ReservasiController::class, "riwayatReservasiPerawatanAdmin"])->name("riwayatreservasis.index");
        Route::post('/reservasi/admin/getdetailriwayatreservasiperawatan', [ReservasiController::class, "getDetailRiwayatReservasiPerawatan"])->name("admin.getdetailriwayatreservasiperawatan");

        Route::post('/reservasi/admin/selectstaf', [ReservasiController::class, "reservasiAdminPilihKaryawanNew"])->name("reservasi.admin.pilihkaryawan");

        Route::post('/reservasi/admin/getslotjamaktif', [SlotJamController::class, "getSlotJamAktif"])->name("reservasi.admin.getslotjamaktif");
        Route::post('/reservasi/admin/getdetailperawatan', [PerawatanController::class, "getDetailPerawatan"])->name("reservasi.admin.getdetailperawatan");
        Route::post('/reservasi/admin/getdetailpaketreservasi', [PaketController::class, "getDetailPaketReservasi"])->name("reservasi.admin.getdetailpaketreservasi");
        Route::post('/reservasi/admin/addpaketreservasitolist', [PaketController::class, "addPaketToListReservasi"])->name("reservasi.admin.addpaketreservasitolist");
        Route::post('/reservasi/admin/updateperawatanafterdeletepaket', [PaketController::class, "updatePerawatanAfterDeletePaket"])->name("reservasi.admin.updatecbperawatanafterdeletepaket");
        Route::post('/reservasi/admin/checkisipaketsama', [PaketController::class, "checkPaketIsiSama"])->name("reservasi.admin.checkpaketisisama");

        Route::post('/reservasi/admin/store', [ReservasiController::class, "reservasiAdminStore"])->name("reservasi.admin.store");
        Route::post('/reservasi/admin/konfirmasi', [ReservasiController::class, "reservasiAdminKonfirmasi"])->name("reservasi.admin.konfirmasireservasi");

        Route::get('/reservasi/admin/tambahproduk/{id}', [ProdukController::class, "reservasiTambahProduk"])->name("reservasi.admin.reservasitambahproduk");
        Route::post('/reservasi/admin/tambahproduk/konfirmasiproduk', [PenjualanController::class, "konfirmasiPenambahanProduk"])->name("reservasi.admin.konfirmasipenambahanproduk");
        Route::get('/reservasi/admin/detailreservasi/{idReservasi}', [ReservasiController::class, "detailReservasi"])->name("reservasi.admin.detailreservasi");
        Route::post('/reservasi/admin/batalkan', [ReservasiController::class, "adminBatalkanReservasi"])->name("reservasi.admin.batalkan");
        Route::post('/reservasi/admin/selesai', [ReservasiController::class, "adminSelesaiReservasi"])->name("reservasi.admin.selesai");

        //Penjualan
        Route::resource('penjualans', PenjualanController::class);

        //Karyawan
        Route::get('/salon/karyawan/index', [KaryawanController::class, "index"])->name("karyawans.index");
        Route::post('/salon/karyawan/getDetailKaryawan', [KaryawanController::class, "getDetailKaryawan"])->name("admin.getdetailkaryawan");

        //Produk
        Route::get('/salon/produk/create', [ProdukController::class, "create"])->name("produks.create");
        Route::post('/salon/produk/store', [ProdukController::class, "store"])->name("produks.store");
        Route::get('/salon/produk/{idProduk}/edit', [ProdukController::class, "edit"])->name("produks.edit");
        Route::put('/salon/produk/{idProduk}/udpate', [ProdukController::class, "update"])->name("produks.update");
        Route::delete('/salon/produk/{idProduk}/destroy', [ProdukController::class, "destroy"])->name("produks.destroy");

        //Kategori
        Route::get('/salon/kategori/create', [KategoriController::class, "create"])->name("kategoris.create");
        Route::post('/salon/kategori/store', [KategoriController::class, "store"])->name("kategoris.store");
        Route::get('/salon/kategori/{idKategori}/edit', [KategoriController::class, "edit"])->name("kategoris.edit");
        Route::put('/salon/kategori/{idKategori}/udpate', [KategoriController::class, "update"])->name("kategoris.update");
        Route::delete('/salon/kategori/{idKategori}/destroy', [KategoriController::class, "destroy"])->name("kategoris.destroy");

        //Merek
        Route::get('/salon/merek/create', [MerekController::class, "create"])->name("mereks.create");
        Route::post('/salon/merek/store', [MerekController::class, "store"])->name("mereks.store");
        Route::get('/salon/merek/{idMerek}/edit', [MerekController::class, "edit"])->name("mereks.edit");
        Route::put('/salon/merek/{idMerek}/udpate', [MerekController::class, "update"])->name("mereks.update");
        Route::delete('/salon/merek/{idMerek}/destroy', [MerekController::class, "destroy"])->name("mereks.destroy");

        //Kondisi
        Route::get('/salon/kondisi/create', [KondisiController::class, "create"])->name("kondisis.create");
        Route::post('/salon/kondisi/store', [KondisiController::class, "store"])->name("kondisis.store");
        Route::get('/salon/kondisi/{idKondisi}/edit', [KondisiController::class, "edit"])->name("kondisis.edit");
        Route::put('/salon/kondisi/{idKondisi}/udpate', [KondisiController::class, "update"])->name("kondisis.update");
        Route::delete('/salon/kondisi/{idKondisi}/destroy', [KondisiController::class, "destroy"])->name("kondisis.destroy");

        //Rekomendasi Produk
        Route::get('/salon/rekomendasiproduk', [AprioriController::class, "settingApriori"])->name("admin.settingrekomendasiproduk");
        Route::post('/salon/rekomendasiproduk/proses', [AprioriController::class, "prosesApriori"])->name("admin.prosesrekomendasiproduk");

        //Perawatan
        Route::get('/salon/perawatan/create', [PerawatanController::class, "create"])->name("perawatans.create");
        Route::post('/salon/perawatan/store', [PerawatanController::class, "store"])->name("perawatans.store");
        Route::get('/salon/perawatan/{idPerawatan}/edit', [PerawatanController::class, "edit"])->name("perawatans.edit");
        Route::put('/salon/perawatan/{idPerawatan}/udpate', [PerawatanController::class, "update"])->name("perawatans.update");
        Route::delete('/salon/perawatan/{idPerawatan}/destroy', [PerawatanController::class, "destroy"])->name("perawatans.destroy");

        //Supplier
        Route::resource("suppliers", SupplierController::class);
        Route::post('/salon/karyawan/getdetailpembeliansupplier', [SupplierController::class, "getDetailPembelianSupplier"])->name("admin.suppliers.getdetailpembeliansupplier");

        //Slot Jam
        Route::resource('slotjams', SlotJamController::class);
        Route::post('/salon/slotjam/editstatusslotjam', [SlotJamController::class, "editStatusSlotJam"])->name("admin.editstatusslotjam");

        //Pembelian
        Route::resource('pembelians', PembelianController::class);
        Route::post('/salon/pembelian/getdetailpembelian', [PembelianController::class, 'getDetailPembelian'])->name('admin.pembelians.getdetailpembelian');
        Route::post('/salon/pembelian/prosestanggalbayar', [PembelianController::class, 'updateTanggalPembayaranPembelian'])->name('admin.pembelians.prosestanggalbayar');

        //Riwayat Pengambilan Produk
        Route::resource('riwayatpengambilanproduks', RiwayatPengambilanProdukController::class);
        Route::post('/salon/riwayatpengambilanproduk/getdetailpengambilanproduk', [RiwayatPengambilanProdukController::class, 'getDetailRiwayatPengambilanProduk'])->name('admin.riwayatpengambilanproduks.getdetailriwayatpengambilanproduk');

        //Presensi Kehadiran
        Route::resource("presensikehadirans", PresensiKehadiranController::class);
        Route::get('/salon/presensi/editpresensi/{tanggalPresensi}', [PresensiKehadiranController::class, "editPresensiKehadiran"])->name("admin.presensikehadirans.editpresensi");
        Route::post('/salon/presensi/updatepresensi', [PresensiKehadiranController::class, "updatePresensiKehadiran"])->name("admin.presensikehadirans.updatepresensikehadiran");
        Route::get('/salon/presensi/riwayatpresensi', [PresensiKehadiranController::class, "riwayatPresensiKaryawan"])->name("admin.presensikehadirans.riwayatpresensi");
        Route::post('/salon/presensi/detailriwayatpresensi', [PresensiKehadiranController::class, "getDetailRiwayatPresensi"])->name("admin.getdetailriwayatpresensi");
        Route::get('/salon/presensi/riwayatizinkehadiran', [PresensiKehadiranController::class, "riwayatIzinKehadiran"])->name("admin.presensikehadirans.riwayatizinkehadiran");
        Route::post('/salon/presensi/detailriwayatizinkehadiran', [PresensiKehadiranController::class, "getDetailIzinKehadiran"])->name("admin.getdetailizinkehadiran");
        Route::post('/salon/presensi/updatestatusizinkehadrian', [PresensiKehadiranController::class, "updateStatusIzin"])->name("admin.updatestatusizin");

        //Paket 
        Route::get('/salon/paket/create', [PaketController::class, "create"])->name("pakets.create");
        Route::post('/salon/paket/store', [PaketController::class, "store"])->name("pakets.store");
        Route::get('/salon/paket/{idPaket}/edit', [PaketController::class, "edit"])->name("pakets.edit");
        Route::put('/salon/paket/{idPaket}/udpate', [PaketController::class, "update"])->name("pakets.update");
        Route::delete('/salon/paket/{idPaket}/destroy', [PaketController::class, "destroy"])->name("pakets.destroy");

        //Diskon
        Route::get('/salon/diskon/create', [DiskonController::class, "create"])->name("diskons.create");
        Route::post('/salon/diskon/store', [DiskonController::class, "store"])->name("diskons.store");
        Route::get('/salon/diskon/{idPaket}/edit', [DiskonController::class, "edit"])->name("diskons.edit");
        Route::put('/salon/diskon/{idPaket}/udpate', [DiskonController::class, "update"])->name("diskons.update");
        Route::get('/salon/diskon/pilihdiskon/{idPenjualan}', [DiskonController::class, 'pilihDiskon'])->name('admin.diskons.pilihdiskon');
        Route::post('/salon/diskon/prosespemakaiandiskon', [DiskonController::class, 'prosesPemakaianDiskon'])->name('admin.diskons.prosespemakaiandiskon');
    });


    Route::middleware(['admin'])->group(function () {
        //Komisi Karyawan
        Route::get('/salon/karyawan/komisikaryawan', [KaryawanController::class, "indexKomisiKaryawan"])->name("admin.karyawans.indexkomisikaryawan");
        Route::post('/salon/karyawan/proseskomisikaryawan', [KaryawanController::class, "prosesKomisiKaryawan"])->name("admin.karyawans.proseskomisikaryawan");
        Route::post('/salon/karyawan/detailkomisikaryawan', [KaryawanController::class, "getDetailKomisiKaryawan"])->name("admin.karyawans.detailkomisikaryawan");

        //Karyawan
        Route::get('/salon/karyawan/create', [KaryawanController::class, "create"])->name("karyawans.create");
        Route::post('/salon/karyawan/store', [KaryawanController::class, "store"])->name("karyawans.store");
        Route::get('/salon/karyawan/{idKaryawan}/edit', [KaryawanController::class, "edit"])->name("karyawans.edit");
        Route::put('/salon/karyawan/{idKaryawan}/udpate', [KaryawanController::class, "update"])->name("karyawans.update");
        Route::delete('/salon/karyawan/{idKaryawan}/destroy', [KaryawanController::class, "destroy"])->name("karyawans.destroy");
    });
});





Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
