<?php

namespace App\Http\Controllers;

use App\Models\FileData;
use App\Models\Village;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class VillagesController extends Controller
{
    public function index(Request $request)
    {
        $villages = Village::all();
        $fileData = FileData::all()->pluck(null, 'id')->toArray();
        $responseArray = [
            'data' => $villages,
            'files' => [
                'files' => $fileData,
            ],
            'user_role' => Auth::user()->role,
        ];
        return response()->json($responseArray);
    }

    public function index_list(Request $request)
    {
        $villages = Village::all();
        return response()->json($villages);
    }

    public function upload(Request $request)
    {

        try {
            $request->validate([
                'upload' => 'required|file|mimes:jpeg,png|max:2048', // Add this line to validate the file
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'error' => 'Only JPG and PNG files are allowed. Maximum file size is 2MB.',
            ], 400);
        }

        $file = $request->file('upload');

        $filename = $file->getClientOriginalName();
        $path = 'images/village/' . $filename;

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

    public function upload_file(Request $request)
    {

        try {
            $request->validate([
                'upload' => 'required|file|mimes:pdf|max:10128', // Add this line to validate the file
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'error' => 'Only JPG and PNG files are allowed. Maximum file size is 10MB.',
            ], 400);
        }

        $file = $request->file('upload');

        $filename = $file->getClientOriginalName();
        $path = 'images/village/' . $filename;

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
        $village = new Village();
        $village->fill($request->all());
        $village->save();

        return response()->json(['message' => 'Village created successfully', 'data' => [$village]], 201);
    }

    public function update(Request $request)
    {
        // Parse the JSON data from the request body
        $jsonData = json_decode($request->getContent(), true);

        // Get the ID and updated data from the JSON data
        $id = key($jsonData['data']);
        $data = $jsonData['data'][$id];

        // Update the Village model with the new data
        $village = Village::findOrFail($id);
        $village->fill($data);

        $village->save();

        // Return a JSON response indicating success
        return response()->json(['message' => 'Village updated successfully', 'data' => [$village]], 200);
    }

    public function destroy(Request $request)
    {

        $jsonData = json_decode($request->getContent(), true);

        // Get the ID from the JSON data
        $id = $jsonData['id'];

        // Delete the Village model with the given ID
        $village = Village::findOrFail($id);
        $village->delete();

        return response()->json(['message' => 'Village deleted successfully'], 204);
    }

}
