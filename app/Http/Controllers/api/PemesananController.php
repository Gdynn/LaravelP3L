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
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class PemesananController extends Controller
{
    public function indexNullJarak()
    {
        try {
            $pemesanan = Pemesanan::with('user')->whereNull('JARAK')->get();

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


    public function showNullJarak(string $id)
    {
        try {
            $pemesanan = Pemesanan::whereNull('JARAK')->get();

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

    public function updateJarak(Request $request, $id)
    {
        // First, validate the incoming request data
        $validatedData = $request->validate([
            'TANGGAL_PESAN' => 'sometimes|date',
            'JARAK' => 'sometimes|numeric',
            'TOTAL' => 'sometimes|numeric',
        ]);

        // $id = '24.05.001'; // Contoh ID yang pasti ada
        $pemesanan = Pemesanan::where('ID_PEMESANAN', $id)->first();

        // Retrieve the first matching record based on ID_PEMESANAN which is a VARCHAR
        // $pemesanan = Pemesanan::where('ID_PEMESANAN', '=', $id)->first();

        if (!$pemesanan) {
            return response()->json([
                "status" => false,
                "message" => "Pesanan dengan ID {$id} tidak ditemukan",
                "data" => []
            ], 404);
        }

        // Check if JARAK is part of the updated fields and recalculate the TOTAL
        if (isset($validatedData['JARAK'])) {
            $additionalFee = $this->calculateDeliveryFee($validatedData['JARAK']);
            $validatedData['TOTAL'] = ($validatedData['TOTAL'] ?? $pemesanan->TOTAL) + $additionalFee;
        }

        // Update the pemesanan with the validated data
        $pemesanan->update($validatedData);

        return response()->json([
            "status" => true,
            "message" => 'Pesanan berhasil diupdate',
            "data" => $pemesanan
        ], 200);
    }


    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'ID_USER' => 'required',
            'TANGGAL_PESAN' => 'required|date',
            'JARAK' => 'required|numeric',
            'TOTAL' => 'required|numeric',  // Assuming this is the base total before adding delivery
        ]);

        // Calculate delivery fee based on JARAK
        $additionalFee = $this->calculateDeliveryFee($validatedData['JARAK']);
        $validatedData['TOTAL'] += $additionalFee;

        try {
            $pemesanan = Pemesanan::create($validatedData);

            return response()->json([
                'status' => true,
                'message' => 'Pesanan berhasil dibuat',
                'data' => $pemesanan
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
                'data' => []
            ], 400);
        }
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

  public function indexPemesanan()
    {
        try {
            $promoPoin = Pemesanan::all();
            return response([
                'message' => 'All Pemesanan Retrieved',
                'data' => $promoPoin
            ], 200);
        } catch (Exception $e) {
            return response([
                'message' => 'Failed to retrieve data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function order(Request $request)
    {
        DB::beginTransaction();
        try {
            // Validasi request
            $validated = $request->validate([
                'ID_USER' => 'required|integer',
                'TANGGAL_AMBIL' => 'required|date',
                'TOTAL' => 'required|numeric',
                'DELIVERY' => 'required|string',
                'ALAMAT' => 'string|nullable',
                'products' => 'array|nullable',
                'hampers' => 'array|nullable',
            ]);

            // Tambahkan TANGGAL_PESAN dan STATUS ke data yang divalidasi
            $validated['TANGGAL_PESAN'] = Carbon::now();
            $validated['STATUS'] = 'Belum Dibayar';

            // Generate ID_PEMESANAN
            $validated['ID_PEMESANAN'] = $this->generateIdPemesanan();
            \Log::info('ID Pemesanan: ' . $validated['ID_PEMESANAN']);

            $id_pesan = $validated['ID_PEMESANAN'];
            $tanggal_ambil = $validated['TANGGAL_AMBIL'];

            // Simpan data pemesanan
            Pemesanan::create($validated);

            // Simpan detail produk
            if (!empty($request->products)) {
                foreach ($request->products as $product) {
                    // Check and create limit_harian if not exists
                    $limit = $this->checkAndCreateLimitHarian($product['ID_PRODUK'], $tanggal_ambil);

                    // Check and update quota
                    if ($limit->LIMIT_KUANTITAS - $product['KUANTITAS'] < 0) {
                        throw new Exception("Quota for product {$product['ID_PRODUK']} on {$tanggal_ambil} is insufficient.");
                    }

                    $limit->LIMIT_KUANTITAS -= $product['KUANTITAS'];
                    $limit->save();

                    // Kurangi kuantitas produk jika jenisnya adalah "titipan"
                    $produk = Produk::find($product['ID_PRODUK']);
                    if ($produk->JENIS_PRODUK === 'Titipan') {
                        if ($produk->KUANTITAS - $product['KUANTITAS'] < 0) {
                            throw new Exception("Insufficient quantity for product {$product['ID_PRODUK']}.");
                        }
                        $produk->KUANTITAS -= $product['KUANTITAS'];
                        $produk->save();
                    }

                    DetailPemesananProduk::create([
                        'ID_PRODUK' => $product['ID_PRODUK'],
                        'ID_PEMESANAN' => $id_pesan,
                        'KUANTITAS' => $product['KUANTITAS'],
                        'HARGA' => $product['HARGA'],
                    ]);
                }
            }

            // Simpan detail hampers
            if (!empty($request->hampers)) {
                foreach ($request->hampers as $hamper) {
                    // Check and create limit_harian if not exists
                    $limit = $this->checkAndCreateLimitHarian($hamper['ID_HAMPERS'], $tanggal_ambil, true);

                    // Check and update quota
                    if ($limit->LIMIT_KUANTITAS - $hamper['KUANTITAS'] < 0) {
                        throw new Exception("Quota for hamper {$hamper['ID_HAMPERS']} on {$tanggal_ambil} is insufficient.");
                    }

                    $limit->LIMIT_KUANTITAS -= $hamper['KUANTITAS'];
                    $limit->save();

                    DetailPemesananHampers::create([
                        'ID_HAMPERS' => $hamper['ID_HAMPERS'],
                        'ID_PEMESANAN' => $id_pesan,
                        'KUANTITAS' => $hamper['KUANTITAS'],
                        'HARGA' => $hamper['HARGA'],
                    ]);
                }
            }

            DB::commit();

            return response()->json(['message' => 'Pemesanan berhasil disimpan'], 201);
        } catch (ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (Exception $e) {
            DB::rollBack();
            \Log::error('Failed to process order: ' . $e->getMessage());
            return response()->json([
                'message' => 'Failed to process order',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    private function checkAndCreateLimitHarian($id, $tanggal, $isHamper = false)
    {
        $model = $isHamper ? Hampers::class : Produk::class;
        $item = $model::find($id);

        if ($item && $item->JENIS_PRODUK !== 'titipan') {
            $existingLimit = LimitHarian::where('ID_PRODUK', $id)
                ->where('TANGGAL', $tanggal)
                ->first();

            if (!$existingLimit) {
                $existingLimit = LimitHarian::create([
                    'ID_PRODUK' => $id,
                    'TANGGAL' => $tanggal,
                    'LIMIT_KUANTITAS' => 15,
                    'STOK_HARI_INI' => 0,
                ]);
            }

            return $existingLimit;
        }

        return null;
    }

    private function generateIdPemesanan()
    {
        $dateNow = Carbon::now();
        $year = $dateNow->format('y');
        $month = $dateNow->format('m');

        $lastOrder = Pemesanan::whereYear('TANGGAL_PESAN', $dateNow->year)
            ->whereMonth('TANGGAL_PESAN', $dateNow->month)
            ->latest('TANGGAL_PESAN')
            ->first();
        \Log::info('Last Order: ' . $lastOrder);

        $lastId = $lastOrder ? (int) substr($lastOrder->ID_PEMESANAN, 6) : 0;
        \Log::info('Last ID: ' . $lastId);
        $newId = str_pad($lastId + 1, 3, '0', STR_PAD_LEFT);
        \Log::info('New ID: ' . $newId);

        return $year . '.' . $month . '.' . $newId;
    }

    public function getPemesananById($id)
    {
        try {
            $pemesanan = Pemesanan::with(['detailPemesananProduk.produk', 'detailPemesananHampers.hampers'])
                ->where('ID_PEMESANAN', $id)
                ->first();

            if (!$pemesanan) {
                return response()->json([
                    'message' => 'Pemesanan not found'
                ], 404);
            }

            return response()->json([
                'message' => 'Pemesanan retrieved successfully',
                'data' => $pemesanan
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve Pemesanan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function uploadBuktiBayar(Request $request, $id)
    {
        try {
            // Validate request
            $validated = $request->validate([
                'TOTAL' => 'required|numeric',
                'BUKTI_BAYAR' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            // Find the pemesanan by ID
            $pemesanan = Pemesanan::findOrFail($id);

            // Handle file upload
            if ($request->hasFile('BUKTI_BAYAR')) {
                $file = $request->file('BUKTI_BAYAR');
                $path = $file->store('bukti_bayar', 'public');
                $validated['BUKTI_BAYAR'] = $path;
            }

            // Update pemesanan
            $pemesanan->update([
                'TOTAL' => $validated['TOTAL'],
                'BUKTI_BAYAR' => $validated['BUKTI_BAYAR'],
                'STATUS' => 'Menunggu Konfirmasi',
            ]);

            return response()->json(['message' => 'Pemesanan updated successfully'], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (Exception $e) {
            \Log::error('Failed to update order: ' . $e->getMessage());
            return response()->json([
                'message' => 'Failed to update order',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}

