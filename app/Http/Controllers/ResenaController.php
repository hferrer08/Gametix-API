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
            'desde' => ['nullable', 'date'], // YYYY-MM-DD
            'hasta' => ['nullable', 'date'], // YYYY-MM-DD
            'activo' => ['nullable'],        // 1/0 true/false (opcional)
            'page' => ['nullable', 'integer', 'min:1'],
            'limit' => ['nullable', 'integer', 'min:1', 'max:100'],
        ]);

        // Base query
        $query = Resena::query()
            ->with(['producto', 'usuario'])
            ->latest('id_resena');

        // Si viene activo, quitamos el GlobalScope para poder listar inactivos también
        if ($request->has('activo')) {
            $activo = filter_var($request->query('activo'), FILTER_VALIDATE_BOOLEAN);
            $query->withoutGlobalScope('activo')->where('activo', $activo);
        }

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

        // Búsqueda opcional (comentario o nombre del producto)
        if (!empty($data['q'])) {
            $q = $data['q'];

            // Agrupar con where(...) 
            $query->where(function ($sub) use ($q) {
                $sub->where('comentario', 'like', "%{$q}%")
                    ->orWhereHas('producto', fn ($p) => $p->where('name', 'like', "%{$q}%"));
            });
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
            'id_usuario' => $user->id,
            'puntuacion' => $data['puntuacion'],
            'comentario' => $data['comentario'] ?? null,
            'activo' => true,
        ]);

        return response()->json($resena, 201);
    }

    public function show($id)
    {
        // show por defecto solo activos 
        return Resena::with(['producto', 'usuario'])->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $user = $request->user();

        $resena = Resena::findOrFail($id);

        // Solo el dueño puede editar su reseña
        if ((int)$resena->id_usuario !== (int)$user->id) {
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

    // DELETE => soft delete lógico (activo = 0)
    public function destroy(Request $request, $id)
    {
        $user = $request->user();

        // Aunque ya esté inactiva 
        $resena = Resena::withoutGlobalScope('activo')->findOrFail($id);

        if ((int)$resena->id_usuario !== (int)$user->id) {
            return response()->json([
                'message' => 'No autorizado para eliminar esta reseña.'
            ], 403);
        }

        if ($resena->activo === false) {
            return response()->json(['message' => 'La reseña ya estaba eliminada.'], 200);
        }

        $resena->activo = false;
        $resena->save();

        return response()->json(['message' => 'Reseña eliminada (soft delete).'], 200);
    }

    // RESTORE => activo = 1
    public function restore(Request $request, $id)
    {
        $user = $request->user();

        $resena = Resena::withoutGlobalScope('activo')->findOrFail($id);

        if ((int)$resena->id_usuario !== (int)$user->id) {
            return response()->json([
                'message' => 'No autorizado para restaurar esta reseña.'
            ], 403);
        }

        if ($resena->activo === true) {
            return response()->json(['message' => 'La reseña ya estaba activa.'], 200);
        }

        $resena->activo = true;
        $resena->save();

        return response()->json(['message' => 'Reseña restaurada.'], 200);
    }
}