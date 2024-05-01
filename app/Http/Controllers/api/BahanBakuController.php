<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BahanBaku;
use Illuminate\Support\Facades\Validator;

class BahanBakuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bahanBaku = BahanBaku::all();

        return response([
            'message' => 'All Bahan Baku Retrieved',
            'data' => $bahanBaku
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->all();

        $validate = Validator::make($data, [
            'NAMA_BAHAN_BAKU' => 'required',
        ]);

        if ($validate->fails()) {
            return response(['message' => $validate->errors()->first()], 400);
        }

        $bahanBaku = BahanBaku::create($data);

        return response([
            'message' => 'Bahan Baku created successfully',
            'data' => $bahanBaku
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $bahanBaku = BahanBaku::find($id);

        if (!$bahanBaku) {
            return response(['message' => 'Bahan Baku not found'], 404);
        }

        return response([
            'message' => 'Bahan Baku retrieved successfully',
            'data' => $bahanBaku
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $bahanBaku = BahanBaku::find($id);

        if (!$bahanBaku) {
            return response(['message' => 'Bahan Baku not found'], 404);
        }

        $data = $request->all();

        $validate = Validator::make($data, [
            'NAMA_BAHAN_BAKU' => 'required',
            'STOK' => 'required',
        ]);

        if ($validate->fails()) {
            return response(['message' => $validate->errors()->first()], 400);
        }

        $bahanBaku->update($data);

        return response([
            'message' => 'Bahan Baku updated successfully',
            'data' => $bahanBaku
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $bahanBaku = BahanBaku::find($id);

        if (!$bahanBaku) {
            return response(['message' => 'Bahan Baku not found'], 404);
        }

        $bahanBaku->delete();

        return response(['message' => 'Bahan Baku deleted successfully'], 200);
    }
}
