<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CharacterController;
use App\Http\Controllers\InventoryMovementController;
use App\Http\Controllers\ItemController;

// PUBLIC ROUTES
// Authentication endpoints
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Public Item endpoints
Route::get('/items', [ItemController::class, 'index']);
Route::get('/items/{item}', [ItemController::class, 'show']);

// PROTECTED ROUTES
Route::middleware('auth:sanctum')->group(function () {
    // Retrieve authenticated user information
    Route::get('/me', function (Request $request) {
        return $request->user();
    });

    // Inventory management
    Route::post('/inventory-movements', [InventoryMovementController::class, 'store']);

    // Character management and specific inventory/equipment views
    Route::apiResource('characters', CharacterController::class);
    Route::get('/characters/{character}/inventory', [InventoryMovementController::class, 'inventory']);
    Route::get('/characters/{character}/equipment', [InventoryMovementController::class, 'equipment']);

    // Administrative Item management
    Route::post('/items', [ItemController::class, 'store']);
    Route::put('/items/{item}', [ItemController::class, 'update']);
    Route::delete('/items/{item}', [ItemController::class, 'destroy']);
});
