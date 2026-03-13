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
        Schema::create('remesas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proceso_id')->constrained('procesos');
            $table->foreignId('secuencia_proceso_id')->constrained('secuencia_procesos');
            $table->foreignId('funcionario_actual_id')->constrained('admins');
            $table->enum('estatus', ['CREADA', 'EN PROCESO DAP', 'EN PROCESO DE APROBACION', 'EN PROCESO FINANCIERO', 'PAGADO'])->default('CREADA');
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
        Schema::dropIfExists('remesas');
    }
};
