<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Resep;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ResepController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $resep = Resep::all();

        return response([
            'message' => 'All Reseps Retrieved',
            'data' => $resep
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->all();

        $validate = Validator::make($data, [
            'NAMA_RESEP' => 'required',
            'KUANTITAS' => 'required',
        ]);

        if ($validate->fails()) {
            return response(['message' => $validate->errors()->first()], 400);
        }

        $resep = Resep::create($data);

        return response([
            'message' => 'Resep created successfully',
            'data' => $resep
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $resep = Resep::find($id);

        if (!$resep) {
            return response(['message' => 'Resep not found'], 404);
        }

        return response([
            'message' => 'Show Resep Successfully',
            'data' => $resep
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $resep = Resep::find($id);

        if (!$resep) {
            return response(['message' => 'Resep not found'], 404);
        }

        $data = $request->all();

        $validate = Validator::make($data, [
            'NAMA_RESEP' => 'required',
            'KUANTITAS' => 'required',
        ]);

        if ($validate->fails()) {
            return response(['message' => $validate->errors()->first()], 400);
        }

        $resep->update($data);

        return response([
            'message' => 'Resep updated successfully',
            'data' => $resep
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $resep = Resep::find($id);

        if (!$resep) {
            return response(['message' => 'Resep not found'], 404);
        }

        $resep->delete();

        return response(['message' => 'Resep deleted successfully'], 200);
    }
}
