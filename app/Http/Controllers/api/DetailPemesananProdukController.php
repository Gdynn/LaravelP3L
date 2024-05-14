<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DetailPemesananProduk;
use Illuminate\Support\Facades\Validator;

class DetailPemesananProdukController extends Controller
{
    public function index()
    {
        $detailPemesananProduk = DetailPemesananProduk::with(['pemesanan', 'produk'])->get();

        return response([
            'message' => 'All Detail Pemesanan Produk Retrieved',
            'data' => $detailPemesananProduk
        ], 200);
    }

    public function store(Request $request)
    {
        $data = $request->all();

        $validate = Validator::make($data, [
            'ID_PEMESANAN' => 'required',
            'ID_PRODUK' => 'required',
            'KUANTITAS' => 'required',
            'HARGA' => 'required',
        ]);

        if ($validate->fails()) {
            return response(['message' => $validate->errors()->first()], 400);
        }

        $detailPemesananProduk = DetailPemesananProduk::create($data);

        return response([
            'message' => 'Detail Pemesanan Produk created successfully',
            'data' => $detailPemesananProduk
        ], 200);
    }

    public function show($id)
    {
        $detailPemesananProduk = DetailPemesananProduk::with(['pemesanan', 'produk'])->find($id);

        if (!$detailPemesananProduk) {
            return response(['message' => 'Detail Pemesanan Produk not found'], 404);
        }

        return response([
            'message' => 'Detail Pemesanan Produk retrieved successfully',
            'data' => $detailPemesananProduk
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $detailPemesananProduk = DetailPemesananProduk::find($id);

        if (!$detailPemesananProduk) {
            return response(['message' => 'Detail Pemesanan Produk not found'], 404);
        }

        $data = $request->all();

        $validate = Validator::make($data, [
            'ID_PEMESANAN' => 'required',
            'ID_PRODUK' => 'required',
            'KUANTITAS' => 'required',
            'HARGA' => 'required',
        ]);

        if ($validate->fails()) {
            return response(['message' => $validate->errors()->first()], 400);
        }
        $detailPemesananProduk->update($data);
        return response([
            'message' => 'Detail Pemesanan Produk updated successfully',
            'data' => $detailPemesananProduk
        ], 200);
    }

    public function destroy($id)
    {
        $detailPemesananProduk = DetailPemesananProduk::find($id);

        if (!$detailPemesananProduk) {
            return response(['message' => 'Detail Pemesanan Produk not found'], 404);
        }

        $detailPemesananProduk->delete();

        return response(['message' => 'Detail Pemesanan Produk deleted successfully'], 200);
    }
}
