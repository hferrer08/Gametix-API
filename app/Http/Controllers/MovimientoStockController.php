<?php

namespace App\Http\Controllers;
use App\Models\MovimientoStock;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class MovimientoStockController extends Controller
{
    public function index()
    {
        return MovimientoStock::orderByDesc('fecha')->paginate(10);
    }

   public function store(Request $request)
{
    $data = $request->validate([
        'id_producto'     => ['required', 'integer', 'exists:products,id'],
        'tipo_movimiento' => ['required', 'string', 'in:ENTRADA,SALIDA'],
        'cantidad'        => ['required', 'integer', 'min:1'],
        'fecha'           => ['nullable', 'date'],
    ]);

    $data['id_usuario'] = $request->user()->id_usuario ?? $request->user()->id;
    $data['fecha'] = $data['fecha'] ?? now();

    $mov = DB::transaction(function () use ($data) {

        $producto = Product::where('id', $data['id_producto'])
            ->lockForUpdate()
            ->firstOrFail();

        $cantidad = (int) $data['cantidad'];

        if ($data['tipo_movimiento'] === 'SALIDA') {
            if ((int)$producto->stock < $cantidad) {
                abort(422, 'Stock insuficiente para realizar la salida.');
            }
            $producto->stock -= $cantidad;
        } else { // ENTRADA
            $producto->stock += $cantidad;
        }

        $producto->save();

        return MovimientoStock::create($data);
    });

    return response()->json($mov, 201);
}
    public function show(MovimientoStock $movimientoStock)
    {
        return $movimientoStock;
    }

    public function update(Request $request, MovimientoStock $movimientoStock)
    {
        $data = $request->validate([
            'tipo_movimiento' => ['sometimes', 'string', 'in:ENTRADA,SALIDA'],
            'cantidad'        => ['sometimes', 'integer', 'min:1'],
            'fecha'           => ['sometimes', 'date'],
            'id_producto' => ['sometimes', 'integer', 'exists:products,id'],
        ]);

        // opcional: si quieres registrar quién modificó:
        // se deje para el futuro
        // $data['id_usuario'] = $request->user()->id_usuario ?? $request->user()->id;

        $movimientoStock->update($data);

        return $movimientoStock;
    }

    public function destroy(MovimientoStock $movimientoStock)
    {
        $movimientoStock->delete();
        return response()->noContent();
    }
}