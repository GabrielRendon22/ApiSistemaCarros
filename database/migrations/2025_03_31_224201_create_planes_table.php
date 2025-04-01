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
        Schema::create('planes', function (Blueprint $table) {
            $table->bigIncrements('id_plan'); // Usando bigIncrements para definir la columna auto-incremental como clave primaria
            $table->string('nombre_plan', 500);
            $table->text('descripcion')->nullable();
            $table->decimal('precio_mensual', 10, 2);
            $table->float('limite_km')->nullable();
            $table->unsignedBigInteger('id_categoria'); // Definir la columna de id_categoria
            $table->foreign('id_categoria')->references('id_categoria')->on('categorias'); // Definir la clave foránea
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('planes');
    }
};
