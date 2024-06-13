<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pemesanan;
use Illuminate\Support\Facades\Log;
use App\Helpers\FirebaseHelper;

class ProsesPesananController extends Controller
{
    public function index()
    {
        try {
            $pemesanan = Pemesanan::with('user')
                ->where('STATUS', 'Diproses')
                ->get()
                ->each(function ($pesanan) {
                    if ($pesanan->BUKTI_BAYAR) {
                        $pesanan->BUKTI_BAYAR = base64_encode($pesanan->BUKTI_BAYAR);
                    }
                });

            // Mengirim notifikasi ke semua pengguna yang pesanan mereka diproses
            $firebaseHelper = new FirebaseHelper();
            foreach ($pemesanan as $pesanan) {
                if ($pesanan->fcm) {
                    Log::info("Mengirim notifikasi ke token: " . $pesanan->fcm);
                    $firebaseHelper->sendNotification($pesanan->fcm, 'Pesanan Diproses', 'Pesanan Anda sedang diproses');
                } else {
                    Log::warning("Token FCM tidak ditemukan untuk pemesanan ID: " . $pesanan->id);
                }
            }

            return response([
                "status" => true,
                'message' => 'All Pesanan Retrieved',
                'data' => $pemesanan
            ], 200);
        } catch (\Exception $e) {
            Log::error('Failed to retrieve or encode pemesanan: ' . $e->getMessage());
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
            $pemesanan = Pemesanan::find($id);
            if (!$pemesanan) {
                return response()->json(['message' => 'Pesanan tidak ditemukan'], 404);
            }

            $validatedData = $request->validate([
                'STATUS' => 'in:siap di pick up,sedang dikirim',
            ]);

            $pemesanan->update([
                'STATUS' => $validatedData['STATUS']
            ]);

            // Mengirim notifikasi setelah status diperbarui
            if ($pemesanan->fcm) {
                Log::info("Mengirim notifikasi ke token: " . $pemesanan->fcm);
                $firebaseHelper = new FirebaseHelper();
                $firebaseHelper->sendNotification($pemesanan->fcm, 'Status Pesanan Diperbarui', 'Status pesanan Anda telah diperbarui ke: ' . $validatedData['STATUS']);
            } else {
                Log::warning("Token FCM tidak ditemukan untuk pemesanan ID: " . $pemesanan->id);
            }

            return response()->json([
                'status' => true,
                'message' => 'Status pesanan berhasil diupdate',
                'data' => $pemesanan
            ], 200);
        } catch (\Exception $e) {
            Log::error('Failed to update pemesanan: ' . $e->getMessage());
            return response()->json([
                "status" => false,
                "message" => $e->getMessage(),
                "data" => []
            ], 400);
        }
    }

    public function store(Request $request)
    {
        try {
            // Tambahkan logika untuk menyimpan pesanan baru
            $validatedData = $request->validate([
                // Validasi data yang masuk
            ]);

            $pemesanan = Pemesanan::create($validatedData);

            // Mengirim notifikasi setelah pesanan baru dibuat
            if ($pemesanan->fcm) {
                Log::info("Mengirim notifikasi ke token: " . $pemesanan->fcm);
                $firebaseHelper = new FirebaseHelper();
                $firebaseHelper->sendNotification($pemesanan->fcm, 'Pesanan Baru', 'Pesanan baru telah dibuat');
            } else {
                Log::warning("Token FCM tidak ditemukan untuk pemesanan ID: " . $pemesanan->id);
            }

            return response()->json([
                'status' => true,
                'message' => 'Pesanan berhasil dibuat',
                'data' => $pemesanan
            ], 201);
        } catch (\Exception $e) {
            Log::error('Failed to create pemesanan: ' . $e->getMessage());
            return response()->json([
                "status" => false,
                "message" => $e->getMessage(),
                "data" => []
            ], 400);
        }
    }
}
