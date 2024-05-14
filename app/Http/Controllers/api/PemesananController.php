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
        $promoPoin = Pemesanan::all();

        return response([
            'message' => 'All Pemesanan Poin Retrieved',
            'data' => $promoPoin
        ], 200);
    }
}
