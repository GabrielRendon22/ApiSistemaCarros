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
        Schema::create('vehiculos', function (Blueprint $table) {
            $table->bigIncrements('id_vehiculo'); // Clave primaria auto-incremental
            $table->string('marca', 50);
            $table->string('modelo', 50);
            $table->string('anio', 4);
            $table->timestamp('fecha_registro')->useCurrent();
            $table->string('placa', 10)->unique();
            
            // Definición de claves foráneas
            $table->unsignedBigInteger('id_estado');
            $table->foreign('id_estado')->references('id_estado')->on('estados')->onDelete('cascade'); // Referencia a la columna id_estado en estados
            
            $table->unsignedBigInteger('id_categoria');
            $table->foreign('id_categoria')->references('id_categoria')->on('categorias')->onDelete('cascade'); // Referencia a la columna id_categoria en categorias

            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehiculos');
    }
};
