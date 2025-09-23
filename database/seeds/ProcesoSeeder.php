<?php

use App\Models\Proceso;
use Illuminate\Database\Seeder;

class ProcesoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // Procesos
        $Procesos = [
            ['FALLECIMIENTOS','Flujo de Fallecimientos', 'ACTIVO', 1],
            ['FUNERARIOS','Flujo de Funerarios', 'ACTIVO', 1],
            ['DISCAPACIDAD','Flujo de Discapacidad', 'ACTIVO', 1]
        ];

        foreach ($Procesos as $value) {
            Proceso::create(['nombre' => $value[0], 'descripcion' => $value[1],'estatus' => $value[2], 'creado_por' => $value[3]]);
        }
        
    }
}
