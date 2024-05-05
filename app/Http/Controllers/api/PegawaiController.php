<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pegawai;
use Illuminate\Support\Facades\Validator;

class PegawaiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $pegawai = Pegawai::all();

            return response([
                "status" => true,
                'message' => 'All Pegawai Retrieved',
                'data' => $pegawai
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => $e->getMessage(),
                "data" => []
            ], 400);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->all();

        $validate = Validator::make($data, [
            'NAMA_PEGAWAI' => 'required',
            'NOTELP_PEGAWAI' => 'required',
            'ALAMAT' => 'required',
            'EMAIL' => 'required',
        ]);

        if ($validate->fails()) {
            return response(['message' => $validate->errors()->first()], 400);
        }

        $pegawai = Pegawai::create($data);

        return response([
            'message' => 'Pegawai created successfully',
            'data' => $pegawai
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $pegawai = Pegawai::find($id);

            if (!$pegawai) throw new \Exception("Pegawai tidak ditemukan");

            return response()->json([
                "status" => true,
                "message" => 'Berhasil menampilkan data',
                "data" => $pegawai
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => $e->getMessage(),
                "data" => []
            ], 400);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $pegawai = Pegawai::find($id);

            if (!$pegawai) throw new \Exception("Pegawai tidak ditemukan");


            $pegawai->update($request->all());

            return response()->json([
                "status" => true,
                "message" => 'Berhasil Update Pegawai',
                "data" => $pegawai
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => $e->getMessage(),
                "data" => []
            ], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $pegawai = Pegawai::find($id);

            if (!$pegawai) throw new \Exception("Pegawai Tidak Ditemukan!");

            $pegawai->delete();

            return response()->json([
                "status" => true,
                "message" => 'Pegawai deleted successfully',
                "data" => $pegawai
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => $e->getMessage(),
                "data" => []
            ], 400);
        }
    }
}
