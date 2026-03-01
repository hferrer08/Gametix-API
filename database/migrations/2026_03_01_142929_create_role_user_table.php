<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up(): void
    {
        Schema::create('role_user', function (Blueprint $table) {
            $table->id(); // necesario por softDelete

            $table->unsignedBigInteger('role_id');
            $table->unsignedBigInteger('user_id');

            $table->timestamps();   // created_at, updated_at
            $table->softDeletes();  // deleted_at

            // Evitar duplicados activos 
            $table->unique(['role_id', 'user_id']);

            $table->foreign('role_id')
                ->references('id')->on('roles')
                ->onDelete('cascade');

            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('role_user');
    }
};
