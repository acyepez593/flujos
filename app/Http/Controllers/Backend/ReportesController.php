<?php
    
declare(strict_types=1);

namespace App\Http\Controllers\Backend;
    
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\OficioRequest;
use App\Models\Admin;
use App\Models\ConfiguracionCamposReporte;
use App\Models\EstadoTramite;
use App\Models\Institucion;
use App\Models\Oficio;
use App\Models\Rezagado;
use App\Models\RezagadoLevantamientoObjecion;
use App\Models\Extemporaneo;
use App\Models\Provincia;
use App\Models\Tipo;
use App\Models\TipoAtencion;
use App\Models\TipoEstadoCaja;
use App\Models\TipoFirma;
use App\Models\File;
use App\Models\FileExtemporaneo;
use App\Models\FileRezagado;
use App\Models\FileRezagadoLevantamientoObjecion;
use App\Models\PrestadorSalud;
use App\Models\RegistroBitacora;
use App\Models\TipoDocumento;
use App\Models\TipoIngreso;
use App\Models\TipoTramite;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Spatie\Permission\Models\Role;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportesController extends Controller
{
    public function index(): Renderable
    {
        $this->checkAuthorization(auth()->user(), ['reporte.view']);

        $tiposReporte = TipoTramite::get(["nombre", "id"]);        

        return view('backend.pages.reportes.index', [
            'tiposReporte' => $tiposReporte,
            'reportes' => [],
            'roles' => Role::all(),
        ]);
    }

    public function create(): Renderable
    {
        $this->checkAuthorization(auth()->user(), ['reporte.view']);

        $funcionario_id = Auth::id();

        $configuracion = ConfiguracionCamposReporte::where("funcionario_id",$funcionario_id);
        $tiposReporte = $configuracion->get(["nombre","id"]);
        $campos = $configuracion->get(["campos"]);
        $funcionarios = Admin::get(["name", "id"]);

        return view('backend.pages.reportes.create', [
            'tiposReporte' => $tiposReporte,
            'campos' => json_decode($campos[0]['campos'],true),
            'funcionarios' => $funcionarios
        ]);
    }

    public function getReporteByFilters(Request $request): JsonResponse
    {
        $this->checkAuthorization(auth()->user(), ['reporteTramites.view']);

        $tipoTramite = json_decode($request->tipo_tramite_search, true);

        if (in_array(1, $tipoTramite) && !in_array(2, $tipoTramite) && !in_array(3, $tipoTramite) && !in_array(4, $tipoTramite)) {
            $oficios = Oficio::where('id',">",0);
            $registros = $this->aplicarFiltros($request,$oficios);
        }

        if (in_array(2, $tipoTramite) && !in_array(1, $tipoTramite) && !in_array(3, $tipoTramite) && !in_array(3, $tipoTramite)) {
            $rezagados = Rezagado::where('id',">",0);
            $registros = $this->aplicarFiltros($request,$rezagados);
        }

        if (in_array(3, $tipoTramite) && !in_array(1, $tipoTramite) && !in_array(2, $tipoTramite) && !in_array(4, $tipoTramite)) {
            $rezagadosLevObj = RezagadoLevantamientoObjecion::where('id',">",0);
            $registros = $this->aplicarFiltros($request,$rezagadosLevObj);
        }

        if (in_array(4, $tipoTramite) && !in_array(1, $tipoTramite) && !in_array(2, $tipoTramite) && !in_array(3, $tipoTramite)) {
            $extemporaneos = Extemporaneo::where('id',">",0);
            $registros = $this->aplicarFiltros($request,$extemporaneos);
        }

        if (in_array(1, $tipoTramite) && in_array(2, $tipoTramite) && !in_array(3, $tipoTramite) && !in_array(4, $tipoTramite)) {
            $rezagados = Rezagado::where('id',">",0);
            $rezagados = $this->aplicarFiltros($request,$rezagados);
            $oficios = Oficio::where('id',">",0);
            $oficios = $this->aplicarFiltros($request,$oficios);
            $registros = $oficios->union($rezagados);
        }

        if (in_array(1, $tipoTramite) && in_array(3, $tipoTramite) && !in_array(2, $tipoTramite) && !in_array(4, $tipoTramite)) {
            $rezagadosLevObj = RezagadoLevantamientoObjecion::where('id',">",0);
            $rezagadosLevObj = $this->aplicarFiltros($request,$rezagadosLevObj);
            $oficios = Oficio::where('id',">",0);
            $oficios = $this->aplicarFiltros($request,$oficios);
            $registros = $oficios->union($rezagadosLevObj);
        }

        if (in_array(1, $tipoTramite) && in_array(4, $tipoTramite) && !in_array(2, $tipoTramite) && !in_array(3, $tipoTramite)) {
            $extemporaneos = Extemporaneo::where('id',">",0);
            $extemporaneos = $this->aplicarFiltros($request,$extemporaneos);
            $oficios = Oficio::where('id',">",0);
            $oficios = $this->aplicarFiltros($request,$oficios);
            $registros = $oficios->union($extemporaneos);
        }

        if (in_array(2, $tipoTramite) && in_array(3, $tipoTramite) && !in_array(1, $tipoTramite) && !in_array(4, $tipoTramite)) {
            $rezagadosLevObj = RezagadoLevantamientoObjecion::where('id',">",0);
            $rezagadosLevObj = $this->aplicarFiltros($request,$rezagadosLevObj);
            $rezagados = Rezagado::where('id',">",0);
            $rezagados = $this->aplicarFiltros($request,$rezagados);
            $registros = $rezagados->union($rezagadosLevObj);
        }

        if (in_array(2, $tipoTramite) && in_array(4, $tipoTramite) && !in_array(1, $tipoTramite) && !in_array(3, $tipoTramite)) {
            $extemporaneos = Extemporaneo::where('id',">",0);
            $registros = Rezagado::where('id',">",0)->union($extemporaneos);
        }

        if (in_array(3, $tipoTramite) && in_array(4, $tipoTramite) && !in_array(1, $tipoTramite) && !in_array(2, $tipoTramite)) {
            $extemporaneos = Extemporaneo::where('id',">",0);
            $extemporaneos = $this->aplicarFiltros($request,$extemporaneos);
            $rezagadosLevObj = RezagadoLevantamientoObjecion::where('id',">",0);
            $rezagadosLevObj = $this->aplicarFiltros($request,$rezagadosLevObj);
            $registros = $rezagadosLevObj->union($extemporaneos);
        }

        if (in_array(1, $tipoTramite) && in_array(2, $tipoTramite) && in_array(3, $tipoTramite) && !in_array(4, $tipoTramite)) {
            $oficios = Oficio::where('id',">",0);
            $oficios = $this->aplicarFiltros($request,$oficios);
            $rezagados = Rezagado::where('id',">",0);
            $rezagados = $this->aplicarFiltros($request,$rezagados);
            $rezagadosLevObj = RezagadoLevantamientoObjecion::where('id',">",0);
            $rezagadosLevObj = $this->aplicarFiltros($request,$rezagadosLevObj);
            $registros = $oficios->union($rezagados)->union($rezagadosLevObj);
        }

        if (in_array(1, $tipoTramite) && in_array(2, $tipoTramite) && in_array(4, $tipoTramite) && !in_array(3, $tipoTramite)) {
            $oficios = Oficio::where('id',">",0);
            $oficios = $this->aplicarFiltros($request,$oficios);
            $rezagados = Rezagado::where('id',">",0);
            $rezagados = $this->aplicarFiltros($request,$rezagados);
            $extemporaneos = Extemporaneo::where('id',">",0);
            $extemporaneos = $this->aplicarFiltros($request,$extemporaneos);
            $registros = $oficios->union($rezagados)->union($extemporaneos);
        }

        if (in_array(1, $tipoTramite) && in_array(3, $tipoTramite) && in_array(4, $tipoTramite) && !in_array(2, $tipoTramite)) {
            $oficios = Oficio::where('id',">",0);
            $oficios = $this->aplicarFiltros($request,$oficios);
            $rezagadosLevObj = RezagadoLevantamientoObjecion::where('id',">",0);
            $rezagadosLevObj = $this->aplicarFiltros($request,$rezagadosLevObj);
            $extemporaneos = Extemporaneo::where('id',">",0);
            $extemporaneos = $this->aplicarFiltros($request,$extemporaneos);
            $registros = $oficios->union($rezagadosLevObj)->union($extemporaneos);
        }

        if (in_array(2, $tipoTramite) && in_array(3, $tipoTramite) && in_array(4, $tipoTramite) && !in_array(1, $tipoTramite)) {
            $rezagados = Rezagado::where('id',">",0);
            $rezagados = $this->aplicarFiltros($request,$rezagados);
            $rezagadosLevObj = RezagadoLevantamientoObjecion::where('id',">",0);
            $rezagadosLevObj = $this->aplicarFiltros($request,$rezagadosLevObj);
            $extemporaneos = Extemporaneo::where('id',">",0);
            $extemporaneos = $this->aplicarFiltros($request,$extemporaneos);
            $registros = $rezagados->union($rezagadosLevObj)->union($extemporaneos);
        }

        if (in_array(1, $tipoTramite) && in_array(2, $tipoTramite) && in_array(3, $tipoTramite) && in_array(4, $tipoTramite)) {
            $oficios = Oficio::where('id',">",0);
            $oficios = $this->aplicarFiltros($request,$oficios);
            $rezagados = Rezagado::where('id',">",0);
            $rezagados = $this->aplicarFiltros($request,$rezagados);
            $rezagadosLevObj = RezagadoLevantamientoObjecion::where('id',">",0);
            $rezagadosLevObj = $this->aplicarFiltros($request,$rezagadosLevObj);
            $extemporaneos = Extemporaneo::where('id',">",0);
            $extemporaneos = $this->aplicarFiltros($request,$extemporaneos);
            $registros = $oficios->union($rezagados)->union($rezagadosLevObj)->union($extemporaneos);
        }
        
        $registros = $registros->orderBy('id', 'desc')->get();

        $registrosByTramiteTmp = $registros->groupBy('tipo_tramite_id');
        $registrosByTramite = [];
        foreach($registrosByTramiteTmp as $index => $registro){
            $registrosByTramite[$index] = $registro->pluck('id');
        }
        $filesByTipoTramite = [];
        foreach($registrosByTramite as $tramite_id => $ids){
            if($tramite_id == 1){
                $oficiosFiles = File::whereIn('oficio_id',$ids)->get();
                $oficiosFiles = collect($oficiosFiles)->groupBy('oficio_id');
                $filesByTipoTramite[$tramite_id] = $oficiosFiles;
            }
            if($tramite_id == 2){
                $rezagadosFiles = FileRezagado::whereIn('rezagado_id',$ids)->get();
                $rezagadosFiles = collect($rezagadosFiles)->groupBy('rezagado_id');
                $filesByTipoTramite[$tramite_id] = $rezagadosFiles;
            }
            if($tramite_id == 3){
                $rezagadosLevObjFiles = FileRezagadoLevantamientoObjecion::whereIn('rezagado_levantamiento_objecion_id',$ids)->get();
                $rezagadosLevObjFiles = collect($rezagadosLevObjFiles)->groupBy('rezagado_levantamiento_objecion_id');
                $filesByTipoTramite[$tramite_id] = $rezagadosLevObjFiles;
            }
            if($tramite_id == 4){
                $extemporaneosFiles = FileExtemporaneo::whereIn('extemporaneo_id',$ids)->get();
                $extemporaneosFiles = collect($extemporaneosFiles)->groupBy('extemporaneo_id');
                $filesByTipoTramite[$tramite_id] = $extemporaneosFiles;
            }
        }

        $tipos = Tipo::all();
        $tipos_atencion = TipoAtencion::all();
        $tipos_tramite = TipoTramite::all();
        $tipos_estado_caja = TipoEstadoCaja::all();
        $provincias = Provincia::all();
        $instituciones = Institucion::all();
        $tipos_firma = TipoFirma::all();
        $responsables = Admin::all();

        $tipos_temp = [];
        foreach($tipos as $tipo){
            $tipos_temp[$tipo->id] = $tipo->nombre;
        }
        $tipos_atencion_temp = [];
        foreach($tipos_atencion as $tipo_atencion){
            $tipos_atencion_temp[$tipo_atencion->id] = $tipo_atencion->nombre;
        }
        $tipos_tramite_temp = [];
        foreach($tipos_tramite as $tipo_tramite){
            $tipos_tramite_temp[$tipo_tramite->id] = $tipo_tramite->nombre;
        }
        $provincias_temp = [];
        foreach($provincias as $provincia){
            $provincias_temp[$provincia->id] = $provincia->nombre;
        }
        $instituciones_temp = [];
        foreach($instituciones as $institucion){
            $instituciones_temp[$institucion->id] = $institucion->nombre;
        }
        $tipos_firma_temp = [];
        foreach($tipos_firma as $tipo_firma){
            $tipos_firma_temp[$tipo_firma->id] = $tipo_firma->nombre;
        }
        $responsables_temp = [];
        foreach($responsables as $responsable){
            $responsables_temp[$responsable->id] = $responsable->name;
        }

        $tipos_estado_caja_temp = [];
        foreach($tipos_estado_caja as $tipo_estado_caja){
            $tipos_estado_caja_temp[$tipo_estado_caja->id] = $tipo_estado_caja->nombre;
        }

        $responsable_id = Auth::id();

        foreach($registros as $registro){
            $registro->tipo_nombre = array_key_exists($registro->tipo_id, $tipos_temp) ? $tipos_temp[$registro->tipo_id] : "";
            $registro->tipo_atencion_nombre = array_key_exists($registro->tipo_atencion_id, $tipos_atencion_temp) ? $tipos_atencion_temp[$registro->tipo_atencion_id] : "";
            $registro->tipo_tramite_nombre = array_key_exists($registro->tipo_tramite_id, $tipos_tramite_temp) ? $tipos_tramite_temp[$registro->tipo_tramite_id] : "";
            $registro->provincia_nombre = array_key_exists($registro->provincia_id, $provincias_temp) ? $provincias_temp[$registro->provincia_id] : "";
            $registro->institucion_nombre = array_key_exists($registro->institucion_id, $instituciones_temp) ? $instituciones_temp[$registro->institucion_id] : "";
            $registro->tipo_firma_nombre = array_key_exists($registro->tipo_firma_id, $tipos_firma_temp) ? $tipos_firma_temp[$registro->tipo_firma_id] : "";
            $registro->responsable_nombre = array_key_exists($registro->responsable_id, $responsables_temp) ? $responsables_temp[$registro->responsable_id] : "";
            $registro->tipo_estado_caja_nombre = array_key_exists($registro->estado_caja_id, $tipos_estado_caja_temp) ? $tipos_estado_caja_temp[$registro->estado_caja_id] : "";
            $registro->esCreadorRegistro = $responsable_id == $registro->responsable_id ? true : false;
            //$registro->files = $registro->files;
        }
        

        $data['registros'] = $registros;
        $data['tipos'] = $tipos;
        $data['tipos_atencion'] = $tipos_atencion;
        $data['tipos_tramite'] = $tipos_tramite;
        $data['tipos_estado_caja'] = $tipos_estado_caja;
        $data['provincias'] = $provincias;
        $data['instituciones'] = $instituciones;
        $data['tipos_firma'] = $tipos_firma;
        $data['responsables'] = $responsables;
        $data['filesByTipoTramite'] = $filesByTipoTramite;
        $data['roles'] = Role::all();
  
        return response()->json($data);
    }

    public function aplicarFiltros(Request $request, $registros)
    {
        $tipoTramite = json_decode($request->tipo_tramite_search, true);
        $filtroFechaRegistroDesdeSearch = $request->fecha_registro_desde_search;
        $filtroFechaRegistroHastaSearch = $request->fecha_registro_hasta_search;
        $filtroTipoIdSearch = json_decode($request->tipo_id_search, true);
        $filtroRucSearch = $request->ruc_search;
        $filtroNumeroEstablecimientoSearch = $request->numero_establecimiento_search;
        $filtroRazonSocialSearch = $request->razon_social_search;
        $filtroFechaRecepcionDesdeSearch = $request->fecha_recepcion_desde_search;
        $filtroFechaRecepcionHastaSearch = $request->fecha_recepcion_hasta_search;
        $filtrotipoAtencionIdSearch = json_decode($request->tipo_atencion_id_search, true);
        $filtroProvinciaIdSearch = json_decode($request->provincia_id_search, true);
        $filtroFechaServicioDesdeSearch = $request->fecha_servicio_desde_search;
        $filtroFechaServicioHastaSearch = $request->fecha_servicio_hasta_search;
        $filtroNumeroCasosSearch = $request->numero_casos_search;
        $filtroMontoPlanillaSearch = $request->monto_planilla_search;
        $filtroNumeroCajaAntSearch = $request->numero_caja_ant_search;
        $filtroNumeroCajaSearch = $request->numero_caja_search;
        $filtroTipoEstadoCajaIdSearch = json_decode($request->tipo_estado_caja_id_search, true);
        $filtroNumeroCajaAuditoriaSearch = $request->numero_caja_auditoria_search;
        $filtroFechaEnvioAuditoriaDesdeSearch = $request->fecha_envio_auditoria_desde_search;
        $filtroFechaEnvioAuditoriaHastaSearch = $request->fecha_envio_auditoria_hasta_search;
        $filtroInstitucionIdSearch = json_decode($request->institucion_id_search, true);
        $filtroDocumentoExternoSearch = $request->documento_externo_search;
        $filtroTipoFirmaSearch = json_decode($request->tipo_firma_search, true);
        $filtroObservacionSearch = $request->observacion_search;
        $filtroNumeroQuipuxSearch = $request->numero_quipux_search;
        if(in_array(4, $tipoTramite)){
            $filtroPeriodoSearch = $request->periodo_search;
        }
        $filtroEstadoTramiteIdSearch = json_decode($request->estado_tramite_id_search, true);
        $filtroFechaDevolucionAuditoriaDesdeSearch = $request->fecha_devolucion_auditoria_desde_search;
        $filtroFechaDevolucionAuditoriaHastaSearch = $request->fecha_devolucion_auditoria_hasta_search;
        $filtroFechaDevolucionPrestadorDesdeSearch = $request->fecha_devolucion_prestador_desde_search;
        $filtroFechaDevolucionPrestadorHastaSearch = $request->fecha_devolucion_prestador_hasta_search;
        $filtroObservacionDevolucionAuditoriaSearch = $request->observacion_devolucion_auditoria_search;
        $filtroObservacionDevolucionPrestadorSearch = $request->observacion_devolucion_prestador_search;
        $filtroResponsableSearch = json_decode($request->responsable_id_search, true);

        if(isset($filtroFechaRegistroDesdeSearch) && !empty($filtroFechaRegistroDesdeSearch)){
            $registros = $registros->where('fecha_registro', '>=', $filtroFechaRegistroDesdeSearch);
        }
        if(isset($filtroFechaRegistroHastaSearch) && !empty($filtroFechaRegistroHastaSearch)){
            $registros = $registros->where('fecha_registro', '<=', $filtroFechaRegistroHastaSearch);
        }
        if(isset($filtroTipoIdSearch) && !empty($filtroTipoIdSearch)){
            $registros = $registros->whereIn('tipo_id', $filtroTipoIdSearch);
        }
        if(isset($filtroRucSearch) && !empty($filtroRucSearch)){
            $registros = $registros->where('ruc', 'like', '%'.$filtroRucSearch.'%');
        }
        if(isset($filtroNumeroEstablecimientoSearch) && !empty($filtroNumeroEstablecimientoSearch)){
            $registros = $registros->where('numero_establecimiento', 'like', '%'.$filtroNumeroEstablecimientoSearch.'%');
        }
        if(isset($filtroRazonSocialSearch) && !empty($filtroRazonSocialSearch)){
            $registros = $registros->where('razon_social', 'like', '%'.$filtroRazonSocialSearch.'%');
        }
        if(isset($filtroFechaRecepcionDesdeSearch) && !empty($filtroFechaRecepcionDesdeSearch)){
            $registros = $registros->where('fecha_recepcion', '>=' , $filtroFechaRecepcionDesdeSearch);
        }
        if(isset($filtroFechaRecepcionHastaSearch) && !empty($filtroFechaRecepcionHastaSearch)){
            $registros = $registros->where('fecha_recepcion', '<=' , $filtroFechaRecepcionHastaSearch);
        }
        if(isset($filtrotipoAtencionIdSearch) && !empty($filtrotipoAtencionIdSearch)){
            $registros = $registros->whereIn('tipo_atencion_id', $filtrotipoAtencionIdSearch);
        }
        if(isset($filtroProvinciaIdSearch) && !empty($filtroProvinciaIdSearch)){
            $registros = $registros->whereIn('provincia_id', $filtroProvinciaIdSearch);
        }
        if(isset($filtroFechaServicioDesdeSearch) && !empty($filtroFechaServicioDesdeSearch)){
            $registros = $registros->where('fecha_servicio', '>=' , $filtroFechaServicioDesdeSearch);
        }
        if(isset($filtroFechaServicioHastaSearch) && !empty($filtroFechaServicioHastaSearch)){
            $registros = $registros->where('fecha_servicio', '<=' , $filtroFechaServicioHastaSearch);
        }
        if(isset($filtroNumeroCasosSearch) && !empty($filtroNumeroCasosSearch)){
            $registros = $registros->where('numero_casos', $filtroNumeroCasosSearch);
        }
        if(isset($filtroMontoPlanillaSearch) && !empty($filtroMontoPlanillaSearch)){
            $registros = $registros->where('monto_planilla', $filtroMontoPlanillaSearch);
        }
        if(isset($filtroNumeroCajaAntSearch) && !empty($filtroNumeroCajaAntSearch)){
            $registros = $registros->where('numero_caja_ant', 'like', '%'.$filtroNumeroCajaAntSearch.'%');
        }
        if(isset($filtroNumeroCajaSearch) && !empty($filtroNumeroCajaSearch)){
            $registros = $registros->where('numero_caja', 'like', '%'.$filtroNumeroCajaSearch.'%');
        }
        if(isset($filtroTipoEstadoCajaIdSearch) && !empty($filtroTipoEstadoCajaIdSearch)){
            $registros = $registros->whereIn('estado_caja_id', $filtroTipoEstadoCajaIdSearch);
        }
        if(isset($filtroNumeroCajaAuditoriaSearch) && !empty($filtroNumeroCajaAuditoriaSearch)){
            $registros = $registros->where('numero_caja_auditoria', 'like', '%'.$filtroNumeroCajaAuditoriaSearch.'%');
        }
        if(isset($filtroFechaEnvioAuditoriaDesdeSearch) && !empty($filtroFechaEnvioAuditoriaDesdeSearch)){
            $registros = $registros->where('fecha_envio_auditoria', '>=' , $filtroFechaEnvioAuditoriaDesdeSearch);
        }
        if(isset($filtroFechaEnvioAuditoriaHastaSearch) && !empty($filtroFechaEnvioAuditoriaHastaSearch)){
            $registros = $registros->where('fecha_envio_auditoria', '<=' , $filtroFechaEnvioAuditoriaHastaSearch);
        }
        if(isset($filtroInstitucionIdSearch) && !empty($filtroInstitucionIdSearch)){
            $registros = $registros->whereIn('institucion_id', $filtroInstitucionIdSearch);
        }
        if(isset($filtroDocumentoExternoSearch) && !empty($filtroDocumentoExternoSearch)){
            $registros = $registros->where('documento_externo', 'like', '%'.$filtroDocumentoExternoSearch.'%');
        }
        if(isset($filtroTipoFirmaSearch) && !empty($filtroTipoFirmaSearch)){
            $registros = $registros->whereIn('tipo_firma_id', $filtroTipoFirmaSearch);
        }
        if(isset($filtroObservacionSearch) && !empty($filtroObservacionSearch)){
            $registros = $registros->where('observaciones', 'like', '%'.$filtroObservacionSearch.'%');
        }
        if(isset($filtroNumeroQuipuxSearch) && !empty($filtroNumeroQuipuxSearch)){
            $registros = $registros->where('numero_quipux', 'like', '%'.$filtroNumeroQuipuxSearch.'%');
        }
        if(in_array(4, $tipoTramite)){
            if(isset($filtroPeriodoSearch) && !empty($filtroPeriodoSearch)){
                $registros = $registros->where('periodo', 'like', '%'.$filtroPeriodoSearch.'%');
            }
        }
        if(isset($filtroEstadoTramiteIdSearch) && !empty($filtroEstadoTramiteIdSearch)){
            $registros = $registros->whereIn('estado_tramite_id', $filtroEstadoTramiteIdSearch);
        }
        if(isset($filtroFechaDevolucionAuditoriaDesdeSearch) && !empty($filtroFechaDevolucionAuditoriaDesdeSearch)){
            $registros = $registros->where('fecha_devolucion_auditoria', '>=', $filtroFechaDevolucionAuditoriaDesdeSearch);
        }
        if(isset($filtroFechaDevolucionAuditoriaHastaSearch) && !empty($filtroFechaDevolucionAuditoriaHastaSearch)){
            $registros = $registros->where('fecha_devolucion_auditoria', '<=', $filtroFechaDevolucionAuditoriaHastaSearch);
        }
        if(isset($filtroFechaDevolucionPrestadorDesdeSearch) && !empty($filtroFechaDevolucionPrestadorDesdeSearch)){
            $registros = $registros->where('fecha_devolucion_prestador', '>=', $filtroFechaDevolucionPrestadorDesdeSearch);
        }
        if(isset($filtroFechaDevolucionPrestadorHastaSearch) && !empty($filtroFechaDevolucionPrestadorHastaSearch)){
            $registros = $registros->where('fecha_devolucion_prestador', '<=', $filtroFechaDevolucionPrestadorHastaSearch);
        }
        if(isset($filtroObservacionDevolucionAuditoriaSearch) && !empty($filtroObservacionDevolucionAuditoriaSearch)){
            $registros = $registros->where('observaciones_devolucion_auditoria', 'like', '%'.$filtroObservacionDevolucionAuditoriaSearch.'%');
        }
        if(isset($filtroObservacionDevolucionPrestadorSearch) && !empty($filtroObservacionDevolucionPrestadorSearch)){
            $registros = $registros->where('observaciones_devolucion_prestador', 'like', '%'.$filtroObservacionDevolucionPrestadorSearch.'%');
        }
        
        if(isset($filtroResponsableSearch) && !empty($filtroResponsableSearch)){
            $registros = $registros->whereIn('responsable_id', $filtroResponsableSearch);
        }

        return $registros;
    }

    public function generarReporteByTipoReporteOld(Request $request)
    {
        $this->checkAuthorization(auth()->user(), ['reporteTramites.download']);

        ini_set('memory_limit', '-1'); // anula el limite 
        $tipoReporte = json_decode($request->tipo_reporte_search, true);

        if (in_array(1, $tipoReporte) && !in_array(2, $tipoReporte) && !in_array(3, $tipoReporte) && !in_array(4, $tipoReporte)) {
            $oficios = Oficio::where('id',">",0);
            $registros = $this->aplicarFiltros($request,$oficios);
        }

        if (in_array(2, $tipoReporte) && !in_array(1, $tipoReporte) && !in_array(3, $tipoReporte) && !in_array(3, $tipoReporte)) {
            $rezagados = Rezagado::where('id',">",0);
            $registros = $this->aplicarFiltros($request,$rezagados);
        }

        if (in_array(3, $tipoReporte) && !in_array(1, $tipoReporte) && !in_array(2, $tipoReporte) && !in_array(4, $tipoReporte)) {
            $rezagadosLevObj = RezagadoLevantamientoObjecion::where('id',">",0);
            $registros = $this->aplicarFiltros($request,$rezagadosLevObj);
        }

        if (in_array(4, $tipoReporte) && !in_array(1, $tipoReporte) && !in_array(2, $tipoReporte) && !in_array(3, $tipoReporte)) {
            $extemporaneos = Extemporaneo::where('id',">",0);
            $registros = $this->aplicarFiltros($request,$extemporaneos);
        }

        if (in_array(1, $tipoReporte) && in_array(2, $tipoReporte) && !in_array(3, $tipoReporte) && !in_array(4, $tipoReporte)) {
            $rezagados = Rezagado::where('id',">",0);
            $rezagados = $this->aplicarFiltros($request,$rezagados);
            $oficios = Oficio::where('id',">",0);
            $oficios = $this->aplicarFiltros($request,$oficios);
            $registros = $oficios->union($rezagados);
        }

        if (in_array(1, $tipoReporte) && in_array(3, $tipoReporte) && !in_array(2, $tipoReporte) && !in_array(4, $tipoReporte)) {
            $rezagadosLevObj = RezagadoLevantamientoObjecion::where('id',">",0);
            $rezagadosLevObj = $this->aplicarFiltros($request,$rezagadosLevObj);
            $oficios = Oficio::where('id',">",0);
            $oficios = $this->aplicarFiltros($request,$oficios);
            $registros = $oficios->union($rezagadosLevObj);
        }

        if (in_array(1, $tipoReporte) && in_array(4, $tipoReporte) && !in_array(2, $tipoReporte) && !in_array(3, $tipoReporte)) {
            $extemporaneos = Extemporaneo::where('id',">",0);
            $extemporaneos = $this->aplicarFiltros($request,$extemporaneos);
            $oficios = Oficio::where('id',">",0);
            $oficios = $this->aplicarFiltros($request,$oficios);
            $registros = $oficios->union($extemporaneos);
        }

        if (in_array(2, $tipoReporte) && in_array(3, $tipoReporte) && !in_array(1, $tipoReporte) && !in_array(4, $tipoReporte)) {
            $rezagadosLevObj = RezagadoLevantamientoObjecion::where('id',">",0);
            $rezagadosLevObj = $this->aplicarFiltros($request,$rezagadosLevObj);
            $rezagados = Rezagado::where('id',">",0);
            $rezagados = $this->aplicarFiltros($request,$rezagados);
            $registros = $rezagados->union($rezagadosLevObj);
        }

        if (in_array(2, $tipoReporte) && in_array(4, $tipoReporte) && !in_array(1, $tipoReporte) && !in_array(3, $tipoReporte)) {
            $extemporaneos = Extemporaneo::where('id',">",0);
            $registros = Rezagado::where('id',">",0)->union($extemporaneos);
        }

        if (in_array(3, $tipoReporte) && in_array(4, $tipoReporte) && !in_array(1, $tipoReporte) && !in_array(2, $tipoReporte)) {
            $extemporaneos = Extemporaneo::where('id',">",0);
            $extemporaneos = $this->aplicarFiltros($request,$extemporaneos);
            $rezagadosLevObj = RezagadoLevantamientoObjecion::where('id',">",0);
            $rezagadosLevObj = $this->aplicarFiltros($request,$rezagadosLevObj);
            $registros = $rezagadosLevObj->union($extemporaneos);
        }

        if (in_array(1, $tipoReporte) && in_array(2, $tipoReporte) && in_array(3, $tipoReporte) && !in_array(4, $tipoReporte)) {
            $oficios = Oficio::where('id',">",0);
            $oficios = $this->aplicarFiltros($request,$oficios);
            $rezagados = Rezagado::where('id',">",0);
            $rezagados = $this->aplicarFiltros($request,$rezagados);
            $rezagadosLevObj = RezagadoLevantamientoObjecion::where('id',">",0);
            $rezagadosLevObj = $this->aplicarFiltros($request,$rezagadosLevObj);
            $registros = $oficios->union($rezagados)->union($rezagadosLevObj);
        }

        if (in_array(1, $tipoReporte) && in_array(2, $tipoReporte) && in_array(4, $tipoReporte) && !in_array(3, $tipoReporte)) {
            $oficios = Oficio::where('id',">",0);
            $oficios = $this->aplicarFiltros($request,$oficios);
            $rezagados = Rezagado::where('id',">",0);
            $rezagados = $this->aplicarFiltros($request,$rezagados);
            $extemporaneos = Extemporaneo::where('id',">",0);
            $extemporaneos = $this->aplicarFiltros($request,$extemporaneos);
            $registros = $oficios->union($rezagados)->union($extemporaneos);
        }

        if (in_array(1, $tipoReporte) && in_array(3, $tipoReporte) && in_array(4, $tipoReporte) && !in_array(2, $tipoReporte)) {
            $oficios = Oficio::where('id',">",0);
            $oficios = $this->aplicarFiltros($request,$oficios);
            $rezagadosLevObj = RezagadoLevantamientoObjecion::where('id',">",0);
            $rezagadosLevObj = $this->aplicarFiltros($request,$rezagadosLevObj);
            $extemporaneos = Extemporaneo::where('id',">",0);
            $extemporaneos = $this->aplicarFiltros($request,$extemporaneos);
            $registros = $oficios->union($rezagadosLevObj)->union($extemporaneos);
        }

        if (in_array(2, $tipoReporte) && in_array(3, $tipoReporte) && in_array(4, $tipoReporte) && !in_array(1, $tipoReporte)) {
            $rezagados = Rezagado::where('id',">",0);
            $rezagados = $this->aplicarFiltros($request,$rezagados);
            $rezagadosLevObj = RezagadoLevantamientoObjecion::where('id',">",0);
            $rezagadosLevObj = $this->aplicarFiltros($request,$rezagadosLevObj);
            $extemporaneos = Extemporaneo::where('id',">",0);
            $extemporaneos = $this->aplicarFiltros($request,$extemporaneos);
            $registros = $rezagados->union($rezagadosLevObj)->union($extemporaneos);
        }

        if (in_array(1, $tipoReporte) && in_array(2, $tipoReporte) && in_array(3, $tipoReporte) && in_array(4, $tipoReporte)) {
            $oficios = Oficio::where('id',">",0);
            $oficios = $this->aplicarFiltros($request,$oficios);
            $rezagados = Rezagado::where('id',">",0);
            $rezagados = $this->aplicarFiltros($request,$rezagados);
            $rezagadosLevObj = RezagadoLevantamientoObjecion::where('id',">",0);
            $rezagadosLevObj = $this->aplicarFiltros($request,$rezagadosLevObj);
            $extemporaneos = Extemporaneo::where('id',">",0);
            $extemporaneos = $this->aplicarFiltros($request,$extemporaneos);
            $registros = $oficios->union($rezagados)->union($rezagadosLevObj)->union($extemporaneos);
        }
        
        $registros = $registros->orderBy('id', 'desc')->get();

        $tipos = Tipo::get(["nombre", "id"])->pluck('nombre','id');
        $tiposAtencion = TipoAtencion::get(["nombre", "id"])->pluck('nombre','id');
        $tiposTramite = TipoTramite::get(["nombre", "id"])->pluck('nombre','id');
        $estadosTramite = EstadoTramite::get(["nombre", "id"])->pluck('nombre','id');
        $tiposEstadoCaja = TipoEstadoCaja::get(["nombre", "id"])->pluck('nombre','id');
        $provincias = Provincia::get(["nombre", "id"])->pluck('nombre','id');
        $instituciones = Institucion::get(["nombre", "id"])->pluck('nombre','id');
        $tiposFirma = TipoFirma::get(["nombre", "id"])->pluck('nombre','id');
        $responsables = Admin::get(["name", "id"])->pluck('name','id');

        $fileName = 'FormatoReporte.xlsx';

        if(public_path('uploads/'.$fileName)){
            $inputFileName = public_path('reporte/'.$fileName);
            $inputFileType = \PhpOffice\PhpSpreadsheet\IOFactory::identify($inputFileName);
            $reader = IOFactory::createReader($inputFileType);
            $spreadsheet = $reader->load($inputFileName);

            $active_sheet = $spreadsheet->getActiveSheet();

            $celdaInicio = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD'];
            $columnaInicio = 2;
            $columnaInicioPivot = 2;

            foreach ($registros as $registro) {
                $active_sheet->setCellValue($celdaInicio[0].$columnaInicioPivot, !isset($registro->fecha_registro) || empty($registro->fecha_registro) || $registro->fecha_registro == '0000-00-00' ? "" : Carbon::createFromFormat('Y-m-d', $registro->fecha_registro)->format('d-M-Y'));
                $active_sheet->setCellValue($celdaInicio[1].$columnaInicioPivot, $registro->tipo_tramite_id > 0 ? $tiposTramite[$registro->tipo_tramite_id] : "");
                $active_sheet->getCell($celdaInicio[2].$columnaInicioPivot)->setValueExplicit($registro->ruc,\PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING2);
                $active_sheet->getCell($celdaInicio[3].$columnaInicioPivot)->setValueExplicit($registro->numero_establecimiento,\PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING2);
                $active_sheet->setCellValue($celdaInicio[4].$columnaInicioPivot, $registro->razon_social);
                $active_sheet->setCellValue($celdaInicio[5].$columnaInicioPivot, !isset($registro->fecha_recepcion) || empty($registro->fecha_recepcion) || $registro->fecha_recepcion == '0000-00-00' ? "" : Carbon::createFromFormat('Y-m-d', $registro->fecha_recepcion)->format('d-M-Y'));
                $active_sheet->setCellValue($celdaInicio[6].$columnaInicioPivot, $registro->tipo_atencion_id > 0 ? $tiposAtencion[$registro->tipo_atencion_id] : "");
                $active_sheet->setCellValue($celdaInicio[7].$columnaInicioPivot, $registro->provincia_id > 0 ? $provincias[$registro->provincia_id] : "");
                $active_sheet->setCellValue($celdaInicio[8].$columnaInicioPivot, !isset($registro->fecha_servicio) || empty($registro->fecha_servicio) || $registro->fecha_servicio == '0000-00-00' ? "" : Carbon::createFromFormat('Y-m-d', $registro->fecha_servicio)->format('M-Y'));
                $active_sheet->setCellValue($celdaInicio[9].$columnaInicioPivot, $registro->numero_casos);
                $active_sheet->setCellValue($celdaInicio[10].$columnaInicioPivot, $registro->monto_planilla);
                $active_sheet->setCellValue($celdaInicio[11].$columnaInicioPivot, $registro->numero_caja_ant);
                $active_sheet->setCellValue($celdaInicio[12].$columnaInicioPivot, $registro->numero_caja);
                $active_sheet->setCellValue($celdaInicio[13].$columnaInicioPivot, $registro->estado_caja_id > 0 ? $tiposEstadoCaja[$registro->estado_caja_id] : "");
                $active_sheet->setCellValue($celdaInicio[14].$columnaInicioPivot, $registro->numero_caja_auditoria);
                $active_sheet->setCellValue($celdaInicio[15].$columnaInicioPivot, !isset($registro->fecha_envio_auditoria) || empty($registro->fecha_envio_auditoria) || $registro->fecha_envio_auditoria == '0000-00-00' ? "" : Carbon::createFromFormat('Y-m-d', $registro->fecha_envio_auditoria)->format('d-M-Y'));
                $active_sheet->setCellValue($celdaInicio[16].$columnaInicioPivot, $registro->institucion_id > 0 ? $instituciones[$registro->institucion_id] : "");
                $active_sheet->setCellValue($celdaInicio[17].$columnaInicioPivot, $registro->documento_externo);
                $active_sheet->setCellValue($celdaInicio[18].$columnaInicioPivot, $registro->tipo_firma_id > 0 ? $tiposFirma[$registro->tipo_firma_id] : "");
                $active_sheet->setCellValue($celdaInicio[19].$columnaInicioPivot, $registro->observaciones);
                $active_sheet->setCellValue($celdaInicio[20].$columnaInicioPivot, $registro->numero_quipux);
                $active_sheet->setCellValue($celdaInicio[21].$columnaInicioPivot, $registro->responsable_id > 0 ? $responsables[$registro->responsable_id] : "");
                $active_sheet->setCellValue($celdaInicio[22].$columnaInicioPivot, $registro->es_historico == 1 ? "SI" : "NO");
                $active_sheet->setCellValue($celdaInicio[23].$columnaInicioPivot, !isset($registro->periodo) || empty($registro->periodo) ? "" : $registro->periodo);
                $active_sheet->setCellValue($celdaInicio[24].$columnaInicioPivot, $registro->estado_tramite_id > 0 ? $estadosTramite[$registro->estado_tramite_id] : "");
                $active_sheet->setCellValue($celdaInicio[25].$columnaInicioPivot, !isset($registro->fecha_devolucion_auditoria) || empty($registro->fecha_devolucion_auditoria) || $registro->fecha_devolucion_auditoria == '0000-00-00' ? "" : Carbon::createFromFormat('Y-m-d', $registro->fecha_devolucion_auditoria)->format('d-M-Y'));
                $active_sheet->setCellValue($celdaInicio[26].$columnaInicioPivot, $registro->observaciones_devolucion_auditoria);
                $active_sheet->setCellValue($celdaInicio[27].$columnaInicioPivot, !isset($registro->fecha_devolucion_prestador) || empty($registro->fecha_devolucion_prestador) || $registro->fecha_devolucion_prestador == '0000-00-00' ? "" : Carbon::createFromFormat('Y-m-d', $registro->fecha_devolucion_prestador)->format('d-M-Y'));
                $active_sheet->setCellValue($celdaInicio[28].$columnaInicioPivot, $registro->observaciones_devolucion_prestador);
                $active_sheet->setCellValue($celdaInicio[29].$columnaInicioPivot, !isset($registro->id) || empty($registro->id) ? "" : $registro->id);

                $columnaInicioPivot += 1;
            }
            $active_sheet->getStyle($celdaInicio[10].$columnaInicio.':'.$celdaInicio[10].$columnaInicioPivot)->getNumberFormat()->setFormatCode('"$"#,##0.00');
            
            $rangoSumaNumeroCasos = $celdaInicio[9].$columnaInicio.':'.$celdaInicio[9].$columnaInicioPivot-1;
            $rangoSumaMontoPlanilla = $celdaInicio[10].$columnaInicio.':'.$celdaInicio[10].$columnaInicioPivot-1;
            $active_sheet->setCellValue($celdaInicio[9].$columnaInicioPivot , '=SUM('.$rangoSumaNumeroCasos.')');
            $active_sheet->setCellValue($celdaInicio[10].$columnaInicioPivot , '=SUM('.$rangoSumaMontoPlanilla.')');

            $active_sheet->getStyle($celdaInicio[9].$columnaInicioPivot.':'.$celdaInicio[10].$columnaInicioPivot)->getFont()->setBold(true)->setSize(16);
            //$active_sheet->getStyle($celdaInicio[0].$columnaInicio.':'.$celdaInicio[10].$columnaInicioPivot-1)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);

            /*$active_sheet->getStyle($celdaInicio[9].$columnaInicioPivot.':'.$celdaInicio[10].$columnaInicioPivot)->getFont()->setBold(true)->setSize(16);
            $active_sheet->getStyle($celdaInicio[0].$columnaInicio.':'.$celdaInicio[10].$columnaInicioPivot-1)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);

            $active_sheet->getStyle($celdaInicio[0].$columnaInicioPivot.':'.$celdaInicio[8].$columnaInicioPivot)->getBorders()->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
            $active_sheet->getStyle($celdaInicio[9].$columnaInicioPivot.':'.$celdaInicio[10].$columnaInicioPivot)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM);
            */
            $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
            $filename = "reporte.xlsx";
            $writer->save(storage_path('app/'. $filename));
            $data['status'] = 200;
            $data['message'] = "OK";
            
            return response()->download(storage_path('app/'.$filename));
            
        }else{
            return false;
        }
    }

    public function generarReporteByTipoReporte(Request $request)
    {
        $this->checkAuthorization(auth()->user(), ['reporteTramites.download']);

        ini_set('memory_limit', '-1'); // anula el limite 

        $tipoReporte = json_decode($request->tipo_reporte_search, true);
        $configuracionCamposReporte = ConfiguracionCamposReporte::find($tipoReporte);
        $campos = json_decode($configuracionCamposReporte->campos, true);
        $listadoCampos = [];
        $headers = [];
        foreach($campos as $obj){
            if($obj['habilitado'] == true || $obj['habilitado'] == 1){
                $listadoCampos[] = $obj['campo'];
                $headers[] = $obj['nombre_campo'];
            }
        }

        $tipoTramite = json_decode($request->tipo_tramite_search, true);

        if (in_array(1, $tipoTramite) && !in_array(2, $tipoTramite) && !in_array(3, $tipoTramite) && !in_array(4, $tipoTramite)) {
            $oficios = Oficio::select($listadoCampos)->where('id',">",0);
            $registros = $this->aplicarFiltros($request,$oficios);
        }

        if (in_array(2, $tipoTramite) && !in_array(1, $tipoTramite) && !in_array(3, $tipoTramite) && !in_array(3, $tipoTramite)) {
            $rezagados = Rezagado::select($listadoCampos)->where('id',">",0);
            $registros = $this->aplicarFiltros($request,$rezagados);
        }

        if (in_array(3, $tipoTramite) && !in_array(1, $tipoTramite) && !in_array(2, $tipoTramite) && !in_array(4, $tipoTramite)) {
            $rezagadosLevObj = RezagadoLevantamientoObjecion::select($listadoCampos)->where('id',">",0);
            $registros = $this->aplicarFiltros($request,$rezagadosLevObj);
        }

        if (in_array(4, $tipoTramite) && !in_array(1, $tipoTramite) && !in_array(2, $tipoTramite) && !in_array(3, $tipoTramite)) {
            $extemporaneos = Extemporaneo::select($listadoCampos)->where('id',">",0);
            $registros = $this->aplicarFiltros($request,$extemporaneos);
        }

        if (in_array(1, $tipoTramite) && in_array(2, $tipoTramite) && !in_array(3, $tipoTramite) && !in_array(4, $tipoTramite)) {
            $rezagados = Rezagado::select($listadoCampos)->where('id',">",0);
            $rezagados = $this->aplicarFiltros($request,$rezagados);
            $oficios = Oficio::select($listadoCampos)->where('id',">",0);
            $oficios = $this->aplicarFiltros($request,$oficios);
            $registros = $oficios->union($rezagados);
        }

        if (in_array(1, $tipoTramite) && in_array(3, $tipoTramite) && !in_array(2, $tipoTramite) && !in_array(4, $tipoTramite)) {
            $rezagadosLevObj = RezagadoLevantamientoObjecion::select($listadoCampos)->where('id',">",0);
            $rezagadosLevObj = $this->aplicarFiltros($request,$rezagadosLevObj);
            $oficios = Oficio::select($listadoCampos)->where('id',">",0);
            $oficios = $this->aplicarFiltros($request,$oficios);
            $registros = $oficios->union($rezagadosLevObj);
        }

        if (in_array(1, $tipoTramite) && in_array(4, $tipoTramite) && !in_array(2, $tipoTramite) && !in_array(3, $tipoTramite)) {
            $extemporaneos = Extemporaneo::select($listadoCampos)->where('id',">",0);
            $extemporaneos = $this->aplicarFiltros($request,$extemporaneos);
            $oficios = Oficio::select($listadoCampos)->where('id',">",0);
            $oficios = $this->aplicarFiltros($request,$oficios);
            $registros = $oficios->union($extemporaneos);
        }

        if (in_array(2, $tipoTramite) && in_array(3, $tipoTramite) && !in_array(1, $tipoTramite) && !in_array(4, $tipoTramite)) {
            $rezagadosLevObj = RezagadoLevantamientoObjecion::select($listadoCampos)->where('id',">",0);
            $rezagadosLevObj = $this->aplicarFiltros($request,$rezagadosLevObj);
            $rezagados = Rezagado::select($listadoCampos)->where('id',">",0);
            $rezagados = $this->aplicarFiltros($request,$rezagados);
            $registros = $rezagados->union($rezagadosLevObj);
        }

        if (in_array(2, $tipoTramite) && in_array(4, $tipoTramite) && !in_array(1, $tipoTramite) && !in_array(3, $tipoTramite)) {
            $extemporaneos = Extemporaneo::select($listadoCampos)->where('id',">",0);
            $registros = Rezagado::select($listadoCampos)->where('id',">",0)->union($extemporaneos);
        }

        if (in_array(3, $tipoTramite) && in_array(4, $tipoTramite) && !in_array(1, $tipoTramite) && !in_array(2, $tipoTramite)) {
            $extemporaneos = Extemporaneo::select($listadoCampos)->where('id',">",0);
            $extemporaneos = $this->aplicarFiltros($request,$extemporaneos);
            $rezagadosLevObj = RezagadoLevantamientoObjecion::select($listadoCampos)->where('id',">",0);
            $rezagadosLevObj = $this->aplicarFiltros($request,$rezagadosLevObj);
            $registros = $rezagadosLevObj->union($extemporaneos);
        }

        if (in_array(1, $tipoTramite) && in_array(2, $tipoTramite) && in_array(3, $tipoTramite) && !in_array(4, $tipoTramite)) {
            $oficios = Oficio::select($listadoCampos)->where('id',">",0);
            $oficios = $this->aplicarFiltros($request,$oficios);
            $rezagados = Rezagado::select($listadoCampos)->where('id',">",0);
            $rezagados = $this->aplicarFiltros($request,$rezagados);
            $rezagadosLevObj = RezagadoLevantamientoObjecion::select($listadoCampos)->where('id',">",0);
            $rezagadosLevObj = $this->aplicarFiltros($request,$rezagadosLevObj);
            $registros = $oficios->union($rezagados)->union($rezagadosLevObj);
        }

        if (in_array(1, $tipoTramite) && in_array(2, $tipoTramite) && in_array(4, $tipoTramite) && !in_array(3, $tipoTramite)) {
            $oficios = Oficio::select($listadoCampos)->where('id',">",0);
            $oficios = $this->aplicarFiltros($request,$oficios);
            $rezagados = Rezagado::select($listadoCampos)->where('id',">",0);
            $rezagados = $this->aplicarFiltros($request,$rezagados);
            $extemporaneos = Extemporaneo::select($listadoCampos)->where('id',">",0);
            $extemporaneos = $this->aplicarFiltros($request,$extemporaneos);
            $registros = $oficios->union($rezagados)->union($extemporaneos);
        }

        if (in_array(1, $tipoTramite) && in_array(3, $tipoTramite) && in_array(4, $tipoTramite) && !in_array(2, $tipoTramite)) {
            $oficios = Oficio::select($listadoCampos)->where('id',">",0);
            $oficios = $this->aplicarFiltros($request,$oficios);
            $rezagadosLevObj = RezagadoLevantamientoObjecion::select($listadoCampos)->where('id',">",0);
            $rezagadosLevObj = $this->aplicarFiltros($request,$rezagadosLevObj);
            $extemporaneos = Extemporaneo::select($listadoCampos)->where('id',">",0);
            $extemporaneos = $this->aplicarFiltros($request,$extemporaneos);
            $registros = $oficios->union($rezagadosLevObj)->union($extemporaneos);
        }

        if (in_array(2, $tipoTramite) && in_array(3, $tipoTramite) && in_array(4, $tipoTramite) && !in_array(1, $tipoTramite)) {
            $rezagados = Rezagado::select($listadoCampos)->where('id',">",0);
            $rezagados = $this->aplicarFiltros($request,$rezagados);
            $rezagadosLevObj = RezagadoLevantamientoObjecion::select($listadoCampos)->where('id',">",0);
            $rezagadosLevObj = $this->aplicarFiltros($request,$rezagadosLevObj);
            $extemporaneos = Extemporaneo::select($listadoCampos)->where('id',">",0);
            $extemporaneos = $this->aplicarFiltros($request,$extemporaneos);
            $registros = $rezagados->union($rezagadosLevObj)->union($extemporaneos);
        }

        if (in_array(1, $tipoTramite) && in_array(2, $tipoTramite) && in_array(3, $tipoTramite) && in_array(4, $tipoTramite)) {
            $oficios = Oficio::select($listadoCampos)->where('id',">",0);
            $oficios = $this->aplicarFiltros($request,$oficios);
            $rezagados = Rezagado::select($listadoCampos)->where('id',">",0);
            $rezagados = $this->aplicarFiltros($request,$rezagados);
            $rezagadosLevObj = RezagadoLevantamientoObjecion::select($listadoCampos)->where('id',">",0);
            $rezagadosLevObj = $this->aplicarFiltros($request,$rezagadosLevObj);
            $extemporaneos = Extemporaneo::select($listadoCampos)->where('id',">",0);
            $extemporaneos = $this->aplicarFiltros($request,$extemporaneos);
            $registros = $oficios->union($rezagados)->union($rezagadosLevObj)->union($extemporaneos);
        }
        
        $registros = $registros->orderBy('id', 'desc')->get();

        $tipos = Tipo::get(["nombre", "id"])->pluck('nombre','id');
        $tiposAtencion = TipoAtencion::get(["nombre", "id"])->pluck('nombre','id');
        $tiposTramite = TipoTramite::get(["nombre", "id"])->pluck('nombre','id');
        $estadosTramite = EstadoTramite::get(["nombre", "id"])->pluck('nombre','id');
        $tiposEstadoCaja = TipoEstadoCaja::get(["nombre", "id"])->pluck('nombre','id');
        $provincias = Provincia::get(["nombre", "id"])->pluck('nombre','id');
        $instituciones = Institucion::get(["nombre", "id"])->pluck('nombre','id');
        $tiposFirma = TipoFirma::get(["nombre", "id"])->pluck('nombre','id');
        $responsables = Admin::get(["name", "id"])->pluck('name','id');

        $fileName = 'FormatoReporte.xlsx';

        if(public_path('uploads/'.$fileName)){
            $inputFileName = public_path('reporte/'.$fileName);
            $inputFileType = \PhpOffice\PhpSpreadsheet\IOFactory::identify($inputFileName);
            $reader = IOFactory::createReader($inputFileType);
            $spreadsheet = $reader->load($inputFileName);

            $active_sheet = $spreadsheet->getActiveSheet();

            $celdaInicio = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF','AG','AH'];
            $columnaInicio = 2;
            $filaInicioPivot = 2;
            $columnaInicioPivot = 0;

            foreach ($headers as $index => $header) {
                $active_sheet->setCellValue($celdaInicio[$index].'1', $header);
                //$active_sheet->getColumnDimension($celdaInicio[$index])->setAutoSize(true);
            }
            $sourceStyle = $active_sheet->getStyle('A1')->exportArray();
            $active_sheet->getStyle('A1'.':'.$celdaInicio[count($headers)-1].'1')->applyFromArray($sourceStyle);

            foreach ($registros as $registro) {
                $columnaInicioPivot = 0;
                foreach($listadoCampos as $index => $campo){
                    switch ($campo) {
                        case 'fecha_registro':
                            $active_sheet->setCellValue($celdaInicio[$columnaInicioPivot].$filaInicioPivot, !isset($registro[$campo]) || empty($registro[$campo]) || $registro[$campo] == '0000-00-00' ? "" : Carbon::createFromFormat('Y-m-d', $registro[$campo])->format('Y/m/d'))->getStyle($celdaInicio[$columnaInicioPivot].$filaInicioPivot)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_DATE_YYYYMMDDSLASH);
                            break;
                        case 'tipo_tramite_id':
                            $active_sheet->setCellValue($celdaInicio[$columnaInicioPivot].$filaInicioPivot, $registro[$campo] > 0 ? $tiposTramite[$registro[$campo]] : "");
                            break;
                        case 'ruc':
                            $key = array_search($campo, $listadoCampos);
                            $active_sheet->getCell($celdaInicio[$key].$filaInicioPivot)->setValueExplicit($registro[$campo],\PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING2);
                            break;
                        case 'numero_establecimiento':
                            $key = array_search($campo, $listadoCampos);
                            $active_sheet->getCell($celdaInicio[$key].$filaInicioPivot)->setValueExplicit($registro[$campo],\PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING2);
                            break;
                        case 'fecha_recepcion':
                            $active_sheet->setCellValue($celdaInicio[$columnaInicioPivot].$filaInicioPivot, !isset($registro[$campo]) || empty($registro[$campo]) || $registro[$campo] == '0000-00-00' ? "" : Carbon::createFromFormat('Y-m-d', $registro[$campo])->format('Y/m/d'))->getStyle($celdaInicio[$columnaInicioPivot].$filaInicioPivot)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_DATE_YYYYMMDDSLASH);
                            break;
                        case 'tipo_atencion_id':
                            $active_sheet->setCellValue($celdaInicio[$columnaInicioPivot].$filaInicioPivot, $registro[$campo] > 0 ? $tiposAtencion[$registro[$campo]] : "");
                            break;
                        case 'provincia_id':
                            $active_sheet->setCellValue($celdaInicio[$columnaInicioPivot].$filaInicioPivot, $registro[$campo] > 0 ? $provincias[$registro[$campo]] : "");
                            break;
                        case 'fecha_servicio':
                            $active_sheet->setCellValue($celdaInicio[$columnaInicioPivot].$filaInicioPivot,!isset($registro[$campo]) || empty($registro[$campo]) || $registro[$campo] == '0000-00-00' ? "" : Carbon::createFromFormat('Y-m-d', $registro[$campo])->format('Y/m/d'))->getStyle($celdaInicio[$columnaInicioPivot].$filaInicioPivot)->getNumberFormat()->setFormatCode('yyyy/m');
                            break;
                        case 'estado_caja_id':
                            $active_sheet->setCellValue($celdaInicio[$columnaInicioPivot].$filaInicioPivot, $registro[$campo] > 0 ? $tiposEstadoCaja[$registro[$campo]] : "");
                            break;
                        case 'fecha_envio_auditoria':
                            $active_sheet->setCellValue($celdaInicio[$columnaInicioPivot].$filaInicioPivot, !isset($registro[$campo]) || empty($registro[$campo]) || $registro[$campo] == '0000-00-00' ? "" : Carbon::createFromFormat('Y-m-d', $registro[$campo])->format('Y/m/d'))->getStyle($celdaInicio[$columnaInicioPivot].$filaInicioPivot)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_DATE_YYYYMMDDSLASH);
                            break;
                        case 'institucion_id':
                            $active_sheet->setCellValue($celdaInicio[$columnaInicioPivot].$filaInicioPivot, $registro[$campo] > 0 ? $instituciones[$registro[$campo]] : "");
                            break;
                        case 'tipo_firma_id':
                            $active_sheet->setCellValue($celdaInicio[$columnaInicioPivot].$filaInicioPivot, $registro[$campo] > 0 ? $tiposFirma[$registro[$campo]] : "");
                            break;
                        case 'fecha_cierre_caja':
                            $active_sheet->setCellValue($celdaInicio[$columnaInicioPivot].$filaInicioPivot, !isset($registro[$campo]) || empty($registro[$campo]) || $registro[$campo] == '0000-00-00' ? "" : Carbon::createFromFormat('Y-m-d', $registro[$campo])->format('Y/m/d'))->getStyle($celdaInicio[$columnaInicioPivot].$filaInicioPivot)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_DATE_YYYYMMDDSLASH);
                            break;
                        case 'fecha_devolucion_auditoria':
                            $active_sheet->setCellValue($celdaInicio[$columnaInicioPivot].$filaInicioPivot, !isset($registro[$campo]) || empty($registro[$campo]) || $registro[$campo] == '0000-00-00' ? "" : Carbon::createFromFormat('Y-m-d', $registro[$campo])->format('Y/m/d'))->getStyle($celdaInicio[$columnaInicioPivot].$filaInicioPivot)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_DATE_YYYYMMDDSLASH);
                            break;
                        case 'fecha_devolucion_prestador':
                            $active_sheet->setCellValue($celdaInicio[$columnaInicioPivot].$filaInicioPivot, !isset($registro[$campo]) || empty($registro[$campo]) || $registro[$campo] == '0000-00-00' ? "" : Carbon::createFromFormat('Y-m-d', $registro[$campo])->format('Y/m/d'))->getStyle($celdaInicio[$columnaInicioPivot].$filaInicioPivot)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_DATE_YYYYMMDDSLASH);
                            break;
                        case 'responsable_id':
                            $active_sheet->setCellValue($celdaInicio[$columnaInicioPivot].$filaInicioPivot, $registro[$campo] > 0 ? $responsables[$registro[$campo]] : "");
                            break;
                        case 'estado_tramite_id':
                            $active_sheet->setCellValue($celdaInicio[$columnaInicioPivot].$filaInicioPivot, $registro[$campo] > 0 ? $estadosTramite[$registro[$campo]] : "");
                            break;
                        default:
                            $active_sheet->setCellValue($celdaInicio[$columnaInicioPivot].$filaInicioPivot, $registro[$campo]);
                    }
                    $maxWidth = 50;
                    $colWidth = $active_sheet->getColumnDimensions($celdaInicio[$index]);
                    if ($colWidth > $maxWidth) {
                        //$active_sheet->getColumnDimension($celdaInicio[$index])->setAutoSize(false)->setWidth($maxWidth);
                        //$colDim->setAutoSize(false);
                        //$colDim->setWidth($maxWidth);
                    }
                    $columnaInicioPivot += 1;
                }
                $filaInicioPivot += 1;
            }
            
            $keyNumeroCasos = array_search('numero_casos', $listadoCampos);
            if($keyNumeroCasos !== false){
                $rangoSumaNumeroCasos = $celdaInicio[$keyNumeroCasos].$columnaInicio.':'.$celdaInicio[$keyNumeroCasos].$filaInicioPivot-1;
                $active_sheet->setCellValue($celdaInicio[$keyNumeroCasos].$filaInicioPivot , '=SUM('.$rangoSumaNumeroCasos.')');

                $active_sheet->getStyle($celdaInicio[$keyNumeroCasos].$filaInicioPivot)->getFont()->setBold(true)->setSize(16);
                
            }
            $keyMontoPlanilla = array_search('monto_planilla', $listadoCampos);
            if($keyMontoPlanilla !== false){
                $active_sheet->getStyle($celdaInicio[$keyMontoPlanilla].$columnaInicio.':'.$celdaInicio[$keyMontoPlanilla].$filaInicioPivot)->getNumberFormat()->setFormatCode('#,##0.00');

                $rangoSumaMontoPlanilla = $celdaInicio[$keyMontoPlanilla].$columnaInicio.':'.$celdaInicio[$keyMontoPlanilla].$filaInicioPivot-1;
                $active_sheet->setCellValue($celdaInicio[$keyMontoPlanilla].$filaInicioPivot , '=SUM('.$rangoSumaMontoPlanilla.')');

                $active_sheet->getStyle($celdaInicio[$keyMontoPlanilla].$filaInicioPivot)->getFont()->setBold(true)->setSize(16);
            }

            //$active_sheet->setCellValue($celdaInicio[0].$columnaInicioPivot, !isset($registro->fecha_registro) || empty($registro->fecha_registro) || $registro->fecha_registro == '0000-00-00' ? "" : Carbon::createFromFormat('Y-m-d', $registro->fecha_registro)->format('d-M-Y'));
            
            /*foreach ($registros as $registro) {
                $active_sheet->setCellValue($celdaInicio[0].$columnaInicioPivot, !isset($registro->fecha_registro) || empty($registro->fecha_registro) || $registro->fecha_registro == '0000-00-00' ? "" : Carbon::createFromFormat('Y-m-d', $registro->fecha_registro)->format('d-M-Y'));
                $active_sheet->setCellValue($celdaInicio[1].$columnaInicioPivot, $registro->tipo_tramite_id > 0 ? $tiposTramite[$registro->tipo_tramite_id] : "");
                $active_sheet->getCell($celdaInicio[2].$columnaInicioPivot)->setValueExplicit($registro->ruc,\PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING2);
                $active_sheet->getCell($celdaInicio[3].$columnaInicioPivot)->setValueExplicit($registro->numero_establecimiento,\PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING2);
                $active_sheet->setCellValue($celdaInicio[4].$columnaInicioPivot, $registro->razon_social);
                $active_sheet->setCellValue($celdaInicio[5].$columnaInicioPivot, !isset($registro->fecha_recepcion) || empty($registro->fecha_recepcion) || $registro->fecha_recepcion == '0000-00-00' ? "" : Carbon::createFromFormat('Y-m-d', $registro->fecha_recepcion)->format('d-M-Y'));
                $active_sheet->setCellValue($celdaInicio[6].$columnaInicioPivot, $registro->tipo_atencion_id > 0 ? $tiposAtencion[$registro->tipo_atencion_id] : "");
                $active_sheet->setCellValue($celdaInicio[7].$columnaInicioPivot, $registro->provincia_id > 0 ? $provincias[$registro->provincia_id] : "");
                $active_sheet->setCellValue($celdaInicio[8].$columnaInicioPivot, !isset($registro->fecha_servicio) || empty($registro->fecha_servicio) || $registro->fecha_servicio == '0000-00-00' ? "" : Carbon::createFromFormat('Y-m-d', $registro->fecha_servicio)->format('M-Y'));
                $active_sheet->setCellValue($celdaInicio[9].$columnaInicioPivot, $registro->numero_casos);
                $active_sheet->setCellValue($celdaInicio[10].$columnaInicioPivot, $registro->monto_planilla);
                $active_sheet->setCellValue($celdaInicio[11].$columnaInicioPivot, $registro->numero_caja_ant);
                $active_sheet->setCellValue($celdaInicio[12].$columnaInicioPivot, $registro->numero_caja);
                $active_sheet->setCellValue($celdaInicio[13].$columnaInicioPivot, $registro->estado_caja_id > 0 ? $tiposEstadoCaja[$registro->estado_caja_id] : "");
                $active_sheet->setCellValue($celdaInicio[14].$columnaInicioPivot, $registro->numero_caja_auditoria);
                $active_sheet->setCellValue($celdaInicio[15].$columnaInicioPivot, !isset($registro->fecha_envio_auditoria) || empty($registro->fecha_envio_auditoria) || $registro->fecha_envio_auditoria == '0000-00-00' ? "" : Carbon::createFromFormat('Y-m-d', $registro->fecha_envio_auditoria)->format('d-M-Y'));
                $active_sheet->setCellValue($celdaInicio[16].$columnaInicioPivot, $registro->institucion_id > 0 ? $instituciones[$registro->institucion_id] : "");
                $active_sheet->setCellValue($celdaInicio[17].$columnaInicioPivot, $registro->documento_externo);
                $active_sheet->setCellValue($celdaInicio[18].$columnaInicioPivot, $registro->tipo_firma_id > 0 ? $tiposFirma[$registro->tipo_firma_id] : "");
                $active_sheet->setCellValue($celdaInicio[19].$columnaInicioPivot, $registro->observaciones);
                $active_sheet->setCellValue($celdaInicio[20].$columnaInicioPivot, $registro->numero_quipux);
                $active_sheet->setCellValue($celdaInicio[21].$columnaInicioPivot, $registro->responsable_id > 0 ? $responsables[$registro->responsable_id] : "");
                $active_sheet->setCellValue($celdaInicio[22].$columnaInicioPivot, $registro->es_historico == 1 ? "SI" : "NO");
                $active_sheet->setCellValue($celdaInicio[23].$columnaInicioPivot, !isset($registro->periodo) || empty($registro->periodo) ? "" : $registro->periodo);
                $active_sheet->setCellValue($celdaInicio[24].$columnaInicioPivot, $registro->estado_tramite_id > 0 ? $estadosTramite[$registro->estado_tramite_id] : "");
                $active_sheet->setCellValue($celdaInicio[25].$columnaInicioPivot, !isset($registro->fecha_devolucion_auditoria) || empty($registro->fecha_devolucion_auditoria) || $registro->fecha_devolucion_auditoria == '0000-00-00' ? "" : Carbon::createFromFormat('Y-m-d', $registro->fecha_devolucion_auditoria)->format('d-M-Y'));
                $active_sheet->setCellValue($celdaInicio[26].$columnaInicioPivot, $registro->observaciones_devolucion_auditoria);
                $active_sheet->setCellValue($celdaInicio[27].$columnaInicioPivot, !isset($registro->fecha_devolucion_prestador) || empty($registro->fecha_devolucion_prestador) || $registro->fecha_devolucion_prestador == '0000-00-00' ? "" : Carbon::createFromFormat('Y-m-d', $registro->fecha_devolucion_prestador)->format('d-M-Y'));
                $active_sheet->setCellValue($celdaInicio[28].$columnaInicioPivot, $registro->observaciones_devolucion_prestador);
                $active_sheet->setCellValue($celdaInicio[29].$columnaInicioPivot, !isset($registro->id) || empty($registro->id) ? "" : $registro->id);

                $columnaInicioPivot += 1;
            }
            $active_sheet->getStyle($celdaInicio[10].$columnaInicio.':'.$celdaInicio[10].$columnaInicioPivot)->getNumberFormat()->setFormatCode('"$"#,##0.00');
            
            $rangoSumaNumeroCasos = $celdaInicio[9].$columnaInicio.':'.$celdaInicio[9].$columnaInicioPivot-1;
            $rangoSumaMontoPlanilla = $celdaInicio[10].$columnaInicio.':'.$celdaInicio[10].$columnaInicioPivot-1;
            $active_sheet->setCellValue($celdaInicio[9].$columnaInicioPivot , '=SUM('.$rangoSumaNumeroCasos.')');
            $active_sheet->setCellValue($celdaInicio[10].$columnaInicioPivot , '=SUM('.$rangoSumaMontoPlanilla.')');

            $active_sheet->getStyle($celdaInicio[9].$columnaInicioPivot.':'.$celdaInicio[10].$columnaInicioPivot)->getFont()->setBold(true)->setSize(16);
            */
            
            $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
            $filename = "reporte.xlsx";
            $writer->save(storage_path('app/'. $filename));
            $data['status'] = 200;
            $data['message'] = "OK";
            
            return response()->download(storage_path('app/'.$filename));
            
        }else{
            return false;
        }
    }

    public function bitacora(): Renderable
    {
        $this->checkAuthorization(auth()->user(), ['reporteBitacora.view']);

        $admins = Admin::get(["name", "id"])->pluck('name','id');
        $tiposDocumento = TipoDocumento::get(["nombre", "id"])->pluck('nombre','id');
        $tiposIngreso = TipoIngreso::get(["nombre", "id"])->pluck('nombre','id');
        $tiposAtencion = TipoAtencion::get(["nombre", "id"])->pluck('nombre','id');
        $receptoresDocumental = $admins;
        $tiposTramite = TipoTramite::get(["nombre", "id"])->pluck('nombre','id');
        $responsables = $admins;

        return view('backend.pages.reportes.bitacora', [
            'tiposDocumento' => $tiposDocumento,
            'tiposIngreso' => $tiposIngreso,
            'tiposAtencion' => $tiposAtencion,
            'receptoresDocumental' => $receptoresDocumental,
            'tiposTramite' => $tiposTramite,
            'responsables' => $responsables
        ]);
    }

    public function generarReporteBitacoraByFilters(Request $request)
    {
        $this->checkAuthorization(auth()->user(), ['reporteBitacora.download']);

        ini_set('memory_limit', '-1'); // anula el limite 
        
        $registrosBitacora = RegistroBitacora::where('id',">",0);

        $filtroFechaRecepcionDesdeSearch = $request->fecha_recepcion_desde_search;
        $filtroFechaRecepcionHastaSearch = $request->fecha_recepcion_hasta_search;
        $filtroTipoDocumentoIdSearch = json_decode($request->tipo_documento_id_search, true);
        $filtroTipoIngresoIdSearch = json_decode($request->tipo_ingreso_id_search, true);
        $filtroTipoAtencionIdSearch = json_decode($request->tipo_atencion_id_search, true);
        $filtroNumeroCasosSearch = $request->numero_casos_search;
        $filtroReceptorDocumentalIdSearch = json_decode($request->receptor_documental_id_search, true);
        $filtroNombrePrestadorSaludSearch = $request->nombre_prestador_salud_search;
        $filtroMontoPlanillaSearch = $request->monto_planilla_search;
        $filtroDescripcionSearch = $request->descripcion_search;
        $filtroFechaServicioDesdeSearch = $request->fecha_servicio_desde_search;
        $filtroFechaServicioHastaSearch = $request->fecha_servicio_hasta_search;
        $filtroTipoTramiteIdSearch = json_decode($request->tipo_tramite_id_search, true);
        $filtroPeriodoSearch = $request->periodo_search;
        $filtroNumeroQuipuxSearch = $request->numero_quipux_search;
        $filtroResponsableSearch = json_decode($request->responsable_id_search, true);
        
        if(isset($filtroFechaRecepcionDesdeSearch) && !empty($filtroFechaRecepcionDesdeSearch)){
            $registrosBitacora = $registrosBitacora->where('fecha_recepcion', '>=', $filtroFechaRecepcionDesdeSearch);
        }
        if(isset($filtroFechaRecepcionHastaSearch) && !empty($filtroFechaRecepcionHastaSearch)){
            $registrosBitacora = $registrosBitacora->where('fecha_recepcion', '<=', $filtroFechaRecepcionHastaSearch);
        }
        if(isset($filtroTipoDocumentoIdSearch) && !empty($filtroTipoDocumentoIdSearch)){
            $registrosBitacora = $registrosBitacora->whereIn('tipo_documento_id', $filtroTipoDocumentoIdSearch);
        }
        if(isset($filtroTipoIngresoIdSearch) && !empty($filtroTipoIngresoIdSearch)){
            $registrosBitacora = $registrosBitacora->whereIn('tipo_ingreso_id', $filtroTipoIngresoIdSearch);
        }
        if(isset($filtroTipoAtencionIdSearch) && !empty($filtroTipoAtencionIdSearch)){
            $registrosBitacora = $registrosBitacora->whereIn('tipo_atencion_id', $filtroTipoAtencionIdSearch);
        }
        if(isset($filtroNumeroCasosSearch) && !empty($filtroNumeroCasosSearch)){
            $registrosBitacora = $registrosBitacora->where('numero_casos', $filtroNumeroCasosSearch);
        }
        if(isset($filtroReceptorDocumentalIdSearch) && !empty($filtroReceptorDocumentalIdSearch)){
            $registrosBitacora = $registrosBitacora->whereIn('receptor_documental_id', $filtroReceptorDocumentalIdSearch);
        }
        if(isset($filtroNombrePrestadorSaludSearch) && !empty($filtroNombrePrestadorSaludSearch)){
            $pretadoresSaludIds = PrestadorSalud::where('prestador_salud', 'like', '%'.$filtroNombrePrestadorSaludSearch.'%')->get(['id'])->pluck('id');

            if($pretadoresSaludIds && isset($pretadoresSaludIds) && !empty($pretadoresSaludIds)){
                $registrosBitacora = $registrosBitacora->whereIn('prestador_salud_id', $pretadoresSaludIds);
            }
        }
        if(isset($filtroMontoPlanillaSearch) && !empty($filtroMontoPlanillaSearch)){
            $registrosBitacora = $registrosBitacora->where('monto_planilla', $filtroMontoPlanillaSearch);
        }
        if(isset($filtroDescripcionSearch) && !empty($filtroDescripcionSearch)){
            $registrosBitacora = $registrosBitacora->where('descripcion', 'like', '%'.$filtroDescripcionSearch.'%');
        }
        if(isset($filtroFechaServicioDesdeSearch) && !empty($filtroFechaServicioDesdeSearch)){
            $registrosBitacora = $registrosBitacora->where('fecha_servicio', '>=', $filtroFechaServicioDesdeSearch);
        }
        if(isset($filtroFechaServicioHastaSearch) && !empty($filtroFechaServicioHastaSearch)){
            $registrosBitacora = $registrosBitacora->where('fecha_servicio', '<=', $filtroFechaServicioHastaSearch);
        }
        if(isset($filtroTipoTramiteIdSearch) && !empty($filtroTipoTramiteIdSearch)){
            $registrosBitacora = $registrosBitacora->whereIn('tipo_tramite_id', $filtroTipoTramiteIdSearch);
        }
        if(isset($filtroPeriodoSearch) && !empty($filtroPeriodoSearch)){
            $registrosBitacora = $registrosBitacora->where('periodo', 'like', '%'.$filtroPeriodoSearch.'%');
        }
        if(isset($filtroNumeroQuipuxSearch) && !empty($filtroNumeroQuipuxSearch)){
            $registrosBitacora = $registrosBitacora->where('numero_quipux', 'like', '%'.$filtroNumeroQuipuxSearch.'%');
        }
        if(isset($filtroResponsableSearch) && !empty($filtroResponsableSearch)){
            $registrosBitacora = $registrosBitacora->whereIn('responsable_id', $filtroResponsableSearch);
        }
        
        $registrosBitacora = $registrosBitacora->orderBy('id', 'desc')->get();

        $admins = Admin::all();
        $tiposDocumento = TipoDocumento::all();
        $tiposIngreso = TipoIngreso::all();
        $tiposAtencion = TipoAtencion::all();
        $receptoresDocumental = $admins;
        $tiposTramite = TipoTramite::all();
        $prestadoresSalud = PrestadorSalud::all();
        $responsables = $admins;

        $tipos_documento_temp = [];
        foreach($tiposDocumento as $tipoDoc){
            $tipos_documento_temp[$tipoDoc->id] = $tipoDoc->nombre;
        }
        $tipos_ingreso_temp = [];
        foreach($tiposIngreso as $tipoIngreso){
            $tipos_ingreso_temp[$tipoIngreso->id] = $tipoIngreso->nombre;
        }
        $tipos_atencion_temp = [];
        foreach($tiposAtencion as $tipoAtencion){
            $tipos_atencion_temp[$tipoAtencion->id] = $tipoAtencion->nombre;
        }
        $receptores_documental_temp = [];
        foreach($receptoresDocumental as $receptorDocumental){
            $receptores_documental_temp[$receptorDocumental->id] = $receptorDocumental->name;
        }
        $prestadores_salud_temp = [];
        foreach($prestadoresSalud as $prestadorSalud){
            $prestadores_salud_temp[$prestadorSalud->id] = $prestadorSalud->prestador_salud;
        }
        $tipos_tramite_temp = [];
        foreach($tiposTramite as $tipoTramite){
            $tipos_tramite_temp[$tipoTramite->id] = $tipoTramite->nombre;
        }
        $responsables_temp = [];
        foreach($responsables as $responsable){
            $responsables_temp[$responsable->id] = $responsable->name;
        }

        $responsable_id = Auth::id();

        foreach($registrosBitacora as $bitacora){
            $bitacora->tipo_documento_nombre = array_key_exists($bitacora->tipo_documento_id, $tipos_documento_temp) ? $tipos_documento_temp[$bitacora->tipo_documento_id] : "";
            $bitacora->tipo_ingreso_nombre = array_key_exists($bitacora->tipo_ingreso_id, $tipos_ingreso_temp) ? $tipos_ingreso_temp[$bitacora->tipo_ingreso_id] : "";
            $bitacora->tipo_atencion_nombre = array_key_exists($bitacora->tipo_atencion_id, $tipos_atencion_temp) ? $tipos_atencion_temp[$bitacora->tipo_atencion_id] : "";
            $bitacora->receptor_documental_nombre = array_key_exists($bitacora->receptor_documental_id, $receptores_documental_temp) ? $receptores_documental_temp[$bitacora->receptor_documental_id] : "";
            $bitacora->prestador_salud_nombre = array_key_exists($bitacora->prestador_salud_id, $prestadores_salud_temp) ? $prestadores_salud_temp[$bitacora->prestador_salud_id] : "";
            $bitacora->tipo_tramite_nombre = array_key_exists($bitacora->tipo_tramite_id, $tipos_tramite_temp) ? $tipos_tramite_temp[$bitacora->tipo_tramite_id] : "";
            $bitacora->responsable_nombre = array_key_exists($bitacora->responsable_id, $responsables_temp) ? $responsables_temp[$bitacora->responsable_id] : "";
            $bitacora->esCreadorRegistro = $responsable_id == $bitacora->responsable_id ? true : false;
        }

        $fileName = 'FormatoReporteBitacora.xlsx';

        if(public_path('uploads/'.$fileName)){
            $inputFileName = public_path('reporte/'.$fileName);
            $inputFileType = \PhpOffice\PhpSpreadsheet\IOFactory::identify($inputFileName);
            $reader = IOFactory::createReader($inputFileType);
            $spreadsheet = $reader->load($inputFileName);

            $active_sheet = $spreadsheet->getActiveSheet();

            $celdaInicio = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P'];
            $columnaInicio = 5;
            $columnaInicioPivot = 5;

            foreach ($registrosBitacora as $index => $registro) {
                $active_sheet->setCellValue($celdaInicio[0].$columnaInicioPivot, $index+1);
                $active_sheet->setCellValue($celdaInicio[1].$columnaInicioPivot, !isset($registro->fecha_recepcion) || empty($registro->fecha_recepcion) || $registro->fecha_recepcion == '0000-00-00' ? "" : Carbon::createFromFormat('Y-m-d', $registro->fecha_recepcion)->format('d/m/Y'));
                $active_sheet->setCellValue($celdaInicio[2].$columnaInicioPivot, !isset($registro->tipo_documento_nombre) || empty($registro->tipo_documento_nombre) ? "" : $registro->tipo_documento_nombre);
                $active_sheet->setCellValue($celdaInicio[3].$columnaInicioPivot, !isset($registro->tipo_ingreso_nombre) || empty($registro->tipo_ingreso_nombre) ? "" : $registro->tipo_ingreso_nombre);
                $active_sheet->setCellValue($celdaInicio[4].$columnaInicioPivot, !isset($registro->tipo_atencion_nombre) || empty($registro->tipo_atencion_nombre) ? "" : $registro->tipo_atencion_nombre);
                $active_sheet->setCellValue($celdaInicio[5].$columnaInicioPivot, !isset($registro->numero_casos) || empty($registro->numero_casos) ? "" : $registro->numero_casos);
                $active_sheet->setCellValue($celdaInicio[6].$columnaInicioPivot, !isset($registro->receptor_documental_nombre) || empty($registro->receptor_documental_nombre) ? "" : $registro->receptor_documental_nombre);
                $active_sheet->setCellValue($celdaInicio[7].$columnaInicioPivot, !isset($registro->prestador_salud_nombre) || empty($registro->prestador_salud_nombre) ? "" : $registro->prestador_salud_nombre);
                $active_sheet->setCellValue($celdaInicio[8].$columnaInicioPivot, !isset($registro->monto_planilla) || empty($registro->monto_planilla) ? "" : $registro->monto_planilla);
                $active_sheet->setCellValue($celdaInicio[9].$columnaInicioPivot, !isset($registro->descripcion) || empty($registro->descripcion) ? "" : $registro->descripcion);
                $active_sheet->setCellValue($celdaInicio[10].$columnaInicioPivot, !isset($registro->fecha_servicio) || empty($registro->fecha_servicio) ? "" : $registro->fecha_servicio);
                $active_sheet->setCellValue($celdaInicio[11].$columnaInicioPivot, !isset($registro->tipo_tramite_nombre) || empty($registro->tipo_tramite_nombre) ? "" : $registro->tipo_tramite_nombre);
                $active_sheet->setCellValue($celdaInicio[12].$columnaInicioPivot, !isset($registro->periodo) || empty($registro->periodo) ? "" : $registro->periodo);
                $active_sheet->setCellValue($celdaInicio[13].$columnaInicioPivot, !isset($registro->numero_quipux) || empty($registro->numero_quipux) ? "" : $registro->numero_quipux);
                $active_sheet->setCellValue($celdaInicio[14].$columnaInicioPivot, $registro->es_historico == 1 ? "SI" : "NO");
                $active_sheet->setCellValue($celdaInicio[15].$columnaInicioPivot, !isset($registro->responsable_nombre) || empty($registro->responsable_nombre) ? "" : $registro->responsable_nombre);

                $columnaInicioPivot += 1;
            }

            $active_sheet->getStyle($celdaInicio[0].$columnaInicio.':'.$celdaInicio[15].$columnaInicioPivot-1)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

            $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
            $filename = "reporteBitacora.xlsx";
            $writer->save(storage_path('app/'. $filename));
            $data['status'] = 200;
            $data['message'] = "OK";
            
            return response()->download(storage_path('app/'.$filename));
            
        }else{
            return false;
        }
    }

}