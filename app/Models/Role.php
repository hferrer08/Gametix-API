<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'label', 'activo'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'role_user')
            ->withTimestamps()
            ->wherePivotNull('deleted_at'); // Solo roles asignados activos
    }
}
