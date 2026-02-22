<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PagoController extends Controller
{
    public function index()
    {
        return Pago::with('pedido')->latest('id_pago')->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'id_pedido'   => ['required', 'integer', 'exists:pedidos,id_pedido'],
            'metodo_pago' => ['required', 'string', 'max:50'],
            'monto'       => ['required', 'numeric', 'min:0'],
            'estado_pago' => ['required', Rule::in(['pendiente', 'pagado', 'rechazado', 'reembolsado'])],
        ]);

        $pago = Pago::create($data);

        return response()->json($pago, 201);
    }

    public function show($id)
    {
        $pago = Pago::findOrFail($id);
        return response()->json($pago);
    }

    public function update(Request $request, $id)
    {
        $pago = Pago::findOrFail($id);

        $data = $request->validate([
            'id_pedido'   => ['sometimes', 'required', 'integer', 'exists:pedidos,id_pedido'],
            'metodo_pago' => ['sometimes', 'required', 'string', 'max:50'],
            'monto'       => ['sometimes', 'required', 'numeric', 'min:0'],
            'estado_pago' => ['sometimes', 'required', Rule::in(['pendiente', 'pagado', 'rechazado', 'reembolsado'])],
        ]);

        $pago->update($data);

        return response()->json($pago);
    }

    public function destroy($id)
    {
        $pago = Pago::findOrFail($id);
        $pago->delete();

        return response()->json(['message' => 'Pago eliminado']);
    }
}
