<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pedido;

class PedidoController extends Controller
{
    public function index(Request $request)
    {
        $userId = $request->user()->id; // o auth()->id()

        $data = $request->validate([
            'q' => ['nullable', 'string', 'max:200'],
            'id_estado' => ['nullable', 'integer'],
            'desde' => ['nullable', 'date'],                 // YYYY-MM-DD
            'hasta' => ['nullable', 'date'],                 // YYYY-MM-DD
            'min' => ['nullable', 'numeric', 'min:0'],
            'max' => ['nullable', 'numeric', 'min:0'],
            'page' => ['nullable', 'integer', 'min:1'],
            'limit' => ['nullable', 'integer', 'min:1', 'max:100'],
        ]);

        $query = Pedido::query()
            ->with(['estado', 'usuario'])
            ->where('id_usuario', $userId)
            ->where('activo', true)
            ->latest('id_pedido');

        // Filtros opcionales
        if (!empty($data['id_estado'])) {
            $query->where('id_estado', $data['id_estado']);
        }

        if (!empty($data['desde'])) {
            $query->whereDate('fecha', '>=', $data['desde']);
        }

        if (!empty($data['hasta'])) {
            $query->whereDate('fecha', '<=', $data['hasta']);
        }

        if (isset($data['min'])) {
            $query->where('monto_total', '>=', $data['min']);
        }

        if (isset($data['max'])) {
            $query->where('monto_total', '<=', $data['max']);
        }

        // Búsqueda libre opcional
        if (!empty($data['q'])) {
            $qtxt = $data['q'];
            $query->where(function ($sub) use ($qtxt) {
                $sub->where('id_pedido', 'like', "%{$qtxt}%")
                    ->orWhere('monto_total', 'like', "%{$qtxt}%");
              
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

    public function show($id)
    {

        $pedido = Pedido::with(['estado', 'usuario'])
            ->where('id_pedido', $id)
            ->where('id_usuario', auth()->id())
            ->where('activo', true)
            ->firstOrFail();

        return response()->json($pedido);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'id_estado' => ['required', 'integer', 'exists:estados,id_estado'],
            'monto_total' => ['required', 'numeric', 'min:0'],
        ]);

        $data['id_usuario'] = auth()->id(); // viene del token
        $data['activo'] = true;

        $pedido = Pedido::create($data);

        return response()->json($pedido, 201);
    }

    public function update(Request $request, $id)
    {
        // Solo del usuario + solo activos
        $pedido = Pedido::where('id_pedido', $id)
            ->where('id_usuario', auth()->id())
            ->where('activo', true)
            ->firstOrFail();

        $data = $request->validate([
            'id_estado' => ['sometimes', 'integer', 'exists:estados,id_estado'],
            'monto_total' => ['sometimes', 'numeric', 'min:0'],
            'activo' => ['sometimes', 'boolean'], //
        ]);

        if (empty($data)) {
            return response()->json(['message' => 'No se enviaron campos para actualizar'], 422);
        }

        $pedido->update($data);

        return response()->json($pedido, 200);
    }

    public function destroy($id)
    {

        $pedido = Pedido::where('id_pedido', $id)
            ->where('id_usuario', auth()->id())
            ->where('activo', true)
            ->firstOrFail();


        $pedido->activo = false;
        $pedido->save();

        $pedido->delete();

        return response()->json(['message' => 'Pedido eliminado (soft delete)']);
    }

    public function restore($id)
    {
        $pedido = Pedido::onlyTrashed()
            ->where('id_pedido', $id)
            ->where('id_usuario', auth()->id())
            ->firstOrFail();

        $pedido->restore();
        $pedido->activo = true;
        $pedido->save();

        return response()->json(['message' => 'Pedido restaurado']);
    }

}
