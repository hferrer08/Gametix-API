<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Product;



class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Trae id category y company, y además el nombre de cada una
        return Product::query()
            ->with([
                'category:id,descripcion',
                'company:id_compania,nombre',
            ])
            ->get();
    }

    public function show($id)
    {
        return Product::query()
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

        $product = Product::create($data);

        return Product::query()
            ->with(['category:id,descripcion', 'company:id_compania,nombre'])
            ->findOrFail($product->id);
    }

    public function update(Request $request, $id)
    {
        $user = $request->user(); // viene del token

        return DB::transaction(function () use ($request, $id, $user) {

            // Bloquea fila para evitar carreras cuando se actualiza el precio
            $product = Product::where('id', $id)->lockForUpdate()->firstOrFail();

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

            // Actualiza producto (solo lo que venga)
            $product->update($data);

            // Si mandaron price y cambió -> inserta histórico
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
        $product = Product::findOrFail($id);
        $product->delete();

        return response()->json(['message' => 'Product deleted']);
    }

}
