<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Resena;

class ResenaController extends Controller
{
    public function index()
    {
        return Resena::with(['producto', 'usuario'])
            ->latest('id_resena')
            ->get();
    }

    public function store(Request $request)
    {
        $user = $request->user(); // 👈 Usuario autenticado por token

        $data = $request->validate([
            'id_producto' => ['required', 'integer', 'exists:products,id'],
            'puntuacion' => ['required', 'integer', 'between:1,5'],
            'comentario' => ['nullable', 'string', 'max:2000'],
        ]);

        $resena = Resena::create([
            'id_producto' => $data['id_producto'],
            'id_usuario' => $user->id, // usamos el ID del usuario autenticado (token)
            'puntuacion' => $data['puntuacion'],
            'comentario' => $data['comentario'] ?? null,
        ]);

        return response()->json($resena, 201);
    }

    public function show($id)
    {
        return Resena::with(['producto', 'usuario'])->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $user = $request->user();

        $resena = Resena::findOrFail($id);

        //Solo el dueño puede editar su reseña
        if ($resena->id_usuario !== $user->id) {
            return response()->json([
                'message' => 'No autorizado para modificar esta reseña.'
            ], 403);
        }

        $data = $request->validate([
            'puntuacion' => ['sometimes', 'integer', 'between:1,5'],
            'comentario' => ['nullable', 'string', 'max:2000'],
        ]);

        $resena->update($data);

        return response()->json($resena);
    }

    public function destroy(Request $request, $id)
    {
        $user = $request->user();
        $resena = Resena::findOrFail($id);

        if ($resena->id_usuario !== $user->id) {
            return response()->json([
                'message' => 'No autorizado para eliminar esta reseña.'
            ], 403);
        }

        $resena->delete();

        return response()->json(['message' => 'Reseña eliminada.']);
    }
}
