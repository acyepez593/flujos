<?php

use App\Models\Admin;
use App\Models\CamposPorProceso;
use Illuminate\Database\Seeder;
use App\Models\Catalogo;
use App\Models\Proceso;
use App\Models\TipoCatalogo;

class ListaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $admin = Admin::find(1);
        
        // Procesos
        $Procesos = [
            ['FALLECIMIENTOS','Flujo de Fallecimientos', 'ACTIVO', 1],
            ['FUNERARIOS','Flujo de Funerarios', 'ACTIVO', 1],
            ['DISCAPACIDAD','Flujo de Discapacidad', 'ACTIVO', 1]
        ];

        foreach ($Procesos as $value) {
            Proceso::create(['nombre' => $value[0], 'descripcion' => $value[1],'estatus' => $value[2], 'creado_por' => $value[3]]);
        }

        $CamposPorProcesos = [
            [1, 'select', 'Tipo Recepción', 'tipo_recepcion_id', 'RECEPCION', 'ACTIVO', 1],
            [1, 'date', 'Fecha Recepción', 'fecha_recepcion', 'RECEPCION', 'ACTIVO', 1],
            [1, 'file', 'Adjuntar Documentos Digitalizados', 'documentos_digitalizados_file', 'RECEPCION', 'ACTIVO', 1],
            [1, 'date', 'Fecha del Siniestro', 'fecha_siniestro', 'SINIESTRO', 'ACTIVO', 1],
            [1, 'select', 'Tipo de Accidente', 'tipo_accidente_id', 'SINIESTRO', 'ACTIVO', 1],
            [1, 'select', 'Agencia', 'agencia_id', 'SINIESTRO', 'ACTIVO', 1],
            [1, 'select', 'Tipo de Identificación', 'tipo_identificacion_id', 'VICTIMA', 'ACTIVO', 1],
            [1, 'text', 'Número Documento', 'numero_documento', 'VICTIMA', 'ACTIVO', 1],
            [1, 'text', 'Primer Nombre', 'primer_nombre', 'VICTIMA', 'ACTIVO', 1],
            [1, 'text', 'Segundo Nombre', 'segundo_nombre', 'VICTIMA', 'ACTIVO', 1],
            [1, 'text', 'Primer Apellido', 'primer_apellido', 'VICTIMA', 'ACTIVO', 1],
            [1, 'text', 'Segundo Apellido', 'segundo_apellido', 'VICTIMA', 'ACTIVO', 1],
            [1, 'select', 'Condición', 'condicion_id', 'VICTIMA', 'ACTIVO', 1],
            [1, 'select', 'Tipo Fallecimiento', 'tipo_fallecimiento_id', 'VICTIMA', 'ACTIVO', 1],
            [1, 'date', 'Fecha Nacimiento', 'fecha_nacimiento', 'VICTIMA', 'ACTIVO', 1],
            [1, 'select', 'Género', 'genero_id', 'VICTIMA', 'ACTIVO', 1],
            [1, 'select', 'Estado Civil', 'estado_civil_id', 'VICTIMA', 'ACTIVO', 1],
            [1, 'date', 'Fecha de Defunción', 'fecha_defuncion', 'VICTIMA', 'ACTIVO', 1],
            [1, 'number', 'Edad Víctima', 'edad', 'VICTIMA', 'ACTIVO', 1],
            [1, 'file', 'Adjuntar Cédula', 'fecha_defuncion_file', 'VICTIMA', 'ACTIVO', 1],
            [1, 'select', 'Tipo de Vehículo', 'tipo_vehiculo_id', 'VEHICULO', 'ACTIVO', 1],
            [1, 'text', 'Número de Placa', 'numero_placa', 'VEHICULO', 'ACTIVO', 1],
            [1, 'number', 'Año Fabricación', 'ano_fabricacion', 'VEHICULO', 'ACTIVO', 1],
            [1, 'text', 'Color', 'color', 'VEHICULO', 'ACTIVO', 1],
            [1, 'text', 'Número Chasis', 'numero_chasis', 'VEHICULO', 'ACTIVO', 1],
            [1, 'text', 'Cilindraje', 'cilindraje', 'VEHICULO', 'ACTIVO', 1],
            [1, 'select', 'Tipo de Servicio', 'tipo_servicio_id', 'VEHICULO', 'ACTIVO', 1],
            [1, 'text', 'Descripción', 'descripcion', 'VEHICULO', 'ACTIVO', 1],
            [1, 'select', 'Tipo de Identificación', 'tipo_identificacion_id', 'RECLAMANTE', 'ACTIVO', 1],
            [1, 'text', 'Número Documento', 'numero_documento', 'RECLAMANTE', 'ACTIVO', 1],
            [1, 'text', 'Primer Nombre', 'primer_nombre', 'RECLAMANTE', 'ACTIVO', 1],
            [1, 'text', 'Segundo Nombre', 'segundo_nombre', 'RECLAMANTE', 'ACTIVO', 1],
            [1, 'text', 'Primer Apellido', 'primer_apellido', 'RECLAMANTE', 'ACTIVO', 1],
            [1, 'text', 'Segundo Apellido', 'segundo_apellido', 'RECLAMANTE', 'ACTIVO', 1],
            [1, 'select', 'Parentesco con Víctima', 'parentesco_victima_id', 'RECLAMANTE', 'ACTIVO', 1],
            [1, 'email', 'Correo electrónico', 'email', 'RECLAMANTE', 'ACTIVO', 1],
            [1, 'text', 'Telefonos', 'telefonos', 'RECLAMANTE', 'ACTIVO', 1],
        ];

        foreach ($CamposPorProcesos as $value) {
            CamposPorProceso::create(['proceso_id' => $value[0],'tipo_campo' => $value[1], 'nombre' => $value[2],'variable' => $value[3], 'seccion_campo' => $value[4], 'estatus' => $value[5], 'creado_por' => $value[6]]);
        }

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

        $Agencias = ['GUAYAQUIL', 'MATRIZ QUITO', 'ZONALES'];
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
