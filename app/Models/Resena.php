<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resena extends Model
{
    protected $table = 'resenas';
    protected $primaryKey = 'id_resena';
    public $timestamps = true;

   protected $fillable = [
        'id_producto',
        'id_usuario',
        'puntuacion',
        'comentario',
        'fecha',
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

    public function producto()
    {
        return $this->belongsTo(Product::class, 'id_producto', 'id');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario', 'id');
    }
}
