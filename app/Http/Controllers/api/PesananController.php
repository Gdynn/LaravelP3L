<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pemesanan;
use Illuminate\Support\Facades\Validator;

class PesananController extends Controller
{

    public function index()
    {
        try {
            // Memuat pemesanan dan relasi user yang terkait
            $pemesanan = Pemesanan::with('user')->whereNotNull('JARAK')->get();

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
            $pemesanan = Pemesanan::whereNotNull('JARAK')->get();

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

    public function store(Request $request)
    {
        $data = $request->validate([
            'ID_USER' => 'required',
            'TANGGAL_PESAN' => 'required|date',
            // asumsikan semua validasi yang diperlukan
            'JARAK' => 'required|numeric',
            'TOTAL' => 'required|numeric',
        ]);

        // Menghitung ongkir berdasarkan jarak
        $additionalFee = $this->calculateDeliveryFee($data['JARAK']);
        $data['TOTAL'] += $additionalFee;  // Menambahkan ongkir ke total

        $pemesanan = Pemesanan::create($data);

        return response()->json([
            'status' => true,
            'message' => 'Pesanan berhasil dibuat',
            'data' => $pemesanan
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $pemesanan = Pemesanan::find($id);
        if (!$pemesanan) {
            return response()->json(['message' => 'Pesanan tidak ditemukan'], 404);
        }

        $data = $request->all();
        $additionalFee = $this->calculateDeliveryFee($data['JARAK'] ?? $pemesanan->JARAK);
        $data['TOTAL'] = ($data['TOTAL'] ?? $pemesanan->TOTAL) + $additionalFee;

        $pemesanan->update($data);

        return response()->json([
            'status' => true,
            'message' => 'Pesanan berhasil diupdate',
            'data' => $pemesanan
        ], 200);
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
