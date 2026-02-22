<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('resenas', function (Blueprint $table) {
            $table->id('id_resena');

            // FK a products.id  (CASCADE)
            $table->unsignedBigInteger('id_producto');

            // FK a users.id (RESTRICT)
            $table->unsignedBigInteger('id_usuario');

            $table->unsignedTinyInteger('puntuacion'); // 1..5
            $table->text('comentario')->nullable();

            $table->timestamp('fecha')->useCurrent();

            // ---- FKs
            $table->foreign('id_producto')
                ->references('id')->on('products')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreign('id_usuario')
                ->references('id')->on('users')
                ->restrictOnUpdate()
                ->restrictOnDelete();

            // Un usuario solo puede reseñar 1 vez un producto
            $table->unique(['id_producto', 'id_usuario'], 'uq_resena_producto_usuario');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('resenas');
    }
};
