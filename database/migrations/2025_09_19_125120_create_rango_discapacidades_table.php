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
        Schema::create('rango_discapacidades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('normativa_id')->constrained('normativa_discapacidades');
            $table->string('grado_discapacidad');
            $table->integer('rango_desde');
            $table->integer('rango_hasta');
            $table->integer('valor_cobertura');
            $table->enum('estatus', ['ACTIVO', 'INACTIVO'])->default('ACTIVO');
            $table->foreignId('creado_por')->constrained('admins');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rango_discapacidades');
    }
};
