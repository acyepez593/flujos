<?php
    
declare(strict_types=1);

namespace App\Http\Controllers\Backend;
    
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ConfiguracionCamposReporteRequest;
use App\Models\Admin;
use App\Models\CamposPorProceso;
use App\Models\ConfiguracionCamposReporte;
use App\Models\Proceso;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class ConfiguracionesCamposReporteController extends Controller
{
    public function index(): Renderable
    {
        $this->checkAuthorization(auth()->user(), ['configuracionCamposReporte.view']);

        $configuracionesCamposReporte = ConfiguracionCamposReporte::all();
        $procesos = Proceso::get(['id', 'nombre']);
        $funcionarios = Admin::get(["name", "id"]);

        $opcionesHabilitar=array
        (
            array("id"=>"1","nombre"=>"SI"),
            array("id"=>"0","nombre"=>"NO"),
        );

        return view('backend.pages.configuracionesCamposReporte.index', [
            'procesos' => $procesos,
            'funcionarios' => $funcionarios,
            'opcionesHabilitar' => $opcionesHabilitar,
            'configuracionesCamposReporte' => $configuracionesCamposReporte
        ]);
    }

    public function create(): Renderable
    {
        $this->checkAuthorization(auth()->user(), ['configuracionCamposReporte.create']);

        $opcionesHabilitar=array
        (
            array("id"=>"1","nombre"=>"SI"),
            array("id"=>"0","nombre"=>"NO"),
        );

        $procesos = Proceso::get(['id', 'nombre']);
        $procesos_temp = [];
        foreach($procesos as $proceso){
            $procesos_temp[$proceso->id] = $proceso->nombre;
        }
        $columnas = CamposPorProceso::where('proceso_id', 1)->get();
        $campos = [];
        foreach($columnas as $index => $col){
            if($col != 'created_at' && $col != 'updated_at' && $col != 'deleted_at'){
                $obj["nombre_campo"] = $col->nombre;
                $obj["nombre_proceso"] = $procesos_temp[$col->proceso_id];
                $obj["nombre_seccion"] = $col->seccion_campo;
                $obj["campo"] = $col->variable;
                $obj["orden"] = $index + 1;
                $obj["habilitado"] = false;
                $campos[] = $obj;
            }
        }

        $funcionarios = Admin::get(['name', 'id']);

        return view('backend.pages.configuracionesCamposReporte.create', [
            'opcionesHabilitar' => $opcionesHabilitar,
            'objCampos' => $campos,
            'funcionarios' => $funcionarios,
            'procesos' => $procesos
        ]);
    }

    public function store(ConfiguracionCamposReporteRequest $request): RedirectResponse
    {
        $this->checkAuthorization(auth()->user(), ['configuracionCamposReporte.create']);

        if($request->nombre && isset($request->nombre) && !empty($request->nombre && !is_null($request->nombre))){
            $nombre = $request->nombre;
        }
        if($request->proceso_id && isset($request->proceso_id) && !empty($request->proceso_id) && !is_null($request->proceso_id)){
            $proceso_id = $request->proceso_id;
        }
        if($request->funcionario_id && isset($request->funcionario_id) && !empty($request->funcionario_id) && !is_null($request->funcionario_id)){
            $funcionario_id = $request->funcionario_id;
        }
        if($request->habilitar && isset($request->habilitar) && !empty($request->habilitar) && !is_null($request->habilitar)){
            $habilitar = $request->habilitar;
        }
        if($request->campos && isset($request->campos) && !empty($request->campos) && !is_null($request->campos)){
            $campos = $request->campos;
        }

        $configuracionCamposReporte = new ConfiguracionCamposReporte();
        $configuracionCamposReporte->nombre = $nombre;
        $configuracionCamposReporte->proceso_id = $proceso_id;
        $configuracionCamposReporte->funcionario_id = $funcionario_id;
        $configuracionCamposReporte->habilitar = $habilitar;
        $configuracionCamposReporte->campos = $campos;
        $configuracionCamposReporte->save();

        session()->flash('success', __('La Configuración de Campos Reporte, ha sido creada satisfactoriamente. '));
        return redirect()->route('admin.configuracionesCamposReporte.index');
        
    }

    public function edit(int $id): Renderable
    {
        $this->checkAuthorization(auth()->user(), ['configuracionCamposReporte.edit']);

        $configuracionCamposReporte = ConfiguracionCamposReporte::findOrFail($id);

        $opcionesHabilitar=array
        (
            array("id"=>"1","nombre"=>"SI"),
            array("id"=>"0","nombre"=>"NO"),
        );
        
        $campos = json_decode($configuracionCamposReporte->campos, true);
        $procesos = Proceso::get(['id', 'nombre']);
        $funcionarios = Admin::get(["name", "id"]);

        return view('backend.pages.configuracionesCamposReporte.edit', [
            'configuracionCamposReporte' => $configuracionCamposReporte,
            'opcionesHabilitar' => $opcionesHabilitar,
            'objCampos' => $campos,
            'funcionarios' => $funcionarios,
            'procesos' => $procesos
        ]);
    }

    public function update(ConfiguracionCamposReporteRequest $request, int $id): RedirectResponse
    {
        $this->checkAuthorization(auth()->user(), ['configuracionCamposReporte.edit']);

        if($request->nombre && isset($request->nombre) && !empty($request->nombre && !is_null($request->nombre))){
            $nombre = $request->nombre;
        }
        if($request->proceso_id && isset($request->proceso_id) && !empty($request->proceso_id) && !is_null($request->proceso_id)){
            $proceso_id = $request->proceso_id;
        }
        if($request->funcionario_id && isset($request->funcionario_id) && !empty($request->funcionario_id) && !is_null($request->funcionario_id)){
            $funcionario_id = $request->funcionario_id;
        }
        if($request->campos && isset($request->campos) && !empty($request->campos) && !is_null($request->campos)){
            $campos = $request->campos;
        }
        
        $habilitar = $request->habilitar;
        
        $configuracionCamposReporte = ConfiguracionCamposReporte::findOrFail($id);
        $configuracionCamposReporte->nombre = $nombre;
        $configuracionCamposReporte->proceso_id = $proceso_id;
        $configuracionCamposReporte->funcionario_id = $funcionario_id;
        $configuracionCamposReporte->habilitar = $habilitar;
        $configuracionCamposReporte->campos = $campos;
        $configuracionCamposReporte->save();

        session()->flash('success', 'La Configuración de Campos Reporte ha sido actualizada satisfactoriamente.');
        return redirect()->route('admin.configuracionesCamposReporte.index');
    }

    public function destroy(int $id): JsonResponse
    {
        $this->checkAuthorization(auth()->user(), ['configuracionCamposReporte.delete']);

        $configuracionCamposReporte = ConfiguracionCamposReporte::findOrFail($id);

        $configuracionCamposReporte->delete();

        $data['status'] = 200;
        $data['message'] = "La Configuración de Campos Reporte ha sido borrado satisfactoriamente.";
  
        return response()->json($data);

    }

    public function getConfiguracionesCamposReporteByFilters(Request $request): JsonResponse
    {
        $this->checkAuthorization(auth()->user(), ['configuracionCamposReporte.view']);

        $configuracionesCamposReporte = ConfiguracionCamposReporte::where('id',">",0);

        $filtroNombreSearch = $request->nombre_search;
        $filtroProcesoIdSearch = json_decode($request->proceso_id_search, true);
        $filtroFuncionarioIdSearch = json_decode($request->funcionario_id_search, true);
        $filtroHabilitarSearch = json_decode($request->habilitar_search, true);

        if(isset($filtroNombreSearch) && !empty($filtroNombreSearch)){
            $configuracionesCamposReporte = $configuracionesCamposReporte->where('nombre', $filtroNombreSearch);
        }
        if(isset($filtroProcesoIdSearch) && !empty($filtroProcesoIdSearch)){
            $configuracionesCamposReporte = $configuracionesCamposReporte->whereIn('proceso_id', $filtroProcesoIdSearch);
        }
        if(isset($filtroFuncionarioIdSearch) && !empty($filtroFuncionarioIdSearch)){
            $configuracionesCamposReporte = $configuracionesCamposReporte->whereIn('funcionario_id', $filtroFuncionarioIdSearch);
        }
        if(isset($filtroHabilitarSearch) && !empty($filtroHabilitarSearch)){
            $configuracionesCamposReporte = $configuracionesCamposReporte->where('habilitar', $filtroHabilitarSearch);
        }

        $configuracionesCamposReporte = $configuracionesCamposReporte->orderBy('id', 'desc')->get();

        $funcionarios = Admin::all();

        $funcionarios_temp = [];
        foreach($funcionarios as $funcionario){
            $funcionarios_temp[$funcionario->id] = $funcionario->name;
        }

        $procesos = Proceso::all();
        $procesos_temp = [];
        foreach($procesos as $proceso){
            $procesos_temp[$proceso->id] = $proceso->nombre;
        }

        foreach($configuracionesCamposReporte as $conf){
            $conf->proceso_nombre = array_key_exists($conf->proceso_id, $procesos_temp) ? $procesos_temp[$conf->proceso_id] : "";
            $conf->funcionario_nombre = array_key_exists($conf->funcionario_id, $funcionarios_temp) ? $funcionarios_temp[$conf->funcionario_id] : "";
        }

        $data['configuracionesCamposReporte'] = $configuracionesCamposReporte;
  
        return response()->json($data);
    }
    
    public function getCamposPorProceso(Request $request): JsonResponse
    {
        $this->checkAuthorization(auth()->user(), ['configuracionCamposReporte.view']);

        $camposPorProceso = CamposPorProceso::where('id',">",0);
        $filtroProcesoIdSearch = $request->proceso_id;

        if(isset($filtroProcesoIdSearch) && !empty($filtroProcesoIdSearch)){
            $camposPorProceso = $camposPorProceso->where('proceso_id', $filtroProcesoIdSearch);
        }

        $proceso = Proceso::find($filtroProcesoIdSearch);
        $columnas = $camposPorProceso->get();
        $campos = [];
        foreach($columnas as $index => $col){
            if($col != 'created_at' && $col != 'updated_at' && $col != 'deleted_at'){
                $obj["nombre_campo"] = $col->nombre;
                $obj["nombre_proceso"] = $proceso->nombre;
                $obj["nombre_seccion"] = $col->seccion_campo;
                $obj["campo"] = $col->variable;
                $obj["orden"] = $index + 1;
                $obj["habilitado"] = false;
                $campos[] = $obj;
            }
        }

        $data['objCampos'] = $campos;
  
        return response()->json($data);
    }

}