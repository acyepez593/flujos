<?php
    
declare(strict_types=1);

namespace App\Http\Controllers\Backend;
    
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\OficioRequest;
use App\Models\Admin;
use App\Models\Beneficiario;
use App\Models\CamposPorProceso;
use App\Models\Catalogo;
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
use App\Models\Proceso;
use App\Models\RegistroBitacora;
use App\Models\SecuenciaProceso;
use App\Models\TipoCatalogo;
use App\Models\TipoDocumento;
use App\Models\TipoIngreso;
use App\Models\TipoTramite;
use App\Models\Tramite;
use Carbon\Carbon;
use ErrorException;
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

    public function create(): Renderable
    {
        $this->checkAuthorization(auth()->user(), ['reporte.view']);

        $procesos = Proceso::get(["nombre","id"]);
        $funcionarios = Admin::get(["name", "id"]);

        return view('backend.pages.reportes.create', [
            'procesos' => $procesos,
            'funcionarios' => $funcionarios
        ]);
    }

    public function getTiposReporteByProcesoId(Request $request): JsonResponse
    {
        try
        {
            $this->checkAuthorization(auth()->user(), ['reporte.view']);

            $funcionario_id = Auth::id();
            $proceso_id = $request->proceso_id_search;

            $secuenciasProcesos = SecuenciaProceso::where('proceso_id',$proceso_id)->where('estatus','ACTIVO')->get(["nombre","id"]);
            $secuenciaProceso = SecuenciaProceso::where('proceso_id',$proceso_id)->where('estatus','ACTIVO')->first();
            $listaCampos = collect($secuenciaProceso->configuracion_campos)->sortBy('seccion_campo');
            $tiposCatalogos = TipoCatalogo::where('estatus','ACTIVO')->get(["nombre", "id","tipo_catalogo_relacionado_id"]);
            $catalogos = Catalogo::where('estatus','ACTIVO')->get(["tipo_catalogo_id","id","nombre","catalogo_id"]);

            $configuracion = ConfiguracionCamposReporte::where("proceso_id",$proceso_id)->where("funcionario_id",$funcionario_id)->where('habilitar', true);
            $tiposReporte = $configuracion->get(["nombre","id"]);
            $campos = $configuracion->get(["campos"]);

            $data['secuenciasProcesos'] = $secuenciasProcesos;
            $data['listaCampos'] = json_decode($listaCampos[0],true);
            $data['tiposCatalogos'] = $tiposCatalogos;
            $data['catalogos'] = $catalogos->groupBy('tipo_catalogo_id');
            $data['tiposReporte'] = $tiposReporte;
            $data['campos'] = $campos;
    
            return response()->json($data);

        } catch (ErrorException $e) {
            $data['status'] = 500;
            $data['error_type'] = 'ErrorException';
            $data['message'] = $e->getMessage();
            return response()->json($data, 500);
        }
    }

    public function getCamposByTipoReporte(Request $request): JsonResponse
    {
        try
        {
            $tipoReporte = $request->tipo_reporte_search;
            $configuracion = ConfiguracionCamposReporte::select('campos')->findOrFail($tipoReporte);
            $camposTmp = json_decode($configuracion['campos'],true);
            $campos = [];

            foreach($camposTmp as $campo){
                if($campo['habilitado'] === true){
                    $campos[] = $campo;
                }
            }

            $data['campos'] = $campos;
    
            return response()->json($data);

        } catch (ErrorException $e) {
            $data['status'] = 500;
            $data['error_type'] = 'ErrorException';
            $data['message'] = $e->getMessage();
            return response()->json($data, 500);
        }
    }

    public function generarReporteByTipoReporte(Request $request)
    {
        $this->checkAuthorization(auth()->user(), ['reporteTramites.download']);

        ini_set('memory_limit', '-1'); // anula el limite 

        $filtroProcesoIdSearch = $request->proceso_id_search;
        $filtroSecuenciaProcesoIdSearch = json_decode($request->secuencia_proceso_id_search, true);
        $filtroFuncionarioActualIdSearch = json_decode($request->funcionario_actual_id_search, true);
        $filtroEstatusSearch = json_decode($request->estatus_id_search, true);
        $filtroFechaCreacionDesdeSearch = $request->fecha_creacion_tramite_desde_search;
        $filtroFechaCreacionHastaSearch = $request->fecha_creacion_tramite_hasta_search;
        $filtrosSearch = json_decode($request->filtros_search, true);
        $filtroTipoReporteIdSearch = $request->tipo_reporte_search;

        if(isset($filtroTipoReporteIdSearch) && !empty($filtroTipoReporteIdSearch)){
            $camposPorProcesos = collect(CamposPorProceso::where('proceso_id',intval($filtroProcesoIdSearch))->get(['tipo_campo','nombre','variable','seccion_campo']));
            $configuracionCamposReporte = ConfiguracionCamposReporte::where('id', $filtroTipoReporteIdSearch)->where('habilitar', true)->first();
            $campos = json_decode($configuracionCamposReporte->campos, true);
            $listadoCampos = [];
            $headers = [];
            $tiposCampos = [];
            $secciones = [];

            usort($campos, function($a, $b) {
                return $a['orden'] > $b['orden'];
            });

            foreach($campos as $obj){
                if($obj['habilitado'] == true || $obj['habilitado'] == 1){
                    $listadoCampos[] = $obj['campo'];
                    $headers[] = $obj['nombre_campo'] . PHP_EOL . '(' . $obj['nombre_seccion'] . ')';
                    $tiposCampos[] = $camposPorProcesos->where('seccion_campo',$obj['nombre_seccion'])->where('variable',$obj['campo'])->first()['tipo_campo'];
                    $secciones [] = $obj['nombre_seccion'];
                }
            }
        }

        $tramites = Tramite::where('id', '>', 0);

        if(isset($filtroProcesoIdSearch) && !empty($filtroProcesoIdSearch)){
            $tramites = $tramites->where('proceso_id', intval($filtroProcesoIdSearch));
        }

        if(isset($filtroSecuenciaProcesoIdSearch) && !empty($filtroSecuenciaProcesoIdSearch)){
            $tramites = $tramites->whereIn('secuencia_proceso_id', $filtroSecuenciaProcesoIdSearch);
        }

        if(isset($filtroFuncionarioActualIdSearch) && !empty($filtroFuncionarioActualIdSearch)){
            $tramites = $tramites->whereIn('funcionario_actual_id', $filtroFuncionarioActualIdSearch);
        }

        if(isset($filtroEstatusSearch) && !empty($filtroEstatusSearch)){
            $tramites = $tramites->whereIn('estatus', $filtroEstatusSearch);
        }

        if(isset($filtroFechaCreacionDesdeSearch) && !empty($filtroFechaCreacionDesdeSearch)){
            $tramites = $tramites->where('created_at', '>=', $filtroFechaCreacionDesdeSearch);
        }

        if(isset($filtroFechaCreacionHastaSearch) && !empty($filtroFechaCreacionHastaSearch)){
            $tramites = $tramites->where('created_at', '<=', $filtroFechaCreacionHastaSearch);
        }

        if(isset($filtrosSearch) && !empty($filtrosSearch)){
            foreach($filtrosSearch as $filtro){
                if($filtro['valor_filtro'] != ""){
                    $tramites = $tramites->whereJSONContains('datos->data->'. $filtro['nombre_seccion'] .'->' . $filtro['campo'],$filtro['valor_filtro']);
                }
            }
        }
        
        $tramites = $tramites->orderBy('id', 'asc')->get();

        $tramitesIds = [];
        $tramitesObj = [];
        foreach ($tramites as $tramite) {
            $tramitesIds[] = $tramite->id;
            $tramitesObj[$tramite->id] = $tramite;
        }

        $beneficiarios = Beneficiario::whereIn('tramite_id',$tramitesIds)->get();
        
        $catalogos = Catalogo::get(['id','tipo_catalogo_id','nombre','catalogo_id']);
        $catalogosTemp = [];
        foreach($catalogos as $catalogo){
            $catalogosTemp[$catalogo->id] = $catalogo->nombre;
        }

        $responsables = Admin::get(['name', 'id'])->pluck('name','id');

        $fileName = 'FormatoReporte.xlsx';

        if(public_path('uploads/'.$fileName)){
            $inputFileName = public_path('reporte/'.$fileName);
            $inputFileType = \PhpOffice\PhpSpreadsheet\IOFactory::identify($inputFileName);
            $reader = IOFactory::createReader($inputFileType);
            $spreadsheet = $reader->load($inputFileName);

            $active_sheet = $spreadsheet->getActiveSheet();

            $celdaInicio = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY','AZ','BA','BB','BC','BD','BE','BF','BG','BH','BI','BJ','BK','BL','BM','BN','BO','BP','BQ','BR','BS','BT','BU','BV','BW','BX','BY','BZ'];
            $columnaInicio = 2;
            $filaInicioPivot = 2;
            $columnaInicioPivot = 0;

            foreach ($headers as $index => $header) {
                $active_sheet->setCellValue($celdaInicio[$index].'1', $header);
                $active_sheet->getColumnDimension($celdaInicio[$index])->setAutoSize(true);
            }
            $sourceStyle = $active_sheet->getStyle('A1')->exportArray();
            $active_sheet->getStyle('A1'.':'.$celdaInicio[count($headers)-1].'1')->applyFromArray($sourceStyle);

            foreach ($beneficiarios as $beneficiario) {
                $beneficiario['tramite'] = $tramitesObj[$beneficiario->tramite_id];
                $camposTramite = json_decode($beneficiario['tramite']['datos'], true);
                $columnaInicioPivot = 0;

                foreach($listadoCampos as $index => $campo){
                    if($secciones[$index] == 'BENEFICIARIOS'){
                        $datosBeneficiario = json_decode($beneficiario['datos'], true);
                        if(isset($tiposCampos[$index])){
                            switch ($tiposCampos[$index]) {
                                case 'text':
                                    $active_sheet->setCellValue($celdaInicio[$columnaInicioPivot].$filaInicioPivot, !isset($datosBeneficiario[$campo]) || empty($datosBeneficiario[$campo]) ? "" : $datosBeneficiario[$campo]);
                                    break;
                                case 'date':
                                    $active_sheet->setCellValue($celdaInicio[$columnaInicioPivot].$filaInicioPivot, !isset($datosBeneficiario[$campo]) || empty($datosBeneficiario[$campo]) ? "" : $datosBeneficiario[$campo]);
                                    break;
                                case 'number':
                                    $active_sheet->setCellValue($celdaInicio[$columnaInicioPivot].$filaInicioPivot, !isset($datosBeneficiario[$campo]) || empty($datosBeneficiario[$campo]) ? "" : $datosBeneficiario[$campo]);
                                    break;
                                case 'email':
                                    $active_sheet->setCellValue($celdaInicio[$columnaInicioPivot].$filaInicioPivot, !isset($datosBeneficiario[$campo]) || empty($datosBeneficiario[$campo]) ? "" : $datosBeneficiario[$campo]);
                                    break;
                                case 'file':
                                    $active_sheet->setCellValue($celdaInicio[$columnaInicioPivot].$filaInicioPivot, !isset($datosBeneficiario[$campo]) || empty($datosBeneficiario[$campo]) ? "" : $datosBeneficiario[$campo]);
                                    break;
                                case 'select':
                                    $active_sheet->setCellValue($celdaInicio[$columnaInicioPivot].$filaInicioPivot, !isset($datosBeneficiario[$campo]) || empty($datosBeneficiario[$campo]) ? "" : $catalogosTemp[$datosBeneficiario[$campo]]);
                                    break;
                            }
                            $columnaInicioPivot += 1;
                        }
                    }else{
                        switch ($tiposCampos[$index]) {
                            case 'text':
                                $active_sheet->setCellValue($celdaInicio[$columnaInicioPivot].$filaInicioPivot, !isset($camposTramite['data'][$secciones[$index]][$campo]) || empty($camposTramite['data'][$secciones[$index]][$campo]) ? "" : $camposTramite['data'][$secciones[$index]][$campo]);
                                break;
                            case 'date':
                                $active_sheet->setCellValue($celdaInicio[$columnaInicioPivot].$filaInicioPivot, !isset($camposTramite['data'][$secciones[$index]][$campo]) || empty($camposTramite['data'][$secciones[$index]][$campo]) ? "" : $camposTramite['data'][$secciones[$index]][$campo]);
                                break;
                            case 'number':
                                $active_sheet->setCellValue($celdaInicio[$columnaInicioPivot].$filaInicioPivot, !isset($camposTramite['data'][$secciones[$index]][$campo]) || empty($camposTramite['data'][$secciones[$index]][$campo]) ? "" : $camposTramite['data'][$secciones[$index]][$campo]);
                                break;
                            case 'email':
                                $active_sheet->setCellValue($celdaInicio[$columnaInicioPivot].$filaInicioPivot, !isset($camposTramite['data'][$secciones[$index]][$campo]) || empty($camposTramite['data'][$secciones[$index]][$campo]) ? "" : $camposTramite['data'][$secciones[$index]][$campo]);
                                break;
                            case 'file':
                                $active_sheet->setCellValue($celdaInicio[$columnaInicioPivot].$filaInicioPivot, !isset($camposTramite['data'][$secciones[$index]][$campo]) || empty($camposTramite['data'][$secciones[$index]][$campo]) ? "" : $camposTramite['data'][$secciones[$index]][$campo]);
                                break;
                            case 'select':
                                $active_sheet->setCellValue($celdaInicio[$columnaInicioPivot].$filaInicioPivot, !isset($camposTramite['data'][$secciones[$index]][$campo]) || empty($camposTramite['data'][$secciones[$index]][$campo]) ? "" : $catalogosTemp[$camposTramite['data'][$secciones[$index]][$campo]]);
                                break;
                        }
                        $columnaInicioPivot += 1;
                    }
                }
                $filaInicioPivot += 1;
            }
            
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

}