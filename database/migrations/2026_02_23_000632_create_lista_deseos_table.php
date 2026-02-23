<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('lista_deseos', function (Blueprint $table) {
            $table->bigIncrements('id_lista'); // PK

            $table->unsignedBigInteger('id_usuario'); // FK (NO NULL)
            $table->timestamp('fecha_creacion')->useCurrent();
            $table->string('nombre'); // NO NULL (por defecto)
            $table->text('descripcion')->nullable(); 

            // FK con PROPAGAR (CASCADE)
            $table->foreign('id_usuario')
                ->references('id')->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lista_deseos');
    }
};
