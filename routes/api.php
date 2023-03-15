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
    Route::post('/houses/{id}', [HousesController::class, 'update']);
    Route::delete('/houses/{id}', [HousesController::class, 'destroy']);
    Route::put('/houses/upload/{id}', [HousesController::class, 'upload']);
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/village', [VillagesController::class, 'index']);
    Route::post('/village/create', [VillagesController::class, 'store']);
    Route::put('/village/{id}', [VillagesController::class, 'update']);
    Route::delete('/village/{id}', [VillagesController::class, 'destroy']);
});
