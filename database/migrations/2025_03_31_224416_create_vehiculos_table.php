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
            $table->id_vehiculo ();
            $table->string('marca', 50);
            $table->string('modelo', 50);
            $table->string('anio', 4);
            $table->timestamp('fecha_registro')->useCurrent();
            $table->string('placa', 10)->unique();
            $table->foreignId('id_estado')->constrained('estados');
            $table->foreignId('id_categoria')->constrained('categorias');
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
