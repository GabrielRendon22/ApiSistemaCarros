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
            $table->id_suscripcion();
            $table->date('fecha_inicio');
            $table->date('fecha_fin')->nullable();
            $table->date('fecha_pago')->nullable();
            $table->foreignId('id_cliente')->constrained('usuarios');
            $table->foreignId('id_plan')->constrained('plans');
            $table->foreignId('id_estado')->constrained('estados');
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
