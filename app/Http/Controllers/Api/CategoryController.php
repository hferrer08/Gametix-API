<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        return Category::query()
            ->where('activo', true)
            ->orderBy('descripcion')
            ->get(['id', 'descripcion', 'activo']);
    }
}
