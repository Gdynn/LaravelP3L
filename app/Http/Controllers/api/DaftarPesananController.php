<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pemesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Helpers\FirebaseHelper;

class DaftarPesananController extends Controller
{
    public function index()
    {
        try {
            // Mengambil data pemesanan dimana BUKTI_BAYAR tidak null dan JUMLAH_BAYAR null
            $pemesanan = Pemesanan::with('user')
                ->whereNotNull('BUKTI_BAYAR')
                ->whereNull('JUMLAH_BAYAR')
                ->get();

            // Log the output to check what is being returned
            Log::info(json_encode($pemesanan));

            return response([
                "status" => true,
                'message' => 'All Pesanan Retrieved',
                'data' => $pemesanan
            ], 200);
        } catch (\Exception $e) {
            // Log error if something goes wrong
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
        $validatedData = $request->validate([
            'TANGGAL_PESAN' => 'required',
            'TANGGAL_AMBIL' => 'required',
            'STATUS' => 'required',
            'JARAK' => 'required',
            'DELIVERY' => 'required',
            'TOTAL' => 'required|numeric',
            'JUMLAH_BAYAR' => 'required|numeric', // Memastikan bahwa JUMLAH_BAYAR diisi dan merupakan numerik
        ]);

        try {
            $pemesanan = Pemesanan::where('ID_PEMESANAN', $id)->first();

            if (!$pemesanan) {
                return response()->json([
                    "status" => false,
                    "message" => "Pesanan dengan ID {$id} tidak ditemukan",
                    "data" => []
                ], 404);
            }

            $validatedData['TIP'] = $validatedData['JUMLAH_BAYAR'] - $validatedData['TOTAL'];
            Log::info('Calculated Tip: ' . $validatedData['TIP']); // Menambahkan log untuk debugging

            // Ubah status menjadi 'Diproses'
            $validatedData['STATUS'] = 'Diproses';

            // Update pemesanan dengan data yang telah divalidasi
            $pemesanan->update($validatedData);

            // Mengirim notifikasi setelah status diperbarui
            if ($pemesanan->fcm) {
                Log::info("Mengirim notifikasi ke token: " . $pemesanan->fcm);
                $firebaseHelper = new FirebaseHelper();
                $firebaseHelper->sendNotification($pemesanan->fcm, 'Pesanan Diproses', 'Pesanan Anda sedang diproses');
            } else {
                Log::warning("Token FCM tidak ditemukan untuk pemesanan ID: " . $pemesanan->id);
            }

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
