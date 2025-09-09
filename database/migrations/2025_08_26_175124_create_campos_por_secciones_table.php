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
        Schema::create('campos_por_secciones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('seccion_pantalla_id');
            $table->index('seccion_pantalla_id');
            $table->json('configuracion');
            $table->string('nombre');
            $table->enum('tipo', ['TEXT', 'NUMBER', 'EMAIL', 'DATE'])->default('TEXT');
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
        Schema::dropIfExists('campos_por_secciones');
    }
};
