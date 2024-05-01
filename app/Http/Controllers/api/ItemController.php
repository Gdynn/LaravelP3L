<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ItemController extends Controller
{
    public function showAll()
    {
        $items = Item::all();

        return response([
            'message' => 'All Items Retrieved',
            'data' => $items
        ], 200);
    }

    public function showById($id)
    {
        $item = Item::find($id);

        if (!$item) {
            return response(['message' => 'Item not found'], 404);
        }

        return response([
            'message' => 'Show Item Successfully',
            'data' => $item
        ], 200);
    }

    public function store(Request $request)
    {
        $data = $request->all();

        $validate = Validator::make($data, [
            'nama_item' => 'required',
            'harga' => 'required',
            'deskripsi' => 'required',
        ]);

        if ($validate->fails()) {
            return response(['message' => $validate->errors()->first()], 400);
        }

        $item = Item::create($data);

        return response([
            'message' => 'Item created successfully',
            'data' => $item
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $item = Item::find($id);

        if (!$item) {
            return response(['message' => 'Item not found'], 404);
        }

        $data = $request->all();

        $validate = Validator::make($data, [
            'nama_item' => 'required',
            'harga' => 'required',
            'deskripsi' => 'required',
        ]);

        if ($validate->fails()) {
            return response(['message' => $validate->errors()->first()], 400);
        }

        $item->update($data);

        return response([
            'message' => 'Item updated successfully',
            'data' => $item
        ], 200);
    }

    public function destroy($id)
    {
        $item = Item::find($id);

        if (!$item) {
            return response(['message' => 'Item not found'], 404);
        }

        $item->delete();

        return response(['message' => 'Item deleted successfully'], 200);
    }
}
