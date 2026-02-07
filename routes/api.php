<?php

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CompaniaController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\ProveedorController;
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

//Compania Routes
Route::apiResource('companias', CompaniaController::class);
Route::post('companias/{id}/restore', [CompaniaController::class, 'restore']);

//Product Routes
Route::apiResource('products', ProductController::class);

//Proveedor Routes
Route::apiResource('proveedores', ProveedorController::class);

Route::patch('proveedores/{id}/desactivar', [ProveedorController::class, 'desactivar']);
Route::patch('proveedores/{id}/activar', [ProveedorController::class, 'activar']);
