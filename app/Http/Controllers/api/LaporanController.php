<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pemesanan;
use App\Models\DetailPemesananHampers;
use App\Models\DetailPemesananProduk;
use App\Models\Hampers;
use App\Models\Produk;
use App\Models\LimitHarian;
use App\Models\DetailResep;
use App\Models\BahanBaku;
use App\Models\DetailHampers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    public function laporanPenjualanBulanan(Request $request, $bulan, $tahun)
    {
        try {
            // Validasi bulan dan tahun
            if (!checkdate($bulan, 1, $tahun)) {
                return response()->json(['error' => 'Bulan atau tahun tidak valid.'], 400);
            }

            // Status yang diizinkan
            $statusDiterima = ['Diterima', 'Diproses', 'Siap di-pickup', 'Sudah di-pickup', 'Selesai'];

            // Mengambil data produk yang terjual selama bulan tertentu dengan status tertentu
            $penjualan = DB::table('detail_pemesanan_produk')
                ->join('pemesanan', 'detail_pemesanan_produk.ID_PEMESANAN', '=', 'pemesanan.ID_PEMESANAN')
                ->join('produk', 'detail_pemesanan_produk.ID_PRODUK', '=', 'produk.ID_PRODUK')
                ->select(
                    'produk.NAMA_PRODUK as produk',
                    DB::raw('SUM(detail_pemesanan_produk.KUANTITAS) as kuantitas'),
                    'detail_pemesanan_produk.HARGA as harga',
                    DB::raw('SUM(detail_pemesanan_produk.KUANTITAS * detail_pemesanan_produk.HARGA) as jumlah_uang')
                )
                ->whereMonth('pemesanan.TANGGAL_PESAN', $bulan)
                ->whereYear('pemesanan.TANGGAL_PESAN', $tahun)
                ->whereIn('pemesanan.STATUS', $statusDiterima)
                ->groupBy('produk.NAMA_PRODUK', 'detail_pemesanan_produk.HARGA')
                ->get();

            // Mengembalikan hasil dalam format JSON
            return response()->json($penjualan);
        } catch (Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan saat mengambil data.'], 500);
        }
    }
}

