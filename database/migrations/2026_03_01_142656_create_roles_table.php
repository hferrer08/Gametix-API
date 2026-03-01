<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50)->unique();      // admin | user
            $table->string('label', 100)->nullable();  // opcional
            $table->boolean('activo')->default(true);  
            $table->timestamps();                      // created_at, updated_at
            $table->softDeletes();                     // deleted_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
