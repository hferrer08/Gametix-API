<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('carritos', function (Blueprint $table) {
            // Agrega created_at y updated_at (nullable para no romper filas existentes)
            $table->timestamp('created_at')->nullable()->after('fecha_creacion');
            $table->timestamp('updated_at')->nullable()->after('created_at');
        });

        // Backfill: copiamos fecha_creacion a created_at para no perder la data
        DB::statement("UPDATE carritos SET created_at = fecha_creacion WHERE created_at IS NULL");

        // Si quieres, también puedes setear updated_at igual a created_at inicialmente
        DB::statement("UPDATE carritos SET updated_at = created_at WHERE updated_at IS NULL");
        
        // OPCIONAL: si ya no usarás fecha_creacion, descomenta esto
        // Schema::table('carritos', function (Blueprint $table) {
        //     $table->dropColumn('fecha_creacion');
        // });
    }

    public function down(): void
    {
        // Si en up() borraste fecha_creacion, aquí tendrías que recrearla (opcional)
        Schema::table('carritos', function (Blueprint $table) {
            $table->dropColumn(['created_at', 'updated_at']);
        });
    }
};
