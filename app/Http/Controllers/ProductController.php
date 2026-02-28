<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Product;



class ProductController extends Controller
{

    public function index(Request $request)
    {
        $data = $request->validate([
            'q' => ['nullable', 'string', 'max:200'],
            'category_id' => ['nullable', 'integer'],
            'id_compania' => ['nullable', 'integer'],
            'min_price' => ['nullable', 'numeric', 'min:0'],
            'max_price' => ['nullable', 'numeric', 'min:0'],
            'in_stock' => ['nullable'], // true/false | 1/0

            'page' => ['nullable', 'integer', 'min:1'],
            'limit' => ['nullable', 'integer', 'min:1', 'max:100'],
        ]);

        $query = Product::activos()
            ->with([
                'category:id,descripcion',
                'company:id_compania,nombre',
            ])
            ->orderByDesc('id'); 

        // Filtro por texto (opcional)
        if (!empty($data['q'])) {
            $q = $data['q'];
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%")
                    ->orWhere('website', 'like', "%{$q}%");
            });
        }

        // Filtros opcionales por FK
        if (!empty($data['category_id'])) {
            $query->where('category_id', $data['category_id']);
        }

        if (!empty($data['id_compania'])) {
            $query->where('id_compania', $data['id_compania']);
        }

        // Rango de precio opcional
        if (isset($data['min_price'])) {
            $query->where('price', '>=', $data['min_price']);
        }

        if (isset($data['max_price'])) {
            $query->where('price', '<=', $data['max_price']);
        }

        // Stock opcional
        if ($request->has('in_stock')) {
            $inStock = filter_var($request->query('in_stock'), FILTER_VALIDATE_BOOLEAN);
            if ($inStock) {
                $query->where('stock', '>', 0);
            } else {
                $query->where('stock', '<=', 0);
            }
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
        return Product::activos()
            ->with([
                'category:id,descripcion',
                'company:id_compania,nombre',
            ])
            ->findOrFail($id);
    }


    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:150',
            'description' => 'nullable|string',
            'price' => ['nullable', 'integer', 'min:0'],
            'website' => 'nullable|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'stock' => ['nullable', 'integer', 'min:0'],
            'id_compania' => 'required|exists:companies,id_compania',

        ]);

        $data['stock'] = $data['stock'] ?? 0;
        $data['price'] = $data['price'] ?? 0;
        $data['activo'] = $data['activo'] ?? true;

        $product = Product::create($data);

        return Product::query()
            ->with(['category:id,descripcion', 'company:id_compania,nombre'])
            ->findOrFail($product->id);
    }

    public function update(Request $request, $id)
    {
        $user = $request->user(); // viene del token

        return DB::transaction(function () use ($request, $id, $user) {

            // Bloquea SOLO si está activo (si está inactivo, 404)
            $product = Product::where('id', $id)
                ->where('activo', true)
                ->lockForUpdate()
                ->firstOrFail();

            $data = $request->validate([
                'name' => ['sometimes', 'string', 'max:150'],
                'description' => ['sometimes', 'nullable', 'string'],
                'price' => ['sometimes', 'integer', 'min:0'],
                'website' => ['sometimes', 'nullable', 'string', 'max:255'],
                'category_id' => ['sometimes', 'exists:categories,id'],
                'stock' => ['sometimes', 'integer', 'min:0'],
                'id_compania' => ['sometimes', 'exists:companies,id_compania'],
            ]);

            if (empty($data)) {
                return response()->json(['message' => 'No se enviaron campos para actualizar'], 422);
            }

            $precioAnterior = (int) $product->price;

            $product->update($data);

            if (array_key_exists('price', $data)) {
                $precioNuevo = (int) $product->price;

                if ($precioAnterior !== $precioNuevo) {
                    DB::table('historico_precios')->insert([
                        'id_producto' => $product->id,
                        'precio' => $precioNuevo,
                        'fecha' => now(),
                        'id_usuario' => $user->id,
                    ]);
                }
            }

            return Product::query()
                ->with(['category:id,descripcion', 'company:id_compania,nombre'])
                ->findOrFail($product->id);
        });
    }

    public function destroy($id)
    {
        // Soft delete
        $product = Product::findOrFail($id);

        if (!$product->activo) {
            return response()->json(['message' => 'El producto ya está desactivado.'], 200);
        }

        $product->update(['activo' => false]);

        return response()->json(['message' => 'Producto desactivado.'], 200);
    }

    // Restaurar producto
    public function restore($id)
    {
        $product = Product::findOrFail($id);

        if ($product->activo) {
            return response()->json(['message' => 'El producto ya está activo.'], 200);
        }

        $product->update(['activo' => true]);

        return response()->json(['message' => 'Producto reactivado.'], 200);
    }

}
