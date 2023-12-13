<?php

use App\Http\Controllers\AprioriController;
use App\Http\Controllers\DiskonController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\KondisiController;
use App\Http\Controllers\MerekController;
use App\Http\Controllers\PaketController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\PembelianController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\PerawatanController;
use App\Http\Controllers\PresensiKehadiranController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\ReservasiController;
use App\Http\Controllers\RiwayatPengambilanProdukController;
use App\Http\Controllers\SlotJamController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UlasanController;
use App\Http\Controllers\UserController;
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
        return redirect()->route('users.halamanutama');
    } else {
        if (Auth::user()->role === 'admin' || Auth::user()->role === 'karyawan') {

            if (Auth::user()->role === 'admin' || Auth::user()->karyawan->jenis_karyawan === 'admin') {
                return redirect()->route('reservasis.index');
            } else {
                //Route untuk ke halaman reservasi untuk karyawan salon tersebut saja
                return redirect()->route('karyawans.daftarreservasi');
            }

        } else if (Auth::user()->role === 'pelanggan') {
            return redirect()->route('pelanggans.index');
        }
    }
})->name("allindex");

Route::get('/serenity', [UserController::class, "halamanUtama"])->name("users.halamanutama");
Route::get('/serenity/daftarproduk', [ProdukController::class, "daftarProdukAllUser"])->name("produks.daftarprodukalluser");
Route::post('/serenity/daftarproduk/filter', [ProdukController::class, "daftarProdukFilterAllUser"])->name("produks.daftarprodukfilteralluser");
Route::get('/serenity/detailproduk/{idProduk}', [ProdukController::class, "detailProdukAllUser"])->name("produks.detailprodukalluser");

Route::get('/serenity/daftarperawatan', [PerawatanController::class, "daftarPerawatanAllUser"])->name("perawatans.daftarperawatanalluser");
Route::post('/serenity/daftarperawatan/filter', [PerawatanController::class, "daftarPerawatanFilterAllUser"])->name("perawatans.daftarperawatanfilteralluser");
Route::get('/serenity/detailperawatan/{idPerawatan}', [PerawatanController::class, "detailPerawatanAllUser"])->name("perawatans.detailperawatanalluser");

Route::get('/serenity/daftarpaket', [PaketController::class, "daftarPaketAllUser"])->name("pakets.daftarpaketalluser");
Route::post('/serenity/daftarpaket/filter', [PaketController::class, "daftarPaketFilterAllUser"])->name("pakets.daftarpaketfilteralluser");
Route::get('/serenity/detailpaket/{idPaket}', [PaketController::class, "detailPaketAllUser"])->name("pakets.detailpaketalluser");


Route::get('/serenity/tentangkami', function () {
    return view("alluser.tentangkami");
})->name("users.tentangkami");

//REGISTER
Route::get('/pelanggan/register', [PelangganController::class, "bukaRegisterAkun"])->name("pelanggans.bukaregister");
Route::post('/pelanggan/prosesregister', [PelangganController::class, "registerAkun"])->name("pelanggans.register");

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
        Route::get('/salon/karyawan/daftarreservasi', [ReservasiController::class, "daftarReservasiKaryawan"])->name("karyawans.daftarreservasi");
        Route::post('/salon/karyawan/detaildaftarreservasipertanggal', [ReservasiController::class, "detailDaftarReservasiKaryawan"])->name("karyawans.detailreservasiperhari");
        Route::get('/salon/karyawan/riwayatreservasi', [ReservasiController::class, "daftarRiwayatReservasiKaryawan"])->name("karyawans.daftarriwayatreservasi");

        //Penjualan
        Route::get('/salon/penjualan/karyawan/index', [PenjualanController::class, "indexKaryawanSalon"])->name("penjualans.karyawan.index");
        Route::get('/salon/penjualan/karyawan/riwayatpenjualan', [PenjualanController::class, "riwayatPenjualanKaryawanSalon"])->name("penjualans.karyawan.riwayatpenjualan");
        Route::get('/salon/penjualan/karyawan/penjualankeseluruhan', [PenjualanController::class, "daftarPenjualanKeseluruhanKaryawanSalon"])->name("penjualans.karyawan.daftarpenjualankeseluruhan");

        Route::post('/salon/penjualan/karyawan/getdetailriwayatpenjualan', [PenjualanController::class, "getDetailRiwayatPenjualanKaryawanSalon"])->name("penjualans.karyawan.getdetailriwayatpenjualan");
        Route::post('/salon/penjualan/karyawan/detailpenjualankeseluruhan', [PenjualanController::class, "detailPenjualanKeseluruhanKaryawanSalon"])->name("penjualans.karyawan.detailpenjualankeseluruhan");

        //Presensi
        Route::get('/salon/karyawan/presensihariini', [PresensiKehadiranController::class, "presensiHariIniKaryawanSalon"])->name("karyawans.presensihariinikaryawansalon");
        Route::post('/salon/karyawan/prosespresensi', [PresensiKehadiranController::class, "prosesPresensiHariIniKaryawanSalon"])->name("karyawans.prosespresensihariinikaryawansalon");
        Route::get('/salon/karyawan/riwayatpresensi', [PresensiKehadiranController::class, "riwayatPresensiKaryawanSalon"])->name("karyawans.riwayatpresensikaryawansalon");
        Route::get('/salon/karyawan/daftarizinkaryawansalon', [PresensiKehadiranController::class, "daftarIzinKaryawanSalon"])->name("karyawans.daftarizinkaryawansalon");
        Route::post('/salon/karyawan/prosesizinkaryawansalon', [PresensiKehadiranController::class, "prosesIzinKaryawanSalon"])->name("karyawans.prosesizinkaryawansalon");

        //Komisi Karyawan Tersebut
        Route::get('/salon/karyawan/komisikaryawan', [KaryawanController::class, "indexKomisiKaryawanSalon"])->name("karyawans.indexkomisikaryawansalon");
        Route::post('/salon/karyawan/proseskomisikaryawan', [KaryawanController::class, "prosesKomisiKaryawanSalon"])->name("karyawans.proseskomisikaryawansalon");
        Route::post('/salon/karyawan/detailkomisikaryawan', [KaryawanController::class, "getDetailKomisiKaryawanSalon"])->name("karyawans.detailkomisikaryawansalon");

    });


    Route::middleware(['karyawanadmin'])->group(function () {

        //After Login
        // Route::get('/salon', [ReservasiController::class, "index"])->name("reservasis.index");


        //Reservasi
        Route::get('/salon/reservasi/admin/index', [ReservasiController::class, "index"])->name("reservasis.index");
        Route::get('/salon/reservasi/admin/create', [ReservasiController::class, "reservasiAdminCreate"])->name("reservasi.admin.create");
        Route::get('/salon/reservasi/admin/riwayatreservasiperawatan', [ReservasiController::class, "riwayatReservasiPerawatanAdmin"])->name("riwayatreservasis.index");
        Route::post('/salon/reservasi/admin/getdetailriwayatreservasiperawatan', [ReservasiController::class, "getDetailRiwayatReservasiPerawatan"])->name("admin.getdetailriwayatreservasiperawatan");

        Route::post('/salon/reservasi/admin/selectstaf', [ReservasiController::class, "reservasiAdminPilihKaryawanNew"])->name("reservasi.admin.pilihkaryawan");

        Route::post('/salon/reservasi/admin/getslotjamaktif', [SlotJamController::class, "getSlotJamAktif"])->name("reservasi.admin.getslotjamaktif");
        Route::post('/salon/reservasi/admin/getdetailperawatan', [PerawatanController::class, "getDetailPerawatan"])->name("reservasi.admin.getdetailperawatan");
        Route::post('/salon/reservasi/admin/getdetailpaketreservasi', [PaketController::class, "getDetailPaketReservasi"])->name("reservasi.admin.getdetailpaketreservasi");
        Route::post('/salon/reservasi/admin/addpaketreservasitolist', [PaketController::class, "addPaketToListReservasi"])->name("reservasi.admin.addpaketreservasitolist");
        Route::post('/salon/reservasi/admin/updateperawatanafterdeletepaket', [PaketController::class, "updatePerawatanAfterDeletePaket"])->name("reservasi.admin.updatecbperawatanafterdeletepaket");
        Route::post('/salon/reservasi/admin/checkisipaketsama', [PaketController::class, "checkPaketIsiSama"])->name("reservasi.admin.checkpaketisisama");

        //Route::post('/salon/reservasi/admin/store', [ReservasiController::class, "reservasiAdminStore"])->name("reservasi.admin.store");
        Route::post('/salon/reservasi/admin/konfirmasipilihkaryawan', [ReservasiController::class, "reservasiAdminKonfirmasi"])->name("reservasi.admin.konfirmasireservasi");

        Route::get('/salon/penjualan/admin/tambahproduk/{id}', [ProdukController::class, "penjualanTambahProduk"])->name("penjualan.admin.penjualantambahproduk");
        Route::post('/salon/penjualan/admin/tambahproduk/konfirmasiproduk', [PenjualanController::class, "konfirmasiPenambahanProduk"])->name("penjualans.admin.konfirmasipenambahanproduk");
        Route::get('/salon/reservasi/admin/detailreservasi/{idReservasi}', [ReservasiController::class, "detailReservasi"])->name("reservasi.admin.detailreservasi");
        Route::post('/salon/reservasi/admin/batalkan', [ReservasiController::class, "adminBatalkanReservasi"])->name("reservasi.admin.batalkan");
        Route::post('/salon/reservasi/admin/selesai', [ReservasiController::class, "adminSelesaiReservasi"])->name("reservasi.admin.selesai");

        Route::post('/salon/reservasi/admin/editpilihkaryawanreservasi', [ReservasiController::class, "editPilihKaryawanReservasi"])->name("reservasi.admin.editpilihkaryawanperawatan");
        Route::post('/salon/reservasi/admin/konfirmasieditpilihkaryawanreservasi', [ReservasiController::class, "konfirmasiEditPilihKaryawanReservasi"])->name("reservasi.admin.konfirmasieditpilihkaryawan");

        //Penjualan
        Route::get('/salon/penjualan/admin/index', [PenjualanController::class, "index"])->name("penjualans.index");
        Route::get('/salon/penjualan/admin/create', [PenjualanController::class, "penjualanAdminCreate"])->name("penjualans.admin.create");
        Route::post('/salon/penjualan/admin/selectstaf', [PenjualanController::class, "penjualanAdminPilihKaryawan"])->name("penjualans.admin.pilihkaryawan");
        Route::post('/salon/penjualan/admin/konfirmasipilihkaryawan', [PenjualanController::class, "penjualanAdminKonfirmasi"])->name("penjualans.admin.konfirmasipenjualan");

        Route::get('/salon/penjualan/admin/riwayatpenjualan', [PenjualanController::class, "riwayatPenjualan"])->name("penjualans.admin.riwayatpenjualan");
        Route::post('/salon/penjualan/admin/getdetailriwayatpenjualan', [PenjualanController::class, "getDetailRiwayatPenjualan"])->name("penjualans.admin.getdetailriwayatpenjualan");

        Route::get('/salon/penjualan/admin/detailpenjualan/{idPenjualan}', [PenjualanController::class, "detailPenjualan"])->name("penjualans.admin.detailpenjualan");

        Route::post('/salon/penjualan/admin/batalkan', [PenjualanController::class, "adminBatalkanPenjualan"])->name("penjualans.admin.batalkan");
        Route::post('/salon/penjualan/admin/selesai', [PenjualanController::class, "adminSelesaiPenjualan"])->name("penjualans.admin.selesai");

        Route::get('/salon/penjualan/admin/penjualankeseluruhan', [PenjualanController::class, "daftarPenjualanKeseluruhan"])->name("penjualans.admin.daftarpenjualankeseluruhan");
        Route::post('/salon/penjualan/admin/detailpenjualankeseluruhan', [PenjualanController::class, "detailPenjualanKeseluruhan"])->name("penjualans.admin.detailpenjualankeseluruhan");

        Route::get('/salon/penjualan/admin/detailnotareservasi/{idReservasi}', [PenjualanController::class, "detailNotaReservasiPenjualan"])->name("penjualans.admin.detailnotareservasipenjualan");
        Route::get('/salon/penjualan/admin/detailnotapenjualan/{idPenjualan}', [PenjualanController::class, "detailNotaPenjualan"])->name("penjualans.admin.detailnotapenjualan");

        Route::post('/salon/penjualan/admin/editpilihkaryawanpenjualan', [PenjualanController::class, "editPilihKaryawanPenjualan"])->name("penjualans.admin.editpilihkaryawanperawatan");
        Route::post('/salon/penjualan/admin/konfirmasieditpilihkaryawanpenjualan', [PenjualanController::class, "konfirmasiEditPilihKaryawanPenjualan"])->name("penjualans.admin.konfirmasieditpilihkaryawan");

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
        Route::post('/salon/rekomendasiproduk/detailpenjualan', [AprioriController::class, "getDetailPenjualan"])->name("admin.rekomendasiproduk.detailpenjualan");

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

        //Pelanggan
        Route::get('/salon/pelanggan/index', [PelangganController::class, "daftarPelanggan"])->name("pelanggans.admin.daftarpelanggan");

        //Ulasan
        Route::get('/salon/ulasan/index', [UlasanController::class, "daftarUlasan"])->name("ulasans.admin.daftarulasan");
        Route::post('/salon/ulasan/editstatus', [UlasanController::class, "editStatusUlasan"])->name("ulasans.admin.editStatusUlasan");
        

    });


    Route::middleware(['admin'])->group(function () {
        //Komisi Karyawan
        Route::get('/salon/admin/karyawan/komisikaryawan', [KaryawanController::class, "indexKomisiKaryawan"])->name("admin.karyawans.indexkomisikaryawan");
        Route::post('/salon/admin/karyawan/proseskomisikaryawan', [KaryawanController::class, "prosesKomisiKaryawan"])->name("admin.karyawans.proseskomisikaryawan");
        Route::post('/salon/admin/karyawan/detailkomisikaryawan', [KaryawanController::class, "getDetailKomisiKaryawan"])->name("admin.karyawans.detailkomisikaryawan");

        //Karyawan
        Route::get('/salon/admin/karyawan/create', [KaryawanController::class, "create"])->name("karyawans.create");
        Route::post('/salon/admin/karyawan/store', [KaryawanController::class, "store"])->name("karyawans.store");
        Route::get('/salon/admin/karyawan/{idKaryawan}/edit', [KaryawanController::class, "edit"])->name("karyawans.edit");
        Route::put('/salon/admin/karyawan/{idKaryawan}/udpate', [KaryawanController::class, "update"])->name("karyawans.update");
        Route::delete('/salon/admin/karyawan/{idKaryawan}/destroy', [KaryawanController::class, "destroy"])->name("karyawans.destroy");

        //Presensi Karyawan
        Route::resource("presensikehadirans", PresensiKehadiranController::class);
        Route::get('/salon/presensi/editpresensi/{tanggalPresensi}', [PresensiKehadiranController::class, "editPresensiKehadiran"])->name("admin.presensikehadirans.editpresensi");
        Route::post('/salon/presensi/updatepresensi', [PresensiKehadiranController::class, "updatePresensiKehadiran"])->name("admin.presensikehadirans.updatepresensikehadiran");
        Route::get('/salon/presensi/riwayatpresensi', [PresensiKehadiranController::class, "riwayatPresensiKaryawan"])->name("admin.presensikehadirans.riwayatpresensi");
        Route::post('/salon/presensi/detailriwayatpresensi', [PresensiKehadiranController::class, "getDetailRiwayatPresensi"])->name("admin.getdetailriwayatpresensi");
        Route::get('/salon/presensi/riwayatizinkehadiran', [PresensiKehadiranController::class, "riwayatIzinKehadiran"])->name("admin.presensikehadirans.riwayatizinkehadiran");
        Route::post('/salon/presensi/detailriwayatizinkehadiran', [PresensiKehadiranController::class, "getDetailIzinKehadiran"])->name("admin.getdetailizinkehadiran");
        Route::post('/salon/presensi/updatestatusizinkehadrian', [PresensiKehadiranController::class, "updateStatusIzin"])->name("admin.updatestatusizin");

        Route::post('/salon/presensi/konfirmasicheckpresensi', [PresensiKehadiranController::class, "konfirmasiCheckPresensi"])->name("admin.presensikehadirans.konfirmasicheckpresensi");

        //Penjualan

    });
});

Route::middleware(['auth', 'pelanggan'])->group(function () {

    //Beranda
    Route::get('/pelanggan/beranda', [PelangganController::class, "index"])->name("pelanggans.index");

    //Reservasi
    Route::get('/pelanggan/reservasi/create', [ReservasiController::class, "reservasiPelangganCreate"])->name("reservasis.pelanggan.create");
    Route::get('/pelanggan/reservasi/riwayatreservasiperawatan', [ReservasiController::class, "riwayatReservasiPerawatanPelanggan"])->name("reservasis.riwayatreservasispelanggan.index");
    Route::post('/pelanggan/reservasi/selectstaf', [ReservasiController::class, "reservasiPelangganPilihKaryawanNew"])->name("reservasis.pelanggan.pilihkaryawan");
    
    Route::post('/pelanggan/reservasi/checkreservasihariini', [ReservasiController::class, "checkReservasiHariIni"])->name("reservasis.pelanggan.checkreservasiharini");

    Route::post('/pelanggan/reservasi/konfirmasipilihkaryawan', [ReservasiController::class, "reservasiPelangganKonfirmasi"])->name("reservasis.pelanggan.konfirmasireservasi");
    Route::get('/pelanggan/tambahprodukpembelian/{id}', [ProdukController::class, "penjualanTambahProdukPelanggan"])->name("penjualans.pelanggan.penjualantambahproduk");
    Route::post('/pelanggan/tambahprodukpembelian/konfirmasiproduk', [PenjualanController::class, "konfirmasiPenambahanProdukPelanggan"])->name("penjualans.pelanggan.konfirmasipenambahanproduk");
    Route::get('/pelanggan/reservasi/detailreservasi/{idReservasi}', [ReservasiController::class, "detailReservasiPelanggan"])->name("reservasis.pelanggan.detailreservasi");
    Route::post('/pelanggan/reservasi/editpilihkaryawanreservasi', [ReservasiController::class, "editPilihKaryawanReservasiPelanggan"])->name("reservasis.pelanggan.editpilihkaryawanperawatan");
    Route::post('/pelanggan/reservasi/konfirmasieditpilihkaryawanreservasi', [ReservasiController::class, "konfirmasiEditPilihKaryawanReservasiPelanggan"])->name("reservasis.pelanggan.konfirmasieditpilihkaryawan");
    Route::get('/pelanggan/detailnotareservasi/{idReservasi}', [PenjualanController::class, "detailNotaReservasiPenjualanPelanggan"])->name("penjualans.pelanggan.detailnotareservasipenjualan");

    Route::post('/pelanggan/reservasi/getslotjamaktif', [SlotJamController::class, "getSlotJamAktifPelanggan"])->name("reservasis.pelanggan.getslotjamaktif");
    Route::post('/pelanggan/reservasi/getdetailperawatan', [PerawatanController::class, "getDetailPerawatanPelanggan"])->name("reservasis.pelanggan.getdetailperawatan");
    Route::post('/pelanggan/reservasi/getdetailpaketreservasi', [PaketController::class, "getDetailPaketReservasiPelanggan"])->name("reservasis.pelanggan.getdetailpaketreservasi");
    Route::post('/pelanggan/reservasi/addpaketreservasitolist', [PaketController::class, "addPaketToListReservasiPelanggan"])->name("reservasis.pelanggan.addpaketreservasitolist");
    Route::post('/pelanggan/reservasi/updateperawatanafterdeletepaket', [PaketController::class, "updatePerawatanAfterDeletePaketPelanggan"])->name("reservasis.pelanggan.updatecbperawatanafterdeletepaket");
    Route::post('/pelanggan/reservasi/checkisipaketsama', [PaketController::class, "checkPaketIsiSamaPelanggan"])->name("reservasis.pelanggan.checkpaketisisama");

    Route::get('/pelanggan/diskon/pilihdiskon/{idPenjualan}', [DiskonController::class, 'pilihDiskonPelanggan'])->name('diskons.pelanggan.pilihdiskon');
    Route::post('/pelanggan/diskon/prosespemakaiandiskon', [DiskonController::class, 'prosesPemakaianDiskonPelanggan'])->name('diskons.pelanggan.prosespemakaiandiskon');

    Route::post('/pelanggan/reservasi/batalkan', [ReservasiController::class, "pelangganBatalkanReservasi"])->name("reservasis.pelanggan.batalkan");

    Route::post('/pelanggan/reservasi/simpanulasan', [UlasanController::class, "pelangganSimpanUlasan"])->name("reservasis.ulasan.store");


});





Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
