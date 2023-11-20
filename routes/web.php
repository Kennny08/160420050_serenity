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

Route::get('/', [ReservasiController::class, "index"])->name("reservasis.index");

//ADMIN
//Reservasi
Route::resource('reservasis', ReservasiController::class);
Route::get('/reservasi/admin/create', [ReservasiController::class, "reservasiAdminCreate"])->name("reservasi.admin.create");
Route::get('/reservasi/admin/riwayatreservasiperawatan', [ReservasiController::class, "riwayatReservasiPerawatanAdmin"])->name("riwayatreservasis.index");
Route::post('/reservasi/admin/getdetailriwayatreservasiperawatan', [ReservasiController::class, "getDetailRiwayatReservasiPerawatan"])->name("admin.getdetailriwayatreservasiperawatan");


Route::post('/reservasi/admin/selectstaf', [ReservasiController::class, "reservasiAdminPilihKaryawan"])->name("reservasi.admin.pilihkaryawan");

Route::post('/reservasi/admin/getslotjamaktif', [SlotJamController::class, "getSlotJamAktif"])->name("reservasi.admin.getslotjamaktif");
Route::post('/reservasi/admin/getdetailperawatan', [PerawatanController::class, "getDetailPerawatan"])->name("reservasi.admin.getdetailperawatan");

Route::post('/reservasi/admin/store', [ReservasiController::class, "reservasiAdminStore"])->name("reservasi.admin.store");
Route::post('/reservasi/admin/konfirmasi', [ReservasiController::class, "reservasiAdminKonfirmasi"])->name("reservasi.admin.konfirmasireservasi");

Route::get('/reservasi/admin/tambahproduk/{id}', [ProdukController::class, "reservasiTambahProduk"])->name("reservasi.admin.reservasitambahproduk");
Route::post('/reservasi/admin/tambahproduk/konfirmasiproduk', [PenjualanController::class, "konfirmasiPenambahanProduk"])->name("reservasi.admin.konfirmasipenambahanproduk");
Route::get('/reservasi/admin/detailreservasi/{idReservasi}', [ReservasiController::class, "detailReservasi"])->name("reservasi.admin.detailreservasi");
Route::post('/reservasi/admin/batalkan', [ReservasiController::class, "adminBatalkanReservasi"])->name("reservasi.admin.batalkan");
Route::post('/reservasi/admin/selesai', [ReservasiController::class, "adminSelesaiReservasi"])->name("reservasi.admin.selesai");

//Penjualan
Route::resource('penjualans', PenjualanController::class);
Route::get('/serenity/penjualan/errorpage', [PenjualanController::class, "errorPageNullPenjualan"])->name("penjualan.null.errorpage");

//Produk
Route::resource('produks', ProdukController::class);


//Kategori
Route::resource("kategoris", KategoriController::class);
Route::post('/admin/kategori/getdaftarprodukkategori', [KategoriController::class, "getDaftarProdukKategori"])->name("admin.kategoris.getdaftarprodukkategori");

//Route::get("admin/produk/kategori/", [KategoriController::class,"index"])->name("kategoris.index");


//Merek
Route::resource("mereks", MerekController::class);
Route::post('/admin/merek/getdaftarprodukmerek', [MerekController::class, "getDaftarProdukMerek"])->name("admin.mereks.getdaftarprodukmerek");


//Kondisi
Route::resource("kondisis", KondisiController::class);
Route::post('/admin/kondisi/getdaftarprodukkondisi', [KondisiController::class, "getDaftarProdukKondisi"])->name("admin.kondisis.getdaftarprodukkondisi");


//Rekomendasi Produk
Route::get('/rekomendasiproduk', [AprioriController::class, "settingApriori"])->name("admin.settingrekomendasiproduk");
Route::post('/rekomendasiproduk/proses', [AprioriController::class, "prosesApriori"])->name("admin.prosesrekomendasiproduk");

//Karyawan
Route::resource('karyawans', KaryawanController::class);
Route::post('/admin/karyawan/getDetailKaryawan', [KaryawanController::class, "getDetailKaryawan"])->name("admin.getdetailkaryawan");

//Perawatan
Route::resource('perawatans', PerawatanController::class);
Route::post('/admin/perawatan/getDetailPerawatan', [PerawatanController::class, "getDetailPerawatanList"])->name("admin.getdetailperawatan");

//Supplier
Route::resource("suppliers", SupplierController::class);
Route::post('/admin/karyawan/getdetailpembeliansupplier', [SupplierController::class, "getDetailPembelianSupplier"])->name("admin.suppliers.getdetailpembeliansupplier");

//Slot Jam
Route::resource('slotjams', SlotJamController::class);
Route::post('/admin/slotjam/editstatusslotjam', [SlotJamController::class, "editStatusSlotJam"])->name("admin.editstatusslotjam");

//Presensi Kehadiran
Route::resource("presensikehadirans", PresensiKehadiranController::class);
Route::get('/admin/presensi/editpresensi/{tanggalPresensi}', [PresensiKehadiranController::class, "editPresensiKehadiran"])->name("admin.presensikehadirans.editpresensi");
Route::post('/admin/presensi/updatepresensi', [PresensiKehadiranController::class, "updatePresensiKehadiran"])->name("admin.presensikehadirans.updatepresensikehadiran");
Route::get('/admin/presensi/riwayatpresensi', [PresensiKehadiranController::class, "riwayatPresensiKaryawan"])->name("admin.presensikehadirans.riwayatpresensi");
Route::post('/admin/presensi/detailriwayatpresensi', [PresensiKehadiranController::class, "getDetailRiwayatPresensi"])->name("admin.getdetailriwayatpresensi");
Route::get('/admin/presensi/riwayatizinkehadiran', [PresensiKehadiranController::class, "riwayatIzinKehadiran"])->name("admin.presensikehadirans.riwayatizinkehadiran");
Route::post('/admin/presensi/detailriwayatizinkehadiran', [PresensiKehadiranController::class, "getDetailIzinKehadiran"])->name("admin.getdetailizinkehadiran");
Route::post('/admin/presensi/updatestatusizinkehadrian', [PresensiKehadiranController::class, "updateStatusIzin"])->name("admin.updatestatusizin");

//Komisi Karyawan
Route::get('/admin/karyawan/komisikaryawan', [KaryawanController::class, "indexKomisiKaryawan"])->name("admin.karyawans.indexkomisikaryawan");
Route::post('/admin/karyawan/proseskomisikaryawan', [KaryawanController::class, "prosesKomisiKaryawan"])->name("admin.karyawans.proseskomisikaryawan");
Route::post('/admin/karyawan/detailkomisikaryawan', [KaryawanController::class, "getDetailKomisiKaryawan"])->name("admin.karyawans.detailkomisikaryawan");


//Pembelian
Route::resource('pembelians', PembelianController::class);
Route::post('/admin/pembelian/getdetailpembelian', [PembelianController::class, 'getDetailPembelian'])->name('admin.pembelians.getdetailpembelian');
Route::post('/admin/pembelian/prosestanggalbayar', [PembelianController::class, 'updateTanggalPembayaranPembelian'])->name('admin.pembelians.prosestanggalbayar');

//Paket
Route::resource('pakets', PaketController::class);

//Riwayat Pengambilan Produk
Route::resource('riwayatpengambilanproduks', RiwayatPengambilanProdukController::class);
Route::post('/admin/riwayatpengambilanproduk/getdetailpengambilanproduk', [RiwayatPengambilanProdukController::class, 'getDetailRiwayatPengambilanProduk'])->name('admin.riwayatpengambilanproduks.getdetailriwayatpengambilanproduk');

//Diskon
Route::resource('diskons', DiskonController::class);
Route::get('/admin/diskon/daftardiskonberlaku', [DiskonController::class, "daftarDiskonBerlaku"])->name("diskons.daftardiskonberlaku");
Route::get('/admin/diskon/daftardiskonselesai', [DiskonController::class, "daftarDiskonSelesai"])->name("diskons.daftardiskonselesai");
Route::post('/admin/diskon/getdetaildiskon', [DiskonController::class, 'getDetailDiskon'])->name('admin.diskons.getdetaildiskons');

