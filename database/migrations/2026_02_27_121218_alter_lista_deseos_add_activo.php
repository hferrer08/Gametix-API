<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('lista_deseos', function (Blueprint $table) {
            if (!Schema::hasColumn('lista_deseos', 'activo')) {
                $table->tinyInteger('activo')->default(1)->after('descripcion'); 
                // tinyint(1) en MySQL, default 1
            }
        });

        // Backfill por si acaso
        DB::statement("UPDATE lista_deseos SET activo = 1 WHERE activo IS NULL");

        // Si ya tienes soft deletes y hay registros con deleted_at, los marcamos inactivos
        if (Schema::hasColumn('lista_deseos', 'deleted_at')) {
            DB::statement("UPDATE lista_deseos SET activo = 0 WHERE deleted_at IS NOT NULL");
        }
    }

    public function down(): void
    {
        Schema::table('lista_deseos', function (Blueprint $table) {
            if (Schema::hasColumn('lista_deseos', 'activo')) {
                $table->dropColumn('activo');
            }
        });
    }
};