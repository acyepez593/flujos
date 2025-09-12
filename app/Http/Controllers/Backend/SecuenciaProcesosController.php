<?php
    
declare(strict_types=1);

namespace App\Http\Controllers\Backend;
    
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\SecuenciaProcesoRequest;
use App\Models\Admin;
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

class SecuenciaProcesosController extends Controller
{
    public function index(): Renderable
    {
        $this->checkAuthorization(auth()->user(), ['proceso.view']);

        $actores = Admin::get(["name", "id"]);

        return view('backend.pages.secuenciaProcesos.index', [
            'actores' => $actores
        ]);
    }

    public function create(): Renderable
    {
        $this->checkAuthorization(auth()->user(), ['proceso.create']);

        $actores = Admin::get(["name", "id"])->pluck('nombre','id');

        return view('backend.pages.secuenciaProcesos.create', [
            'actores' => $actores
        ]);
    }

    public function store(SecuenciaProcesoRequest $request): RedirectResponse
    {
        $this->checkAuthorization(auth()->user(), ['proceso.create']);
        
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
        if(!$request->tiempo_procesamiento || !isset($request->tiempo_procesamiento) || empty($request->tiempo_procesamiento) || is_null($request->tiempo_procesamiento)){
            $tiempo_procesamiento = "";
        }else{
            $tiempo_procesamiento = $request->tiempo_procesamiento;
        }
        if(!$request->actores || !isset($request->actores) || empty($request->actores) || is_null($request->actores)){
            $actores = "";
        }else{
            $actores = $request->actores;
        }
        if(!$request->configuracion || !isset($request->configuracion) || empty($request->configuracion) || is_null($request->configuracion)){
            $configuracion = "";
        }else{
            $configuracion = $request->configuracion;
        }

        $secuenciaProceso = new SecuenciaProceso();
        $secuenciaProceso->nombre = $nombre;
        $secuenciaProceso->descripcion = $descripcion;
        $secuenciaProceso->estatus = $estatus;
        $secuenciaProceso->tiempo_procesamiento = $tiempo_procesamiento;
        $secuenciaProceso->actores = $actores;
        $secuenciaProceso->configuracion = $configuracion;
        $secuenciaProceso->creado_por = $creado_por;
        $secuenciaProceso->save();

        session()->flash('success', __('Secuencia Proceso ha sido creado satisfactoriamente. '));
        return redirect()->route('admin.secuenciaProcesos.index');
    }

    public function edit(int $id): Renderable
    {
        $this->checkAuthorization(auth()->user(), ['proceso.edit']);

        $secuenciaProceso = SecuenciaProceso::findOrFail($id);
        if($secuenciaProceso->creado_por != Auth::id()){
            abort(403, 'Lo sentimos !! Usted no está autorizado para realizar esta acción.');
        }

        $actores = Admin::get(["name", "id"])->pluck('nombre','id');

        return view('backend.pages.secuenciaProcesos.edit', [
            'secuenciaProceso' => $secuenciaProceso,
            'actores' => $actores
        ]);
    }

    public function update(SecuenciaProcesoRequest $request, int $id): RedirectResponse
    {
        $this->checkAuthorization(auth()->user(), ['proceso.edit']);

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
        if(!$request->tiempo_procesamiento || !isset($request->tiempo_procesamiento) || empty($request->tiempo_procesamiento) || is_null($request->tiempo_procesamiento)){
            $tiempo_procesamiento = "";
        }else{
            $tiempo_procesamiento = $request->tiempo_procesamiento;
        }
        if(!$request->actores || !isset($request->actores) || empty($request->actores) || is_null($request->actores)){
            $actores = "";
        }else{
            $actores = $request->actores;
        }
        if(!$request->configuracion || !isset($request->configuracion) || empty($request->configuracion) || is_null($request->configuracion)){
            $configuracion = "";
        }else{
            $configuracion = $request->configuracion;
        }

        $proceso = Proceso::findOrFail($id);
        $proceso->nombre = $nombre;
        $proceso->descripcion = $descripcion;
        $proceso->estatus = $estatus;
        $proceso->tiempo_procesamiento = $tiempo_procesamiento;
        $proceso->actores = $actores;
        $proceso->configuracion = $configuracion;
        $proceso->save();

        session()->flash('success', 'Secuencia Proceso ha sido actualizado satisfactoriamente.');
        return redirect()->route('admin.secuenciaProcesos.index');
    }

    public function destroy(int $id): JsonResponse
    {
        $this->checkAuthorization(auth()->user(), ['proceso.delete']);

        $secuenciaProceso = SecuenciaProceso::findOrFail($id);
        if($secuenciaProceso->creado_por != Auth::id()){
            abort(403, 'Lo sentimos !! Usted no está autorizado para realizar esta acción.');
        }

        $secuenciaProceso->delete();

        $data['status'] = 200;
        $data['message'] = "Secuencia Proceso ha sido borrado satisfactoriamente.";
  
        return response()->json($data);

    }

    public function getProcesosByFilters(Request $request): JsonResponse
    {
        $this->checkAuthorization(auth()->user(), ['proceso.view']);

        $secuenciaProcesos = SecuenciaProceso::where('id',">",0);

        $filtroNombreSearch = $request->nombre_search;
        $filtroDescripcionSearch = $request->descripcion_search;
        $filtroEstatus = json_decode($request->estatus_search, true);
        $filtroTiempoProcesamiento = $request->tiempo_procesamiento;
        $filtroActores = json_decode($request->actores, true);
        $filtroConfiguracion = json_decode($request->configuracion, true);
        $filtroCreadoPorSearch = json_decode($request->creado_por_search, true);
        
        if(isset($filtroNombreSearch) && !empty($filtroNombreSearch)){
            $secuenciaProcesos = $secuenciaProcesos->where('nombre', 'like', '%'.$filtroNombreSearch.'%');
        }
        if(isset($filtroDescripcionSearch) && !empty($filtroDescripcionSearch)){
            $secuenciaProcesos = $secuenciaProcesos->where('descripcion', 'like', '%'.$filtroDescripcionSearch.'%');
        }
        if(isset($filtroEstatus) && !empty($filtroEstatus)){
            $secuenciaProcesos = $secuenciaProcesos->whereIn('estatus', $filtroEstatus);
        }
        if(isset($filtroTiempoProcesamiento) && !empty($filtroTiempoProcesamiento)){
            $secuenciaProcesos = $secuenciaProcesos->whereIn('tiempo_procesamiento', $filtroTiempoProcesamiento);
        }
        if(isset($filtroActores) && !empty($filtroActores)){
            $secuenciaProcesos = $secuenciaProcesos->whereIn('actores', $filtroActores);
        }
        if(isset($filtroConfiguracion) && !empty($filtroConfiguracion)){
            $secuenciaProcesos = $secuenciaProcesos->whereIn('configuracion', $filtroConfiguracion);
        }
        if(isset($filtroCreadoPorSearch) && !empty($filtroCreadoPorSearch)){
            $secuenciaProcesos = $secuenciaProcesos->whereIn('creado_por', $filtroCreadoPorSearch);
        }
        
        $secuenciaProcesos = $secuenciaProcesos->orderBy('id', 'desc')->get();

        $actores = Admin::all();

        $actores_temp = [];
        foreach($actores as $actor){
            $creadores_temp[$actor->id] = $actor->name;
        }

        $usuario_actual_id = Auth::id();

        foreach($secuenciaProcesos as $secuenciaProceso){
            $secuenciaProceso->creado_por_nombre = array_key_exists($secuenciaProceso->creado_por, $creadores_temp) ? $creadores_temp[$secuenciaProceso->creado_por] : "";
            $secuenciaProceso->esCreadorRegistro = $usuario_actual_id == $secuenciaProceso->creado_por ? true : false;
        }

        $data['secuenciaProcesos'] = $secuenciaProcesos;
        $data['actores'] = $actores;
  
        return response()->json($data);
    }

}