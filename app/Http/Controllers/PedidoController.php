<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pedido;

class PedidoController extends Controller
{
    public function index()
    {
        // Estado y usuario
        return Pedido::with(['estado', 'usuario'])->get();
    }    

    public function show($id)
    {
        $pedido = Pedido::with(['estado', 'usuario'])->findOrFail($id);
        return response()->json($pedido);
    }

    public function store(Request $request)
{
    $data = $request->validate([
        'fecha' => ['required', 'date'],
        'id_estado' => ['required', 'integer', 'exists:estados,id_estado'],
        'monto_total' => ['required', 'numeric', 'min:0'],
    ]);

    $data['id_usuario'] = auth()->id(); // id del usuario viene del token

    $pedido = Pedido::create($data);

    return response()->json($pedido, 201);
}

public function update(Request $request, $id)
{
    $pedido = Pedido::findOrFail($id);

    // Asegurar que solo el dueño edite
    if ($pedido->id_usuario !== auth()->id()) {
        return response()->json(['message' => 'No autorizado'], 403);
    }

    $data = $request->validate([
        'fecha' => ['sometimes', 'date'],
        'id_estado' => ['sometimes', 'integer', 'exists:estados,id_estado'],
        'monto_total' => ['sometimes', 'numeric', 'min:0'],
    ]);

    $pedido->update($data);

    return response()->json($pedido);
}

public function destroy($id)
{
    $pedido = Pedido::findOrFail($id);

    // Sólo el dueño puede eliminar
    if ($pedido->id_usuario !== auth()->id()) {
        return response()->json(['message' => 'No autorizado'], 403);
    }

    $pedido->delete();

    return response()->json(['message' => 'Pedido eliminado']);
}
}
