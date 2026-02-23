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
            'nombre' => ['required', 'string', 'max:150'],
            'descripcion' => ['nullable', 'string', 'max:2000'],
        ]);

        $lista->update($data);

        return $lista;
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
}
