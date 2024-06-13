<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pemesanan;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

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

        // Menambahkan token FCM ke data pemesanan
        $data['fcm'] = 'd6eOsNmeTKiSYOlLVE3UNY:APA91bHNnQ5-eQQH9k-K4CjDDBJBt-qY2J-SaM2l4TZcsxgf6gdi1q5z9X05_EUg8pyKX0TPP2XdhVaywetBJpYoK52ZY4a61sENgJVo_PuV3GdXbrmYduL5JCQ6Dabd53uxqYzo2-6G';

        Log::info('Data yang akan disimpan: ' . json_encode($data));

        $pemesanan = Pemesanan::create($data);

        Log::info('Pemesanan berhasil dibuat dengan ID: ' . $pemesanan->ID_PEMESANAN);

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

        // Menambahkan token FCM ke data pemesanan jika belum ada
        if (!isset($data['fcm']) || empty($data['fcm'])) {
            $data['fcm'] = 'd6eOsNmeTKiSYOlLVE3UNY:APA91bHNnQ5-eQQH9k-K4CjDDBJBt-qY2J-SaM2l4TZcsxgf6gdi1q5z9X05_EUg8pyKX0TPP2XdhVaywetBJpYoK52ZY4a61sENgJVo_PuV3GdXbrmYduL5JCQ6Dabd53uxqYzo2-6G';
        }

        Log::info('Data yang akan diupdate: ' . json_encode($data));

        $pemesanan->update($data);

        Log::info('Pemesanan berhasil diupdate dengan ID: ' . $pemesanan->ID_PEMESANAN);

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
