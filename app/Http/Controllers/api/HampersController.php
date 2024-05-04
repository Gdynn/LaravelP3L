<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Hampers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HampersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $hampers = Hampers::all();

        return response([
            'message' => 'All Hampers Retrieved',
            'data' => $hampers
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->all();

        $validate = Validator::make($data, [
            'NAMA_HAMPERS' => 'required',
            'KETERANGAN' => 'required',
            'HARGA' => 'required',
        ]);

        if ($validate->fails()) {
            return response(['message' => $validate->errors()->first()], 400);
        }

        $hampers = Hampers::create($data);

        return response([
            'message' => 'Hampers created successfully',
            'data' => $hampers
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $hampers = Hampers::find($id);

        if (!$hampers) {
            return response(['message' => 'Hampers not found'], 404);
        }

        return response([
            'message' => 'Hampers Retrieved',
            'data' => $hampers
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $hampers = Hampers::find($id);

        if (!$hampers) {
            return response(['message' => 'Hampers not found'], 404);
        }

        $data = $request->all();

        $validate = Validator::make($data, [
            'NAMA_HAMPERS' => 'required',
            'KETERANGAN' => 'required',
            'HARGA' => 'required',
        ]);

        if ($validate->fails()) {
            return response(['message' => $validate->errors()->first()], 400);
        }

        $hampers->update($data);

        return response([
            'message' => 'Hampers updated successfully',
            'data' => $hampers
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $hampers = Hampers::find($id);

        if (!$hampers) {
            return response(['message' => 'Hampers not found'], 404);
        }

        $hampers->delete();

        return response(['message' => 'Hampers deleted successfully'], 200);
    }
}
