<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class ProdukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Produk::all();

        return response([
            'message' => 'All Products Retrieved',
            'data' => $products
        ], 200);
    }

    public function fetchMobile()
    {
        $today = Carbon::today()->toDateString(); // Get today's date

        $products = Produk::with([
            'limitHarian' => function ($query) use ($today) {
                $query->whereDate('TANGGAL', $today); // Filter by today's date
            }
        ])->get();

        $result = $products->map(function ($product) {
            return [
                'ID_PRODUK' => $product->ID_PRODUK,
                'ID_RESEP' => $product->ID_RESEP,
                'NAMA_PRODUK' => $product->NAMA_PRODUK,
                'KUANTITAS' => $product->KUANTITAS,
                'HARGA' => $product->HARGA,
                'JENIS_PRODUK' => $product->JENIS_PRODUK,
                'limit_harian' => $product->limitHarian->map(function ($limitharian) {
                    return [
                        'ID_LIMIT' => $limitharian->ID_LIMIT,
                        'TANGGAL' => $limitharian->TANGGAL,
                        'LIMIT_KUANTITAS' => $limitharian->LIMIT_KUANTITAS,
                        'STOK_HARI_INI' => $limitharian->STOK_HARI_INI,
                    ];
                }),
            ];
        });

        return response([
            'message' => 'All Products Retrieved',
            'data' => $result
        ], 200);
    }

    public function fetchProdukLimit()
    {
        $products = Produk::with('limitHarian')->get();

        $result = $products->map(function ($product) {
            return [
                'ID_PRODUK' => $product->ID_PRODUK,
                'ID_RESEP' => $product->ID_RESEP,
                'NAMA_PRODUK' => $product->NAMA_PRODUK,
                'KUANTITAS' => $product->KUANTITAS,
                'HARGA' => $product->HARGA,
                'JENIS_PRODUK' => $product->JENIS_PRODUK,
                'limit_harian' => $product->limitHarian->map(function ($limitharian) {
                    return [
                        'ID_LIMIT' => $limitharian->ID_LIMIT,
                        'TANGGAL' => $limitharian->TANGGAL,
                        'LIMIT_KUANTITAS' => $limitharian->LIMIT_KUANTITAS,
                        'STOK_HARI_INI' => $limitharian->STOK_HARI_INI,
                    ];
                }),
            ];
        });

        return response([
            'message' => 'All Products Retrieved',
            'data' => $result
        ], 200);
    }

    public function fetchCake()
    {
        $products = Produk::with('limitHarian')->where('JENIS_PRODUK', 'Cake')->get();

        return response([
            'message' => 'All Cake Retrieved',
            'data' => $products
        ], 200);
    }

    public function fetchRoti()
    {
        $products = Produk::with('limitHarian')->where('JENIS_PRODUK', 'Roti')->get();

        return response([
            'message' => 'All Roti Retrieved',
            'data' => $products
        ], 200);
    }

    public function fetchMinuman()
    {
        $products = Produk::with('limitHarian')->where('JENIS_PRODUK', 'Minuman')->get();

        return response([
            'message' => 'All Minuman Retrieved',
            'data' => $products
        ], 200);
    }

    public function fetchTitipan()
    {
        $products = Produk::with('limitHarian')->where('JENIS_PRODUK', 'Titipan')->get();

        return response([
            'message' => 'All Titipan Retrieved',
            'data' => $products
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->all();

        $validate = Validator::make($data, [
            'NAMA_PRODUK' => 'required',
            'HARGA' => 'required',
            'JENIS_PRODUK' => 'required',
        ]);

        if ($validate->fails()) {
            return response(['message' => $validate->errors()->first()], 400);
        }

        $product = Produk::create($data);

        return response([
            'message' => 'Product created successfully',
            'data' => $product
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $product = Produk::find($id);

        if (!$product) {
            return response(['message' => 'Product not found'], 404);
        }

        return response([
            'message' => 'Show Product Successfully',
            'data' => $product
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $product = Produk::find($id);

        if (!$product) {
            return response(['message' => 'Product not found'], 404);
        }

        $data = $request->all();

        $validate = Validator::make($data, [
            'NAMA_PRODUK' => 'required',
            'HARGA' => 'required',
            'JENIS_PRODUK' => 'required',
        ]);

        if ($validate->fails()) {
            return response(['message' => $validate->errors()->first()], 400);
        }

        $product->update($data);

        return response([
            'message' => 'Product updated successfully',
            'data' => $product
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $product = Produk::find($id);

        if (!$product) {
            return response(['message' => 'Product not found'], 404);
        }

        $product->delete();

        return response(['message' => 'Product deleted successfully'], 200);
    }
}
