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
        Schema::create('pedidos', function (Blueprint $table) {
            $table->bigIncrements('id_pedido');

            // NO nulo
            $table->dateTime('fecha');


            $table->decimal('monto_total', 12, 2);

            // FKs NO nulos
            $table->unsignedBigInteger('id_estado');
            $table->unsignedBigInteger('id_usuario'); // referencia a users.id

            // RESTRICT delete/update
            $table->foreign('id_estado')
                ->references('id_estado')
                ->on('estados')
                ->restrictOnDelete()
                ->restrictOnUpdate();

            $table->foreign('id_usuario')
                ->references('id')
                ->on('users')
                ->restrictOnDelete()
                ->restrictOnUpdate();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pedidos');
    }
};
