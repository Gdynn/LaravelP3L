<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->all();

        $validate = Validator::make($data, [
            'NAMA_PRODUK' => 'required',
            'ID_RESEP' => 'required',
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
