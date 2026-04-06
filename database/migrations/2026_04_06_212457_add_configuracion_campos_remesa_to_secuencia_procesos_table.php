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
        Schema::table('secuencia_procesos', function (Blueprint $table) {
            $table->json('configuracion_campos_remesa')->after('configuracion_campos');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('secuencia_procesos', function (Blueprint $table) {
            $table->dropColumn('configuracion_campos_remesa');
        });
    }
};
