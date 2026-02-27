<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
     public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // boolean => tinyint(1) en MySQL 
            $table->boolean('activo')->default(true)->after('stock');
        });

        DB::table('products')->whereNull('activo')->update(['activo' => 1]);
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('activo');
        });
    }
};
