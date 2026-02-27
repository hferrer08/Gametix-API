<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::table('carritos', function (Blueprint $table) {
        $table->timestamp('updatedat_')->nullable()->after('fecha_creacion');
    });
}

public function down(): void
{
    Schema::table('carritos', function (Blueprint $table) {
        $table->dropColumn('updatedat_');
    });
}
};
