<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pemesanan;

class PesananYangDiProsesController extends Controller
{
    public function index()
    {
        try {
            $pemesanan = Pemesanan::with('user')
                ->whereIn('STATUS', ['Siap di pick up', 'Sedang dikirim'])
                ->get();

            return response([
                "status" => true,
                'message' => 'All Pesanan Retrieved',
                'data' => $pemesanan
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => $e->getMessage(),
                "data" => []
            ], 400);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $pemesanan = Pemesanan::where('ID_PEMESANAN', $id)->first();

            if (!$pemesanan) {
                return response()->json([
                    "status" => false,
                    "message" => "Pesanan dengan ID {$id} tidak ditemukan",
                    "data" => []
                ], 404);
            }

            // Validate the incoming status
            $validatedData = $request->validate([
                'STATUS' => 'in:Sudah di pick up,Diambil sendiri',
            ]);

            // Update pemesanan with the validated status
            $pemesanan->update($validatedData);

            return response()->json([
                "status" => true,
                "message" => 'Pesanan berhasil diupdate',
                "data" => $pemesanan
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
