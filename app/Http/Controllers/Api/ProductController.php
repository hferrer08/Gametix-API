<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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
            'price'  => ['nullable','integer','min:0'],
            'website' => 'nullable|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'stock'  => ['nullable','integer','min:0'],
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
        $product = Product::findOrFail($id);

        $data = $request->validate([
            'name' => 'sometimes|required|string|max:150',
            'description' => 'nullable|string',
            'website' => 'nullable|string|max:255',
            'category_id' => 'sometimes|required|exists:categories,id',
            'id_compania' => 'sometimes|required|exists:companies,id_compania',
        ]);

        $product->update($data);

        return Product::query()
            ->with(['category:id,descripcion', 'company:id_compania,nombre'])
            ->findOrFail($product->id);
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return response()->json(['message' => 'Product deleted']);
    }

}
