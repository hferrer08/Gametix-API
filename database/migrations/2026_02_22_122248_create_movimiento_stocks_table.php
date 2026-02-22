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
       Schema::create('movimiento_stock', function (Blueprint $table) {
            $table->id('id_movimiento');

            $table->foreignId('id_producto')
                ->constrained('products', 'id')
                ->restrictOnDelete()
                ->restrictOnUpdate();

            $table->string('tipo_movimiento', 50); // ej: ENTRADA / SALIDA
            $table->unsignedInteger('cantidad');
            $table->dateTime('fecha');

            $table->foreignId('id_usuario')
                ->constrained('users', 'id')
                ->restrictOnDelete()
                ->restrictOnUpdate();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movimiento_stock');
    }
};
