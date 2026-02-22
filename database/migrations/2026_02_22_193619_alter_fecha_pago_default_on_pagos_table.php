<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
     public function up(): void
    {
        DB::statement("ALTER TABLE pagos MODIFY fecha_pago DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP");
    }

     public function down(): void
    {
        // Revertir: quitar default (queda NOT NULL sin default)
        DB::statement("ALTER TABLE pagos MODIFY fecha_pago DATETIME NOT NULL");
    }
};
