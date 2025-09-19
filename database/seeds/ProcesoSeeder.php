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
        $procesos = json_decode("[
            {'nombre_flujo': 'FALLECIMIENTOS', 'descripcion': 'Flujo de Fallecimientos','creado_por': '1' },
            {'nombre_flujo': 'FUNERARIOS', 'descripcion': 'Flujo de Funerarios','creado_por': '1' },
            {'nombre_flujo': 'DISCAPACIDAD', 'descripcion': 'Flujo de Discapacidad','creado_por': '1' }
        ]",true);
        foreach ($procesos as $proceso) {
            Proceso::create(['nombre' => $proceso['nombre_flujo'], 'descripcion' => $procesos['descripcion'], 'creado_por' => $procesos['creado_por']]);
        }
        
    }
}
