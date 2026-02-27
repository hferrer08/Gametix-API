<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use App\Models\Pedido;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PagoController extends Controller
{

    public function index(Request $request)
    {
        $user = $request->user();

        return Pago::with('pedido')
            ->whereHas('pedido', function ($q) use ($user) {
                $q->where('id_usuario', $user->id);
            })
            ->latest('id_pago')
            ->get();
    }

    // POST /api/pagos
    public function store(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'id_pedido'   => ['required', 'integer', 'exists:pedidos,id_pedido'],
            'metodo_pago' => ['required', 'string', 'max:50'],
            'monto'       => ['required', 'numeric', 'min:0'],
            'estado_pago' => ['required', Rule::in(['pendiente', 'pagado', 'rechazado', 'reembolsado'])],
        ]);

        //El pedido debe ser del usuario autenticado
        $pedido = Pedido::where('id_pedido', $data['id_pedido'])
            ->where('id_usuario', $user->id)
            ->first();

        if (!$pedido) {
            return response()->json([
                'message' => 'No tienes permiso para registrar un pago en este pedido.'
            ], 403);
        }

        $pago = Pago::create($data);

        return response()->json($pago->load('pedido'), 201);
    }

    public function show(Request $request, $id)
    {
        $user = $request->user();

        $pago = Pago::with('pedido')
            ->where('id_pago', $id)
            ->whereHas('pedido', function ($q) use ($user) {
                $q->where('id_usuario', $user->id);
            })
            ->first();

        if (!$pago) {
            return response()->json([
                'message' => 'Pago no encontrado o no autorizado.'
            ], 404);
        }

        return response()->json($pago);
    }

    public function update(Request $request, $id)
    {
        $user = $request->user();

        $pago = Pago::with('pedido')
            ->where('id_pago', $id)
            ->whereHas('pedido', function ($q) use ($user) {
                $q->where('id_usuario', $user->id);
            })
            ->first();

        if (!$pago) {
            return response()->json([
                'message' => 'Pago no encontrado o no autorizado.'
            ], 404);
        }

        $data = $request->validate([
           
            'metodo_pago' => ['sometimes', 'required', 'string', 'max:50'],
            'monto'       => ['sometimes', 'required', 'numeric', 'min:0'],
            'estado_pago' => ['sometimes', 'required', Rule::in(['pendiente', 'pagado', 'rechazado', 'reembolsado'])],
        ]);

        $pago->update($data);

        return response()->json($pago->fresh()->load('pedido'));
    }

    public function destroy(Request $request, $id)
    {
        $user = $request->user();

        $pago = Pago::where('id_pago', $id)
            ->whereHas('pedido', function ($q) use ($user) {
                $q->where('id_usuario', $user->id);
            })
            ->first();

        if (!$pago) {
            return response()->json([
                'message' => 'Pago no encontrado o no autorizado.'
            ], 404);
        }

        $pago->delete();

        return response()->json(['message' => 'Pago eliminado'], 200);
    }
}