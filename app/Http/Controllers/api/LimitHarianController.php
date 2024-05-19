<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LimitHarian;
use Illuminate\Support\Facades\Validator;

class LimitHarianController extends Controller
{
    public function index()
    {
        $limitHarian = LimitHarian::with(['produk'])->get();

        return response([
            'message' => 'All Limit Harian Produk Retrieved',
            'data' => $limitHarian
        ], 200);
    }

    public function store(Request $request)
    {
        $data = $request->all();

        $validate = Validator::make($data, [
            'ID_PRODUK' => 'required',
            'TANGGAL' => 'required',
            'LIMIT_HARIAN' => 'required',
        ]);

        if ($validate->fails()) {
            return response(['message' => $validate->errors()->first()], 400);
        }

        $limitHarian = LimitHarian::create($data);

        return response([
            'message' => 'Limit Harian Produk created successfully',
            'data' => $limitHarian
        ], 200);
    }

    public function show($id)
    {
        $limitHarian = LimitHarian::with(['produk'])->find($id);

        if (!$limitHarian) {
            return response(['message' => 'Limit Harian Produk not found'], 404);
        }

        return response([
            'message' => 'Limit Harian Produk retrieved successfully',
            'data' => $limitHarian
        ], 200);
    }

    public function searchByHariIni()
    {
        $currentDate = date('Y-m-d');
        $limitHarian = LimitHarian::with(['produk'])
            ->where('TANGGAL', $currentDate)
            ->get();
        return response([
            'message' => 'Limit Harian Produk with current date retrieved',
            'data' => $limitHarian
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $limitHarian = LimitHarian::find($id);

        if (!$limitHarian) {
            return response(['message' => 'Limit Harian Produk not found'], 404);
        }

        $data = $request->all();

        $validate = Validator::make($data, [
            'ID_PRODUK' => 'required',
            'TANGGAL' => 'required',
            'LIMIT_HARIAN' => 'required',
        ]);

        if ($validate->fails()) {
            return response(['message' => $validate->errors()->first()], 400);
        }

        $limitHarian->update($data);

        return response([
            'message' => 'Limit Harian Produk updated successfully',
            'data' => $limitHarian
        ], 200);
    }

    public function destroy($id)
    {
        $limitHarian = LimitHarian::find($id);

        if (!$limitHarian) {
            return response(['message' => 'Limit Harian Produk not found'], 404);
        }

        $limitHarian->delete();

        return response(['message' => 'Limit Harian Produk deleted successfully'], 200);
    }
}
