<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Compania;
use Illuminate\Http\Request;

class CompaniaController extends Controller
{
    public function index()
    {
        return response()->json(
            Compania::orderBy('id_compania', 'desc')->get()
        );
    }

    public function show(int $id)
    {
        $compania = Compania::findOrFail($id);
        return response()->json($compania);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:150'],
            'descripcion' => ['nullable', 'string', 'max:500'],
            'sitio_web' => ['nullable', 'string', 'max:255'],
        ]);

        $compania = Compania::create($data);

        return response()->json($compania, 201);
    }

    public function update(Request $request, int $id)
    {
        $compania = Compania::findOrFail($id);

        $data = $request->validate([
            'nombre' => ['sometimes', 'required', 'string', 'max:150'],
            'descripcion' => ['nullable', 'string', 'max:500'],
            'sitio_web' => ['nullable', 'string', 'max:255'],
        ]);

        $compania->update($data);

        return response()->json($compania);
    }

    public function destroy(int $id)
    {
        $compania = Compania::where('id_compania', $id)
            ->where('activo', 1)
            ->firstOrFail();

        // delete lógico: activo=0 + softdelete
        $compania->activo = 0;
        $compania->save();
        $compania->delete(); // set deleted_at

        return response()->json(null, 204);
    }

    // POST /api/companias/{id}/restore
    public function restore(int $id)
    {
        $compania = Compania::withTrashed()
            ->where('id_compania', $id)
            ->firstOrFail();

        $compania->restore(); // null deleted_at
        $compania->activo = 1;
        $compania->save();

        return response()->json($compania);
    }
}
