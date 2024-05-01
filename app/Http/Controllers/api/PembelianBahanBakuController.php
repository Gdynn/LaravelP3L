<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PembelianBahanBaku;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class PembelianBahanBakuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pembelianBahanBaku = PembelianBahanBaku::all();

        return response([
            'message' => 'All Pembelian Bahan Baku Retrieved',
            'data' => $pembelianBahanBaku
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->all();

        $validate = Validator::make($data, [
            'KUANTITAS' => 'required',
            'HARGA' => 'required',
            'TANGGAL' => 'required',
        ]);

        if ($validate->fails()) {
            return response(['message' => $validate->errors()->first()], 400);
        }

        $pembelianBahanBaku = PembelianBahanBakuController::create($data);

        return response([
            'message' => 'Pembelian Bahan Baku created successfully',
            'data' => $pembelianBahanBaku
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $pembelianBahanBaku = PembelianBahanBaku::find($id);

        if (!$pembelianBahanBaku) {
            return response(['message' => 'Pembelian Bahan Baku not found'], 404);
        }

        return response([
            'message' => 'Pembelian Bahan Baku retrieved successfully',
            'data' => $pembelianBahanBaku
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $pembelianBahanBaku = PembelianBahanBaku::find($id);

        if (!$pembelianBahanBaku) {
            return response(['message' => 'Pembelian Bahan Baku not found'], 404);
        }

        $data = $request->all();

        $validate = Validator::make($data, [
            'KUANTITAS' => 'required',
            'HARGA' => 'required',
            'TANGGAL' => 'required',
        ]);

        if ($validate->fails()) {
            return response(['message' => $validate->errors()->first()], 400);
        }

        $pembelianBahanBaku->update($data);

        return response([
            'message' => 'Pembelian Bahan Baku updated successfully',
            'data' => $pembelianBahanBaku
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $pembelianBahanBaku = PembelianBahanBaku::find($id);

        if (!$pembelianBahanBaku) {
            return response(['message' => 'Pembelian Bahan Baku not found'], 404);
        }

        $pembelianBahanBaku->delete();

        return response(['message' => 'Pembelian Bahan Baku deleted successfully'], 200);
    }
}
