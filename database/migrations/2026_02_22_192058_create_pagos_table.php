<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up(): void
    {
        Schema::create('pagos', function (Blueprint $table) {
            $table->id('id_pago');

            $table->unsignedBigInteger('id_pedido');

            $table->string('metodo_pago'); 
            $table->decimal('monto', 12, 2);
            $table->dateTime('fecha_pago');

            $table->enum('estado_pago', [
                'pendiente',
                'pagado',
                'rechazado',
                'reembolsado',
            ]);

            $table->timestamps();

            // FK: RESTRICT delete, CASCADE update (PROPAGAR)
            $table->foreign('id_pedido')
                ->references('id_pedido')->on('pedidos')
                ->restrictOnDelete()
                ->cascadeOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pagos');
    }
};
