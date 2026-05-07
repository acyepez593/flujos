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
        Schema::table('tramites', function (Blueprint $table) {
            $table->unsignedBigInteger('secuencial_tramite_id')->default(0)->after('id');
            $table->index('secuencial_tramite_id');
            $table->unsignedBigInteger('tramite_relacionado_id')->default(0)->after('secuencial_tramite_id');
            $table->index('tramite_relacionado_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tramites', function (Blueprint $table) {
            $table->dropColumn('secuencial_tramite_id');
            $table->dropColumn('tramite_relacionado_id');
        });
    }
};
