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
        Schema::create('suscripciones', function (Blueprint $table) {
            $table->bigIncrements('id_suscripcion'); // Clave primaria auto-incremental
            $table->date('fecha_inicio');
            $table->date('fecha_fin')->nullable();
            $table->date('fecha_pago')->nullable();
            
            // Definición de claves foráneas
            $table->unsignedBigInteger('id_cliente'); 
            $table->foreign('id_cliente')->references('id_usuario')->on('usuarios')->onDelete('cascade'); // Referencia a la columna id_usuario en usuarios

            $table->unsignedBigInteger('id_plan');
            $table->foreign('id_plan')->references('id_plan')->on('planes')->onDelete('cascade'); // Referencia a la columna id_plan en planes

            $table->unsignedBigInteger('id_estado');
            $table->foreign('id_estado')->references('id_estado')->on('estados')->onDelete('cascade'); // Referencia a la columna id_estado en estados

            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suscripciones');
    }
};
