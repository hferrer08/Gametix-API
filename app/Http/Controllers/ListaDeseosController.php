<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ListaDeseo;

class ListaDeseosController extends Controller
{

    public function index(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'q' => ['nullable', 'string', 'max:200'],
            'page' => ['nullable', 'integer', 'min:1'],
            'limit' => ['nullable', 'integer', 'min:1', 'max:100'],
        ]);

        $q = $data['q'] ?? null;

        $query = ListaDeseo::query()
            ->where('id_usuario', $user->id)
            ->where('activo', true)
            ->orderByDesc('id_lista');

        // Filtros opcionales
        if ($q) {
            $query->where(function ($sub) use ($q) {
                $sub->where('nombre', 'like', "%{$q}%")
                    ->orWhere('descripcion', 'like', "%{$q}%");
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
        $user = $request->user();

        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:150'],
            'descripcion' => ['nullable', 'string', 'max:2000'],
        ]);

        $lista = ListaDeseo::create([
            'id_usuario' => $user->id,
            'nombre' => $data['nombre'],
            'descripcion' => $data['descripcion'] ?? null,
            'activo' => 1,
        ]);

        return response()->json($lista, 201);
    }

    public function show(Request $request, $id)
    {
        $user = $request->user();

        $lista = ListaDeseo::where('id_lista', $id)
            ->where('id_usuario', $user->id)
            ->firstOrFail();

        return $lista;
    }

    public function update(Request $request, $id)
    {
        $user = $request->user();

        $lista = ListaDeseo::where('id_lista', $id)
            ->where('id_usuario', $user->id)
            ->firstOrFail();

        $data = $request->validate([
            'nombre' => ['sometimes', 'string', 'max:150'],
            'descripcion' => ['sometimes', 'nullable', 'string', 'max:2000'],
        ]);

        if (empty($data)) {
            return response()->json(['message' => 'No se enviaron campos para actualizar'], 422);
        }

        $lista->update($data);

        return response()->json($lista, 200);
    }

    public function destroy(Request $request, $id)
    {
        $user = $request->user();

        $lista = ListaDeseo::where('id_lista', $id)
            ->where('id_usuario', $user->id)
            ->firstOrFail();

        // limpiar pivot
        $lista->productos()->detach();

        $lista->delete();

        return response()->json(['message' => 'Lista eliminada correctamente.'], 200);
    }

    public function agregarProducto(Request $request, $id_lista)
    {
        $user = $request->user();

        $lista = ListaDeseo::where('id_lista', $id_lista)
            ->where('id_usuario', $user->id)
            ->firstOrFail();

        $data = $request->validate([
            'id_producto' => ['required', 'integer', 'exists:products,id'],
        ]);

        $lista->productos()->syncWithoutDetaching([
            $data['id_producto'] => []
        ]);

        return response()->json([
            'message' => 'Producto agregado a la lista.'
        ], 201);
    }

    public function quitarProducto(Request $request, $id_lista, $id_producto)
    {
        $user = $request->user();

        $lista = ListaDeseo::where('id_lista', $id_lista)
            ->where('id_usuario', $user->id)
            ->firstOrFail();

        $lista->productos()->detach($id_producto);

        return response()->json([
            'message' => 'Producto eliminado de la lista.'
        ]);
    }
    public function productos($id_lista, Request $request)
    {
        $user = $request->user();

        $lista = ListaDeseo::where('id_lista', $id_lista)
            ->where('id_usuario', $user->id)
            ->firstOrFail();

        return $lista->productos;
    }

    public function restore(Request $request, $id)
    {
        $user = $request->user();

        $lista = ListaDeseo::onlyTrashed()
            ->where('id_lista', $id)
            ->where('id_usuario', $user->id)
            ->firstOrFail();

        $lista->restore(); // deleted_at NULL + ACTIVO=1 (por evento del modelo)

        return response()->json(['message' => 'Lista restaurada correctamente.'], 200);
    }
}
