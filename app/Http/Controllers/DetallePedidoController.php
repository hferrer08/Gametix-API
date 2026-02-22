<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DetallePedido;
use App\Models\Pedido;
use Illuminate\Support\Facades\DB;


class DetallePedidoController extends Controller
{
    private function pedidoDelUsuarioOrFail($id_pedido): Pedido
    {
        return Pedido::where('id_pedido', $id_pedido)
            ->where('id_usuario', auth()->id())
            ->firstOrFail();
    }

    private function recalcularMontoTotal($id_pedido): void
    {
        $total = DetallePedido::where('id_pedido', $id_pedido)
            ->selectRaw('COALESCE(SUM(cantidad * precio_unitario), 0) as total')
            ->value('total');

        Pedido::where('id_pedido', $id_pedido)->update(['monto_total' => $total]);
    }

    public function index($id_pedido)
    {
        $this->pedidoDelUsuarioOrFail($id_pedido);

        $detalles = DetallePedido::with('producto')
            ->where('id_pedido', $id_pedido)
            ->get();

        return response()->json($detalles);
    }

    public function store(Request $request, $id_pedido)
    {
        $this->pedidoDelUsuarioOrFail($id_pedido);

        $data = $request->validate([
            'id_producto' => ['required', 'integer', 'exists:products,id'],
            'cantidad' => ['required', 'integer', 'min:1'],
            'precio_unitario' => ['required', 'numeric', 'min:0'],
        ]);

        DB::transaction(function () use ($id_pedido, $data) {
            // upsert simple: si ya existe ese producto en el pedido, sumamos cantidad y actualizamos precio
            $detalle = DetallePedido::where('id_pedido', $id_pedido)
                ->where('id_producto', $data['id_producto'])
                ->first();

            if ($detalle) {
                $detalle->cantidad = $detalle->cantidad + $data['cantidad'];
                $detalle->precio_unitario = $data['precio_unitario'];
                $detalle->save();
            } else {
                DetallePedido::create([
                    'id_pedido' => $id_pedido,
                    'id_producto' => $data['id_producto'],
                    'cantidad' => $data['cantidad'],
                    'precio_unitario' => $data['precio_unitario'],
                ]);
            }

            $this->recalcularMontoTotal($id_pedido);
        });

        return response()->json(['message' => 'Detalle agregado y total actualizado'], 201);
    }

    public function update(Request $request, $id_pedido, $id_producto)
    {
        $this->pedidoDelUsuarioOrFail($id_pedido);

        $data = $request->validate([
            'cantidad' => ['sometimes', 'integer', 'min:1'],
            'precio_unitario' => ['sometimes', 'numeric', 'min:0'],
        ]);

        DB::transaction(function () use ($id_pedido, $id_producto, $data) {
            $detalle = DetallePedido::where('id_pedido', $id_pedido)
                ->where('id_producto', $id_producto)
                ->firstOrFail();

            $detalle->update($data);

            $this->recalcularMontoTotal($id_pedido);
        });

        return response()->json(['message' => 'Detalle actualizado y total actualizado']);
    }

    public function destroy($id_pedido, $id_producto)
    {
        $this->pedidoDelUsuarioOrFail($id_pedido);

        DB::transaction(function () use ($id_pedido, $id_producto) {
            DetallePedido::where('id_pedido', $id_pedido)
                ->where('id_producto', $id_producto)
                ->delete();

            $this->recalcularMontoTotal($id_pedido);
        });

        return response()->json(['message' => 'Detalle eliminado y total actualizado']);
    }
}
