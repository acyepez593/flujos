<?php
    
declare(strict_types=1);

namespace App\Http\Controllers\Backend;
    
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\TramiteRequest;
use App\Models\Admin;
use App\Models\CamposPorProceso;
use App\Models\Catalogo;
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
use Spatie\Permission\Models\Role;
use Symfony\Component\HttpFoundation\StreamedResponse;

class TramitesController extends Controller
{
    public function index(): Renderable
    {
        $this->checkAuthorization(auth()->user(), ['tramite.view']);

        $creadores = Admin::get(["name", "id"]);
        $responsable_id = Auth::id();

        return view('backend.pages.tramites.index', [
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

        $proceso = Proceso::findOrFail($id);
        if($proceso->creado_por != Auth::id()){
            abort(403, 'Lo sentimos !! Usted no está autorizado para realizar esta acción.');
        }

        $creadores = Admin::get(["name", "id"])->pluck('nombre','id');

        return view('backend.pages.tramites.edit', [
            'proceso' => $proceso,
            'creadores' => $creadores
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

    public function getTramitesByFilters(Request $request): JsonResponse
    {
        $this->checkAuthorization(auth()->user(), ['tramite.view']);

        $procesos = Proceso::where('id',">",0);

        $filtroNombreSearch = $request->nombre_search;
        $filtroDescripcionSearch = $request->descripcion_search;
        $filtroEstatus = json_decode($request->estatus_search, true);
        $filtroCreadoPorSearch = json_decode($request->creado_por_search, true);
        
        if(isset($filtroNombreSearch) && !empty($filtroNombreSearch)){
            $procesos = $procesos->where('nombre', 'like', '%'.$filtroNombreSearch.'%');
        }
        if(isset($filtroDescripcionSearch) && !empty($filtroDescripcionSearch)){
            $procesos = $procesos->where('descripcion', 'like', '%'.$filtroDescripcionSearch.'%');
        }
        if(isset($filtroEstatus) && !empty($filtroEstatus)){
            $procesos = $procesos->whereIn('estatus', $filtroEstatus);
        }
        if(isset($filtroCreadoPorSearch) && !empty($filtroCreadoPorSearch)){
            $procesos = $procesos->whereIn('creado_por', $filtroCreadoPorSearch);
        }
        
        $procesos = $procesos->orderBy('id', 'desc')->get();

        $creadores = Admin::all();

        $creadores_temp = [];
        foreach($creadores as $creador){
            $creadores_temp[$creador->id] = $creador->name;
        }

        $usuario_actual_id = Auth::id();

        foreach($procesos as $proceso){
            $proceso->creado_por_nombre = array_key_exists($proceso->creado_por, $creadores_temp) ? $creadores_temp[$proceso->creado_por] : "";
            $proceso->esCreadorRegistro = $usuario_actual_id == $proceso->creado_por ? true : false;
        }

        $data['procesos'] = $procesos;
        $data['creadores'] = $creadores;
  
        return response()->json($data);
    }

    public function consultarSCI(Request $request): JsonResponse
    {
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

            return response()->json($data);
            
        } else {
            $statusCode = $response->status();
            $errorMessage = $response->body();
            
            return response()->json($response->body());
        }

        return response()->json($response->body());
    }

}