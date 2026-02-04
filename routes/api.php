<?php

use App\Http\Controllers\Api\CategoryController;
use Illuminate\Support\Facades\Route;

Route::get('/ping', function () {
    return response()->json([
        'ok' => true,
        'message' => 'GAMETIX API OK'
    ]);
});

Route::get('/categories', [CategoryController::class, 'index']);
Route::delete('/categories/{id}', [CategoryController::class, 'delete']);
