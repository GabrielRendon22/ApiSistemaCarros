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
        Schema::create('reservaciones', function (Blueprint $table) {
            $table->bigIncrements('id_reservacion'); // Definición de clave primaria
            $table->date('fecha_desde');
            $table->date('fecha_hasta');
            $table->timestamp('fecha_registro')->useCurrent();

            // Definición de claves foráneas
            $table->unsignedBigInteger('id_suscripcion');
            $table->foreign('id_suscripcion')->references('id_suscripcion')->on('suscripciones')->onDelete('cascade'); // Referencia a id_suscripcion en suscripciones
            
            $table->unsignedBigInteger('id_vehiculo');
            $table->foreign('id_vehiculo')->references('id_vehiculo')->on('vehiculos')->onDelete('cascade'); // Referencia a id_vehiculo en vehiculos

            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservaciones');
    }
};
