<?php

namespace App\Http\Controllers;

use App\Models\Proveedor;
use Illuminate\Http\Request;

class ProveedorController extends Controller
{
    public function index()
    {
        return Proveedor::orderBy('nombre')->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:150'],
            'descripcion' => ['nullable', 'string'],
            'sitio_web' => ['nullable', 'string', 'max:255'],
        ]);

        $data['activo'] = true;

        return Proveedor::create($data);
    }

    public function show(string $id)
    {
        return Proveedor::findOrFail($id);
    }

    public function update(Request $request, string $id)
    {
        $prov = Proveedor::findOrFail($id);

        $data = $request->validate([
            'nombre' => ['sometimes', 'string', 'max:150'],
            'descripcion' => ['sometimes', 'nullable', 'string'],
            'sitio_web' => ['sometimes', 'nullable', 'string', 'max:255'],
        ]);

        if (empty($data)) {
            return response()->json(['message' => 'No se enviaron campos para actualizar'], 422);
        }

        $prov->update($data);

        return response()->json($prov, 200);
    }

    // SoftDelete: solo se marca como inactivo, no se borra de la base de datos
    public function destroy(string $id)
    {
        return $this->desactivar($id);
    }

    public function desactivar(string $id)
    {
        $prov = Proveedor::withoutGlobalScope('activo')->findOrFail($id);
        $prov->activo = false;
        $prov->save();

        return response()->json(['message' => 'Proveedor desactivado']);
    }

    public function activar(string $id)
    {
        $prov = Proveedor::withoutGlobalScope('activo')->findOrFail($id);
        $prov->activo = true;
        $prov->save();

        return response()->json(['message' => 'Proveedor activado']);
    }
}
