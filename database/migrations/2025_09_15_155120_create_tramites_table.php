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
        Schema::create('tramites', function (Blueprint $table) {
            $table->id();
            /*$table->unsignedBigInteger('proceso_id');
            $table->index('proceso_id');*/
            $table->foreignId('proceso_id')->constrained('procesos');
            $table->foreignId('secuencia_proceso_id')->constrained('secuencia_procesos');
            /*$table->unsignedBigInteger('secuencia_proceso_id');
            $table->index('secuencia_proceso_id');
            $table->unsignedBigInteger('funcionario_actual_id');
            $table->index('funcionario_actual_id');*/
            $table->foreignId('funcionario_actual_id')->constrained('admins');
            $table->json('datos');
            $table->enum('estatus', ['INGRESADO', 'EN PROCESO DAP', 'EN ANALISIS DE PROCEDENCIA', 'EN PROCESO FINANCIERO', 'PAGADO'])->default('INGRESADO');
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
        Schema::dropIfExists('tramites');
    }
};
