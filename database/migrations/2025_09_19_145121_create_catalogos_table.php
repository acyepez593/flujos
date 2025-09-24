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
        Schema::create('catalogos', function (Blueprint $table) {
            $table->id();
            /*$table->unsignedBigInteger('tipo_catalogo_id');
            $table->index('tipo_catalogo_id');*/
            $table->foreignId('tipo_catalogo_id')->constrained('tipo_catalogos');
            $table->string('nombre');
            $table->enum('estatus', ['ACTIVO', 'INACTIVO'])->default('ACTIVO');
            $table->unsignedBigInteger('creado_por');
            $table->index('creado_por');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('catalogos');
    }
};
