<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
Route::get('/users/{id}', [App\Http\Controllers\Api\UserController::class, 'showById']);

Route::middleware('auth:api')->group(function () {
    //user
    Route::get('/users', [App\Http\Controllers\Api\UserController::class, 'showAll']);
    Route::post('/users/update', [App\Http\Controllers\Api\UserController::class, 'updateProfile']);
    Route::post('/usersUpdate/{id}', [App\Http\Controllers\Api\UserController::class, 'updateUser']);
    Route::get('/userLogin', [App\Http\Controllers\Api\UserController::class, 'showByLogin']);
    Route::delete('/users/{id}', [App\Http\Controllers\Api\UserController::class, 'destroy']);
    
    //item
    Route::get('/item', [App\Http\Controllers\Api\ItemController::class, 'showAll']);
    Route::get('/item/{id}', [App\Http\Controllers\Api\ItemController::class, 'showById']);
    Route::post('/item', [App\Http\Controllers\Api\ItemController::class, 'store']);
    Route::put('/item/{id}', [App\Http\Controllers\Api\ItemController::class, 'update']);
    Route::delete('/item/{id}', [App\Http\Controllers\Api\ItemController::class, 'destroy']);

    //produk
    Route::get('/produk', [App\Http\Controllers\Api\ProdukController::class, 'index']);
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
});
