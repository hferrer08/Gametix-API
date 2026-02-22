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
        Schema::create('detalle_pedido', function (Blueprint $table) {
            $table->unsignedBigInteger('id_pedido');
            $table->unsignedBigInteger('id_producto');

            $table->integer('cantidad');
            $table->decimal('precio_unitario', 12, 2);

            // PK compuesta
            $table->primary(['id_pedido', 'id_producto']);

            // FK -> pedidos (CASCADE)
            $table->foreign('id_pedido')
                ->references('id_pedido')
                ->on('pedidos')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            // FK -> products (CASCADE)
            $table->foreign('id_producto')
                ->references('id')
                ->on('products')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detalle_pedido');
    }
};
