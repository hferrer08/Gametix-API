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
        Schema::create('products', function (Blueprint $table) {
      $table->id();

      $table->string('name', 150);                
      $table->text('description')->nullable();     
      $table->string('website', 255)->nullable(); 

      // FK a categories.id
    $table->unsignedBigInteger('category_id');
    $table->foreign('category_id')
          ->references('id')->on('categories')
          ->restrictOnDelete();

    // FK a companies.id_compania
    $table->unsignedBigInteger('id_compania');
    $table->foreign('id_compania')
          ->references('id_compania')->on('companies')
          ->restrictOnDelete();

      $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
