<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Compania;
use Illuminate\Http\Request;

class CompaniaController extends Controller
{
    public function index(Request $request)
{
    $data = $request->validate([
        'q'     => ['nullable', 'string', 'max:200'],
        'page'  => ['nullable', 'integer', 'min:1'],
        'limit' => ['nullable', 'integer', 'min:1', 'max:100'],
    ]);

    $q = $data['q'] ?? null;

    $query = Compania::query()
        ->where('activo', true)
        ->orderByDesc('id_compania');

    //Búsqueda opcional (nombre/descripcion/sitio_web)
    if ($q) {
        $query->where(function ($sub) use ($q) {
            $sub->where('nombre', 'like', "%{$q}%")
                ->orWhere('descripcion', 'like', "%{$q}%")
                ->orWhere('sitio_web', 'like', "%{$q}%");
        });
    }

    //Si viene limit o page => paginar. Si no => devolver todo.
    $wantsPagination = $request->has('limit') || $request->has('page');

    if ($wantsPagination) {
        $limit = max(1, min((int)($data['limit'] ?? 10), 100));

        return response()->json(
            $query->paginate($limit)->appends($request->query()),
            200
        );
    }

    return response()->json($query->get(), 200);
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
            'nombre' => ['sometimes', 'string', 'max:150'],
            'descripcion' => ['sometimes', 'nullable', 'string', 'max:500'],
            'sitio_web' => ['sometimes', 'nullable', 'string', 'max:255'],
        ]);

        if (empty($data)) {
            return response()->json(['message' => 'No se enviaron campos para actualizar'], 422);
        }

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
