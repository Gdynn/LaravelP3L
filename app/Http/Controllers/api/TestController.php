<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TestController extends Controller
{
    public function readFirebaseConfig()
    {
        // Path ke file JSON
        $path = storage_path('app/firebase/pushhnotif-7e106-firebase-adminsdk-7fe9h-1ae7f33070.json');

        Log::info("Mencoba membaca file JSON dari path: $path");

        // Cek apakah file ada
        if (file_exists($path)) {
            // Baca konten file
            $content = file_get_contents($path);
            // Decode konten JSON
            $json = json_decode($content, true);
            // Cek apakah decoding JSON berhasil
            if (json_last_error() === JSON_ERROR_NONE) {
                Log::info("File JSON berhasil dibaca dan diparse.");
                return response()->json(['message' => 'File dapat dibaca', 'content' => $json]);
            } else {
                $error = json_last_error_msg();
                Log::error("Error parsing JSON: $error");
                return response()->json(['message' => 'Error parsing JSON', 'error' => $error]);
            }
        } else {
            Log::error("File tidak ditemukan di path: $path");
            return response()->json(['message' => 'File tidak ditemukan']);
        }
    }
}
