<?php
    
declare(strict_types=1);

namespace App\Http\Controllers\Backend;
    
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\TramiteRequest;
use App\Mail\Notification;
use App\Models\Admin;
use App\Models\Beneficiario;
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
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Throwable;

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

    public function reassign(): Renderable
    {
        $this->checkAuthorization(auth()->user(), ['tramite.reassign']);

        $procesos = Proceso::where('estatus','ACTIVO')->get(["nombre", "id"]);
        $catalogos = Catalogo::where('estatus','ACTIVO')->get(["tipo_catalogo_id","id","nombre"]);
        $creadores = Admin::get(["name", "id"]);
        $funcionarios = $creadores;

        return view('backend.pages.tramites.reassign', [
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
        $tiposCatalogos = TipoCatalogo::where('estatus','ACTIVO')->get(["nombre", "id","tipo_catalogo_relacionado_id"]);
        $catalogos = Catalogo::where('estatus','ACTIVO')->get(["tipo_catalogo_id","id","nombre","catalogo_id"]);

        $tiposCatalogosRelacionadosIds = [];
        $tiposCatalogosIds = [];
        foreach($tiposCatalogos as $tipoCatalogo){
            if(!empty($tipoCatalogo->tipo_catalogo_relacionado_id)){
                $tiposCatalogosRelacionadosIds[] = $tipoCatalogo->tipo_catalogo_relacionado_id;
            }
            $tiposCatalogosIds[] = $tipoCatalogo->id;
        }

        $catalogosRelacionadosByTipoCatalogo = Catalogo::whereIn('tipo_catalogo_id',$tiposCatalogosRelacionadosIds)->where('estatus','ACTIVO')->get(['tipo_catalogo_id','catalogo_id','id','nombre'])->groupBy('tipo_catalogo_id');

        $catalogosByCatalogoId = Catalogo::where('estatus','ACTIVO')->whereNotNull('catalogo_id')->get(['id','tipo_catalogo_id','catalogo_id','nombre'])->groupBy('catalogo_id');

        return view('backend.pages.tramites.create', [
            'secuenciaProcesoId' => $secuenciaProcesoId,
            'campos' => $campos,
            'configuracionSecuencia' => $configuracionSecuencia,
            'listaCampos' => $listaCampos[0],
            'tiposCatalogos' => $tiposCatalogos,
            'catalogos' => $catalogos->groupBy('tipo_catalogo_id'),
            'catalogosRelacionadosByTipoCatalogo' => $catalogosRelacionadosByTipoCatalogo,
            'catalogosByCatalogoId' => $catalogosByCatalogoId,
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

        $tramite = new Tramite();
        $tramite->proceso_id = $proceso_id;
        $tramite->secuencia_proceso_id = $secuencia_proceso_id;
        $tramite->funcionario_actual_id = $funcionario_actual_id;
        $tramite->datos = $datos;
        $tramite->estatus = $estatus;
        $tramite->creado_por = $creado_por;
        $tramite->save();

        $beneficiarios = json_decode($datos, true)['data']['BENEFICIARIOS'];
        foreach($beneficiarios as $ben){
            $beneficiario = new Beneficiario();
            $beneficiario->tramite_id = $tramite->id;
            $beneficiario->datos =json_encode($ben);
            $beneficiario->creado_por = $creado_por;
            $beneficiario->save();
        }

        session()->flash('success', __('Trámite ha sido creado satisfactoriamente. '));
        return redirect()->route('admin.tramites.inbox');
    }

    public function edit(int $id): Renderable
    {
        $this->checkAuthorization(auth()->user(), ['tramite.edit']);

        $tramite = Tramite::findOrFail($id);
        $proceso_id = $tramite->proceso_id;
        $secuenciaProceso = SecuenciaProceso::findOrFail($tramite->secuencia_proceso_id);
        $secuenciaProcesoId = $secuenciaProceso->id;
        $campos = CamposPorProceso::where('proceso_id', $proceso_id)->where('estatus','ACTIVO')->get(["nombre", "id"])->pluck('nombre','id');
        $configuracionSecuencia = $secuenciaProceso->configuracion;
        $listaCampos = collect($secuenciaProceso->configuracion_campos)->sortBy('seccion_campo');
        $tiposCatalogos = TipoCatalogo::where('estatus','ACTIVO')->get(["nombre", "id","tipo_catalogo_relacionado_id"]);
        $catalogos = Catalogo::where('estatus','ACTIVO')->get(["tipo_catalogo_id","id","nombre","catalogo_id"]);
        $beneficiarios = Beneficiario::where('tramite_id',$tramite->id)->get();

        $tiposCatalogosRelacionadosIds = [];
        $tiposCatalogosIds = [];
        foreach($tiposCatalogos as $tipoCatalogo){
            if(!empty($tipoCatalogo->tipo_catalogo_relacionado_id)){
                $tiposCatalogosRelacionadosIds[] = $tipoCatalogo->tipo_catalogo_relacionado_id;
            }
            $tiposCatalogosIds[] = $tipoCatalogo->id;
        }

        $catalogosRelacionadosByTipoCatalogo = Catalogo::whereIn('tipo_catalogo_id',$tiposCatalogosRelacionadosIds)->where('estatus','ACTIVO')->get(['tipo_catalogo_id','catalogo_id','id','nombre'])->groupBy('tipo_catalogo_id');

        $catalogosByCatalogoId = Catalogo::where('estatus','ACTIVO')->whereNotNull('catalogo_id')->get(['id','tipo_catalogo_id','catalogo_id','nombre'])->groupBy('catalogo_id');

        return view('backend.pages.tramites.edit', [
            'tramite' => $tramite,
            'beneficiarios' => $beneficiarios,
            'secuenciaProcesoId' => $secuenciaProcesoId,
            'campos' => $campos,
            'configuracionSecuencia' => $configuracionSecuencia,
            'listaCampos' => $listaCampos[0],
            'tiposCatalogos' => $tiposCatalogos,
            'catalogos' => $catalogos->groupBy('tipo_catalogo_id'),
            'catalogosRelacionadosByTipoCatalogo' => $catalogosRelacionadosByTipoCatalogo,
            'catalogosByCatalogoId' => $catalogosByCatalogoId,
            'proceso_id' => $proceso_id
        ]);
    }

    public function update(TramiteRequest $request, int $id): RedirectResponse
    {
        $this->checkAuthorization(auth()->user(), ['tramite.edit']);

        $creado_por = Auth::id();

        if(!$request->datos || !isset($request->datos) || empty($request->datos || is_null($request->datos))){
            $datos = "";
        }else{
            $datos = $request->datos;
        }

        $tramite = Tramite::findOrFail($id);
        $tramite->datos = $datos;
        $tramite->save();

        Beneficiario::where('tramite_id',$tramite->id)->delete();

        $beneficiarios = json_decode($datos, true)['data']['BENEFICIARIOS'];
        foreach($beneficiarios as $ben){
            $beneficiario = new Beneficiario();
            $beneficiario->tramite_id = $tramite->id;
            $beneficiario->datos =json_encode($ben);
            $beneficiario->creado_por = $creado_por;
            $beneficiario->save();
        }

        session()->flash('success', 'Trámite ha sido actualizado satisfactoriamente.');
        return redirect()->route('admin.tramites.inbox');
    }

    public function getBandejaTramitesByFilters(Request $request): JsonResponse
    {
        $this->checkAuthorization(auth()->user(), ['tramite.view']);

        $funcionario_actual_id = Auth::id();
        $tramites = Tramite::where('funcionario_actual_id',$funcionario_actual_id);

        $filtroProcesoIdSearch = $request->proceso_id_search;
        $filtroSecuenciaIdProcesoSearch = $request->secuencia_proceso_id_search;
        $filtroEstatus = json_decode($request->estatus_search, true);
        
        if(isset($filtroProcesoIdSearch) && !empty($filtroProcesoIdSearch)){
            $tramites = $tramites->where('proceso_id', $filtroProcesoIdSearch);
        }
        if(isset($filtroSecuenciaIdProcesoSearch) && !empty($filtroSecuenciaIdProcesoSearch)){
            $tramites = $tramites->where('proceso_id', $filtroSecuenciaIdProcesoSearch);
        }
        if(isset($filtroEstatus) && !empty($filtroEstatus)){
            $tramites = $tramites->whereIn('estatus', $filtroEstatus);
        }
        
        $tramites = $tramites->orderBy('id', 'asc')->get();

        $procesos = Proceso::where('estatus','ACTIVO')->get();

        $procesos_temp = [];
        foreach($procesos as $proceso){
            $procesos_temp[$proceso->id] = $proceso->nombre;
        }

        $secuenciasProceso = SecuenciaProceso::where('proceso_id',$filtroProcesoIdSearch)->where('estatus','ACTIVO')->get();

        $secuencias_proceso_temp = [];
        $configuracion_secuencia_temp = [];
        foreach($secuenciasProceso as $secuencia){
            $secuencias_proceso_temp[$secuencia->id] = $secuencia->nombre;
            $configuracion_secuencia_temp[$secuencia->id] = json_decode($secuencia->configuracion, true);
        }

        $creadores = Admin::all();

        $creadores_temp = [];
        foreach($creadores as $creador){
            $creadores_temp[$creador->id] = $creador->name;
        }

        $usuario_actual_id = Auth::id();

        $secuencia_proceso_id = 0;

        foreach($tramites as $tramite){
            $secuencia_proceso_id = $tramite->secuencia_proceso_id;
            $tramite->proceso_nombre = array_key_exists($tramite->proceso_id, $procesos_temp) ? $procesos_temp[$tramite->proceso_id] : "";
            $tramite->secuencia_nombre = array_key_exists($tramite->secuencia_proceso_id, $secuencias_proceso_temp) ? $secuencias_proceso_temp[$tramite->secuencia_proceso_id] : "";
            $tramite->funcionario_actual_nombre = array_key_exists($tramite->funcionario_actual_id, $creadores_temp) ? $creadores_temp[$tramite->funcionario_actual_id] : "";
            $tramite->creado_por_nombre = array_key_exists($tramite->creado_por, $creadores_temp) ? $creadores_temp[$tramite->creado_por] : "";
            $tramite->esCreadorRegistro = $usuario_actual_id == $tramite->creado_por ? true : false;
            $tramite->esEditorRegistro = $usuario_actual_id == $tramite->funcionario_actual_id ? true : false;
            $tramite->habilidato_para_continuar = array_key_exists($tramite->secuencia_proceso_id, $configuracion_secuencia_temp) ? !$configuracion_secuencia_temp[$tramite->secuencia_proceso_id]['requiere_evaluacion'] : "";
            $tramite->requiere_memorando = array_key_exists($tramite->secuencia_proceso_id, $configuracion_secuencia_temp) ? !$configuracion_secuencia_temp[$tramite->secuencia_proceso_id]['requiere_memorando'] : "";
            $tramite->requiere_fecha_memorando = array_key_exists($tramite->secuencia_proceso_id, $configuracion_secuencia_temp) ? !$configuracion_secuencia_temp[$tramite->secuencia_proceso_id]['requiere_fecha_memorando'] : "";
            $tramite->requiere_adjuntar_memorando = array_key_exists($tramite->secuencia_proceso_id, $configuracion_secuencia_temp) ? !$configuracion_secuencia_temp[$tramite->secuencia_proceso_id]['requiere_adjuntar_memorando'] : "";
        }

        $secuencia = SecuenciaProceso::find($secuencia_proceso_id);        

        $data['tramites'] = $tramites;
        $data['secuencia'] = $secuencia;
  
        return response()->json($data);
    }

    public function getTramitesParaReasignarByFilters(Request $request): JsonResponse
    {
        $this->checkAuthorization(auth()->user(), ['tramite.reassign']);

        $funcionario_actual_id = Auth::id();
        $tramites = Tramite::where('estatus','<>','PAGADO');

        $filtroProcesoIdSearch = $request->proceso_id_search;
        $filtroSecuenciaIdProcesoSearch = $request->secuencia_proceso_id_search;
        $filtroEstatus = json_decode($request->estatus_search, true);
        
        if(isset($filtroProcesoIdSearch) && !empty($filtroProcesoIdSearch)){
            $tramites = $tramites->where('proceso_id', $filtroProcesoIdSearch);
        }
        if(isset($filtroSecuenciaIdProcesoSearch) && !empty($filtroSecuenciaIdProcesoSearch)){
            $tramites = $tramites->where('proceso_id', $filtroSecuenciaIdProcesoSearch);
        }
        if(isset($filtroEstatus) && !empty($filtroEstatus)){
            $tramites = $tramites->whereIn('estatus', $filtroEstatus);
        }
        
        $tramites = $tramites->orderBy('id', 'asc')->get();

        $procesos = Proceso::where('estatus','ACTIVO')->get();

        $procesos_temp = [];
        foreach($procesos as $proceso){
            $procesos_temp[$proceso->id] = $proceso->nombre;
        }

        $secuenciasProceso = SecuenciaProceso::where('proceso_id',$filtroProcesoIdSearch)->where('estatus','ACTIVO')->get();

        $secuencias_proceso_temp = [];
        $configuracion_secuencia_temp = [];
        foreach($secuenciasProceso as $secuencia){
            $secuencias_proceso_temp[$secuencia->id] = $secuencia->nombre;
            $configuracion_secuencia_temp[$secuencia->id] = json_decode($secuencia->configuracion, true);
        }

        $creadores = Admin::all();

        $creadores_temp = [];
        foreach($creadores as $creador){
            $creadores_temp[$creador->id] = $creador->name;
        }

        $usuario_actual_id = Auth::id();

        $secuencia_proceso_id = 0;

        foreach($tramites as $tramite){
            $secuencia_proceso_id = $tramite->secuencia_proceso_id;
            $tramite->proceso_nombre = array_key_exists($tramite->proceso_id, $procesos_temp) ? $procesos_temp[$tramite->proceso_id] : "";
            $tramite->secuencia_nombre = array_key_exists($tramite->secuencia_proceso_id, $secuencias_proceso_temp) ? $secuencias_proceso_temp[$tramite->secuencia_proceso_id] : "";
            $tramite->funcionario_actual_nombre = array_key_exists($tramite->funcionario_actual_id, $creadores_temp) ? $creadores_temp[$tramite->funcionario_actual_id] : "";
            $tramite->creado_por_nombre = array_key_exists($tramite->creado_por, $creadores_temp) ? $creadores_temp[$tramite->creado_por] : "";
            $tramite->esCreadorRegistro = $usuario_actual_id == $tramite->creado_por ? true : false;
            $tramite->esEditorRegistro = $usuario_actual_id == $tramite->funcionario_actual_id ? true : false;
            $tramite->habilidato_para_continuar = array_key_exists($tramite->secuencia_proceso_id, $configuracion_secuencia_temp) ? !$configuracion_secuencia_temp[$tramite->secuencia_proceso_id]['requiere_evaluacion'] : "";
            $tramite->requiere_memorando = array_key_exists($tramite->secuencia_proceso_id, $configuracion_secuencia_temp) ? !$configuracion_secuencia_temp[$tramite->secuencia_proceso_id]['requiere_memorando'] : "";
            $tramite->requiere_fecha_memorando = array_key_exists($tramite->secuencia_proceso_id, $configuracion_secuencia_temp) ? !$configuracion_secuencia_temp[$tramite->secuencia_proceso_id]['requiere_fecha_memorando'] : "";
            $tramite->requiere_adjuntar_memorando = array_key_exists($tramite->secuencia_proceso_id, $configuracion_secuencia_temp) ? !$configuracion_secuencia_temp[$tramite->secuencia_proceso_id]['requiere_adjuntar_memorando'] : "";
        }

        $secuencia = SecuenciaProceso::find($secuencia_proceso_id);        

        $data['tramites'] = $tramites;
        $data['secuencia'] = $secuencia;
  
        return response()->json($data);
    }

    public function procesarTramites(Request $request): JsonResponse
    {
        $this->checkAuthorization(auth()->user(), ['tramite.edit']);

        try {
            $proceso_id = $request->proceso_id;
            $tramites_ids = json_decode($request->tramites_ids, true);
            $numero_memorando = $request->numero_memorando;

            $tramites = Tramite::whereIn('id',$tramites_ids)->get();

            $numeroTramites = $tramites->count();
            $secuencia_proceso_id = $tramites[0]['secuencia_proceso_id'];
            $secuencia_proceso = SecuenciaProceso::findOrFail($secuencia_proceso_id);
            $configuracion_secuencia = json_decode($secuencia_proceso->configuracion, true);

            $contadorTramite = 0;
            $cont = 0;
            $tramitesPorUsuario = [];

            foreach($tramites as $tramite){
                $datos = json_decode($tramite->datos, true);
                $datos['data']['MEMORANDOS'] = [];
                $datos['data']['MEMORANDOS'][$tramite->secuencia_proceso_id] = [];

                $obj1 = [];
                $obj['secuencia_proceso_id'] = $tramite->secuencia_proceso_id;
                $obj['numero_memorando'] = $numero_memorando;

                array_push($datos['data']['MEMORANDOS'][$tramite->secuencia_proceso_id], $obj);
                $tramite->datos = json_encode($datos);

                if($configuracion_secuencia['requiere_evaluacion'] == false){
                    $siguiente_secuencia_proceso = SecuenciaProceso::findOrFail($configuracion_secuencia['camino_sin_evaluacion']);
                    $configuracion_siguiente_secuencia = json_decode($siguiente_secuencia_proceso->configuracion, true);
                    $tramite->secuencia_proceso_id = $configuracion_secuencia['camino_sin_evaluacion'];
                    if($configuracion_siguiente_secuencia['distribuir_automaticamente_tramites'] == false){
                        $tramite->funcionario_actual_id = $siguiente_secuencia_proceso->actor_id;
                        $tramite->estatus = 'EN PROCESO DAP';

                        if($contadorTramite == 0){
                            $tramitesPorUsuario[$tramite->funcionario_actual_id] = [];
                        }
                        array_push($tramitesPorUsuario[$tramite->funcionario_actual_id], $tramite->id);
                    }else{
                        //distribucion automatica de tramites
                        $rolId = $siguiente_secuencia_proceso->rol_id;
                        $usersId = Role::getUsersByRol($rolId);
                        if($contadorTramite == 0){
                            foreach($usersId as $userId){
                                $tramitesPorUsuario[$userId] = [];
                            }
                        }
                        $numeroUsuariosConRol = $usersId->count();
                        $numeroTramitesPorUsuario = ceil($numeroTramites/$numeroUsuariosConRol);
                        
                        if($contadorTramite == $numeroTramitesPorUsuario){
                            $cont += 1;
                        }

                        $tramite->funcionario_actual_id = $usersId[$cont];
                        $tramite->estatus = 'EN ANALISIS DE PROCEDENCIA';

                        array_push($tramitesPorUsuario[$tramite->funcionario_actual_id], $tramite->id);

                    }
                    
                }
                $tramite->save();
                $contadorTramite += 1;

            }

            foreach($tramitesPorUsuario as $tramitePorUsuario){
                $numTramites = count($tramitePorUsuario);
                $this->enviarCorreo($secuencia_proceso_id, $tramitePorUsuario[0], strval($numTramites));
            }

            return response()->json(['tramites' => $tramites,'message' => 'Tramites procesados exitosamente!'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Algo salió mal, por favor intente nuevamenteee.'.env('MAIL_HOST').'--'.env('MAIL_PORT'). '------'.$e], 500);
        } catch (Throwable $e) {
            return response()->json(['error' => 'Algo salió mal, por favor intente nuevamente.'], 500);
        }

    }

    public function reasignarTramites(Request $request): JsonResponse
    {
        $this->checkAuthorization(auth()->user(), ['tramite.reassign']);
        
        $proceso_id = $request->proceso_id;
        $tramites_ids = json_decode($request->tramites_ids, true);
        $funcionario_a_reasignar = $request->funcionario_a_reasignar;
        $comentario_reasignacion = $request->comentario_reasignacion;

        $tramites = Tramite::whereIn('id',$tramites_ids)->get();

        foreach($tramites as $tramite){
            $tramite->funcionario_actual_id = $funcionario_a_reasignar;
            $tramite->save();
        }

        session()->flash('success', __('Tramites reasignados exitosamente! '));
        return response()->json(['tramites' => $tramites,'message' => 'Tramites reasignados exitosamente!'], 200);

    }

    public function enviarCorreo($secuencia_proceso_id, $tramite_id, $num_tramites)
    {
        try {
            $secuencia_proceso = SecuenciaProceso::findOrFail($secuencia_proceso_id);
            $tramite = Tramite::find($tramite_id);
            $proceso = Proceso::find($tramite->proceso_id);
            $configuracion_correo = json_decode($secuencia_proceso->configuracion_correo,true);

            $funcionario_actual = Admin::find($tramite->funcionario_actual_id)->name;
            $numero_tramites = $num_tramites;
            $tiempo_procesamiento = strval($secuencia_proceso->tiempo_procesamiento);

            $subject = $configuracion_correo['subject'] . ' ' . $proceso->nombre;
            $content = $configuracion_correo['contenido_html'];


            $content = str_replace("[funcionario_actual]", $funcionario_actual, $content);
            $content = str_replace("[numero_tramites]", strval($numero_tramites), $content);
            $content = str_replace("[tiempo_procesamiento]", $tiempo_procesamiento, $content);
            Mail::to('augusto.yepez@sppat.gob.ec')->queue(new Notification($subject,$content));
            //Mail::to($funcionario_actual->email)->queue(new Notification($subject,$content));
            //Mail::to('augusto.yepez@sppat.gob.ec')->send(new Notification($subject,$content));
        } catch (\Exception $e) {
            //return response()->json(['error' => 'Algo salió mal, por favor intente nuevamenteee.'.env('MAIL_HOST').'--'.env('MAIL_PORT'). '------'.$e], 500);
        } catch (Throwable $e) {
            //return response()->json(['error' => 'Algo salió mal, por favor intente nuevamente.'], 500);
        }
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
        //$this->checkAuthorization(auth()->user(), ['tramite.create']);

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