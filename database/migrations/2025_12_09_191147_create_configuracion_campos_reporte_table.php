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
        Schema::create('configuracion_campos_reporte', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->foreignId('proceso_id')->constrained('procesos');
            $table->enum('seccion_campo', ['RECEPCION', 'SINIESTRO', 'VICTIMA', 'VEHICULO', 'RECLAMANTE', 'BENEFICIARIOS', 'MEDICA', 'PROCEDENCIA', 'FINANCIERO']);
            $table->boolean('habilitar');
            $table->json('campos');
            $table->foreignId('funcionario_id')->constrained('admins');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('configuracion_campos_reporte');
    }
};
