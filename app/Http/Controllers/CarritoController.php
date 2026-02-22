<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Carrito;

class CarritoController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        return Carrito::where('id_usuario', $user->id)
            ->latest('id_carrito')
            ->get();
    }

    public function store(Request $request)
    {
        $user = $request->user(); // viene del token 

        $data = $request->validate([
            'estado' => ['nullable', 'string', 'max:30'],
        ]);

        $carrito = Carrito::create([
            'id_usuario' => $user->id,
            'estado' => $data['estado'] ?? 'abierto',
        ]);

        return response()->json($carrito, 201);
    }

    public function show(Request $request, $id)
    {
        $user = $request->user();

        $carrito = Carrito::where('id_carrito', $id)
            ->where('id_usuario', $user->id)
            ->firstOrFail();

        return $carrito;
    }
}
