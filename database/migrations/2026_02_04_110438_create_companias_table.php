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
        Schema::create('companies', function (Blueprint $table) {
            $table->bigIncrements('id_compania'); // PK autoincremental
            $table->string('nombre', 150);        // NOT NULL por defecto
            $table->string('descripcion', 500)->nullable();
            $table->string('sitio_web', 255)->nullable();
            $table->boolean('activo')->default(true); // BIT default 1
            $table->softDeletes(); // delete_at para softdelete
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companias');
    }
};
