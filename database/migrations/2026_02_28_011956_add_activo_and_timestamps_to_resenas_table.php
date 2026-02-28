<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
     public function up(): void
    {
        Schema::table('resenas', function (Blueprint $table) {
            // BIT/boolean con default true
            $table->boolean('activo')->default(true)->after('fecha');

            // created_at y updated_at
            $table->timestamps();
        });

        // Backfill para registros existentes (si ya hay filas)
        DB::table('resenas')
            ->whereNull('created_at')
            ->update([
                'created_at' => now(),
                'updated_at' => now(),
                'activo'     => 1,
            ]);
    }

    public function down(): void
    {
        Schema::table('resenas', function (Blueprint $table) {
            $table->dropColumn('activo');
            $table->dropTimestamps();
        });
    }
};
