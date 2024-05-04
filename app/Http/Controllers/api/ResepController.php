<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Resep;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;


class ResepController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $resep = Resep::all();

            return response([
                "status" => true,
                'message' => 'All Reseps Retrieved',
                'data' => $resep
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
            $resep = Resep::create($request->all());
            return response()->json([
                "status" => true,
                "message" => 'Berhasil Membuat data',
                "data" => $resep
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
    public function show($id)
    {
        try {
            $resep = Resep::find($id);

            if (!$resep) throw new \Exception("Resep tidak ditemukan");

            return response()->json([
                "status" => true,
                "message" => 'Berhasil menampilkan data',
                "data" => $resep
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
    public function update(Request $request, $id)
    {
        // Tambahkan log ini untuk melihat ID yang sedang dicari
        Log::info("Mencari Resep dengan ID: " . $id);

        try {
            $resep = Resep::find($id);

            if (!$resep) throw new \Exception("Resep tidak ditemukan");

            // Tambahkan log untuk melihat data yang diterima
            Log::info('Data yang diterima:', $request->all());

            $resep->update($request->all());

            return response()->json([
                "status" => true,
                "message" => 'Berhasil Update Resep',
                "data" => $resep
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
    public function destroy($id)
    {
        try {
            $resep = Resep::find($id);

            if (!$resep) throw new \Exception("Resep Tidak Ditemukan!");

            $resep->delete();

            return response()->json([
                "status" => true,
                "message" => 'Resep deleted successfully',
                "data" => $resep
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
