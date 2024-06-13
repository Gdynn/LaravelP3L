<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\ProsesPesananController;

use App\Http\Controllers\Api\TokenController;

Route::post('/save-token', [TokenController::class, 'store']);

use App\Http\Controllers\TestController;

Route::get('/test-read-firebase', [TestController::class, 'readFirebaseConfig']);

// routes/api.php

use App\Http\Controllers\api\LaporanPerbulanController;

Route::get('/monthlysales', [LaporanPerbulanController::class, 'getMonthlySales']);

use App\Http\Controllers\api\LaporanBahanBakuController;

Route::get('/pengeluaran', [LaporanBahanBakuController::class, 'getPengeluaran']);



/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/register', [App\Http\Controllers\Api\AuthController::class, 'register']);
Route::post('/login', [App\Http\Controllers\Api\AuthController::class, 'login']);
Route::post('/test/{id}', [App\Http\Controllers\Api\UserController::class, 'tempCreate']);
Route::get('/verify/{verify_key}', [App\Http\Controllers\Api\AuthController::class, 'verify']);
Route::get('/usersalamat/{id}', [App\Http\Controllers\Api\UserController::class, 'showByIdUserAlamat']);

Route::get('/produk', [App\Http\Controllers\Api\ProdukController::class, 'index']);
Route::get('/produkCake', [App\Http\Controllers\Api\ProdukController::class, 'fetchCake']);
Route::get('/produkRoti', [App\Http\Controllers\Api\ProdukController::class, 'fetchRoti']);
Route::get('/produkMinuman', [App\Http\Controllers\Api\ProdukController::class, 'fetchMinuman']);
Route::get('/produkTitipan', [App\Http\Controllers\Api\ProdukController::class, 'fetchTitipan']);
Route::get('/produkMobile', [App\Http\Controllers\Api\ProdukController::class, 'fetchMobile']);
Route::get('/produkLimit', [App\Http\Controllers\Api\ProdukController::class, 'fetchProdukLimit']);

Route::middleware('auth:api')->group(function () {
    //user
    Route::get('/users', [App\Http\Controllers\Api\UserController::class, 'showAll']);
    Route::post('/users/update', [App\Http\Controllers\Api\UserController::class, 'updateProfile']);
    Route::post('/usersUpdate/{id}', [App\Http\Controllers\Api\UserController::class, 'updateUser']);
    Route::get('/userLogin', [App\Http\Controllers\Api\UserController::class, 'showByLogin']);
    Route::delete('/users/{id}', [App\Http\Controllers\Api\UserController::class, 'destroy']);
    Route::get('/user/unpaid-orders', [App\Http\Controllers\Api\UserController::class, 'getUnpaidOrders']);
    Route::put('/user/{id}/update-poin', [App\Http\Controllers\Api\UserController::class, 'updatePoin']);

    //item
    Route::get('/item', [App\Http\Controllers\Api\ItemController::class, 'showAll']);
    Route::get('/item/{id}', [App\Http\Controllers\Api\ItemController::class, 'showById']);
    Route::post('/item', [App\Http\Controllers\Api\ItemController::class, 'store']);
    Route::put('/item/{id}', [App\Http\Controllers\Api\ItemController::class, 'update']);
    Route::delete('/item/{id}', [App\Http\Controllers\Api\ItemController::class, 'destroy']);

    //produk
    Route::get('/produk/{id}', [App\Http\Controllers\Api\ProdukController::class, 'show']);
    Route::post('/produk', [App\Http\Controllers\Api\ProdukController::class, 'store']);
    Route::put('/produk/{id}', [App\Http\Controllers\Api\ProdukController::class, 'update']);
    Route::delete('/produk/{id}', [App\Http\Controllers\Api\ProdukController::class, 'destroy']);

    //hampers
    Route::get('/hampers', [App\Http\Controllers\Api\HampersController::class, 'index']);
    Route::get('/hampers/{id}', [App\Http\Controllers\Api\HampersController::class, 'show']);
    Route::post('/hampers', [App\Http\Controllers\Api\HampersController::class, 'store']);
    Route::put('/hampers/{id}', [App\Http\Controllers\Api\HampersController::class, 'update']);
    Route::delete('/hampers/{id}', [App\Http\Controllers\Api\HampersController::class, 'destroy']);

    //bahan baku
    Route::get('/bahanbaku', [App\Http\Controllers\Api\BahanBakuController::class, 'index']);
    Route::get('/bahanbaku/{id}', [App\Http\Controllers\Api\BahanBakuController::class, 'show']);
    Route::post('/bahanbaku', [App\Http\Controllers\Api\BahanBakuController::class, 'store']);
    Route::put('/bahanbaku/{id}', [App\Http\Controllers\Api\BahanBakuController::class, 'update']);
    Route::delete('/bahanbaku/{id}', [App\Http\Controllers\Api\BahanBakuController::class, 'destroy']);
    Route::put('stokbahanbaku/{id}', [App\Http\Controllers\Api\BahanBakuController::class, 'updateStok']);

    //detail hampers
    Route::get('/detailhampers', [App\Http\Controllers\Api\DetailHampersController::class, 'index']);
    Route::get('/detailhampers/{id}', [App\Http\Controllers\Api\DetailHampersController::class, 'show']);
    Route::post('/detailhampers', [App\Http\Controllers\Api\DetailHampersController::class, 'store']);
    Route::put('/detailhampers/{id}', [App\Http\Controllers\Api\DetailHampersController::class, 'update']);
    Route::delete('/detailhampers/{id}', [App\Http\Controllers\Api\DetailHampersController::class, 'destroy']);

    //detail pengeluaran
    Route::get('/detailpengeluaran', [App\Http\Controllers\Api\DetailPengeluaranController::class, 'index']);
    Route::get('/detailpengeluaran/{id}', [App\Http\Controllers\Api\DetailPengeluaranController::class, 'show']);
    Route::post('/detailpengeluaran', [App\Http\Controllers\Api\DetailPengeluaranController::class, 'store']);
    Route::put('/detailpengeluaran/{id}', [App\Http\Controllers\Api\DetailPengeluaranController::class, 'update']);
    Route::delete('/detailpengeluaran/{id}', [App\Http\Controllers\Api\DetailPengeluaranController::class, 'destroy']);

    //pembelian bahan baku
    Route::get('/pembelianbahanbaku', [App\Http\Controllers\Api\PembelianBahanBakuController::class, 'index']);
    Route::get('/pembelianbahanbaku/{id}', [App\Http\Controllers\Api\PembelianBahanBakuController::class, 'show']);
    Route::post('/pembelianbahanbaku', [App\Http\Controllers\Api\PembelianBahanBakuController::class, 'store']);
    Route::put('/pembelianbahanbaku/{id}', [App\Http\Controllers\Api\PembelianBahanBakuController::class, 'update']);
    Route::delete('/pembelianbahanbaku/{id}', [App\Http\Controllers\Api\PembelianBahanBakuController::class, 'destroy']);

    //Resep
    Route::get('/resep', [App\Http\Controllers\Api\ResepController::class, 'index']);
    Route::get('/resep/{id}', [App\Http\Controllers\Api\ResepController::class, 'show']);
    Route::post('/resep', [App\Http\Controllers\Api\ResepController::class, 'store']);
    Route::put('/resep/{id}', [App\Http\Controllers\Api\ResepController::class, 'update']);
    Route::delete('/resep/{id}', [App\Http\Controllers\Api\ResepController::class, 'destroy']);

    //Presensi
    Route::get('/presensi', [App\Http\Controllers\Api\PresensiController::class, 'index']);
    Route::get('/presensi/{id}', [App\Http\Controllers\Api\PresensiController::class, 'show']);
    Route::post('/presensi', [App\Http\Controllers\Api\PresensiController::class, 'store']);
    Route::put('/presensi/{id}', [App\Http\Controllers\Api\PresensiController::class, 'update']);
    Route::delete('/presensi/{id}', [App\Http\Controllers\Api\PresensiController::class, 'destroy']);

    //Pegawai
    Route::get('/pegawai', [App\Http\Controllers\Api\PegawaiController::class, 'index']);
    Route::get('/pegawai/{id}', [App\Http\Controllers\Api\PegawaiController::class, 'show']);
    Route::post('/pegawai', [App\Http\Controllers\Api\PegawaiController::class, 'store']);
    Route::put('/pegawai/{id}', [App\Http\Controllers\Api\PegawaiController::class, 'update']);
    Route::delete('/pegawai/{id}', [App\Http\Controllers\Api\PegawaiController::class, 'destroy']);

    //Jabatan
    Route::get('/jabatan', [App\Http\Controllers\Api\JabatanController::class, 'index']);
    Route::get('/jabatan/{id}', [App\Http\Controllers\Api\JabatanController::class, 'show']);
    Route::post('/jabatan', [App\Http\Controllers\Api\JabatanController::class, 'store']);
    Route::put('/jabatan/{id}', [App\Http\Controllers\Api\JabatanController::class, 'update']);
    Route::delete('/jabatan/{id}', [App\Http\Controllers\Api\JabatanController::class, 'destroy']);

    //Alamat
    Route::get('/alamat', [App\Http\Controllers\Api\AlamatController::class, 'index']);
    Route::get('/alamat/{id}', [App\Http\Controllers\Api\AlamatController::class, 'show']);
    Route::post('/alamat', [App\Http\Controllers\Api\AlamatController::class, 'store']);
    Route::put('/alamat/{id}', [App\Http\Controllers\Api\AlamatController::class, 'update']);
    Route::delete('/alamat/{id}', [App\Http\Controllers\Api\AlamatController::class, 'destroy']);

    //PromoPoin
    Route::get('/promopoin', [App\Http\Controllers\Api\PromoPoinController::class, 'index']);
    Route::get('/promopoin/{id}', [App\Http\Controllers\Api\PromoPoinController::class, 'show']);
    Route::post('/promopoin', [App\Http\Controllers\Api\PromoPoinController::class, 'store']);
    Route::put('/promopoin/{id}', [App\Http\Controllers\Api\PromoPoinController::class, 'update']);
    Route::delete('/promopoin/{id}', [App\Http\Controllers\Api\PromoPoinController::class, 'destroy']);

    //Pemesanan
    Route::get('/pemesanan', [App\Http\Controllers\Api\PemesananController::class, 'indexNullJarak']);
    Route::get('/pemesanan/{id}', [App\Http\Controllers\Api\PemesananController::class, 'showNullJarak']);
    //     Route::post('/pemesanan', [App\Http\Controllers\Api\PemesananController::class, 'store']);
    Route::put('/pemesanan/{id}', [App\Http\Controllers\Api\PemesananController::class, 'updateJarak']);
    //     Route::delete('/pemesanan/{id}', [App\Http\Controllers\Api\PemesananController::class, 'destroy']);
    Route::get('/pemesananId/{id}', [App\Http\Controllers\Api\PemesananController::class, 'getPemesananById']);
    Route::post('/pemesanan', [App\Http\Controllers\Api\PemesananController::class, 'order']);
    // Route::put('/pemesanan/{id}', [App\Http\Controllers\Api\PemesananController::class, 'update']);
    Route::put('/buktibayar/{id}', [App\Http\Controllers\Api\PemesananController::class, 'uploadBuktiBayar']);
    Route::delete('/pemesanan/{id}', [App\Http\Controllers\Api\PemesananController::class, 'destroy']);
    Route::get('/pemesananunpaid', [App\Http\Controllers\Api\PemesananController::class, 'getUnpaidOrders']);

    //DetailPemesananHampers
    Route::get('/detailpemesananhampers', [App\Http\Controllers\Api\DetailPemesananHampersController::class, 'index']);
    Route::get('/detailpemesananhampers/{id}', [App\Http\Controllers\Api\DetailPemesananHampersController::class, 'show']);
    Route::post('/detailpemesananhampers', [App\Http\Controllers\Api\DetailPemesananHampersController::class, 'store']);
    Route::put('/detailpemesananhampers/{id}', [App\Http\Controllers\Api\DetailPemesananHampersController::class, 'update']);
    Route::delete('/detailpemesananhampers/{id}', [App\Http\Controllers\Api\DetailPemesananHampersController::class, 'destroy']);

    //DetailPemesananProduk
    Route::get('/detailpemesananproduk', [App\Http\Controllers\Api\DetailPemesananProdukController::class, 'index']);
    Route::get('/detailpemesananproduk/{id}', [App\Http\Controllers\Api\DetailPemesananProdukController::class, 'show']);
    Route::post('/detailpemesananproduk', [App\Http\Controllers\Api\DetailPemesananProdukController::class, 'store']);
    Route::put('/detailpemesananproduk/{id}', [App\Http\Controllers\Api\DetailPemesananProdukController::class, 'update']);
    Route::delete('/detailpemesananproduk/{id}', [App\Http\Controllers\Api\DetailPemesananProdukController::class, 'destroy']);

    //Pesanan untuk nampilkan semua pesanan yang udah ada jarak
    Route::get('/pesanan', [App\Http\Controllers\Api\PesananController::class, 'index']);
    Route::get('/pesanan/{id}', [App\Http\Controllers\Api\PesananController::class, 'show']);
    Route::post('/pesanan', [App\Http\Controllers\Api\PesananController::class, 'store']);
    Route::put('/pesanan/{id}', [App\Http\Controllers\Api\PesananController::class, 'update']);
    Route::delete('/pesanan/{id}', [App\Http\Controllers\Api\PesananController::class, 'destroy']);

    //Datar Pesanan (Pesanan yang belum di konfirmasi)
    Route::get('/daftarpesanan', [App\Http\Controllers\Api\DaftarPesananController::class, 'index']);
    Route::get('/daftarpesanan/{id}', [App\Http\Controllers\Api\DaftarPesananController::class, 'show']);
    Route::post('/daftarpesanan', [App\Http\Controllers\Api\DaftarPesananController::class, 'store']);
    Route::put('/daftarpesanan/{id}', [App\Http\Controllers\Api\DaftarPesananController::class, 'update']);
    Route::delete('/daftarpesanan/{id}', [App\Http\Controllers\Api\DaftarPesananController::class, 'destroy']);

    //Tip Pesanan
    Route::get('/tippesanan', [App\Http\Controllers\Api\TipPesananController::class, 'index']);
    Route::get('/tippesanan/{id}', [App\Http\Controllers\Api\TipPesananController::class, 'show']);
    Route::post('/tippesanan', [App\Http\Controllers\Api\TipPesananController::class, 'store']);
    Route::put('/tippesanan/{id}', [App\Http\Controllers\Api\TipPesananController::class, 'update']);
    Route::delete('/tippesanan/{id}', [App\Http\Controllers\Api\TipPesananController::class, 'destroy']);

    //Limit Harian
    Route::get('/limitharian', [App\Http\Controllers\Api\LimitHarianController::class, 'index']);
    Route::get('/limitharian/{id}', [App\Http\Controllers\Api\LimitHarianController::class, 'show']);
    Route::get('/limitharianHariIni', [App\Http\Controllers\Api\LimitHarianController::class, 'searchByHariIni']);
    Route::post('/limitharian', [App\Http\Controllers\Api\LimitHarianController::class, 'store']);
    Route::put('/limitharian/{id}', [App\Http\Controllers\Api\LimitHarianController::class, 'update']);
    Route::delete('/limitharian/{id}', [App\Http\Controllers\Api\LimitHarianController::class, 'destroy']);

    //Pesanan dengan status diproses
    Route::get('/prosespesanan', [App\Http\Controllers\Api\ProsesPesananController::class, 'index']);
    Route::get('/prosespesanan/{id}', [App\Http\Controllers\Api\ProsesPesananController::class, 'show']);
    Route::post('/prosespesanan', [App\Http\Controllers\Api\ProsesPesananController::class, 'store']);
    Route::put('/prosespesanan/{id}', [App\Http\Controllers\Api\ProsesPesananController::class, 'update']);
    Route::delete('/prosespesanan/{id}', [App\Http\Controllers\Api\ProsesPesananController::class, 'destroy']);

    //untuk menampilkan pesanan dengan status siap di pick up atau diambil sendiri
    Route::get('/pickuppesanan', [App\Http\Controllers\Api\PesananYangDiProsesController::class, 'index']);
    Route::get('/pickuppesanan/{id}', [App\Http\Controllers\Api\PesananYangDiProsesController::class, 'show']);
    Route::post('/pickuppesanan', [App\Http\Controllers\Api\PesananYangDiProsesController::class, 'store']);
    Route::put('/pickuppesanan/{id}', [App\Http\Controllers\Api\PesananYangDiProsesController::class, 'update']);
    Route::delete('/pickuppesanan/{id}', [App\Http\Controllers\Api\PesananYangDiProsesController::class, 'destroy']);

    //pesanan yang statusnnya akan diselesaikan
    Route::get('/statusselesai', [App\Http\Controllers\Api\PesananSelesaiController::class, 'index']);
    Route::get('/statusselesai/{id}', [App\Http\Controllers\Api\PesananSelesaiController::class, 'show']);
    Route::post('/statusselesai', [App\Http\Controllers\Api\PesananSelesaiController::class, 'store']);
    Route::put('/statusselesai/{id}', [App\Http\Controllers\Api\PesananSelesaiController::class, 'update']);
    Route::delete('/statusselesai/{id}', [App\Http\Controllers\Api\PesananSelesaiController::class, 'destroy']);

    //laporan perbulanan
    // Route::get('/monthlysales', [App\Http\Controllers\Api\LaporanPerbulanController::class, 'index']);
    // Route::get('/monthlysales/{id}', [App\Http\Controllers\Api\LaporanPerbulanController::class, 'show']);
    // Route::post('/monthlysales', [App\Http\Controllers\Api\LaporanPerbulanController::class, 'store']);
    // Route::put('/monthlysales/{id}', [App\Http\Controllers\Api\LaporanPerbulanController::class, 'update']);
    // Route::delete('/monthlysales/{id}', [App\Http\Controllers\Api\LaporanPerbulanController::class, 'destroy']);
});
