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
        Schema::create('pagos', function (Blueprint $table) {
            $table->bigIncrements('id_pago'); // Definición de clave primaria
            $table->timestamp('fecha_registro')->useCurrent();

            // Definición de la clave foránea
            $table->unsignedBigInteger('id_suscripcion');
            $table->foreign('id_suscripcion')->references('id_suscripcion')->on('suscripciones')->onDelete('cascade'); // Referencia a id_suscripcion en suscripciones

            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pagos');
    }
};
