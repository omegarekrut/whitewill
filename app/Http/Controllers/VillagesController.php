<?php

namespace App\Http\Controllers;

use App\Models\Village;
use Illuminate\Http\Request;

class VillagesController extends Controller
{
    public function index(Request $request)
    {
        $houses = Village::all();

        return response()->json($houses);
    }

    public function store(Request $request)
    {
        $house = new Village();
        $house->fill($request->all());
        $house->save();

        return response()->json(['message' => 'House created successfully', 'data' => $house], 201);
    }

    public function update(Request $request, $id)
    {
        $house = Village::findOrFail($id);
        $house->fill($request->all());
        $house->save();

        return response()->json(['message' => 'House updated successfully', 'data' => $house], 200);
    }

    public function destroy($id)
    {
        $house = Village::findOrFail($id);
        $house->delete();

        return response()->json(['message' => 'House deleted successfully'], 204);
    }
}
