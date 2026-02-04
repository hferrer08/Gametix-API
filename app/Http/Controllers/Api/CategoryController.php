<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        return Category::query()
            ->where('activo', true)
            ->orderBy('descripcion')
            ->get(['id', 'descripcion', 'activo']);
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
}
