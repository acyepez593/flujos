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
        Schema::create('campos_por_procesos', function (Blueprint $table) {
            $table->id();
            /*$table->unsignedBigInteger('proceso_id');
            $table->index('proceso_id');*/
            $table->enum('tipo_campo', ['text','textarea','hidden','email','number','date','datetime','file','select'])->default('text');
            $table->foreignId('proceso_id')->constrained('procesos');
            $table->string('nombre');
            $table->string('variable');
            $table->enum('seccion_campo', ['RECEPCION', 'SINIESTRO', 'VICTIMA', 'VEHICULO', 'RECLAMANTE', 'BENEFICIARIO', 'MEDICA', 'FINANCIERO']);
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
        Schema::dropIfExists('campos_por_procesos');
    }
};
