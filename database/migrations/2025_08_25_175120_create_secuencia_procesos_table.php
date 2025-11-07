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
        Schema::create('secuencia_procesos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('descripcion');
            $table->foreignId('proceso_id')->constrained('procesos');
            $table->enum('estatus', ['ACTIVO', 'INACTIVO'])->default('ACTIVO');
            /*$table->foreignId('actor_id')->constrained('admins')->nullable();
            $table->foreignId('rol_id')->constrained('roles')->nullable();*/
            $table->unsignedBigInteger('actor_id')->nullable();
            $table->index('actor_id');
            $table->unsignedBigInteger('rol_id')->nullable();
            $table->index('rol_id');
            $table->integer('tiempo_procesamiento')->default(1);
            $table->json('configuracion');
            $table->json('configuracion_campos');
            $table->json('configuracion_correo');
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
        Schema::dropIfExists('secuencia_procesos');
    }
};
