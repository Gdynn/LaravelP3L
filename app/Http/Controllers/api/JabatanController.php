<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Jabatan;

class JabatanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $jabatan = Jabatan::all();

            return response([
                "status" => true,
                'message' => 'All Jabatans Retrieved',
                'data' => $jabatan
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
            $jabatan = Jabatan::create($request->all());
            return response()->json([
                "status" => true,
                "message" => 'Berhasil Membuat data',
                "data" => $jabatan
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
            $jabatan = Jabatan::find($id);

            if (!$jabatan) throw new \Exception("Jabatan tidak ditemukan");

            return response()->json([
                "status" => true,
                "message" => 'Berhasil menampilkan data',
                "data" => $jabatan
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
            $jabatan = Jabatan::find($id);

            if (!$jabatan) throw new \Exception("Resep tidak ditemukan");

            $jabatan->update($request->all());

            return response()->json([
                "status" => true,
                "message" => 'Berhasil Update Jabatan',
                "data" => $jabatan
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
            $jabatan = Jabatan::find($id);

            if (!$jabatan) throw new \Exception("Jabatan Tidak Ditemukan!");

            $jabatan->delete();

            return response()->json([
                "status" => true,
                "message" => 'Jabatan deleted successfully',
                "data" => $jabatan
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
