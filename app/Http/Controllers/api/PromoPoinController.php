<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PromoPoin;
use Illuminate\Support\Facades\Validator;

class PromoPoinController extends Controller
{
    public function index()
    {
        $promoPoin = PromoPoin::all();

        return response([
            'message' => 'All Promo Poin Retrieved',
            'data' => $promoPoin
        ], 200);
    }

    public function store(Request $request)
    {
        $data = $request->all();

        $validate = Validator::make($data, [
            'NAMA_PROMO' => 'required',
            'POIN' => 'required',
            'HARGA_PEMESANAN' => 'required',
        ]);

        if ($validate->fails()) {
            return response(['message' => $validate->errors()->first()], 400);
        }

        $promoPoin = PromoPoin::create($data);

        return response([
            'message' => 'Promo Poin created successfully',
            'data' => $promoPoin
        ], 200);
    }

    public function show($id)
    {
        $promoPoin = PromoPoin::find($id);

        if (!$promoPoin) {
            return response(['message' => 'Promo Poin not found'], 404);
        }

        return response([
            'message' => 'Promo Poin retrieved successfully',
            'data' => $promoPoin
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $promoPoin = PromoPoin::find($id);

        if (!$promoPoin) {
            return response(['message' => 'Promo Poin not found'], 404);
        }

        $data = $request->all();

        $validate = Validator::make($data, [
            'NAMA_PROMO' => 'required',
            'POIN' => 'required',
            'HARGA_PEMESANAN' => 'required',
        ]);

        if ($validate->fails()) {
            return response(['message' => $validate->errors()->first()], 400);
        }

        $promoPoin->update($data);

        return response([
            'message' => 'Promo Poin updated successfully',
            'data' => $promoPoin
        ], 200);
    }

    public function destroy($id)
    {
        $promoPoin = PromoPoin::find($id);

        if (!$promoPoin) {
            return response(['message' => 'Promo Poin not found'], 404);
        }
        $promoPoin->delete();
        return response([
            'message' => 'Promo Poin deleted successfully'
        ], 200);
    }
}
