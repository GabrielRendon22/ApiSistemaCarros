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
        Schema::create('contrasenas', function (Blueprint $table) {
            $table->id('id_contrasena');
            $table->string('contrasena', 255);
            $table->timestamp('fecha_registro')->useCurrent();
            $table->boolean('es_activa')->default(true); 
            $table->unsignedBigInteger('id_usuario'); // Correcta definición de columna
            $table->foreign('id_usuario')->references('id_usuario')->on('usuarios')->onDelete('cascade'); // Referencia a la columna correcta
            
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contrasenas');
    }
};
