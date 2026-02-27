<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Carrito extends Model
{
    protected $table = 'carritos';
    protected $primaryKey = 'id_carrito';


    protected $fillable = [
        'id_usuario',
        'estado',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario', 'id');
    }

    public function items()
    {
        return $this->hasMany(\App\Models\DetalleCarrito::class, 'id_carrito', 'id_carrito');
    }
}
