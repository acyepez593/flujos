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
        Schema::create('trazabilidad_remesas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proceso_id')->constrained('procesos');
            $table->foreignId('secuencia_proceso_id')->constrained('secuencia_procesos');
            $table->foreignId('funcionario_actual_id')->constrained('admins');
            $table->json('datos');
            $table->enum('estatus', ['CREADA', 'PASO A APROBACION REMESA POR EL DIRECTOR DE ANALISIS DE PROTECCIONES', 'PASO A AUTORIZACION GASTO POR EL DIRECTOR EJECUTIVO', 'PASO A ELABORACION MEMO DE PAGO POR EL DIRECCTOR DE ANALISIS DE PROTECCIONES', 'PASO A AUTORIZACION MEMO DE PAGO POR EL DIRECTOR FINANCIERO', 'PASO A LA EJECUCION DEL CONTROL PREVIO', 'EN EJECUCION DEL CONTROL PREVIO', 'REMESA DEVUELTA', 'DEVENGADO'])->default('CREADA');
            $table->unsignedBigInteger('creado_por');
            $table->index('creado_por');
            $table->enum('tipo', ['CREACION', 'MODIFICACION', 'CAMBIO SECCION','CONDICIONAL', 'FINALIZACION'])->default('CREACION');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('remesas');
    }
};
