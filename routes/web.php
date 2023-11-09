<?php

use App\Http\Controllers\AprioriController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\KondisiController;
use App\Http\Controllers\MerekController;
use App\Http\Controllers\PaketController;
use App\Http\Controllers\PembelianController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\PerawatanController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\ReservasiController;
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

Route::resource('reservasis', ReservasiController::class);
Route::resource('produks', ProdukController::class);
Route::resource('perawatans', PerawatanController::class);
Route::resource('pakets', PaketController::class);
Route::resource('penjualans', PenjualanController::class);
Route::resource('pembelians', PembelianController::class);
Route::resource('karyawans', KaryawanController::class);
Route::resource('slotjams', SlotJamController::class);

Route::get('/serenity/penjualan/errorpage', [PenjualanController::class, "errorPageNullPenjualan"])->name("penjualan.null.errorpage");

//ADMIN
//Reservasi
Route::get('/reservasi/admin/create', [ReservasiController::class, "reservasiAdminCreate"])->name("reservasi.admin.create");

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

//Produk



//Kategori
Route::resource("kategoris",KategoriController::class);
//Route::get("admin/produk/kategori/", [KategoriController::class,"index"])->name("kategoris.index");


//Merek
Route::resource("mereks", MerekController::class);


//Kondisi
Route::resource("kondisis", KondisiController::class);


//Rekomendasi Produk
Route::get('/rekomendasiproduk', [AprioriController::class, "settingApriori"])->name("admin.settingrekomendasiproduk");
Route::post('/rekomendasiproduk/proses', [AprioriController::class, "prosesApriori"])->name("admin.prosesrekomendasiproduk");

//Karyawan
Route::post('/admin/karyawan/getDetailKaryawan', [KaryawanController::class, "getDetailKaryawan"])->name("admin.getdetailkaryawan");

//Perawatan
Route::post('/admin/perawatan/getDetailPerawatan', [PerawatanController::class, "getDetailPerawatanList"])->name("admin.getdetailperawatan");

//Supplier
Route::resource("suppliers", SupplierController::class);
Route::post('/admin/karyawan/getDetailSupplier', [SupplierController::class, "getDetailSupplier"])->name("admin.getdetailsupplier");

//Slot Jam
Route::post('/admin/slotjam/editstatusslotjam', [SlotJamController::class, "editStatusSlotJam"])->name("admin.editstatusslotjam");