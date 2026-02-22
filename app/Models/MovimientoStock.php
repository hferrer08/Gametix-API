<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MovimientoStock extends Model
{
    protected $table = 'movimiento_stock';
    protected $primaryKey = 'id_movimiento';

    protected $fillable = [
        'id_producto',
        'tipo_movimiento',
        'cantidad',
        'fecha',
        'id_usuario',
    ];

    public function producto()
    {
        return $this->belongsTo(Product::class, 'id_producto', 'id');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario', 'id');
    }
}