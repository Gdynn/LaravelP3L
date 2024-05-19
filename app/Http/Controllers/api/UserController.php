<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Pemesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Exception;

class UserController extends Controller
{
    public function showAll()
    {
        $users = User::all();

        return response([
            'message' => 'All Users Retrieved',
            'data' => $users
        ], 200);
    }

    public function showByIdUserAlamat($id)
    {
        // Use User::with('alamat')->find($id) to find the user with the specific id and eager load the related alamat
        $user = User::with('alamat')->find($id);

        if (!$user) {
            return response(['message' => 'User not found'], 404);
        }

        $result = [
            'ID_USER' => $user->id_user, // Change to 'id_user' based on your model
            'NAMA_USER' => $user->username, // Change to 'username' based on your model
            'EMAIL' => $user->email,
            'NO_TELP' => $user->notelp,
            'POIN' => $user->poin,
            'TANGGAL_LAHIR' => $user->tanggal_lahir,
            'ALAMAT' => $user->alamat->map(function ($alamat) {
                return [
                    'ID_ALAMAT' => $alamat->ID_ALAMAT,
                    'NAMA_ALAMAT' => $alamat->NAMA_ALAMAT,
                    'ALAMAT' => $alamat->ALAMAT,
                ];
            })
        ];

        return response([
            'message' => 'Show User Successfully',
            'data' => $result
        ], 200);
    }

    public function showByLogin()
    {
        $user = User::with('alamat')->find(auth()->id());

        if (!$user) {
            return response(['message' => 'User not found'], 404);
        }

        $result = [
            'ID_USER' => $user->id_user, // Change to 'id_user' based on your model
            'NAMA_USER' => $user->username, // Change to 'username' based on your model
            'EMAIL' => $user->email,
            'NO_TELP' => $user->notelp,
            'POIN' => $user->poin,
            'ALAMAT' => $user->alamat->map(function ($alamat) {
                return [
                    'ID_ALAMAT' => $alamat->ID_ALAMAT,
                    'NAMA_ALAMAT' => $alamat->NAMA_ALAMAT,
                    'ALAMAT' => $alamat->ALAMAT,
                ];
            })
        ];

        return response([
            'message' => 'Show User Successfully',
            'data' => $result
        ], 200);
    }

    public function getUnpaidOrders()
    {
        $userId = auth()->id();

        $unpaidOrders = Pemesanan::where('ID_USER', $userId)
            ->where('STATUS', 'Belum Dibayar')
            ->with([
                'detailPemesananProduk.produk',
                'detailPemesananHampers.hampers'
            ])
            ->get();

        if ($unpaidOrders->isEmpty()) {
            return response([
                'message' => 'No unpaid orders found',
                'data' => []
            ], 404);
        }

        $result = $unpaidOrders->map(function ($order) {
            return [
                'ID_PEMESANAN' => $order->ID_PEMESANAN,
                'ID_USER' => $order->ID_USER,
                'TANGGAL_PESAN' => $order->TANGGAL_PESAN,
                'TANGGAL_LUNAS' => $order->TANGGAL_LUNAS,
                'TANGGAL_AMBIL' => $order->TANGGAL_AMBIL,
                'STATUS' => $order->STATUS,
                'TOTAL' => $order->TOTAL,
                'BUKTI_BAYAR' => $order->BUKTI_BAYAR,
                'JUMLAH_BAYAR' => $order->JUMLAH_BAYAR,
                'JARAK' => $order->JARAK,
                'DELIVERY' => $order->DELIVERY,
                'ALAMAT' => $order->ALAMAT,
                'DETAIL_PEMESANAN_PRODUK' => $order->detailPemesananProduk->map(function ($detail) {
                    return [
                        'ID_DETAIL_PEMESANAN_PRODUK' => $detail->ID_DETAIL_PRODUK,
                        'ID_PEMESANAN' => $detail->ID_PEMESANAN,
                        'ID_PRODUK' => $detail->ID_PRODUK,
                        'KUANTITAS' => $detail->KUANTITAS,
                        'HARGA' => $detail->HARGA,
                        'PRODUK' => [
                            'ID_PRODUK' => $detail->produk->ID_PRODUK,
                            'NAMA_PRODUK' => $detail->produk->NAMA_PRODUK,
                            'HARGA' => $detail->produk->HARGA,
                            'JENIS_PRODUK' => $detail->produk->JENIS_PRODUK,
                            'KUANTITAS' => $detail->produk->KUANTITAS,
                        ]
                    ];
                }),
                'DETAIL_PEMESANAN_HAMPERS' => $order->detailPemesananHampers->map(function ($detail) {
                    return [
                        'ID_DETAIL_PEMESANAN_HAMPERS' => $detail->ID_DETAIL_HAMPERS,
                        'ID_PEMESANAN' => $detail->ID_PEMESANAN,
                        'ID_HAMPERS' => $detail->ID_HAMPERS,
                        'KUANTITAS' => $detail->KUANTITAS,
                        'HARGA' => $detail->HARGA,
                        'HAMPERS' => [
                            'ID_HAMPERS' => $detail->hampers->ID_HAMPERS,
                            'NAMA_HAMPERS' => $detail->hampers->NAMA_HAMPERS,
                            'HARGA' => $detail->hampers->HARGA,
                            'KETERANGAN' => $detail->hampers->KETERANGAN,
                        ]
                    ];
                })
            ];
        });

        return response([
            'message' => 'Unpaid orders retrieved successfully',
            'data' => $result
        ], 200);
    }

    public function updatePoin(Request $request, $id)
    {
        try {
            // Validate request
            $validated = $request->validate([
                'poin' => 'required|numeric'
            ]);

            // Find the user by ID
            $user = User::findOrFail($id);

            // Update poin
            $user->update([
                'poin' => $validated['poin']
            ]);

            return response()->json(['message' => 'Poin updated successfully'], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (Exception $e) {
            \Log::error('Failed to update poin: ' . $e->getMessage());
            return response()->json([
                'message' => 'Failed to update poin',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}