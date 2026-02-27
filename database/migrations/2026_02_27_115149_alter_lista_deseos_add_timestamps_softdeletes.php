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
            // Agregamos timestamps + soft deletes (nullable para no romper filas existentes)
            if (!Schema::hasColumn('lista_deseos', 'created_at')) {
                $table->timestamp('created_at')->nullable();
            }
            if (!Schema::hasColumn('lista_deseos', 'updated_at')) {
                $table->timestamp('updated_at')->nullable();
            }
            if (!Schema::hasColumn('lista_deseos', 'deleted_at')) {
                $table->softDeletes(); // deleted_at
            }
        });

        // 2) Fecha vieja a created_at (y updated_at también)
        if (Schema::hasColumn('lista_deseos', 'fecha_creacion')) {
            DB::statement("UPDATE lista_deseos SET created_at = fecha_creacion WHERE created_at IS NULL");
            DB::statement("UPDATE lista_deseos SET updated_at = created_at WHERE updated_at IS NULL");
        }

        // Columna vieja
        Schema::table('lista_deseos', function (Blueprint $table) {
            if (Schema::hasColumn('lista_deseos', 'fecha_creacion')) {
                $table->dropColumn('fecha_creacion');
            }
        });
    }

    public function down(): void
    {
        Schema::table('lista_deseos', function (Blueprint $table) {
            // Volvemos a crear fecha_creacion (si la necesitas de vuelta)
            if (!Schema::hasColumn('lista_deseos', 'fecha_creacion')) {
                $table->dateTime('fecha_creacion')->nullable();
            }

            if (Schema::hasColumn('lista_deseos', 'deleted_at')) {
                $table->dropSoftDeletes();
            }

            if (Schema::hasColumn('lista_deseos', 'created_at') || Schema::hasColumn('lista_deseos', 'updated_at')) {
                $table->dropTimestamps();
            }
        });
    }
};