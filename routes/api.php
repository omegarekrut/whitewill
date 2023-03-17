<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HousesController;
use App\Http\Controllers\VillagesController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/houses', [HousesController::class, 'index']);
    Route::post('/houses/create', [HousesController::class, 'store']);
    Route::post('/houses/update', [HousesController::class, 'update']);
    Route::post('/houses/delete', [HousesController::class, 'destroy']);
    Route::post('/houses/upload-image', [HousesController::class, 'upload']);
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/village', [VillagesController::class, 'index']);
    Route::post('/village/list', [VillagesController::class, 'index_list']);
    Route::post('/village/create', [VillagesController::class, 'store']);
    Route::post('/village/update', [VillagesController::class, 'update']);
    Route::post('/village/delete', [VillagesController::class, 'destroy']);
    Route::post('/village/upload-image', [VillagesController::class, 'upload']);
    Route::post('/village/upload-file', [VillagesController::class, 'upload_file']);
});
