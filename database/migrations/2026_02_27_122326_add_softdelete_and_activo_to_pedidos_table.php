<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pedidos', function (Blueprint $table) {
            // BIT/boolean en MySQL = tinyint(1)
            $table->boolean('activo')->default(true)->after('id_usuario');

            // Soft delete (timestamp nullable)
            $table->softDeletes(); // crea deleted_at
        });
    }

    public function down(): void
    {
        Schema::table('pedidos', function (Blueprint $table) {
            $table->dropColumn('activo');
            $table->dropSoftDeletes(); // borra deleted_at
        });
    }
};