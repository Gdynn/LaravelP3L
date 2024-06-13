<?php

// App\Http\Controllers\api\LaporanPerbulanController.php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pemesanan;
use Illuminate\Support\Facades\Log;

class LaporanPerbulanController extends Controller
{
    public function getMonthlySales()
    {
        $monthlySales = Pemesanan::getMonthlySales();

        // Pastikan selalu mengembalikan array JSON
        return response()->json($monthlySales->toArray());
    }
}
