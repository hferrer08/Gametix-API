<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;

class ListaDeseo extends Model
{
    use SoftDeletes;
    protected $table = 'lista_deseos';
    protected $primaryKey = 'id_lista';

    public $timestamps = true;

    protected $fillable = [
        'id_usuario',
        'nombre',
        'descripcion',
        'activo',
    ];

    protected static function booted()
    {
        static::addGlobalScope('activo', function (Builder $builder) {
            $builder->where('activo', 1);
        });

        static::deleting(function ($model) {
            if (!$model->isForceDeleting()) {
                $model->activo = 0;
                $model->saveQuietly();
            }
        });

        static::restoring(function ($model) {
            $model->activo = 1;
            $model->saveQuietly();
        });
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario', 'id');
    }

    public function productos()
    {
        return $this->belongsToMany(
            \App\Models\Product::class,
            'contiene',
            'id_lista',
            'id_producto'
        )->withPivot('fecha_agregado');
    }
}
