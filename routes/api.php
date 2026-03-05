<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CharacterController;
use App\Http\Controllers\InventoryMovementController;
use App\Http\Controllers\ItemController;

// PUBLIC ROUTES
// Authentication
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Items
Route::get('/items', [ItemController::class, 'index']);
Route::get('/items/{item}', [ItemController::class, 'show']);

// PROTECTED ROUTES
Route::middleware('auth:sanctum')->group(function () {
    // Example route to get the authenticated user's information
    Route::get('/me', function (Request $request) {
        return $request->user();
    });

    // Inventory
    Route::post('/inventory-movements', [InventoryMovementController::class, 'store']);

    // Characters
    Route::apiResource('characters', CharacterController::class);
    Route::get('/characters/{character}/inventory', [InventoryMovementController::class, 'inventory']);
    Route::get('/characters/{character}/equipment', [CharacterController::class, 'equipment']);

    // Items
    Route::post('/items', [ItemController::class, 'store']);
    Route::put('/items/{item}', [ItemController::class, 'update']);
    Route::delete('/items/{item}', [ItemController::class, 'destroy']);
});
