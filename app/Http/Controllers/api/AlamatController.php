<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Alamat;
use Illuminate\Support\Facades\Validator;

class AlamatController extends Controller
{
    public function index()
    {
        $alamat = Alamat::all();

        return response([
            'message' => 'All Alamat Retrieved',
            'data' => $alamat
        ], 200);
    }

    public function store(Request $request)
    {
        $data = $request->all();

        $validate = Validator::make($data, [
            'NAMA_ALAMAT' => 'required',
            'ALAMAT' => 'required',
        ]);

        if ($validate->fails()) {
            return response(['message' => $validate->errors()->first()], 400);
        }

        $alamat = Alamat::create($data);

        return response([
            'message' => 'Alamat created successfully',
            'data' => $alamat
        ], 200);
    }

    public function show($id)
    {
        $alamat = Alamat::find($id);

        if (!$alamat) {
            return response(['message' => 'Alamat not found'], 404);
        }

        return response([
            'message' => 'Alamat retrieved successfully',
            'data' => $alamat
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $alamat = Alamat::find($id);

        if (!$alamat) {
            return response(['message' => 'Alamat not found'], 404);
        }

        $data = $request->all();

        $validate = Validator::make($data, [
            'NAMA_ALAMAT' => 'required',
            'ALAMAT' => 'required',
        ]);

        if ($validate->fails()) {
            return response(['message' => $validate->errors()->first()], 400);
        }

        $alamat->update($data);

        return response([
            'message' => 'Alamat updated successfully',
            'data' => $alamat
        ], 200);
    }

    public function destroy($id)
    {
        $alamat = Alamat::find($id);

        if (!$alamat) {
            return response(['message' => 'Alamat not found'], 404);
        }

        $alamat->delete();

        return response(['message' => 'Alamat deleted successfully'], 200);
    }
}
