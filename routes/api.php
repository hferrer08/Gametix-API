<?php

use App\Http\Controllers\Api\CategoryController;
use Illuminate\Support\Facades\Route;


Route::get('/ping', function () {
    return response()->json([
        'ok' => true,
        'message' => 'GAMETIX API OK'
    ]);
});

//Category Routes
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/categories/{id}', [CategoryController::class, 'getById']);
Route::post('/categories', [CategoryController::class, 'create']);
Route::put('/categories/{id}', [CategoryController::class, 'update']);
Route::delete('/categories/{id}', [CategoryController::class, 'delete']);
Route::put('/categories/{id}/restore', [CategoryController::class, 'restore']);
