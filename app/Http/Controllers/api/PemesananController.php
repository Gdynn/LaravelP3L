<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pemesanan;
use Illuminate\Support\Facades\Validator;

class PemesananController extends Controller
{
    public function index()
    {
        try {
            $pemesanan = Pemesanan::with('user')->whereNull('JARAK')->get();

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


    public function show(string $id)
    {
        try {
            $pemesanan = Pemesanan::whereNull('JARAK')->get();

            if (!$pemesanan) throw new \Exception("Pesanan tidak ditemukan");

            return response()->json([
                "status" => true,
                "message" => 'Berhasil menampilkan data',
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

    public function update(Request $request, $id)
    {
        // First, validate the incoming request data
        $validatedData = $request->validate([
            'TANGGAL_PESAN' => 'sometimes|date',
            'JARAK' => 'sometimes|numeric',
            'TOTAL' => 'sometimes|numeric',
        ]);

        // $id = '24.05.001'; // Contoh ID yang pasti ada
        $pemesanan = Pemesanan::where('ID_PEMESANAN', $id)->first();

        // Retrieve the first matching record based on ID_PEMESANAN which is a VARCHAR
        // $pemesanan = Pemesanan::where('ID_PEMESANAN', '=', $id)->first();

        if (!$pemesanan) {
            return response()->json([
                "status" => false,
                "message" => "Pesanan dengan ID {$id} tidak ditemukan",
                "data" => []
            ], 404);
        }

        // Check if JARAK is part of the updated fields and recalculate the TOTAL
        if (isset($validatedData['JARAK'])) {
            $additionalFee = $this->calculateDeliveryFee($validatedData['JARAK']);
            $validatedData['TOTAL'] = ($validatedData['TOTAL'] ?? $pemesanan->TOTAL) + $additionalFee;
        }

        // Update the pemesanan with the validated data
        $pemesanan->update($validatedData);

        return response()->json([
            "status" => true,
            "message" => 'Pesanan berhasil diupdate',
            "data" => $pemesanan
        ], 200);
    }


    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'ID_USER' => 'required',
            'TANGGAL_PESAN' => 'required|date',
            'JARAK' => 'required|numeric',
            'TOTAL' => 'required|numeric',  // Assuming this is the base total before adding delivery
        ]);

        // Calculate delivery fee based on JARAK
        $additionalFee = $this->calculateDeliveryFee($validatedData['JARAK']);
        $validatedData['TOTAL'] += $additionalFee;

        try {
            $pemesanan = Pemesanan::create($validatedData);

            return response()->json([
                'status' => true,
                'message' => 'Pesanan berhasil dibuat',
                'data' => $pemesanan
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
                'data' => []
            ], 400);
        }
    }

    protected function calculateDeliveryFee($jarak)
    {
        if ($jarak <= 5) {
            return 10000;
        } elseif ($jarak > 5 && $jarak <= 10) {
            return 15000;
        } elseif ($jarak > 10 && $jarak <= 15) {
            return 20000;
        } elseif ($jarak > 15) {
            return 25000;
        }
        return 0;
    }
}
