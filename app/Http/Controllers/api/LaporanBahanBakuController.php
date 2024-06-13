<?php

// App\Http\Controllers\api\LaporanPengeluaranController.php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DetailPengeluaran;

class LaporanBahanBakuController extends Controller
{
    public function getPengeluaran(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $pengeluaran = DetailPengeluaran::getPengeluaran($startDate, $endDate);

        return response()->json($pengeluaran);
    }
}
