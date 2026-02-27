<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pedido extends Model
{
    use SoftDeletes;
    protected $table = 'pedidos';
    protected $primaryKey = 'id_pedido';

    public $incrementing = true;
    protected $keyType = 'int';

     protected $fillable = [
        'fecha',
        'monto_total',
        'id_estado',
        'id_usuario',
        'activo',
    ];

    // Relaciones 
    public function estado()
    {
        return $this->belongsTo(Estado::class, 'id_estado', 'id_estado');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario', 'id');
    }

    protected $casts = [
        'activo' => 'boolean',
        'fecha' => 'datetime',
    ];
}
