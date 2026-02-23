<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contiene', function (Blueprint $table) {

            $table->unsignedBigInteger('id_lista');
            $table->unsignedBigInteger('id_producto');

            // Timestamp automático
            $table->timestamp('fecha_agregado')->useCurrent();

            // PK compuesta
            $table->primary(['id_lista', 'id_producto']);

            // FK → lista_deseos
            $table->foreign('id_lista')
                ->references('id_lista')
                ->on('lista_deseos')
                ->onDelete('cascade')      // PROPAGAR
                ->onUpdate('restrict');    // RESTRINGIR

            // FK → products.id
            $table->foreign('id_producto')
                ->references('id')
                ->on('products')
                ->onDelete('cascade')      // PROPAGAR
                ->onUpdate('restrict');    // RESTRINGIR
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contiene');
    }
};
