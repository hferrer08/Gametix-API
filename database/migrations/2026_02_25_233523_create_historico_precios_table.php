<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('historico_precios', function (Blueprint $table) {
            $table->bigIncrements('id_historico');

            $table->unsignedBigInteger('id_producto');
            $table->integer('precio');
            $table->dateTime('fecha');
            $table->unsignedBigInteger('id_usuario');

            // FKs (RESTRICT en delete/update)
            $table->foreign('id_producto')
                ->references('id')->on('products')
                ->restrictOnDelete()
                ->restrictOnUpdate();

            $table->foreign('id_usuario')
                ->references('id')->on('users')
                ->restrictOnDelete()
                ->restrictOnUpdate();

            $table->index(['id_producto', 'fecha']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('historico_precios');
    }
};
