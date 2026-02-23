<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ListaDeseo extends Model
{
    protected $table = 'lista_deseos';
    protected $primaryKey = 'id_lista';

    public $timestamps = false;

    protected $fillable = [
        'id_usuario',
        'nombre',
        'descripcion',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario', 'id');
    }
}
