<?php

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CompaniaController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\Api\ProveedorProductController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MovimientoStockController;
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

// Rutas para gestionar la relación Proveedor-Product
Route::get('proveedores/{id_proveedor}/products', [ProveedorProductController::class, 'index']);
Route::post('proveedores/{id_proveedor}/products', [ProveedorProductController::class, 'store']);
Route::delete('proveedores/{id_proveedor}/products/{product_id}', [ProveedorProductController::class, 'destroy']);
Route::put('proveedores/{id_proveedor}/products', [ProveedorProductController::class, 'sync']);

//Rutas Auth
Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/register', [AuthController::class, 'register']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    //Movimiento stock
    Route::apiResource('movimiento-stock', MovimientoStockController::class);
});

