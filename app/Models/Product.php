<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'description',
        'website',
        'category_id',
        'id_compania',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function company()
    {
        return $this->belongsTo(Compania::class, 'id_compania', 'id_compania');
    }

    public function proveedores()
{
    return $this->belongsToMany(
        Proveedor::class,
        'suministra',
        'product_id',    // FK en suministra hacia products
        'id_proveedor'   // FK en suministra hacia proveedores
    );
}
}
