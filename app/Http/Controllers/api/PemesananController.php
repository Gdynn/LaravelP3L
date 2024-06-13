<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UploadBuktiBayarRequest;
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
use App\Models\PenggunaanBahanBaku;
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

            if (!$pemesanan)
                throw new \Exception("Pesanan tidak ditemukan");

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

        $lastId = $lastOrder ? (int) substr($lastOrder->ID_PEMESANAN, 6) : 0;
        $newId = str_pad($lastId + 1, 3, '0', STR_PAD_LEFT);

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

    public function uploadBuktiBayar(UploadBuktiBayarRequest $request, $idPemesanan)
    {
        \Log::info('Request received for uploadBuktiBayar');
        \Log::info($request->all());

        $pemesanan = Pemesanan::findOrFail($idPemesanan);

        if ($request->hasFile('BUKTI_BAYAR')) {
            // Store the file
            $file = $request->file('BUKTI_BAYAR');
            $path = $file->store('bukti_bayar', 'public');

            // Update the pemesanan record
            $pemesanan->BUKTI_BAYAR = $path;
            $pemesanan->TOTAL = $request->input('TOTAL');
            $pemesanan->save();

            return response()->json([
                'message' => 'Payment proof uploaded successfully!',
                'fileUrl' => Storage::url($path)
            ], 200);
        }

        \Log::warning('No file uploaded');
        return response()->json(['message' => 'No file uploaded'], 400);
    }

    public function indexDiterima()
    {
        try {
            $besok = Carbon::now()->addDay()->format('Y-m-d:00:00:00');

            $pemesanan = Pemesanan::where('STATUS', 'Diterima')
                ->whereDate('TANGGAL_AMBIL', $besok)
                ->get();

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

    public function updateStatusToDiproses($id)
    {
        DB::beginTransaction();
        try {
            $pemesanan = Pemesanan::with(['detailPemesananProduk.produk', 'detailPemesananHampers.hampers'])->findOrFail($id);

            if (!$this->checkBahanBakuStock($pemesanan)) {
                throw new \Exception("Stok bahan baku tidak mencukupi untuk memproses pesanan ini.");
            }

            $pemesanan->STATUS = 'Diproses';
            $pemesanan->save();

            $this->reduceBahanBakuStock($pemesanan);
            $this->recordBahanBakuUsage($pemesanan); // Record the usage of raw materials

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Status berhasil diubah menjadi Diproses',
                'data' => $pemesanan
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
                'data' => []
            ], 400);
        }
    }

    protected function checkBahanBakuStock($pemesanan)
    {
        foreach ($pemesanan->detailPemesananProduk as $detailProduk) {
            $produk = $detailProduk->produk;
            $resepDetails = DetailResep::where('ID_PRODUK', $produk->ID_PRODUK)->get();

            foreach ($resepDetails as $detailResep) {
                $bahanBaku = BahanBaku::find($detailResep->ID_BAHAN_BAKU);
                if ($bahanBaku && $bahanBaku->STOK < ($detailResep->PENGGUNAAN_STOK * $detailProduk->KUANTITAS)) {
                    return false;
                }
            }
        }

        foreach ($pemesanan->detailPemesananHampers as $detailHampers) {
            $hamper = $detailHampers->hampers;
            $detailHampersProduk = DetailHampers::where('ID_HAMPERS', $hamper->ID_HAMPERS)->get();

            foreach ($detailHampersProduk as $detailHamperProduk) {
                $produk = Produk::find($detailHamperProduk->ID_PRODUK);
                $resepDetails = DetailResep::where('ID_PRODUK', $produk->ID_PRODUK)->get();

                foreach ($resepDetails as $detailResep) {
                    $bahanBaku = BahanBaku::find($detailResep->ID_BAHAN_BAKU);
                    if ($bahanBaku && $bahanBaku->STOK < ($detailResep->PENGGUNAAN_STOK * $detailHampers->KUANTITAS)) {
                        return false;
                    }
                }
            }
        }
        return true;
    }

    protected function reduceBahanBakuStock($pemesanan)
    {
        foreach ($pemesanan->detailPemesananProduk as $detailProduk) {
            $produk = $detailProduk->produk;
            $resepDetails = DetailResep::where('ID_PRODUK', $produk->ID_PRODUK)->get();

            foreach ($resepDetails as $detailResep) {
                $bahanBaku = BahanBaku::find($detailResep->ID_BAHAN_BAKU);
                if ($bahanBaku) {
                    $bahanBaku->STOK -= ($detailResep->PENGGUNAAN_STOK * $detailProduk->KUANTITAS);
                    $bahanBaku->save();
                }
            }
        }

        foreach ($pemesanan->detailPemesananHampers as $detailHampers) {
            $hamper = $detailHampers->hampers;
            $detailHampersProduk = DetailHampers::where('ID_HAMPERS', $hamper->ID_HAMPERS)->get();

            foreach ($detailHampersProduk as $detailHamperProduk) {
                $produk = Produk::find($detailHamperProduk->ID_PRODUK);
                $resepDetails = DetailResep::where('ID_PRODUK', $produk->ID_PRODUK)->get();

                foreach ($resepDetails as $detailResep) {
                    $bahanBaku = BahanBaku::find($detailResep->ID_BAHAN_BAKU);
                    if ($bahanBaku) {
                        $bahanBaku->STOK -= ($detailResep->PENGGUNAAN_STOK * $detailHampers->KUANTITAS);
                        $bahanBaku->save();
                    }
                }
            }
        }
    }

    public function getBahanBakuUsage($id)
    {
        try {
            $pemesanan = Pemesanan::with(['detailPemesananProduk.produk', 'detailPemesananHampers.hampers'])->findOrFail($id);

            $bahanBakuUsage = [];
            $produkList = [];
            $hampersList = [];

            // Loop through detail_pemesanan_produk
            foreach ($pemesanan->detailPemesananProduk as $detailProduk) {
                $produk = $detailProduk->produk;
                $produkList[] = [
                    'ID_PRODUK' => $produk->ID_PRODUK,
                    'NAMA_PRODUK' => $produk->NAMA_PRODUK,
                    'KUANTITAS' => $detailProduk->KUANTITAS,
                    'HARGA' => $detailProduk->HARGA
                ];

                $resepDetails = DetailResep::where('ID_PRODUK', $produk->ID_PRODUK)->get();
                foreach ($resepDetails as $detailResep) {
                    $bahanBaku = BahanBaku::find($detailResep->ID_BAHAN_BAKU);
                    if ($bahanBaku) {
                        if (!isset($bahanBakuUsage[$bahanBaku->ID_BAHAN_BAKU])) {
                            $bahanBakuUsage[$bahanBaku->ID_BAHAN_BAKU] = [
                                'NAMA_BAHAN_BAKU' => $bahanBaku->NAMA_BAHAN_BAKU,
                                'TOTAL_PENGGUNAAN' => 0
                            ];
                        }

                        $penggunaanStok = $detailResep->PENGGUNAAN_STOK;

                        // Check if product name contains '1/2'
                        if (strpos($produk->NAMA_PRODUK, '1/2') !== false) {
                            if ($detailProduk->KUANTITAS == 1) {
                                $penggunaanStok = $detailResep->PENGGUNAAN_STOK;
                            } else {
                                $penggunaanStok = $detailResep->PENGGUNAAN_STOK / 2;
                            }
                        }

                        $bahanBakuUsage[$bahanBaku->ID_BAHAN_BAKU]['TOTAL_PENGGUNAAN'] += ($penggunaanStok * $detailProduk->KUANTITAS);
                    }
                }
            }

            // Loop through detail_pemesanan_hampers
            foreach ($pemesanan->detailPemesananHampers as $detailHampers) {
                $hamper = $detailHampers->hampers;
                $hampersList[] = [
                    'ID_HAMPERS' => $hamper->ID_HAMPERS,
                    'NAMA_HAMPERS' => $hamper->NAMA_HAMPERS,
                    'KUANTITAS' => $detailHampers->KUANTITAS,
                    'HARGA' => $detailHampers->HARGA
                ];

                $detailHampersProduk = DetailHampers::where('ID_HAMPERS', $hamper->ID_HAMPERS)->get();
                foreach ($detailHampersProduk as $detailHamperProduk) {
                    $produk = Produk::find($detailHamperProduk->ID_PRODUK);
                    $resepDetails = DetailResep::where('ID_PRODUK', $produk->ID_PRODUK)->get();
                    foreach ($resepDetails as $detailResep) {
                        $bahanBaku = BahanBaku::find($detailResep->ID_BAHAN_BAKU);
                        if ($bahanBaku) {
                            if (!isset($bahanBakuUsage[$bahanBaku->ID_BAHAN_BAKU])) {
                                $bahanBakuUsage[$bahanBaku->ID_BAHAN_BAKU] = [
                                    'NAMA_BAHAN_BAKU' => $bahanBaku->NAMA_BAHAN_BAKU,
                                    'TOTAL_PENGGUNAAN' => 0
                                ];
                            }

                            $penggunaanStok = $detailResep->PENGGUNAAN_STOK;

                            // Check if product name contains '1/2'
                            if (strpos($produk->NAMA_PRODUK, '1/2') !== false) {
                                if ($detailHampers->KUANTITAS == 1) {
                                    $penggunaanStok = $detailResep->PENGGUNAAN_STOK;
                                } else {
                                    $penggunaanStok = $detailResep->PENGGUNAAN_STOK / 2;
                                }
                            }

                            $bahanBakuUsage[$bahanBaku->ID_BAHAN_BAKU]['TOTAL_PENGGUNAAN'] += ($penggunaanStok * $detailHampers->KUANTITAS);
                        }
                    }
                }
            }

            return response()->json([
                'status' => true,
                'message' => 'Bahan baku usage retrieved successfully',
                'data' => [
                    'bahan_baku' => array_values($bahanBakuUsage),
                    'produk' => $produkList,
                    'hampers' => $hampersList
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
                'data' => []
            ], 400);
        }
    }

    private function recordBahanBakuUsage($pemesanan)
    {
        $bahanBakuUsage = [];

        // Loop through detail_pemesanan_produk
        foreach ($pemesanan->detailPemesananProduk as $detailProduk) {
            $produk = $detailProduk->produk;

            $resepDetails = DetailResep::where('ID_PRODUK', $produk->ID_PRODUK)->get();
            foreach ($resepDetails as $detailResep) {
                $bahanBaku = BahanBaku::find($detailResep->ID_BAHAN_BAKU);
                if ($bahanBaku) {
                    if (!isset($bahanBakuUsage[$bahanBaku->ID_BAHAN_BAKU])) {
                        $bahanBakuUsage[$bahanBaku->ID_BAHAN_BAKU] = [
                            'ID_BAHAN_BAKU' => $bahanBaku->ID_BAHAN_BAKU,
                            'KUANTITAS' => 0,
                            'TANGGAL' => Carbon::now()
                        ];
                    }
                    $bahanBakuUsage[$bahanBaku->ID_BAHAN_BAKU]['KUANTITAS'] += ($detailResep->PENGGUNAAN_STOK * $detailProduk->KUANTITAS);
                }
            }
        }

        // Loop through detail_pemesanan_hampers
        foreach ($pemesanan->detailPemesananHampers as $detailHampers) {
            $hamper = $detailHampers->hampers;

            $detailHampersProduk = DetailHampers::where('ID_HAMPERS', $hamper->ID_HAMPERS)->get();
            foreach ($detailHampersProduk as $detailHamperProduk) {
                $produk = Produk::find($detailHamperProduk->ID_PRODUK);
                $resepDetails = DetailResep::where('ID_PRODUK', $produk->ID_PRODUK)->get();
                foreach ($resepDetails as $detailResep) {
                    $bahanBaku = BahanBaku::find($detailResep->ID_BAHAN_BAKU);
                    if ($bahanBaku) {
                        if (!isset($bahanBakuUsage[$bahanBaku->ID_BAHAN_BAKU])) {
                            $bahanBakuUsage[$bahanBaku->ID_BAHAN_BAKU] = [
                                'ID_BAHAN_BAKU' => $bahanBaku->ID_BAHAN_BAKU,
                                'KUANTITAS' => 0,
                                'TANGGAL' => Carbon::now()
                            ];
                        }
                        $bahanBakuUsage[$bahanBaku->ID_BAHAN_BAKU]['KUANTITAS'] += ($detailResep->PENGGUNAAN_STOK * $detailHampers->KUANTITAS);
                    }
                }
            }
        }

        // Insert the usage data into the database
        foreach ($bahanBakuUsage as $usage) {
            PenggunaanBahanBaku::create($usage);
        }
    }
}
