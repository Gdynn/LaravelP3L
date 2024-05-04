<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DetailHampers;
use Illuminate\Support\Facades\Validator;

class DetailHampersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $detailHampers = DetailHampers::all();
        $detailHampers = DetailHampers::with(['produk', 'hampers'])->get();

        return response([
            'message' => 'All Detail Hampers Retrieved',
            'data' => $detailHampers
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->all();

        $validate = Validator::make($data, [
            'ID_HAMPERS' => 'required',
            'ID_PRODUK' => 'required',
        ]);

        if ($validate->fails()) {
            return response(['message' => $validate->errors()->first()], 400);
        }

        $detailHampers = DetailHampers::create($data);

        return response([
            'message' => 'Detail Hampers created successfully',
            'data' => $detailHampers
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // $detailHampers = DetailHampers::find($id);
        $detailHampers = DetailHampers::with(['produk', 'hampers'])->find($id);

        if (!$detailHampers) {
            return response(['message' => 'Detail Hampers not found'], 404);
        }

        return response([
            'message' => 'Detail Hampers retrieved successfully',
            'data' => $detailHampers
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // $detailHampers = DetailHampers::find($id);
        $detailHampers = DetailHampers::with(['produk', 'hampers'])->find($id);

        if (!$detailHampers) {
            return response(['message' => 'Detail Hampers not found'], 404);
        }

        $data = $request->all();

        $validate = Validator::make($data, [
            'ID_HAMPERS' => 'required',
            'ID_PRODUK' => 'required',
        ]);

        if ($validate->fails()) {
            return response(['message' => $validate->errors()->first()], 400);
        }

        // $detailHampers->ID_HAMPERS = $data['ID_HAMPERS'];
        // $detailHampers->ID_PRODUK = $data['ID_PRODUK'];

        $detailHampers->update($data);

        // $detailHampers->save();

        return response([
            'message' => 'Detail Hampers updated successfully',
            'data' => $detailHampers
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // $detailHampers = DetailHampers::find($id);
        $detailHampers = DetailHampers::with(['produk', 'hampers'])->find($id);

        if (!$detailHampers) {
            return response(['message' => 'Detail Hampers not found'], 404);
        }

        $detailHampers->delete();

        return response(['message' => 'Detail Hampers deleted successfully'], 200);
    }
}
