<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Resena;

class ResenaController extends Controller
{
    public function index(Request $request)
    {
        $data = $request->validate([
            'q' => ['nullable', 'string', 'max:200'],
            'id_producto' => ['nullable', 'integer'],
            'id_usuario' => ['nullable', 'integer'],
            'puntuacion' => ['nullable', 'integer', 'between:1,5'],
            'desde' => ['nullable', 'date'],                 // YYYY-MM-DD
            'hasta' => ['nullable', 'date'],                 // YYYY-MM-DD
            'page' => ['nullable', 'integer', 'min:1'],
            'limit' => ['nullable', 'integer', 'min:1', 'max:100'],
        ]);

        $query = Resena::query()
            ->with(['producto', 'usuario'])
            ->latest('id_resena');

        // Filtros opcionales
        if (!empty($data['id_producto'])) {
            $query->where('id_producto', $data['id_producto']);
        }

        if (!empty($data['id_usuario'])) {
            $query->where('id_usuario', $data['id_usuario']);
        }

        if (!empty($data['puntuacion'])) {
            $query->where('puntuacion', $data['puntuacion']);
        }

        if (!empty($data['desde'])) {
            $query->whereDate('fecha', '>=', $data['desde']);
        }

        if (!empty($data['hasta'])) {
            $query->whereDate('fecha', '<=', $data['hasta']);
        }

        // Búsqueda opcional (comentario)
        if (!empty($data['q'])) {
            $q = $data['q'];
            $query->where('comentario', 'like', "%{$q}%");

            // Por nombre del producto:
            $query->orWhereHas('producto', fn($p) => $p->where('name','like',"%{$q}%"));
        }

        // Paginación opcional
        $wantsPagination = $request->has('limit') || $request->has('page');

        if ($wantsPagination) {
            $limit = max(1, min((int) ($data['limit'] ?? 10), 100));

            return response()->json(
                $query->paginate($limit)->appends($request->query()),
                200
            );
        }

        return response()->json($query->get(), 200);
    }

    public function store(Request $request)
    {
        $user = $request->user(); // Usuario autenticado por token

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

        // Solo el dueño puede editar su reseña
        if ($resena->id_usuario !== $user->id) {
            return response()->json([
                'message' => 'No autorizado para modificar esta reseña.'
            ], 403);
        }

        $data = $request->validate([
            'puntuacion' => ['sometimes', 'integer', 'between:1,5'],
            'comentario' => ['sometimes', 'nullable', 'string', 'max:2000'],
        ]);

        if (empty($data)) {
            return response()->json(['message' => 'No se enviaron campos para actualizar'], 422);
        }

        $resena->update($data);

        return response()->json($resena, 200);
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
