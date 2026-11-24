<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Crea la tabla categorias.
     */
    public function up(): void
    {
        Schema::create('categorias', function (Blueprint $table) {
            $table->id();                                   // BIGINT UNSIGNED, PK, autoincremental
            $table->string('nombre', 150);                  // obligatorio
            $table->enum('tipo', ['ingreso', 'gasto']);     // obligatorio
            $table->string('descripcion', 255)->nullable(); // opcional
            $table->timestamps();                           // created_at y updated_at
        });
    }

    /**
     * Elimina la tabla categorias.
     */
    public function down(): void
    {
        Schema::dropIfExists('categorias');
    }
};
