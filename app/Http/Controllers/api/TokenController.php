<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class TokenController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
        ]);

        $user = auth()->user(); // Asumsikan pengguna sudah diautentikasi
        if ($user) {
            $user->fcm_token = $request->token;
            $user->save(); // Simpan token FCM ke database
        } else {
            return response()->json(['message' => 'User not authenticated'], 401);
        }

        return response()->json(['message' => 'Token FCM berhasil disimpan']);
    }
}
