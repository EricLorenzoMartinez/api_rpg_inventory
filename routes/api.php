<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CharacterController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Todas las rutas dentro de este grupo requieren que el usuario esté logueado
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('characters', CharacterController::class);
});
