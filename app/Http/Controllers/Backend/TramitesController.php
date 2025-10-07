<?php
    
declare(strict_types=1);

namespace App\Http\Controllers\Backend;
    
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\TramiteRequest;
use App\Models\Admin;
use App\Models\CamposPorProceso;
use App\Models\Catalogo;
use App\Models\Proceso;
use App\Models\Tramite;
use App\Models\SecuenciaProceso;
use App\Models\TipoCatalogo;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class TramitesController extends Controller
{
    public function index(): Renderable
    {
        $this->checkAuthorization(auth()->user(), ['tramite.view']);

        $procesos = Proceso::where('estatus','ACTIVO')->get(["nombre", "id"]);
        $catalogos = Catalogo::where('estatus','ACTIVO')->get(["tipo_catalogo_id","id","nombre"]);
        $creadores = Admin::get(["name", "id"]);
        $funcionarios = $creadores;

        return view('backend.pages.tramites.index', [
            'procesos' => $procesos,
            'catalogos' => $catalogos->groupBy('tipo_catalogo_id'),
            'funcionarios' => $funcionarios,
            'creadores' => $creadores
        ]);
    }

    public function inbox(): Renderable
    {
        $this->checkAuthorization(auth()->user(), ['tramite.view']);

        $procesos = Proceso::where('estatus','ACTIVO')->get(["nombre", "id"]);
        $catalogos = Catalogo::where('estatus','ACTIVO')->get(["tipo_catalogo_id","id","nombre"]);
        $creadores = Admin::get(["name", "id"]);
        $funcionarios = $creadores;

        return view('backend.pages.tramites.inbox', [
            'procesos' => $procesos,
            'catalogos' => $catalogos->groupBy('tipo_catalogo_id'),
            'funcionarios' => $funcionarios,
            'creadores' => $creadores
        ]);
    }

    public function create(int $proceso_id): Renderable
    {
        $this->checkAuthorization(auth()->user(), ['tramite.create']);

        $secuenciaProceso = SecuenciaProceso::where('proceso_id',$proceso_id)->where('estatus','ACTIVO')->first();
        $secuenciaProcesoId = $secuenciaProceso->id;
        $campos = CamposPorProceso::where('proceso_id', $proceso_id)->where('estatus','ACTIVO')->get(["nombre", "id"])->pluck('nombre','id');
        $configuracionSecuencia = $secuenciaProceso->configuracion;
        $listaCampos = collect($secuenciaProceso->configuracion_campos)->sortBy('seccion_campo');
        $tiposCatalogos = TipoCatalogo::where('estatus','ACTIVO')->get(["nombre", "id"])->pluck('nombre','id');
        $catalogos = Catalogo::where('estatus','ACTIVO')->get(["tipo_catalogo_id","id","nombre"]);

        return view('backend.pages.tramites.create', [
            'secuenciaProcesoId' => $secuenciaProcesoId,
            'campos' => $campos,
            'configuracionSecuencia' => $configuracionSecuencia,
            'listaCampos' => $listaCampos[0],
            'tiposCatalogos' => $tiposCatalogos,
            'catalogos' => $catalogos->groupBy('tipo_catalogo_id'),
            'proceso_id' => $proceso_id
        ]);
    }

    public function store(int $proceso_id, TramiteRequest $request): RedirectResponse
    {
        $this->checkAuthorization(auth()->user(), ['tramite.create']);
        
        $creado_por = Auth::id();
        $funcionario_actual_id = $creado_por;
        $estatus = "INGRESADO";


        if(!$request->datos || !isset($request->datos) || empty($request->datos || is_null($request->datos))){
            $datos = "";
        }else{
            $datos = $request->datos;
        }

        if(!$request->secuencia_proceso_id || !isset($request->secuencia_proceso_id) || empty($request->secuencia_proceso_id || is_null($request->secuencia_proceso_id))){
            $secuencia_proceso_id = "";
        }else{
            $secuencia_proceso_id = $request->secuencia_proceso_id;
        }

        $secuenciaProceso = SecuenciaProceso::find($secuencia_proceso_id);

        $tramite = new Tramite();
        $tramite->proceso_id = $proceso_id;
        $tramite->secuencia_proceso_id = $secuencia_proceso_id;
        $tramite->funcionario_actual_id = $funcionario_actual_id;
        $tramite->datos = $datos;
        $tramite->estatus = $estatus;
        $tramite->creado_por = $creado_por;
        $tramite->save();

        session()->flash('success', __('Tramite ha sido creado satisfactoriamente. '));
        return redirect()->route('admin.tramites.index');
    }

    public function edit(int $id): Renderable
    {
        $this->checkAuthorization(auth()->user(), ['tramite.edit']);

        $tramite = Tramite::findOrFail($id);
        $proceso_id = $tramite->proceso_id;
        $secuenciaProceso = SecuenciaProceso::findOrFail('secuencia_proceso_id');
        $secuenciaProcesoId = $secuenciaProceso->id;
        $campos = CamposPorProceso::where('proceso_id', $proceso_id)->where('estatus','ACTIVO')->get(["nombre", "id"])->pluck('nombre','id');
        $configuracionSecuencia = $secuenciaProceso->configuracion;
        $listaCampos = collect($secuenciaProceso->configuracion_campos)->sortBy('seccion_campo');
        $tiposCatalogos = TipoCatalogo::where('estatus','ACTIVO')->get(["nombre", "id"])->pluck('nombre','id');
        $catalogos = Catalogo::where('estatus','ACTIVO')->get(["tipo_catalogo_id","id","nombre"]);

        return view('backend.pages.tramites.create', [
            'tramite' => $tramite,
            'secuenciaProcesoId' => $secuenciaProcesoId,
            'campos' => $campos,
            'configuracionSecuencia' => $configuracionSecuencia,
            'listaCampos' => $listaCampos[0],
            'tiposCatalogos' => $tiposCatalogos,
            'catalogos' => $catalogos->groupBy('tipo_catalogo_id'),
            'proceso_id' => $proceso_id
        ]);
    }

    public function update(TramiteRequest $request, int $id): RedirectResponse
    {
        $this->checkAuthorization(auth()->user(), ['tramite.edit']);

        if(!$request->nombre || !isset($request->nombre) || empty($request->nombre || is_null($request->nombre))){
            $nombre = "";
        }else{
            $nombre = $request->nombre;
        }
        if(!$request->descripcion || !isset($request->descripcion) || empty($request->descripcion) || is_null($request->descripcion)){
            $descripcion = "";
        }else{
            $descripcion = $request->descripcion;
        }
        if(!$request->estatus || !isset($request->estatus) || empty($request->estatus) || is_null($request->estatus)){
            $estatus = "";
        }else{
            $estatus = $request->estatus;
        }

        $proceso = Proceso::findOrFail($id);
        $proceso->nombre = $nombre;
        $proceso->descripcion = $descripcion;
        $proceso->estatus = $estatus;
        $proceso->save();

        session()->flash('success', 'Proceso ha sido actualizado satisfactoriamente.');
        return redirect()->route('admin.tramites.index');
    }

    public function getBandejaTramitesByFilters(Request $request): JsonResponse
    {
        $this->checkAuthorization(auth()->user(), ['tramite.view']);

        $funcionario_actual_id = Auth::id();
        $tramites = Tramite::where('funcionario_actual_id',$funcionario_actual_id);

        $filtroProcesoSearch = $request->proceso_search;
        $filtroEstatus = json_decode($request->estatus_search, true);
        $filtroFuncionarioSearch = json_decode($request->funcionario_search, true);
        $filtroCreadoPorSearch = json_decode($request->creado_por_search, true);
        
        if(isset($filtroProcesoSearch) && !empty($filtroProcesoSearch)){
            $tramites = $tramites->where('proceso_id', 1);
        }
        if(isset($filtroEstatus) && !empty($filtroEstatus)){
            $tramites = $tramites->whereIn('estatus', $filtroEstatus);
        }
        if(isset($filtroFuncionarioSearch) && !empty($filtroFuncionarioSearch)){
            $tramites = $tramites->whereIn('funcionario_actual_id', $filtroFuncionarioSearch);
        }
        if(isset($filtroCreadoPorSearch) && !empty($filtroCreadoPorSearch)){
            $tramites = $tramites->whereIn('creado_por', $filtroCreadoPorSearch);
        }
        
        $tramites = $tramites->orderBy('id', 'asc')->get();

        $procesos = Proceso::where('estatus','ACTIVO')->get();

        $procesos_temp = [];
        foreach($procesos as $proceso){
            $procesos_temp[$proceso->id] = $proceso->nombre;
        }

        $secuenciasProceso = SecuenciaProceso::where('proceso_id',$filtroProcesoSearch)->where('estatus','ACTIVO')->get();

        $secuencias_proceso_temp = [];
        foreach($secuenciasProceso as $secuencia){
            $secuencias_proceso_temp[$secuencia->id] = $secuencia->nombre;
        }

        $creadores = Admin::all();

        $creadores_temp = [];
        foreach($creadores as $creador){
            $creadores_temp[$creador->id] = $creador->name;
        }

        $usuario_actual_id = Auth::id();

        foreach($tramites as $tramite){
            $tramite->proceso_nombre = array_key_exists($tramite->proceso_id, $procesos_temp) ? $procesos_temp[$tramite->proceso_id] : "";
            $tramite->secuencia_nombre = array_key_exists($tramite->secuencia_proceso_id, $secuencias_proceso_temp) ? $secuencias_proceso_temp[$tramite->secuencia_proceso_id] : "";
            $tramite->funcionario_actual_nombre = array_key_exists($tramite->funcionario_actual_id, $creadores_temp) ? $creadores_temp[$tramite->funcionario_actual_id] : "";
            $tramite->creado_por_nombre = array_key_exists($tramite->creado_por, $creadores_temp) ? $creadores_temp[$tramite->creado_por] : "";
            $tramite->esCreadorRegistro = $usuario_actual_id == $tramite->creado_por ? true : false;
            $tramite->esEditorRegistro = $usuario_actual_id == $tramite->funcionario_actual_id ? true : false;
        }

        $data['tramites'] = $tramites;
        $data['creadores'] = $creadores;
  
        return response()->json($data);
    }

    public function getTramitesByFilters(Request $request): JsonResponse
    {
        $this->checkAuthorization(auth()->user(), ['tramite.view']);

        $tramites = Tramite::where('id',">",0);

        $filtroProcesoSearch = $request->proceso_search;
        $filtroEstatus = json_decode($request->estatus_search, true);
        $filtroFuncionarioSearch = json_decode($request->funcionario_search, true);
        $filtroCreadoPorSearch = json_decode($request->creado_por_search, true);
        
        if(isset($filtroProcesoSearch) && !empty($filtroProcesoSearch)){
            $tramites = $tramites->where('proceso_id', 1);
        }
        if(isset($filtroEstatus) && !empty($filtroEstatus)){
            $tramites = $tramites->whereIn('estatus', $filtroEstatus);
        }
        if(isset($filtroFuncionarioSearch) && !empty($filtroFuncionarioSearch)){
            $tramites = $tramites->whereIn('funcionario_actual_id', $filtroFuncionarioSearch);
        }
        if(isset($filtroCreadoPorSearch) && !empty($filtroCreadoPorSearch)){
            $tramites = $tramites->whereIn('creado_por', $filtroCreadoPorSearch);
        }
        
        $tramites = $tramites->orderBy('id', 'asc')->get();

        $procesos = Proceso::where('estatus','ACTIVO')->get();

        $procesos_temp = [];
        foreach($procesos as $proceso){
            $procesos_temp[$proceso->id] = $proceso->nombre;
        }

        $secuenciasProceso = SecuenciaProceso::where('proceso_id',$filtroProcesoSearch)->where('estatus','ACTIVO')->get();

        $secuencias_proceso_temp = [];
        foreach($secuenciasProceso as $secuencia){
            $secuencias_proceso_temp[$secuencia->id] = $secuencia->nombre;
        }

        $creadores = Admin::all();

        $creadores_temp = [];
        foreach($creadores as $creador){
            $creadores_temp[$creador->id] = $creador->name;
        }

        $usuario_actual_id = Auth::id();

        foreach($tramites as $tramite){
            $tramite->proceso_nombre = array_key_exists($tramite->proceso_id, $procesos_temp) ? $procesos_temp[$tramite->proceso_id] : "";
            $tramite->secuencia_nombre = array_key_exists($tramite->secuencia_proceso_id, $secuencias_proceso_temp) ? $secuencias_proceso_temp[$tramite->secuencia_proceso_id] : "";
            $tramite->funcionario_actual_nombre = array_key_exists($tramite->funcionario_actual_id, $creadores_temp) ? $creadores_temp[$tramite->funcionario_actual_id] : "";
            $tramite->creado_por_nombre = array_key_exists($tramite->creado_por, $creadores_temp) ? $creadores_temp[$tramite->creado_por] : "";
            $tramite->esCreadorRegistro = $usuario_actual_id == $tramite->creado_por ? true : false;
        }

        $data['tramites'] = $tramites;
        $data['creadores'] = $creadores;
  
        return response()->json($data);
    }

    public function getListaCamposByTramite(Request $request): JsonResponse
    {
        $tramiteId = $request->tramite_id;
        $tramite = Tramite::findOrFail($tramiteId);
        $secuenciaProceso = SecuenciaProceso::findOrFail($tramite->secuencia_proceso_id);
        $listaCampos = collect($secuenciaProceso->configuracion_campos)->sortBy('seccion_campo');

        $data['listaCampos'] = $listaCampos;
  
        return response()->json($data);
    }

    public function consultarSCI(Request $request): JsonResponse
    {
        $this->checkAuthorization(auth()->user(), ['tramite.create']);

        $url = env('URL_LOGIN_SCI');
        $username = env('SCI_USERNAME');
        $password = env('SCI_PASSWORD');

        $urlLogin = $url.'login'; 

        $response = Http::post($urlLogin, [
            'email' => $username,
            'password' => $password,
        ]);

        if ($response->successful()) {
            $data = $response->json();
            $token = $data['data']['token'];

            $urlWS = $url.'getConsultaWsInteroperabilidad';
            
            $responseWS = Http::withToken($token)->post($urlWS, [
                'tipo_consulta_id' => 1,
                'identificacion' => $request->identificacion,
            ]);

            if ($responseWS->successful()) {
                $dataWs = $responseWS->json();

                return response()->json($dataWs);
            }else {
                
                return response()->json($responseWS->body());
            }
        } else {
            
            return response()->json($response->body());
        }

        return response()->json($response->body());
    }

}