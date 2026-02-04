<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Compania extends Model
{
    use SoftDeletes;
    protected $table = 'companies';
    protected $primaryKey = 'id_compania';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'nombre',
        'descripcion',
        'sitio_web',
        'activo',
    ];
}
