<?php

namespace App\Http\Controllers;
use App\Models\MovimientoStock;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class MovimientoStockController extends Controller
{
    public function index(Request $request)
    {
        $data = $request->validate([
            'q' => ['nullable', 'string', 'max:200'],
            'id_producto' => ['nullable', 'integer'],
            'tipo' => ['nullable', 'string', 'max:20'], // ENTRADA / SALIDA
            'id_usuario' => ['nullable', 'integer'],
            'desde' => ['nullable', 'date'],            // YYYY-MM-DD
            'hasta' => ['nullable', 'date'],            // YYYY-MM-DD
            'page' => ['nullable', 'integer', 'min:1'],
            'limit' => ['nullable', 'integer', 'min:1', 'max:100'],
        ]);

        $query = MovimientoStock::query()
            ->orderByDesc('fecha');

        // Filtros opcionales directos
        if (!empty($data['id_producto'])) {
            $query->where('id_producto', $data['id_producto']);
        }

        if (!empty($data['tipo'])) {
            $query->where('tipo_movimiento', $data['tipo']); // ej: ENTRADA/SALIDA
        }

        if (!empty($data['id_usuario'])) {
            $query->where('id_usuario', $data['id_usuario']);
        }

        // Rango de fechas (por columna 'fecha' datetime)
        if (!empty($data['desde'])) {
            $query->whereDate('fecha', '>=', $data['desde']);
        }

        if (!empty($data['hasta'])) {
            $query->whereDate('fecha', '<=', $data['hasta']);
        }

        // Búsqueda libre opcional 
        if (!empty($data['q'])) {
            $q = $data['q'];
            $query->where(function ($sub) use ($q) {
                $sub->where('tipo_movimiento', 'like', "%{$q}%")
                    ->orWhere('id_producto', 'like', "%{$q}%")
                    ->orWhere('id_usuario', 'like', "%{$q}%");
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

    public function store(Request $request)
    {
        $data = $request->validate([
            'id_producto' => ['required', 'integer', 'exists:products,id'],
            'tipo_movimiento' => ['required', 'string', 'in:ENTRADA,SALIDA'],
            'cantidad' => ['required', 'integer', 'min:1'],
            'fecha' => ['nullable', 'date'],
        ]);

        $data['id_usuario'] = $request->user()->id_usuario ?? $request->user()->id;
        $data['fecha'] = $data['fecha'] ?? now();

        $mov = DB::transaction(function () use ($data) {

            $producto = Product::where('id', $data['id_producto'])
                ->lockForUpdate()
                ->firstOrFail();

            $cantidad = (int) $data['cantidad'];

            if ($data['tipo_movimiento'] === 'SALIDA') {
                if ((int) $producto->stock < $cantidad) {
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
    public function destroy(MovimientoStock $movimientoStock)
    {
        $movimientoStock->delete();
        return response()->noContent();
    }
}