<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\LostFoundController;
use App\Http\Controllers\Api\GateController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::middleware('auth:sanctum')->group(function () {

    Route::get('/user', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::apiResource('reports', ReportController::class);

    Route::apiResource('lost-found', LostFoundController::class);

    Route::get('/gates', [GateController::class, 'index']);
    Route::get('/gates/{id}', [GateController::class, 'show']);
    Route::post('/gates/{id}/update', [GateController::class, 'update']);
});
