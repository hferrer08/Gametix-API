<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Estado extends Model
{
    use SoftDeletes;
    protected $table = 'estados';
    protected $primaryKey = 'id_estado';

    protected $fillable = [
        'descripcion',
        'activo',
    ];

    protected static function booted()
    {
        static::addGlobalScope('solo_activos', function ($query) {
            $query->where('activo', 1);
        });
    }
}
