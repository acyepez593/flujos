<?php

use App\Models\Admin;
use Illuminate\Database\Seeder;
use App\Models\Catalogo;
use App\Models\TipoCatalogo;

class ListaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $admin = Admin::find(1);
        
        // Tipos Catalogo
        $Tipos = [
            ['TIPO RECEPCION','ACTIVO', 1],
            ['TIPO ACCIDENTE','ACTIVO', 1],
            ['AGENCIA','ACTIVO', 1],
            ['TIPO IDENTIFICACION','ACTIVO', 1],
            ['CONDICION','ACTIVO', 1],
            ['GENERO','ACTIVO', 1],
            ['ESTADO CIVIL','ACTIVO', 1],
            ['TIPO DE VEHICULO','ACTIVO', 1],
            ['TIPO SERVICIO','ACTIVO', 1],
            ['PARENTESCO VICTIMA','ACTIVO', 1]
        ];
        foreach ($Tipos as $value) {
            TipoCatalogo::create(['nombre' => $value[0],'estatus' => $value[1], 'creado_por' => $admin->id]);
        }

        // Catalogos
        $TiposRecepcion = ['COURRIER', 'PRESENCIAL'];
        foreach ($TiposRecepcion as $value) {
            Catalogo::create(['tipo_catalogo_id' => 1, 'nombre' => $value, 'creado_por' => 1]);
        }

        $TiposAccidente = ['ATROPELLO', 'CAIDA', 'CHOQUE', 'VOLCAMIENTO', 'DESCONOCIDO'];
        foreach ($TiposAccidente as $value) {
            Catalogo::create(['tipo_catalogo_id' => 2, 'nombre' => $value, 'creado_por' => 1]);
        }

        $Agencias = ['GUAYAQUIL', 'MATRIZ QUITO', 'ZONALES', 'VOLCAMIENTO', 'DESCONOCIDO'];
        foreach ($Agencias as $value) {
            Catalogo::create(['tipo_catalogo_id' => 3, 'nombre' => $value, 'creado_por' => 1]);
        }

        $TiposIdentificacion = ['CEDULA', 'PASAPORTE', 'PARTIDA DE NACIMIENTO'];
        foreach ($TiposIdentificacion as $value) {
            Catalogo::create(['tipo_catalogo_id' => 4, 'nombre' => $value, 'creado_por' => 1]);
        }

        $Condiciones = ['OCUPANTE', 'PEATON', 'BICICLETA'];
        foreach ($Condiciones as $value) {
            Catalogo::create(['tipo_catalogo_id' => 5, 'nombre' => $value, 'creado_por' => 1]);
        }

        $Generos = ['MASCULINO', 'FEMENINO'];
        foreach ($Generos as $value) {
            Catalogo::create(['tipo_catalogo_id' => 6, 'nombre' => $value, 'creado_por' => 1]);
        }

        $EstadosCiviles = ['SOLTERO/A', 'CASADO/A', 'DIVORCIADO/A', 'UNION DE HECHO', 'SEPARADO/A'];
        foreach ($EstadosCiviles as $value) {
            Catalogo::create(['tipo_catalogo_id' => 7, 'nombre' => $value, 'creado_por' => 1]);
        }

        $TiposVehiculos = ['DESCONOCIDO', 'AUTO', 'BUS', 'CAMION', 'CAMIONETA', 'FURGONETA', 'JEEP', 'MOTO', 'TRACTOR', 'TRAILER', 'TRICIMOTO'];
        foreach ($TiposVehiculos as $value) {
            Catalogo::create(['tipo_catalogo_id' => 8, 'nombre' => $value, 'creado_por' => 1]);
        }

        $TiposServicio = ['PARTICULAR', 'TRANSPORTE PUBLICO', 'VEHICULO DEL ESTADO'];
        foreach ($TiposServicio as $value) {
            Catalogo::create(['tipo_catalogo_id' => 9, 'nombre' => $value, 'creado_por' => 1]);
        }

        $Parentescos = ['ESPOSO/A', 'CONVIVIENTE', 'HIJO/A', 'PADRE', 'MADRE', 'HERMANO/A', 'ABUELO/A', 'NIETO/A', 'TIO/A', 'OTRO'];
        foreach ($Parentescos as $value) {
            Catalogo::create(['tipo_catalogo_id' => 10, 'nombre' => $value, 'creado_por' => 1]);
        }

    }
}
