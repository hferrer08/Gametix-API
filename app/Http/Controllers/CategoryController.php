<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $data = $request->validate([
            'q' => ['nullable', 'string', 'max:200'],
            'page' => ['nullable', 'integer', 'min:1'],
            'limit' => ['nullable', 'integer', 'min:1', 'max:100'],
        ]);

        $q = $data['q'] ?? null;

        $query = Category::query()
            ->where('activo', true)
            ->orderBy('descripcion')
            ->select(['id', 'descripcion', 'activo']);

        // filtro opcional
        if ($q) {
            $query->where('descripcion', 'like', "%{$q}%");
        }

        $wantsPagination = $request->has('limit') || $request->has('page');

        if ($wantsPagination) {
            $limit = max(1, min((int) ($data['limit'] ?? 10), 100));
            return $query->paginate($limit)->appends($request->query());
        }

        return $query->get();
    }

    public function getById(int $id)
    {
        $category = Category::query()
            ->select(['id', 'descripcion', 'activo'])
            ->findOrFail($id);

        return response()->json($category, 200);
    }

    public function create(Request $request): JsonResponse
    {
        $data = $request->validate([
            'descripcion' => ['required', 'string', 'max:150', 'unique:categories,descripcion'],
            'activo' => ['nullable', 'boolean'],
        ]);

        // Si no viene activo, por defecto true
        $data['activo'] = $data['activo'] ?? true;

        $category = Category::create($data);

        return response()->json([
            'message' => 'Categoria creada correctamente',
            'data' => [
                'id' => $category->id,
                'descripcion' => $category->descripcion,
                'activo' => $category->activo,
            ],
        ], 201);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $category = Category::findOrFail($id);

        $data = $request->validate([
            'descripcion' => ['sometimes', 'string', 'max:150', 'unique:categories,descripcion,' . $category->id],
            'activo' => ['sometimes', 'boolean'],
        ]);

        // opcional: si no mandan nada, responde bonito
        if (empty($data)) {
            return response()->json([
                'message' => 'No se enviaron campos para actualizar',
            ], 422);
        }

        $category->update($data);

        return response()->json([
            'message' => 'Categoria actualizada correctamente',
            'data' => [
                'id' => $category->id,
                'descripcion' => $category->descripcion,
                'activo' => $category->activo,
            ],
        ], 200);
    }

    // DELETE lógico: activo = 0 (false)
    public function delete(int $id)
    {
        $category = Category::findOrFail($id);

        // Si ya está desactivada, no hacemos nada
        if (!$category->activo) {
            return response()->json([
                'message' => 'La categoria ya estaba desactivada',
                'id' => $category->id,
            ], 200);
        }

        $category->activo = false;
        $category->save();

        return response()->json([
            'message' => 'Categoria desactivada correctamente',
            'id' => $category->id,
            'activo' => $category->activo,
        ], 200);
    }

    // RESTORE lógico: activo = 1 (true)
    public function restore(int $id)
    {
        $category = Category::findOrFail($id);

        if ($category->activo) {
            return response()->json([
                'message' => 'La categoria ya estaba activa',
                'id' => $category->id,
                'activo' => $category->activo,
            ], 200);
        }

        $category->activo = true;
        $category->save();

        return response()->json([
            'message' => 'Categoria reactivada correctamente',
            'id' => $category->id,
            'activo' => $category->activo,
        ], 200);
    }
}
