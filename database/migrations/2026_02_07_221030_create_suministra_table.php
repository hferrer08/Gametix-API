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
        Schema::create('suministra', function (Blueprint $table) {
             $table->unsignedBigInteger('id_proveedor');
            $table->unsignedBigInteger('product_id');

            // Evita duplicados proveedor-producto
            $table->primary(['id_proveedor', 'product_id']);

            // FK a proveedores.id_proveedor
            $table->foreign('id_proveedor')
                ->references('id_proveedor')->on('proveedores')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            // FK a products.id
            $table->foreign('product_id')
                ->references('id')->on('products')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suministra');
    }
};
