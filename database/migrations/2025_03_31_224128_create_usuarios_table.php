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
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id_usuario();
            $table->string('nombres', 500);
            $table->string('email', 100)->unique();
            $table->string('telefono', 16)->nullable();
            $table->string('dui', 16)->unique()->nullable();
            $table->timestamp('fecha_registro')->useCurrent();
            $table->foreignId('id_rol')->constrained('rols');
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};
