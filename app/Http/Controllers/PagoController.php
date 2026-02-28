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

        $data = $request->validate([
            'q' => ['nullable', 'string', 'max:200'],
            'id_pedido' => ['nullable', 'integer'],
            'estado' => ['nullable', 'string', 'max:30'],   // pagado/pendiente/etc
            'metodo' => ['nullable', 'string', 'max:50'],   // tarjeta, transferencia...
            'desde' => ['nullable', 'date'],               // YYYY-MM-DD
            'hasta' => ['nullable', 'date'],               // YYYY-MM-DD
            'min' => ['nullable', 'numeric', 'min:0'],
            'max' => ['nullable', 'numeric', 'min:0'],
            'page' => ['nullable', 'integer', 'min:1'],
            'limit' => ['nullable', 'integer', 'min:1', 'max:100'],
        ]);

        $query = Pago::query()
            ->with('pedido')
            ->whereHas('pedido', function ($q) use ($user) {
                $q->where('id_usuario', $user->id);
            })
            ->latest('id_pago');

        // Filtros opcionales
        if (!empty($data['id_pedido'])) {
            $query->where('id_pedido', $data['id_pedido']);
        }

        if (!empty($data['estado'])) {
            $query->where('estado_pago', $data['estado']);
        }

        if (!empty($data['metodo'])) {
            $query->where('metodo_pago', 'like', "%{$data['metodo']}%");
        }

        if (!empty($data['desde'])) {
            $query->whereDate('fecha_pago', '>=', $data['desde']);
        }

        if (!empty($data['hasta'])) {
            $query->whereDate('fecha_pago', '<=', $data['hasta']);
        }

        if (isset($data['min'])) {
            $query->where('monto', '>=', $data['min']);
        }

        if (isset($data['max'])) {
            $query->where('monto', '<=', $data['max']);
        }

        // Búsqueda libre opcional
        if (!empty($data['q'])) {
            $qtxt = $data['q'];
            $query->where(function ($sub) use ($qtxt) {
                $sub->where('metodo_pago', 'like', "%{$qtxt}%")
                    ->orWhere('estado_pago', 'like', "%{$qtxt}%")
                    ->orWhere('id_pedido', 'like', "%{$qtxt}%")
                    ->orWhere('monto', 'like', "%{$qtxt}%");
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

    // POST /api/pagos
    public function store(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'id_pedido' => ['required', 'integer', 'exists:pedidos,id_pedido'],
            'metodo_pago' => ['required', 'string', 'max:50'],
            'monto' => ['required', 'numeric', 'min:0'],
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
            'monto' => ['sometimes', 'required', 'numeric', 'min:0'],
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