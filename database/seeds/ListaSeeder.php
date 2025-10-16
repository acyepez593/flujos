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
            [1, 'text', 'Nombre Completo', 'nombre_completo', 'VICTIMA', 'ACTIVO', 1],
            [1, 'select', 'Condición', 'condicion_id', 'VICTIMA', 'ACTIVO', 1],
            [1, 'select', 'Tipo Fallecimiento', 'tipo_fallecimiento_id', 'VICTIMA', 'ACTIVO', 1],
            [1, 'date', 'Fecha Nacimiento', 'fecha_nacimiento', 'VICTIMA', 'ACTIVO', 1],
            [1, 'select', 'Género', 'genero_id', 'VICTIMA', 'ACTIVO', 1],
            [1, 'select', 'Estado Civil', 'estado_civil_id', 'VICTIMA', 'ACTIVO', 1],
            [1, 'date', 'Fecha de Defunción', 'fecha_defuncion', 'VICTIMA', 'ACTIVO', 1],
            [1, 'number', 'Edad Víctima', 'edad', 'VICTIMA', 'ACTIVO', 1],
            [1, 'file', 'Adjuntar Cédula', 'fecha_defuncion_file', 'VICTIMA', 'ACTIVO', 1],
            [1, 'text', 'Número Documento', 'numero_documento', 'BENEFICIARIOS', 'ACTIVO', 1],
            [1, 'text', 'Nombre Completo', 'nombre_completo', 'BENEFICIARIOS', 'ACTIVO', 1],
            [1, 'select', 'Parentesco con Víctima', 'parentesco_victima_id', 'BENEFICIARIOS', 'ACTIVO', 1],
            [1, 'text', 'Porcentaje a Pagar', 'porcentaje_pagar', 'BENEFICIARIOS', 'ACTIVO', 1],
            [1, 'text', 'Valor a Pagar', 'valor_pagar', 'BENEFICIARIOS', 'ACTIVO', 1],
            [1, 'select', 'Cuenta bancaria', 'tipo_cuenta_bancaria_id', 'BENEFICIARIOS', 'ACTIVO', 1],
            [1, 'select', 'Es cuenta bancaria de menor de edad', 'es_cuenta_bancaria_menor_id', 'BENEFICIARIOS', 'ACTIVO', 1],
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
            [1, 'text', 'Nombre Completo', 'nombre_completo', 'RECLAMANTE', 'ACTIVO', 1],
            [1, 'select', 'Parentesco con Víctima', 'parentesco_victima_id', 'RECLAMANTE', 'ACTIVO', 1],
            [1, 'email', 'Correo electrónico', 'email', 'RECLAMANTE', 'ACTIVO', 1],
            [1, 'text', 'Telefonos', 'telefonos', 'RECLAMANTE', 'ACTIVO', 1],
        ];

        foreach ($CamposPorProcesos as $value) {
            CamposPorProceso::create(['proceso_id' => $value[0],'tipo_campo' => $value[1], 'nombre' => $value[2],'variable' => $value[3], 'seccion_campo' => $value[4], 'estatus' => $value[5], 'creado_por' => $value[6]]);
        }

        // Tipos Catalogo
        $Tipos = [
            ['TIPO RECEPCION','ACTIVO', NULL, 1],
            ['TIPO ACCIDENTE','ACTIVO', NULL, 1],
            ['AGENCIA','ACTIVO', NULL, 1],
            ['TIPO IDENTIFICACION','ACTIVO', NULL, 1],
            ['CONDICION','ACTIVO', NULL, 1],
            ['TIPO FALLECIMIENTO','ACTIVO', NULL, 1],
            ['GENERO','ACTIVO', NULL, 1],
            ['ESTADO CIVIL','ACTIVO', NULL, 1],
            ['TIPO DE VEHICULO','ACTIVO', NULL, 1],
            ['TIPO SERVICIO','ACTIVO', NULL, 1],
            ['PARENTESCO VICTIMA','ACTIVO', NULL, 1],
            ['TIPO CUENTA','ACTIVO', NULL, 1],
            ['OBSERVACIONES GENERALES','ACTIVO', NULL, 1],
            ['PROVINCIA','ACTIVO', NULL, 1],
            ['CANTON','ACTIVO', 14, 1],
            ['AUTODETERMINACION ETNICA','ACTIVO', NULL, 1],
            ['DETALLE AUTODETERMINACION ETNICA','ACTIVO', 16, 1],
            ['TIPO DISCAPACIDAD', 'ACTIVO', NULL, 1],
            ['GRADO DISCAPACIDAD', 'ACTIVO', NULL, 1],
            ['ORIGEN CUENTA BANCARIA', 'ACTIVO', NULL, 1]
        ];
        foreach ($Tipos as $value) {
            TipoCatalogo::create(['nombre' => $value[0],'estatus' => $value[1], 'tipo_catalogo_relacionado_id' => $value[2], 'creado_por' => $admin->id]);
        }

        // Catalogos
        $TiposRecepcion = ['COURIER', 'PRESENCIAL'];
        foreach ($TiposRecepcion as $value) {
            Catalogo::create(['tipo_catalogo_id' => 1, 'nombre' => $value, 'creado_por' => 1]);
        }

        $TiposAccidente = ['ATROPELLO', 'CAIDA', 'CHOQUE', 'VOLCAMIENTO', 'DESCONOCIDO'];
        foreach ($TiposAccidente as $value) {
            Catalogo::create(['tipo_catalogo_id' => 2, 'nombre' => $value, 'creado_por' => 1]);
        }

        $Agencias = ['AMBATO', 'BABAHOYO', 'CUENCA', 'ESMERALDAS', 'GUAYAQUIL', 'IBARRA', 'LATACUNGA', 'LOJA', 'MACHALA', 'MATRIZ QUITO', 'PORTOVIEJO', 'RIOBAMBA', 'SANTO DOMINGO'];
        foreach ($Agencias as $value) {
            Catalogo::create(['tipo_catalogo_id' => 3, 'nombre' => $value, 'creado_por' => 1]);
        }

        $TiposIdentificacion = ['CEDULA', 'PASAPORTE', 'PARTIDA DE NACIMIENTO'];
        foreach ($TiposIdentificacion as $value) {
            Catalogo::create(['tipo_catalogo_id' => 4, 'nombre' => $value, 'creado_por' => 1]);
        }

        $Condiciones = ['OCUPANTE', 'PEATON'];
        foreach ($Condiciones as $value) {
            Catalogo::create(['tipo_catalogo_id' => 5, 'nombre' => $value, 'creado_por' => 1]);
        }

        $TiposFallecimientos = ['FALLECIMIENTO EN SITIO', 'FALLECIMIENTO POST ACCIDENTE'];
        foreach ($TiposFallecimientos as $value) {
            Catalogo::create(['tipo_catalogo_id' => 6, 'nombre' => $value, 'creado_por' => 1]);
        }

        $Generos = ['MASCULINO', 'FEMENINO'];
        foreach ($Generos as $value) {
            Catalogo::create(['tipo_catalogo_id' => 7, 'nombre' => $value, 'creado_por' => 1]);
        }

        $EstadosCiviles = ['SOLTERO/A', 'CASADO/A', 'DIVORCIADO/A', 'UNION DE HECHO', 'SEPARADO/A'];
        foreach ($EstadosCiviles as $value) {
            Catalogo::create(['tipo_catalogo_id' => 8, 'nombre' => $value, 'creado_por' => 1]);
        }

        $TiposVehiculos = ['DESCONOCIDO', 'AUTO', 'BUS', 'CAMION', 'CAMIONETA', 'FURGONETA', 'JEEP', 'MOTO', 'TRACTOR', 'TRAILER', 'TRICIMOTO'];
        foreach ($TiposVehiculos as $value) {
            Catalogo::create(['tipo_catalogo_id' => 9, 'nombre' => $value, 'creado_por' => 1]);
        }

        $TiposServicio = ['PARTICULAR', 'TRANSPORTE PUBLICO', 'TRANSPORTE DE CARGA', 'VEHICULO DEL ESTADO'];
        foreach ($TiposServicio as $value) {
            Catalogo::create(['tipo_catalogo_id' => 10, 'nombre' => $value, 'creado_por' => 1]);
        }

        $Parentescos = ['ESPOSO/A', 'CONVIVIENTE', 'HIJO/A', 'PADRE', 'MADRE', 'HERMANO/A', 'ABUELO/A', 'NIETO/A', 'TIO/A', 'SOBRINO/A', 'OTRO'];
        foreach ($Parentescos as $value) {
            Catalogo::create(['tipo_catalogo_id' => 11, 'nombre' => $value, 'creado_por' => 1]);
        }

        $TipoSCuenta = ['AHORROS', 'CORRIENTE', 'EXTRANJERA'];
        foreach ($TipoSCuenta as $value) {
            Catalogo::create(['tipo_catalogo_id' => 12, 'nombre' => $value, 'creado_por' => 1]);
        }

        $ObservacionesGenerales = ['P-(EXPE.PRINCIPAL)', 'A-(ADICIONAL POR SUBSANACIÓN SOLICITADA)', 'S-(SEGUNDA CARPETA)', 'R-(RESTANTE)', 'GM-Gastos médicos'];
        foreach ($ObservacionesGenerales as $value) {
            Catalogo::create(['tipo_catalogo_id' => 13, 'nombre' => $value, 'creado_por' => 1]);
        }

        $Provincias = ['AZUAY','BOLIVAR','CAÑAR','CARCHI','COTOPAXI','CHIMBORAZO','EL ORO','ESMERALDAS','GUAYAS','IMBABURA','LOJA','LOS RIOS','MANABI','MORONA SANTIAGO','NAPO','PASTAZA','PICHINCHA','TUNGURAHUA','ZAMORA CHINCHIPE','GALAPAGOS','SUCUMBIOS','ORELLANA','SANTO DOMINGO DE LOS TSACHILAS','SANTA ELENA'];
        foreach ($Provincias as $value) {
            Catalogo::create(['tipo_catalogo_id' => 14, 'nombre' => $value, 'creado_por' => 1]);
        }

        $Cantones = [
            ['CUENCA','69'],
            ['GIRÓN','69'],
            ['GUALACEO','69'],
            ['NABÓN','69'],
            ['PAUTE','69'],
            ['PUCARA','69'],
            ['SAN FERNANDO','69'],
            ['SANTA ISABEL','69'],
            ['SIGSIG','69'],
            ['OÑA','69'],
            ['CHORDELEG','69'],
            ['EL PAN','69'],
            ['SEVILLA DE ORO','69'],
            ['GUACHAPALA','69'],
            ['CAMILO PONCE ENRÍQUEZ','69'],
            ['GUARANDA','70'],
            ['CHILLANES','70'],
            ['CHIMBO','70'],
            ['ECHEANDÍA','70'],
            ['SAN MIGUEL','70'],
            ['CALUMA','70'],
            ['LAS NAVES','70'],
            ['AZOGUES','71'],
            ['BIBLIÁN','71'],
            ['CAÑAR','71'],
            ['LA TRONCAL','71'],
            ['EL TAMBO','71'],
            ['DÉLEG','71'],
            ['SUSCAL','71'],
            ['TULCÁN','72'],
            ['BOLÍVAR','72'],
            ['ESPEJO','72'],
            ['MIRA','72'],
            ['MONTÚFAR','72'],
            ['SAN PEDRO DE HUACA','72'],
            ['LATACUNGA','73'],
            ['LA MANÁ','73'],
            ['PANGUA','73'],
            ['PUJILI','73'],
            ['SALCEDO','73'],
            ['SAQUISILÍ','73'],
            ['SIGCHOS','73'],
            ['RIOBAMBA','74'],
            ['ALAUSI','74'],
            ['COLTA','74'],
            ['CHAMBO','74'],
            ['CHUNCHI','74'],
            ['GUAMOTE','74'],
            ['GUANO','74'],
            ['PALLATANGA','74'],
            ['PENIPE','74'],
            ['CUMANDÁ','74'],
            ['MACHALA','75'],
            ['ARENILLAS','75'],
            ['ATAHUALPA','75'],
            ['BALSAS','75'],
            ['CHILLA','75'],
            ['EL GUABO','75'],
            ['HUAQUILLAS','75'],
            ['MARCABELÍ','75'],
            ['PASAJE','75'],
            ['PIÑAS','75'],
            ['PORTOVELO','75'],
            ['SANTA ROSA','75'],
            ['ZARUMA','75'],
            ['LAS LAJAS','75'],
            ['ESMERALDAS','76'],
            ['ELOY ALFARO','76'],
            ['MUISNE','76'],
            ['QUININDÉ','76'],
            ['SAN LORENZO','76'],
            ['ATACAMES','76'],
            ['RIOVERDE','76'],
            ['LA CONCORDIA','76'],
            ['GUAYAQUIL','77'],
            ['ALFREDO BAQUERIZO MORENO (JUJÁN)','77'],
            ['BALAO','77'],
            ['BALZAR','77'],
            ['COLIMES','77'],
            ['DAULE','77'],
            ['DURÁN','77'],
            ['EL EMPALME','77'],
            ['EL TRIUNFO','77'],
            ['MILAGRO','77'],
            ['NARANJAL','77'],
            ['NARANJITO','77'],
            ['PALESTINA','77'],
            ['PEDRO CARBO','77'],
            ['SAMBORONDÓN','77'],
            ['SANTA LUCÍA','77'],
            ['SALITRE (URBINA JADO)','77'],
            ['SAN JACINTO DE YAGUACHI','77'],
            ['PLAYAS','77'],
            ['SIMÓN BOLÍVAR','77'],
            ['ORONEL MARCELINO MARIDUE','77'],
            ['LOMAS DE SARGENTILLO','77'],
            ['NOBOL','77'],
            ['GENERAL ANTONIO ELIZALDE','77'],
            ['ISIDRO AYORA','77'],
            ['IBARRA','78'],
            ['ANTONIO ANTE','78'],
            ['COTACACHI','78'],
            ['OTAVALO','78'],
            ['PIMAMPIRO','78'],
            ['SAN MIGUEL DE URCUQUÍ','78'],
            ['LOJA','79'],
            ['CALVAS','79'],
            ['CATAMAYO','79'],
            ['CELICA','79'],
            ['CHAGUARPAMBA','79'],
            ['ESPÍNDOLA','79'],
            ['GONZANAMÁ','79'],
            ['MACARÁ','79'],
            ['PALTAS','79'],
            ['PUYANGO','79'],
            ['SARAGURO','79'],
            ['SOZORANGA','79'],
            ['ZAPOTILLO','79'],
            ['PINDAL','79'],
            ['QUILANGA','79'],
            ['OLMEDO','79'],
            ['BABAHOYO','80'],
            ['BABA','80'],
            ['MONTALVO','80'],
            ['PUEBLOVIEJO','80'],
            ['QUEVEDO','80'],
            ['URDANETA','80'],
            ['VENTANAS','80'],
            ['VÍNCES','80'],
            ['PALENQUE','80'],
            ['BUENA FÉ','80'],
            ['VALENCIA','80'],
            ['MOCACHE','80'],
            ['QUINSALOMA','80'],
            ['PORTOVIEJO','81'],
            ['BOLÍVAR','81'],
            ['CHONE','81'],
            ['EL CARMEN','81'],
            ['FLAVIO ALFARO','81'],
            ['JIPIJAPA','81'],
            ['JUNÍN','81'],
            ['MANTA','81'],
            ['MONTECRISTI','81'],
            ['PAJÁN','81'],
            ['PICHINCHA','81'],
            ['ROCAFUERTE','81'],
            ['SANTA ANA','81'],
            ['SUCRE','81'],
            ['TOSAGUA','81'],
            ['24 DE MAYO','81'],
            ['PEDERNALES','81'],
            ['OLMEDO','81'],
            ['PUERTO LÓPEZ','81'],
            ['JAMA','81'],
            ['JARAMIJÓ','81'],
            ['SAN VICENTE','81'],
            ['MORONA','82'],
            ['GUALAQUIZA','82'],
            ['LIMÓN INDANZA','82'],
            ['PALORA','82'],
            ['SANTIAGO','82'],
            ['SUCÚA','82'],
            ['HUAMBOYA','82'],
            ['SAN JUAN BOSCO','82'],
            ['TAISHA','82'],
            ['LOGROÑO','82'],
            ['PABLO SEXTO','82'],
            ['TIWINTZA','82'],
            ['TENA','83'],
            ['ARCHIDONA','83'],
            ['EL CHACO','83'],
            ['QUIJOS','83'],
            ['CARLOS JULIO AROSEMENA TOL','83'],
            ['PASTAZA','84'],
            ['MERA','84'],
            ['SANTA CLARA','84'],
            ['ARAJUNO','84'],
            ['QUITO','85'],
            ['CAYAMBE','85'],
            ['MEJIA','85'],
            ['PEDRO MONCAYO','85'],
            ['RUMIÑAHUI','85'],
            ['SAN MIGUEL DE LOS BANCOS','85'],
            ['PEDRO VICENTE MALDONADO','85'],
            ['PUERTO QUITO','85'],
            ['AMBATO','86'],
            ['BAÑOS DE AGUA SANTA','86'],
            ['CEVALLOS','86'],
            ['MOCHA','86'],
            ['PATATE','86'],
            ['QUERO','86'],
            ['SAN PEDRO DE PELILEO','86'],
            ['SANTIAGO DE PÍLLARO','86'],
            ['TISALEO','86'],
            ['ZAMORA','87'],
            ['CHINCHIPE','87'],
            ['NANGARITZA','87'],
            ['YACUAMBI','87'],
            ['YANTZAZA (YANZATZA)','87'],
            ['EL PANGUI','87'],
            ['CENTINELA DEL CÓNDOR','87'],
            ['PALANDA','87'],
            ['PAQUISHA','87'],
            ['SAN CRISTÓBAL','88'],
            ['ISABELA','88'],
            ['SANTA CRUZ','88'],
            ['LAGO AGRIO','89'],
            ['GONZALO PIZARRO','89'],
            ['PUTUMAYO','89'],
            ['SHUSHUFINDI','89'],
            ['SUCUMBÍOS','89'],
            ['CASCALES','89'],
            ['CUYABENO','89'],
            ['ORELLANA','90'],
            ['AGUARICO','90'],
            ['LA JOYA DE LOS SACHAS','90'],
            ['LORETO','90'],
            ['SANTO DOMINGO','91'],
            ['SANTA ELENA','92'],
            ['LA LIBERTAD','92'],
            ['SALINAS','92']
        ];
        foreach ($Cantones as $value) {
            Catalogo::create(['tipo_catalogo_id' => 15, 'nombre' => $value[0], 'catalogo_id' => $value[1], 'creado_por' => 1]);
        }

        $AutodeterminacionEtnica = ['INDIGENA','AFROECUATORIANO','MONTUBIO','MESTIZO','BLANCO','ETNIA EXTRANJERA'];
        foreach ($AutodeterminacionEtnica as $value) {
            Catalogo::create(['tipo_catalogo_id' => 16, 'nombre' => $value, 'creado_por' => 1]);
        }

        $DetalleAutodeterminacionEtnica = [
            ['SIERRA','314'],
            ['AMAZONÍA','314'],
            ['COSTA','314'],
            ['INSULAR(GALÁPAGOS)','314'],
            ['NO ESPECIFICA','314'],
            ['ESMERALDAS','315'],
            ['VALLE DEL CHOTA','315'],
            ['GUAYAQUIL / COSTA','315'],
            ['SIERRA CENTRO','315'],
            ['OTRA','315'],
            ['MANABÍ','316'],
            ['LOS RÍOS','316'],
            ['GUAYAS','316'],
            ['SANTA ELENA','316'],
            ['EL ORO','316'],
            ['OTRA','316'],
            ['AFRODECENDIENTE EXTRANJERO','319'],
            ['NATIVO AMERICANO','319'],
            ['EUROPEO','319'],
            ['ASIÁTICO','319'],
            ['ÁRABE / MEDIO ORIENTE','319'],
            ['AFRICANO CONTINENTAL','319'],
            ['CARIBEÑO','319'],
            ['OCEÁNICO','319'],
            ['OTRO','319']
        ];
        foreach ($DetalleAutodeterminacionEtnica as $value) {
            Catalogo::create(['tipo_catalogo_id' => 17, 'nombre' => $value[0], 'catalogo_id' => $value[1], 'creado_por' => 1]);
        }

        $TiposDiscapacidades = ['FÍSICA','SENSORIAL','INTELECTUAL','PSICOSOCIAL O MENTAL','LENGUAJE','MÚLTIPLES'];
        foreach ($TiposDiscapacidades as $value) {
            Catalogo::create(['tipo_catalogo_id' => 18, 'nombre' => $value, 'creado_por' => 1]);
        }

        $GradosDiscapacidad = ['LEVE (5 A 24)%','MODERADA (25 A 49)%','GRAVE (50 A 74)%','MUY GRAVE (75 A 95)%','COMPLETA (96 A 100)%'];
        foreach ($GradosDiscapacidad as $value) {
            Catalogo::create(['tipo_catalogo_id' => 19, 'nombre' => $value, 'creado_por' => 1]);
        }

        $OrigenCuentaBancaria = ['NACIONAL','INTERNACIONAL'];
        foreach ($OrigenCuentaBancaria as $value) {
            Catalogo::create(['tipo_catalogo_id' => 20, 'nombre' => $value, 'creado_por' => 1]);
        }

    }
}
