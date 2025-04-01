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
            $table->id_contrasena();
            $table->string('contrasena', 255);
            $table->timestamp('fecha_registro')->useCurrent();
            $table->boolean('es_activa')->default(true);
            $table->foreignId('id_usuario')->constrained('usuarios');
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
