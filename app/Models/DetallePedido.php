<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetallePedido extends Model
{
    protected $table = 'detalle_pedido';

    public $incrementing = false; // PK compuesta
    public $timestamps = false; // No hay created_at ni updated_at

    protected $fillable = [
        'id_pedido',
        'id_producto',
        'cantidad',
        'precio_unitario',
    ];

    public function pedido()
    {
        return $this->belongsTo(Pedido::class, 'id_pedido', 'id_pedido');
    }

    public function producto()
    {
        return $this->belongsTo(Product::class, 'id_producto', 'id');
    }
}
