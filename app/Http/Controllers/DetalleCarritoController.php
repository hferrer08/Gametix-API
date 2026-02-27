<?php

namespace App\Http\Controllers;

use App\Models\Carrito;
use App\Models\DetalleCarrito;
use Illuminate\Http\Request;

class DetalleCarritoController extends Controller
{
    // Ver items de un carrito (solo del dueño)
    public function index(Request $request, $idCarrito)
    {
        $user = $request->user();

        $carrito = Carrito::where('id_carrito', $idCarrito)
            ->where('id_usuario', $user->id)
            ->firstOrFail();

        return $carrito->items()->with('producto')->get();
    }

    // Agregar item (si existe, suma cantidad)
    public function store(Request $request, $idCarrito)
    {
        $user = $request->user();

        $carrito = Carrito::where('id_carrito', $idCarrito)
            ->where('id_usuario', $user->id)
            ->firstOrFail();

        $data = $request->validate([
            'id_producto' => ['required', 'integer', 'exists:products,id'],
            'cantidad' => ['required', 'integer', 'min:1'],
        ]);

        $item = DetalleCarrito::where('id_carrito', $carrito->id_carrito)
            ->where('id_producto', $data['id_producto'])
            ->first();

        if ($item) {
            $item->cantidad += $data['cantidad'];
            $item->save();
        } else {
            $item = DetalleCarrito::create([
                'id_carrito' => $carrito->id_carrito,
                'id_producto' => $data['id_producto'],
                'cantidad' => $data['cantidad'],
            ]);
        }

        return response()->json($item, 201);
    }

    // Cambiar cantidad de un item (set)
    public function update(Request $request, $idCarrito, $idProducto)
    {
        $user = $request->user();

        $carrito = Carrito::where('id_carrito', $idCarrito)
            ->where('id_usuario', $user->id)
            ->firstOrFail();

        $data = $request->validate([
            'cantidad' => ['sometimes', 'integer', 'min:1'],
        ]);

        if (empty($data)) {
            return response()->json(['message' => 'No se enviaron campos para actualizar'], 422);
        }

        $item = DetalleCarrito::where('id_carrito', $carrito->id_carrito)
            ->where('id_producto', $idProducto)
            ->firstOrFail();

        // solo si viene cantidad
        if (array_key_exists('cantidad', $data)) {
            $item->cantidad = $data['cantidad'];
        }

        $item->save();

        return response()->json($item, 200);
    }
    // Quitar item del carrito
    public function destroy(Request $request, $idCarrito, $idProducto)
    {
        $user = $request->user();

        $carrito = Carrito::where('id_carrito', $idCarrito)
            ->where('id_usuario', $user->id)
            ->firstOrFail();

        DetalleCarrito::where('id_carrito', $carrito->id_carrito)
            ->where('id_producto', $idProducto)
            ->delete();

        return response()->json(['message' => 'Item eliminado'], 200);
    }
}