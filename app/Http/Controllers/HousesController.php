<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\House;

class HousesController extends Controller
{
    public function index(Request $request)
    {
        $houses = House::all();

        return response()->json($houses);
    }

    public function store(Request $request)
    {
        $house = new House();
        $house->fill($request->all());
        $house->save();

        return response()->json(['message' => 'House created successfully', 'data' => [$house]], 201);
    }

    public function update(Request $request, $id)
    {
        // Get the updated data for the row with the given ID
        $data = $request->input('data.' . $id);

        // Check if the $data variable is not null
        if (!$data) {
            return response()->json(['error' => 'No data provided for the specified ID'], 400);
        }

        // Update the House model with the new data
        $house = House::findOrFail($id);
        $house->fill($data);
        $house->save();

        // Return a JSON response indicating success
        return response()->json(['message' => 'House updated successfully', 'data' => [$house]], 200);
    }


    public function destroy($id)
    {
        $house = House::findOrFail($id);
        $house->delete();

        return response()->json(['message' => 'House deleted successfully'], 204);
    }

    public function upload(Request $request)
    {
        $houseId = $request->input('id');
        $house = House::findOrFail($houseId);

        if ($request->hasFile('image_gallery')) {
            $files = $request->file('image_gallery');
            $filenames = [];

            foreach ($files as $file) {
                $filename = $file->getClientOriginalName();
                $path = 'images/houses/' . $houseId . '/' . $filename;

                $file->storeAs('public/' . $path);
                $filenames[] = $path;
            }

            $house->image_gallery = implode(',', $filenames);
            $house->save();
        }

        return response()->json(['message' => 'Images uploaded successfully', 'data' => $house]);
    }

}
