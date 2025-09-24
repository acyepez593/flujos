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
            $table->unsignedBigInteger('secuencia_proceso_id');
            $table->index('secuencia_proceso_id');
            /*$table->unsignedBigInteger('funcionario_actual_id');
            $table->index('funcionario_actual_id');*/
            $table->foreignId('funcionario_actual_id')->constrained('admins');
            $table->json('datos');
            $table->enum('estatus', ['INGRESADO', 'EN PROCESO DAP', 'EN PROCESO AUDITORIA', 'EN PROCESO FINANCIERO', 'PAGADO'])->default('INGRESADO');
            $table->unsignedBigInteger('creado_por');
            $table->index('creado_por');
            $table->softDeletes();
            $table->timestamps();

            /*
            //Recepcion
            $table->unsignedBigInteger('tipo_recepcion_id');
            $table->index('tipo_recepcion_id');
            $table->string('numero_guia_courrier');
            $table->unsignedBigInteger('agencia_recepcion_id');
            $table->index('agencia_recepcion_id');
            $table->string('archivo_recepcion');//Documentos digitalizados
            //Siniestro
            $table->date('fecha_siniestro');
            $table->unsignedBigInteger('tipo_accidente_id');
            $table->index('tipo_accidente_id');
            //Victima
            $table->unsignedBigInteger('tipo_documento_victima_id');
            $table->index('tipo_documento_victima_id');
            $table->string('numero_documento_victima');
            $table->string('primer_nombre_victima');
            $table->string('segundo_nombre_victima');
            $table->string('primer_apellido_victima');
            $table->string('segundo_apellido_victima');
            $table->unsignedBigInteger('estado_civil_victima_id');
            $table->index('estado_civil_victima_id');
            $table->unsignedBigInteger('tipo_condicion_victima_id');
            $table->index('tipo_condicion_victima_id');
            $table->unsignedBigInteger('tipo_fallecimiento_victima_id');
            $table->index('tipo_fallecimiento_victima_id');
            $table->date('fecha_nacimiento_victima');
            $table->string('edad_victima');
            $table->index('genero_victima_id');
            $table->unsignedBigInteger('genero_victima_id');
            $table->date('fecha_muerte_victima');
            $table->string('archivo_cedula');
            //Vehiculo
            $table->index('tipo_vehiculo_id');
            $table->unsignedBigInteger('tipo_vehiculo_id');
            $table->index('tipo_servicio_vehiculo_id');
            $table->unsignedBigInteger('tipo_servicio_vehiculo_id');
            $table->string('numero_placa_vehiculo');
            $table->string('ano_fabricacion_vehiculo');
            $table->string('numero_chasis_vehiculo');
            $table->string('numero_motor_vehiculo');
            $table->string('color_primario');
            //Reclamante
            $table->unsignedBigInteger('tipo_documento_reclamante_id');
            $table->index('tipo_documento_reclamante_id');
            $table->string('numero_documento_reclamante');
            $table->string('primer_nombre_reclamante');
            $table->string('segundo_nombre_reclamante');
            $table->string('primer_apellido_reclamante');
            $table->string('segundo_apellido_reclamante');
            $table->unsignedBigInteger('parentesco_reclamante_id');
            $table->index('parentesco_reclamante_id');
            $table->string('correo_electronico_reclamante');
            $table->string('telefonos_reclamante');
            */
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
