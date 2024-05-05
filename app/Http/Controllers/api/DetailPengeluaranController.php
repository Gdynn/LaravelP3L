<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DetailPengeluaran;
use Illuminate\Support\Facades\Validator;

class DetailPengeluaranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $detailPengeluaran = DetailPengeluaran::all();
        $detailPengeluaran = DetailPengeluaran::with(['pembelianBahanBaku', 'bahanBaku'])->get();

        return response([
            'message' => 'All Pembelian Bahan Baku Retrieved',
            'data' => $detailPengeluaran
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->all();

        $validate = Validator::make($data, [
            'ID_PEMBELIAN' => 'required',
            'ID_BAHAN_BAKU' => 'required',
        ]);

        if ($validate->fails()) {
            return response(['message' => $validate->errors()->first()], 400);
        }

        $detailPengeluaran = DetailPengeluaran::create($data);

        return response([
            'message' => 'Detail Pengeluaran created successfully',
            'data' => $detailPengeluaran
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // $detailPengeluaran = DetailPengeluaran::find($id);
        $detailPengeluaran = DetailPengeluaran::with(['pembelianBahanBaku', 'bahanBaku'])->find($id);

        if (!$detailPengeluaran) {
            return response(['message' => 'Detail Pengeluaran not found'], 404);
        }

        return response([
            'message' => 'Detail Pengeluaran retrieved successfully',
            'data' => $detailPengeluaran
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // $detailPengeluaran = DetailPengeluaran::find($id);
        $detailPengeluaran = DetailPengeluaran::with(['pembelianBahanBaku', 'bahanBaku'])->find($id);

        if (!$detailPengeluaran) {
            return response(['message' => 'Detail Pengeluaran not found'], 404);
        }

        $data = $request->all();

        $validate = Validator::make($data, [
            'ID_BAHAN_BAKU' => 'required',
        ]);

        if ($validate->fails()) {
            return response(['message' => $validate->errors()->first()], 400);
        }

        // $detailPengeluaran->ID_PEMBELIAN = $data['ID_PEMBELIAN'];
        // $detailPengeluaran->ID_BAHAN_BAKU = $data['ID_BAHAN_BAKU'];

        // $detailPengeluaran->save();

        $detailPengeluaran->update($data);

        return response([
            'message' => 'Detail Pengeluaran updated successfully',
            'data' => $detailPengeluaran
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // $detailPengeluaran = DetailPengeluaran::find($id);
        $detailPengeluaran = DetailPengeluaran::with(['pembelianBahanBaku', 'bahanBaku'])->find($id);

        if (!$detailPengeluaran) {
            return response(['message' => 'Detail Pengeluaran not found'], 404);
        }

        $detailPengeluaran->delete();

        return response(['message' => 'Detail Pengeluaran deleted successfully'], 200);
    }
}
