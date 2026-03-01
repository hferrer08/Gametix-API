<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CompaniaController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\ProveedorProductController;
use App\Http\Controllers\MovimientoStockController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\DetallePedidoController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\ResenaController;
use App\Http\Controllers\CarritoController;
use App\Http\Controllers\DetalleCarritoController;
use App\Http\Controllers\ListaDeseosController;
use App\Http\Controllers\EstadoController;

/* Ping */
Route::get('/ping', function () {
    return response()->json([
        'ok' => true,
        'message' => 'GAMETIX API OK'
    ]);
});

/* 1) RUTAS PÚBLICAS (sin token) */

// Auth
Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/register', [AuthController::class, 'register']);

// Products (público: catálogo)
Route::apiResource('products', ProductController::class)->only(['index', 'show']);

// Categories (público: catálogo)
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/categories/{id}', [CategoryController::class, 'show']);

// Reseñas (público: lectura)
Route::apiResource('resenas', ResenaController::class)->only(['index', 'show']);

//Compañías (público: catálogo)
Route::apiResource('companias', CompaniaController::class)->only(['index', 'show']);


/* 2) RUTAS AUTENTICADAS (token requerido) */
Route::middleware('auth:sanctum')->group(function () {

    // Auth
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/auth/logout', [AuthController::class, 'logout']);

    // USER normal (cliente) 

    // Pedido
    Route::apiResource('pedidos', PedidoController::class);
    Route::post('pedidos/{id}/restore', [PedidoController::class, 'restore']);

    // Detalle Pedido
    Route::get('pedidos/{id_pedido}/detalles', [DetallePedidoController::class, 'index']);
    Route::post('pedidos/{id_pedido}/detalles', [DetallePedidoController::class, 'store']);
    Route::match(['put', 'patch'], 'pedidos/{id_pedido}/detalles/{id_producto}', [DetallePedidoController::class, 'update']);
    Route::delete('pedidos/{id_pedido}/detalles/{id_producto}', [DetallePedidoController::class, 'destroy']);

    // Pago
    Route::apiResource('pagos', PagoController::class);

    // Reseñas 
    Route::apiResource('resenas', ResenaController::class)->except(['index', 'show']);
    Route::post('resenas/{id}/restore', [ResenaController::class, 'restore']);

    // Carrito
    Route::apiResource('carritos', CarritoController::class)->only(['index', 'store', 'show']);

    // Detalle Carrito (items)
    Route::get('carritos/{idCarrito}/items', [DetalleCarritoController::class, 'index']);
    Route::post('carritos/{idCarrito}/items', [DetalleCarritoController::class, 'store']);
    Route::match(['put', 'patch'], 'carritos/{idCarrito}/items/{idProducto}', [DetalleCarritoController::class, 'update']);
    Route::delete('carritos/{idCarrito}/items/{idProducto}', [DetalleCarritoController::class, 'destroy']);

    // Lista deseos
    Route::apiResource('lista-deseos', ListaDeseosController::class);
    Route::post('lista-deseos/{id_lista}/productos', [ListaDeseosController::class, 'agregarProducto']);
    Route::delete('lista-deseos/{id_lista}/productos/{id_producto}', [ListaDeseosController::class, 'quitarProducto']);
    Route::get('lista-deseos/{id_lista}/productos', [ListaDeseosController::class, 'productos']);
    Route::post('lista-deseos/{id}/restore', [ListaDeseosController::class, 'restore']);

    // Estados 
    Route::get('/estados', [EstadoController::class, 'index']);
    Route::delete('/estados/{id_estado}', [EstadoController::class, 'destroy']);
    Route::patch('/estados/{id_estado}/reactivar', [EstadoController::class, 'reactivar']);

    // Movimiento stock 
    Route::apiResource('movimiento-stock', MovimientoStockController::class)->only(['index', 'store', 'show', 'destroy']);


    /* 3) RUTAS SOLO ADMIN (auth + role:admin) */
    Route::middleware('role:admin')->group(function () {

        // Products 
        Route::apiResource('products', ProductController::class)->except(['index', 'show']);
        Route::patch('products/{id}/restore', [ProductController::class, 'restore']);

        // Categories 
        Route::post('/categories', [CategoryController::class, 'create']);
        Route::match(['put', 'patch'], '/categories/{id}', [CategoryController::class, 'update']);
        Route::delete('/categories/{id}', [CategoryController::class, 'delete']);
        Route::put('/categories/{id}/restore', [CategoryController::class, 'restore']);

        // Compañías 
        Route::apiResource('companias', CompaniaController::class)->except(['index', 'show']);
        Route::post('companias/{id}/restore', [CompaniaController::class, 'restore']);

        // Proveedores 
        Route::apiResource('proveedores', ProveedorController::class);
        Route::patch('proveedores/{id}/desactivar', [ProveedorController::class, 'desactivar']);
        Route::patch('proveedores/{id}/activar', [ProveedorController::class, 'activar']);

        // Relación Proveedor-Product 
        Route::get('proveedores/{id_proveedor}/products', [ProveedorProductController::class, 'index']);
        Route::post('proveedores/{id_proveedor}/products', [ProveedorProductController::class, 'store']);
        Route::delete('proveedores/{id_proveedor}/products/{product_id}', [ProveedorProductController::class, 'destroy']);
        Route::match(['put', 'patch'], 'proveedores/{id_proveedor}/products', [ProveedorProductController::class, 'sync']);


    });
});