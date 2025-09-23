<?php
    
declare(strict_types=1);

namespace App\Http\Controllers\Backend;
    
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\TramiteRequest;
use App\Models\Admin;
use App\Models\CamposPorProceso;
use App\Models\Proceso;
use App\Models\SecuenciaProceso;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
        
        $secuenciaProceso = SecuenciaProceso::where('proceso_id', $proceso_id)->first();
        $campos = CamposPorProceso::where('proceso_id', $proceso_id)->get(["nombre", "id"])->pluck('nombre','id');
        $listaCampos = $secuenciaProceso->configuracion_campos;
        $actores = Admin::get(["name", "id"])->pluck('name','id');

        return view('backend.pages.tramites.create', [
            'campos' => $campos,
            'listaCampos' => $listaCampos
        ]);
    }

    public function store(TramiteRequest $request): RedirectResponse
    {
        $this->checkAuthorization(auth()->user(), ['tramite.create']);
        
        $creado_por = Auth::id();

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

        $proceso = new Proceso();
        $proceso->nombre = $nombre;
        $proceso->descripcion = $descripcion;
        $proceso->estatus = $estatus;
        $proceso->creado_por = $creado_por;
        $proceso->save();

        session()->flash('success', __('Proceso ha sido creado satisfactoriamente. '));
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

}