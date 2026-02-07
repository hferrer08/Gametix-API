<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
     protected $table = 'proveedores';
    protected $primaryKey = 'id_proveedor';

    protected $fillable = [
        'nombre',
        'descripcion',
        'sitio_web',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    // Solo activos por defecto
    protected static function booted()
    {
        static::addGlobalScope('activo', function ($builder) {
            $builder->where('activo', true);
        });
    }
}
