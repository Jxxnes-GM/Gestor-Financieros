<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Crea la tabla transacciones con sus llaves foráneas.
     * restrictOnDelete() impide borrar una cuenta o categoría que tenga transacciones.
     */
    public function up(): void
    {
        Schema::create('transacciones', function (Blueprint $table) {
            $table->id();                                   // BIGINT UNSIGNED, PK, autoincremental
            $table->foreignId('cuenta_id')                  // FK a cuentas.id, obligatorio
                  ->constrained('cuentas')
                  ->restrictOnDelete();
            $table->foreignId('categoria_id')               // FK a categorias.id, obligatorio
                  ->constrained('categorias')
                  ->restrictOnDelete();
            $table->enum('tipo', ['ingreso', 'gasto']);     // obligatorio
            $table->decimal('monto', 18, 2);                // obligatorio
            $table->date('fecha');                          // obligatorio
            $table->string('descripcion', 255)->nullable(); // opcional
            $table->string('creado_por', 150)->nullable();  // usuario que registró el movimiento
            $table->timestamps();                           // created_at y updated_at
        });
    }

    /**
     * Elimina la tabla transacciones.
     */
    public function down(): void
    {
        Schema::dropIfExists('transacciones');
    }
};
