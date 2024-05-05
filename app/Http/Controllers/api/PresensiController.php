<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Presensi;

class PresensiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $presensi = Presensi::all();

            return response([
                "status" => true,
                'message' => 'All Presensi Retrieved',
                'data' => $presensi
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
        try {
            $presensi = Presensi::create($request->all());
            return response()->json([
                "status" => true,
                "message" => 'Berhasil Membuat data',
                "data" => $presensi
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
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $presensi = Presensi::find($id);

            if (!$presensi) throw new \Exception("Presensi tidak ditemukan");

            return response()->json([
                "status" => true,
                "message" => 'Berhasil menampilkan data',
                "data" => $presensi
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
            $presensi = Presensi::find($id);

            if (!$presensi) throw new \Exception("Resep tidak ditemukan");


            $presensi->update($request->all());

            return response()->json([
                "status" => true,
                "message" => 'Berhasil Update Presensi',
                "data" => $presensi
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
            $presensi = Presensi::find($id);

            if (!$presensi) throw new \Exception("Presensi Tidak Ditemukan!");

            $presensi->delete();

            return response()->json([
                "status" => true,
                "message" => 'Presensi deleted successfully',
                "data" => $presensi
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
