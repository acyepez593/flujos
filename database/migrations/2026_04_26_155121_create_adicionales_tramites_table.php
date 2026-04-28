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
        Schema::create('adicionales_tramites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tramite_id')->constrained('tramites');
            $table->foreignId('proceso_id')->constrained('procesos');
            $table->foreignId('creado_por')->constrained('admins');
            $table->json('datos');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('adicionales_tramites');
    }
};
