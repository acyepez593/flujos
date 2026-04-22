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
        Schema::table('admins', function (Blueprint $table) {
            $table->unsignedBigInteger('cargo_id')->after('username');
            $table->index('cargo_id');
            $table->unsignedBigInteger('abreviacion_titulo_id')->after('cargo_id');
            $table->index('abreviacion_titulo_id');
            $table->unsignedBigInteger('agencia_id')->after('abreviacion_titulo_id');
            $table->index('agencia_id');
            $table->enum('estatus', ['ACTIVO', 'INACTIVO'])->default('ACTIVO')->after('agencia_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('admins', function (Blueprint $table) {
            $table->dropColumn('cargo_id');
            $table->dropColumn('abreviacion_titulo_id');
            $table->dropColumn('agencia_id');
            $table->dropColumn('estatus');
        });
    }
};
