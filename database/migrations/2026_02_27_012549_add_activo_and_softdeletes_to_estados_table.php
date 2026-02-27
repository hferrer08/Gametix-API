<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('estados', function (Blueprint $table) {
            // Activo
            $table->boolean('activo')->default(true)->after('descripcion');

            // Soft delete
            $table->softDeletes(); // deleted_at
        });

        // Arreglar timestamps que quedaron NULL (para que quede consistente)
        DB::statement("UPDATE estados SET created_at = NOW() WHERE created_at IS NULL");
        DB::statement("UPDATE estados SET updated_at = created_at WHERE updated_at IS NULL");
    }

    public function down(): void
    {
        Schema::table('estados', function (Blueprint $table) {
            $table->dropColumn(['activo', 'deleted_at']);
        });
    }
};
