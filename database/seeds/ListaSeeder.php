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
            ['TIPO RECEPCION','ACTIVO', 'PRINCIPAL', 1],
            ['TIPO ACCIDENTE','ACTIVO', 'PRINCIPAL', 1],
            ['AGENCIA','ACTIVO', 'PRINCIPAL', 1],
            ['TIPO IDENTIFICACION','ACTIVO', 'PRINCIPAL', 1],
            ['CONDICION','ACTIVO', 'PRINCIPAL', 1],
            ['TIPO FALLECIMIENTO','ACTIVO', 1],
            ['GENERO','ACTIVO', 'PRINCIPAL', 1],
            ['ESTADO CIVIL','ACTIVO', 'PRINCIPAL', 1],
            ['TIPO DE VEHICULO','ACTIVO', 'PRINCIPAL', 1],
            ['TIPO SERVICIO','ACTIVO', 'PRINCIPAL', 1],
            ['PARENTESCO VICTIMA','ACTIVO', 'PRINCIPAL', 1],
            ['TIPO CUENTA','ACTIVO', 'PRINCIPAL', 1],
            ['OBSERVACIONES GENERALES','ACTIVO', 'PRINCIPAL', 1],
            ['PROVINCIA','ACTIVO', 'PRINCIPAL', 1],
            ['CANTON','ACTIVO', 'DEPENDIENTE', 1],
            ['AUTODETERMINACION ETNICA','ACTIVO', 'PRINCIPAL', 1],
            ['DETALLE AUTODETERMINACION ETNICA','ACTIVO', 'DEPENDIENTE', 1],
            ['TIPO DISCAPACIDAD', 'PRINCIPAL', 1],
            ['GRADO DISCAPACIDAD', 'PRINCIPAL', 1],
            ['ORIGEN CUENTA BANCARIA', 'PRINCIPAL', 1]
        ];
        foreach ($Tipos as $value) {
            TipoCatalogo::create(['nombre' => $value[0],'estatus' => $value[1], 'tipo' => $value[2], 'creado_por' => $admin->id]);
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
            ['CUENCA','66'],
            ['GIRÓN','66'],
            ['GUALACEO','66'],
            ['NABÓN','66'],
            ['PAUTE','66'],
            ['PUCARA','66'],
            ['SAN FERNANDO','66'],
            ['SANTA ISABEL','66'],
            ['SIGSIG','66'],
            ['OÑA','66'],
            ['CHORDELEG','66'],
            ['EL PAN','66'],
            ['SEVILLA DE ORO','66'],
            ['GUACHAPALA','66'],
            ['CAMILO PONCE ENRÍQUEZ','66'],
            ['GUARANDA','67'],
            ['CHILLANES','67'],
            ['CHIMBO','67'],
            ['ECHEANDÍA','67'],
            ['SAN MIGUEL','67'],
            ['CALUMA','67'],
            ['LAS NAVES','67'],
            ['AZOGUES','68'],
            ['BIBLIÁN','68'],
            ['CAÑAR','68'],
            ['LA TRONCAL','68'],
            ['EL TAMBO','68'],
            ['DÉLEG','68'],
            ['SUSCAL','68'],
            ['TULCÁN','69'],
            ['BOLÍVAR','69'],
            ['ESPEJO','69'],
            ['MIRA','69'],
            ['MONTÚFAR','69'],
            ['SAN PEDRO DE HUACA','69'],
            ['LATACUNGA','70'],
            ['LA MANÁ','70'],
            ['PANGUA','70'],
            ['PUJILI','70'],
            ['SALCEDO','70'],
            ['SAQUISILÍ','70'],
            ['SIGCHOS','70'],
            ['RIOBAMBA','71'],
            ['ALAUSI','71'],
            ['COLTA','71'],
            ['CHAMBO','71'],
            ['CHUNCHI','71'],
            ['GUAMOTE','71'],
            ['GUANO','71'],
            ['PALLATANGA','71'],
            ['PENIPE','71'],
            ['CUMANDÁ','71'],
            ['MACHALA','72'],
            ['ARENILLAS','72'],
            ['ATAHUALPA','72'],
            ['BALSAS','72'],
            ['CHILLA','72'],
            ['EL GUABO','72'],
            ['HUAQUILLAS','72'],
            ['MARCABELÍ','72'],
            ['PASAJE','72'],
            ['PIÑAS','72'],
            ['PORTOVELO','72'],
            ['SANTA ROSA','72'],
            ['ZARUMA','72'],
            ['LAS LAJAS','72'],
            ['ESMERALDAS','73'],
            ['ELOY ALFARO','73'],
            ['MUISNE','73'],
            ['QUININDÉ','73'],
            ['SAN LORENZO','73'],
            ['ATACAMES','73'],
            ['RIOVERDE','73'],
            ['LA CONCORDIA','73'],
            ['GUAYAQUIL','74'],
            ['ALFREDO BAQUERIZO MORENO (JUJÁN)','74'],
            ['BALAO','74'],
            ['BALZAR','74'],
            ['COLIMES','74'],
            ['DAULE','74'],
            ['DURÁN','74'],
            ['EL EMPALME','74'],
            ['EL TRIUNFO','74'],
            ['MILAGRO','74'],
            ['NARANJAL','74'],
            ['NARANJITO','74'],
            ['PALESTINA','74'],
            ['PEDRO CARBO','74'],
            ['SAMBORONDÓN','74'],
            ['SANTA LUCÍA','74'],
            ['SALITRE (URBINA JADO)','74'],
            ['SAN JACINTO DE YAGUACHI','74'],
            ['PLAYAS','74'],
            ['SIMÓN BOLÍVAR','74'],
            ['ORONEL MARCELINO MARIDUE','74'],
            ['LOMAS DE SARGENTILLO','74'],
            ['NOBOL','74'],
            ['GENERAL ANTONIO ELIZALDE','74'],
            ['ISIDRO AYORA','74'],
            ['IBARRA','75'],
            ['ANTONIO ANTE','75'],
            ['COTACACHI','75'],
            ['OTAVALO','75'],
            ['PIMAMPIRO','75'],
            ['SAN MIGUEL DE URCUQUÍ','75'],
            ['LOJA','76'],
            ['CALVAS','76'],
            ['CATAMAYO','76'],
            ['CELICA','76'],
            ['CHAGUARPAMBA','76'],
            ['ESPÍNDOLA','76'],
            ['GONZANAMÁ','76'],
            ['MACARÁ','76'],
            ['PALTAS','76'],
            ['PUYANGO','76'],
            ['SARAGURO','76'],
            ['SOZORANGA','76'],
            ['ZAPOTILLO','76'],
            ['PINDAL','76'],
            ['QUILANGA','76'],
            ['OLMEDO','76'],
            ['BABAHOYO','77'],
            ['BABA','77'],
            ['MONTALVO','77'],
            ['PUEBLOVIEJO','77'],
            ['QUEVEDO','77'],
            ['URDANETA','77'],
            ['VENTANAS','77'],
            ['VÍNCES','77'],
            ['PALENQUE','77'],
            ['BUENA FÉ','77'],
            ['VALENCIA','77'],
            ['MOCACHE','77'],
            ['QUINSALOMA','77'],
            ['PORTOVIEJO','78'],
            ['BOLÍVAR','78'],
            ['CHONE','78'],
            ['EL CARMEN','78'],
            ['FLAVIO ALFARO','78'],
            ['JIPIJAPA','78'],
            ['JUNÍN','78'],
            ['MANTA','78'],
            ['MONTECRISTI','78'],
            ['PAJÁN','78'],
            ['PICHINCHA','78'],
            ['ROCAFUERTE','78'],
            ['SANTA ANA','78'],
            ['SUCRE','78'],
            ['TOSAGUA','78'],
            ['24 DE MAYO','78'],
            ['PEDERNALES','78'],
            ['OLMEDO','78'],
            ['PUERTO LÓPEZ','78'],
            ['JAMA','78'],
            ['JARAMIJÓ','78'],
            ['SAN VICENTE','78'],
            ['MORONA','79'],
            ['GUALAQUIZA','79'],
            ['LIMÓN INDANZA','79'],
            ['PALORA','79'],
            ['SANTIAGO','79'],
            ['SUCÚA','79'],
            ['HUAMBOYA','79'],
            ['SAN JUAN BOSCO','79'],
            ['TAISHA','79'],
            ['LOGROÑO','79'],
            ['PABLO SEXTO','79'],
            ['TIWINTZA','79'],
            ['TENA','80'],
            ['ARCHIDONA','80'],
            ['EL CHACO','80'],
            ['QUIJOS','80'],
            ['CARLOS JULIO AROSEMENA TOL','80'],
            ['PASTAZA','81'],
            ['MERA','81'],
            ['SANTA CLARA','81'],
            ['ARAJUNO','81'],
            ['QUITO','82'],
            ['CAYAMBE','82'],
            ['MEJIA','82'],
            ['PEDRO MONCAYO','82'],
            ['RUMIÑAHUI','82'],
            ['SAN MIGUEL DE LOS BANCOS','82'],
            ['PEDRO VICENTE MALDONADO','82'],
            ['PUERTO QUITO','82'],
            ['AMBATO','83'],
            ['BAÑOS DE AGUA SANTA','83'],
            ['CEVALLOS','83'],
            ['MOCHA','83'],
            ['PATATE','83'],
            ['QUERO','83'],
            ['SAN PEDRO DE PELILEO','83'],
            ['SANTIAGO DE PÍLLARO','83'],
            ['TISALEO','83'],
            ['ZAMORA','84'],
            ['CHINCHIPE','84'],
            ['NANGARITZA','84'],
            ['YACUAMBI','84'],
            ['YANTZAZA (YANZATZA)','84'],
            ['EL PANGUI','84'],
            ['CENTINELA DEL CÓNDOR','84'],
            ['PALANDA','84'],
            ['PAQUISHA','84'],
            ['SAN CRISTÓBAL','85'],
            ['ISABELA','85'],
            ['SANTA CRUZ','85'],
            ['LAGO AGRIO','86'],
            ['GONZALO PIZARRO','86'],
            ['PUTUMAYO','86'],
            ['SHUSHUFINDI','86'],
            ['SUCUMBÍOS','86'],
            ['CASCALES','86'],
            ['CUYABENO','86'],
            ['ORELLANA','87'],
            ['AGUARICO','87'],
            ['LA JOYA DE LOS SACHAS','87'],
            ['LORETO','87'],
            ['SANTO DOMINGO','88'],
            ['SANTA ELENA','89'],
            ['LA LIBERTAD','89'],
            ['SALINAS','89']

        ];
        foreach ($Cantones as $value) {
            Catalogo::create(['tipo_catalogo_id' => 15, 'nombre' => $value[0], 'catalogo_id' => $value[1], 'creado_por' => 1]);
        }

        $AutodeterminacionEtnica = ['INDIGENA','AFROECUATORIANO','MONTUBIO','MESTIZO','BLANCO','ETNIA EXTRANJERA'];
        foreach ($AutodeterminacionEtnica as $value) {
            Catalogo::create(['tipo_catalogo_id' => 16, 'nombre' => $value, 'creado_por' => 1]);
        }

        $DetalleAutodeterminacionEtnica = [
            ['SIERRA','311'],
            ['AMAZONÍA','311'],
            ['INSULAR(GALÁPAGOS)','311'],
            ['NO ESPECIFICA','311'],
            ['ESMERALDAS','312'],
            ['VALLE DEL CHOTA','312'],
            ['GUAYAQUIL / COSTA','312'],
            ['SIERRA CENTRO','312'],
            ['OTRA','312 '],
            ['MANABÍ','313'],
            ['LOS RÍOS','313'],
            ['GUAYAS','313'],
            ['SANTA ELENA','313'],
            ['EL ORO','313'],
            ['OTRA','313'],
            ['AFRODECENDIENTE EXTRANJERO','314'],
            ['NATIVO AMERICANO','314'],
            ['EUROPEO','314'],
            ['ASIÁTICO','314'],
            ['ÁRABE / MEDIO ORIENTE','314'],
            ['AFRICANO CONTINENTAL','314'],
            ['CARIBEÑO','314'],
            ['OCEÁNICO','314'],
            ['OTRO','314']
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
        foreach ($GradosDiscapacidad as $value) {
            Catalogo::create(['tipo_catalogo_id' => 20, 'nombre' => $value, 'creado_por' => 1]);
        }

    }
}
