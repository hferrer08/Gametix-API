<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up(): void
    {
        Schema::create('carritos', function (Blueprint $table) {
            $table->bigIncrements('id_carrito');

            // FK a users.id (NO nullable)
            $table->unsignedBigInteger('id_usuario');

            // fecha_creacion automática (default CURRENT_TIMESTAMP)
            $table->timestamp('fecha_creacion')->useCurrent();

            // estado fijo 
            $table->string('estado', 30)->default('abierto');


            // RESTRINGIR borrado/modificación
            $table->foreign('id_usuario')
                ->references('id')->on('users')
                ->restrictOnDelete()
                ->restrictOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('carritos');
    }
};
