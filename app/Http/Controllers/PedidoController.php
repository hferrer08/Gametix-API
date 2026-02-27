<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pedido;

class PedidoController extends Controller
{
    public function index()
    {
        return Pedido::with(['estado', 'usuario'])
            ->where('id_usuario', auth()->id())
            ->where('activo', true)
            ->latest('id_pedido')
            ->get();
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
