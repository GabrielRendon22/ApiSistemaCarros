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
            $table->id_reservacion();
            $table->date('fecha_desde');
            $table->date('fecha_hasta');
            $table->timestamp('fecha_registro')->useCurrent();
            $table->foreignId('id_suscripcion')->constrained('suscripciones');
            $table->foreignId('id_vehiculo')->constrained('vehiculos');
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
