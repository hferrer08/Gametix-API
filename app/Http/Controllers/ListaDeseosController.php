<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ListaDeseo;

class ListaDeseosController extends Controller
{

    public function index(Request $request)
    {
        $user = $request->user();

        return ListaDeseo::where('id_usuario', $user->id)
            ->orderByDesc('id_lista')
            ->get();
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

        $lista->delete(); // cascada si hay hijos (cuando creemos items)
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
}
