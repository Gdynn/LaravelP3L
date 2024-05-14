<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DetailPemesananHampers;
use Illuminate\Support\Facades\Validator;

class DetailPemesananHampersController extends Controller
{
    public function index()
    {
        $detailPemesananHampers = DetailPemesananHampers::with(['pemesanan', 'hampers'])->get();

        return response([
            'message' => 'All Detail Pemesanan Hampers Retrieved',
            'data' => $detailPemesananHampers
        ], 200);
    }

    public function store(Request $request)
    {
        $data = $request->all();

        $validate = Validator::make($data, [
            'ID_PEMESANAN' => 'required',
            'ID_HAMPERS' => 'required',
            'KUANTITAS' => 'required',
            'HARGA' => 'required',
        ]);

        if ($validate->fails()) {
            return response(['message' => $validate->errors()->first()], 400);
        }

        $detailPemesananHampers = DetailPemesananHampers::create($data);

        return response([
            'message' => 'Detail Pemesanan Hampers created successfully',
            'data' => $detailPemesananHampers
        ], 200);
    }

    public function show($id)
    {
        $detailPemesananHampers = DetailPemesananHampers::with(['pemesanan', 'hampers'])->find($id);

        if (!$detailPemesananHampers) {
            return response(['message' => 'Detail Pemesanan Hampers not found'], 404);
        }

        return response([
            'message' => 'Detail Pemesanan Hampers retrieved successfully',
            'data' => $detailPemesananHampers
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $detailPemesananHampers = DetailPemesananHampers::find($id);

        if (!$detailPemesananHampers) {
            return response(['message' => 'Detail Pemesanan Hampers not found'], 404);
        }

        $data = $request->all();

        $validate = Validator::make($data, [
            'ID_PEMESANAN' => 'required',
            'ID_HAMPERS' => 'required',
            'KUANTITAS' => 'required',
            'HARGA' => 'required',
        ]);

        if ($validate->fails()) {
            return response(['message' => $validate->errors()->first()], 400);
        }

        $detailPemesananHampers->update($data);

        return response([
            'message' => 'Detail Pemesanan Hampers updated successfully',
            'data' => $detailPemesananHampers
        ], 200);
    }

    public function destroy($id)
    {
        $detailPemesananHampers = DetailPemesananHampers::find($id);

        if (!$detailPemesananHampers) {
            return response(['message' => 'Detail Pemesanan Hampers not found'], 404);
        }

        $detailPemesananHampers->delete();

        return response(['message' => 'Detail Pemesanan Hampers deleted successfully'], 200);
    }
}
