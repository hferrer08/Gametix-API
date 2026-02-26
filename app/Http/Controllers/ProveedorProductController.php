<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Proveedor;
use Illuminate\Http\Request;

class ProveedorProductController extends Controller
{
     // GET /api/proveedores/{id_proveedor}/products
    public function index($id_proveedor)
    {
        $proveedor = Proveedor::findOrFail($id_proveedor);

        return response()->json($proveedor->products()->get());
    }

    // POST /api/proveedores/{id_proveedor}/products  body: { "product_id": 1 }
    public function store(Request $request, $id_proveedor)
    {
        $data = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
        ]);

        $proveedor = Proveedor::findOrFail($id_proveedor);

        // attach sin duplicar
        $proveedor->products()->syncWithoutDetaching([$data['product_id']]);

        return response()->json(['message' => 'Producto asociado al proveedor.'], 201);
    }

    // DELETE /api/proveedores/{id_proveedor}/products/{product_id}
    public function destroy($id_proveedor, $product_id)
    {
        $proveedor = Proveedor::findOrFail($id_proveedor);

        $proveedor->products()->detach($product_id);

        return response()->json(['message' => 'Producto desasociado del proveedor.']);
    }

    // PUT /api/proveedores/{id_proveedor}/products  body: { "product_ids": [1,2,3] }
    public function sync(Request $request, $id_proveedor)
    {
        $data = $request->validate([
            'product_ids' => ['required', 'array'],
            'product_ids.*' => ['integer', 'exists:products,id'],
        ]);

        $proveedor = Proveedor::findOrFail($id_proveedor);
        $proveedor->products()->sync($data['product_ids']);

        return response()->json(['message' => 'Productos sincronizados.']);
    }
}