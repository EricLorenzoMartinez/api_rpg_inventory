<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CharacterController;
use App\Http\Controllers\InventoryMovementController;

// PUBLIC ROUTES
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// PROTECTED ROUTES
Route::middleware('auth:sanctum')->group(function () {
    // Example route to get the authenticated user's information
    Route::get('/me', function (Request $request) {
        return $request->user();
    });
    Route::apiResource('characters', CharacterController::class);
    Route::post('/inventory-movements', [InventoryMovementController::class, 'store']);
    Route::get('/characters/{character}/inventory', [InventoryMovementController::class, 'inventory']);
    Route::get('/characters/{character}/equipment', [InventoryMovementController::class, 'equipment']);
});
