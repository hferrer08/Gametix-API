<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resena extends Model
{
    protected $table = 'resenas';
    protected $primaryKey = 'id_resena';
    public $timestamps = false;

    protected $fillable = [
        'id_producto',
        'id_usuario',
        'puntuacion',
        'comentario',
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
