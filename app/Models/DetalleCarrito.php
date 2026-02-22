<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetalleCarrito extends Model
{
    protected $table = 'detalle_carrito';
    public $timestamps = false;

    // PK compuesta -> Eloquent no la maneja como primaryKey normal where(id_carrito, id_producto).
    protected $fillable = ['id_carrito', 'id_producto', 'cantidad'];

    public function carrito()
    {
        return $this->belongsTo(Carrito::class, 'id_carrito', 'id_carrito');
    }

    public function producto()
    {
        return $this->belongsTo(Product::class, 'id_producto', 'id');
    }
}
