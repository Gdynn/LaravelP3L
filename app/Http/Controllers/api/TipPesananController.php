<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Pemesanan;
use Illuminate\Http\Request;

class TipPesananController extends Controller
{
    public function index()
    {
        try {
            // Memuat pemesanan dan relasi user yang terkait
            $pemesanan = Pemesanan::with('user')->whereNotNull('TIP')->get();

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
}
