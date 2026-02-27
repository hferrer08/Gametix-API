<?php

namespace App\Http\Controllers;

use App\Models\Estado;
use Illuminate\Http\Request;

class EstadoController extends Controller
{

    public function index()
    {
        return Estado::orderBy('id_estado')->get();
    }

    // DELETE /api/estados/{id_estado}
    // Soft delete + desactivar
    public function destroy($id_estado)
    {
        // Necesitamos buscar también si está inactivo (sin scope)
        $estado = Estado::withoutGlobalScope('solo_activos')
            ->where('id_estado', $id_estado)
            ->firstOrFail();

        // Si ya estaba borrado, igual respondemos OK idempotente
        if ($estado->trashed()) {
            return response()->json(['message' => 'Estado ya estaba eliminado'], 200);
        }

        // Desactiva y soft delete
        $estado->activo = 0;
        $estado->save();

        $estado->delete(); // llena deleted_at

        return response()->json(['message' => 'Estado eliminado (soft delete)'], 200);
    }

    // PATCH /api/estados/{id_estado}/reactivar
    // Reactiva: activo=1 y restore del soft delete
    public function reactivar($id_estado)
    {
        // Para poder restaurar, hay que buscar conTrashed
        $estado = Estado::withoutGlobalScope('solo_activos')
            ->withTrashed()
            ->where('id_estado', $id_estado)
            ->firstOrFail();

        // Restore si estaba soft deleted
        if ($estado->trashed()) {
            $estado->restore();
        }

        // Reactivar
        $estado->activo = 1;
        $estado->save();

        return response()->json(['message' => 'Estado reactivado'], 200);
    }
}