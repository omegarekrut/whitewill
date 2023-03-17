<?php

namespace App\Http\Controllers;

use App\Models\FileData;
use Illuminate\Http\Request;
use App\Models\House;

class HousesController extends Controller
{
    public function index(Request $request)
    {
        $houses = House::all();
        $fileData = FileData::all()->pluck(null, 'id')->toArray();
        $responseArray = [
            'data' => $houses,
            'files' => [
                'files' => $fileData,
            ]
        ];
        return response()->json($responseArray);
    }

    public function upload(Request $request)
    {
        $file = $request->file('upload');

        $filename = $file->getClientOriginalName();
        $path = 'images/houses/' . $filename;

        $file->storeAs('public/' . $path);

        // Save file data to the file_data table
        $fileData = FileData::create([
            'filename' => $filename,
            'filesize' => $file->getSize(),
            'web_path' => asset('storage/' . $path),
            'system_path' => storage_path('app/public/' . $path),
        ]);

        $id = $fileData->id;

        // Return the uploaded file details in the specified JSON format
        return response()->json([
            'files' => [
                'files' => [
                    $id => [
                        'id' => $id,
                        'filename' => $filename,
                        'filesize' => $file->getSize(),
                        'web_path' => asset('storage/' . $path),
                        'system_path' => storage_path('app/public/' . $path),
                    ],
                ],
            ],
            'upload' => [
                'id' => $id,
            ],
        ]);
    }



    public function store(Request $request)
    {
        $house = new House();
        $house->fill($request->all());
        $house->save();

        return response()->json(['message' => 'House created successfully', 'data' => [$house]], 201);
    }

    public function update(Request $request)
    {
        // Parse the JSON data from the request body
        $jsonData = json_decode($request->getContent(), true);

        // Get the ID and updated data from the JSON data
        $id = key($jsonData['data']);
        $data = $jsonData['data'][$id];

        // Update the House model with the new data
        $house = House::findOrFail($id);
        $house->fill($data);

        $house->save();

        // Return a JSON response indicating success
        return response()->json(['message' => 'House updated successfully', 'data' => [$house]], 200);
    }

    public function destroy(Request $request)
    {
        // Parse the JSON data from the request body
        $jsonData = json_decode($request->getContent(), true);

        // Get the ID from the JSON data
        $id = $jsonData['id'];

        // Delete the House model with the given ID
        $house = House::findOrFail($id);
        $house->delete();

        return response()->json(['message' => 'House deleted successfully'], 204);
    }
}
