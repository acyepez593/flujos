<?php
    
declare(strict_types=1);

namespace App\Http\Controllers\Backend;
    
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\SecuenciaProcesoRequest;
use App\Models\Admin;
use App\Models\CamposPorProceso;
use App\Models\Proceso;
use App\Models\SecuenciaProceso;
use App\Models\TipoCatalogo;
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
    public function index(int $proceso_id): Renderable
    {
        $this->checkAuthorization(auth()->user(), ['proceso.view']);

        $secuenciaProceso = SecuenciaProceso::where('proceso_id', $proceso_id)->get();

        $creadores = Admin::get(["name", "id"]);
        $actores = Admin::get(["name", "id"]);

        return view('backend.pages.secuenciaProcesos.index', [
            'creadores' => $creadores,
            'actores' => $actores,
            'proceso_id' => $proceso_id,
            'secuenciaProceso' => $secuenciaProceso
        ]);
    }

    public function create(int $proceso_id): Renderable
    {
        $this->checkAuthorization(auth()->user(), ['proceso.create']);

        $secuenciaProceso = SecuenciaProceso::where('proceso_id', $proceso_id)->get();
        $listaActividades = SecuenciaProceso::where('proceso_id', $proceso_id)->get(["nombre", "id"])->pluck('nombre','id');
        $campos = CamposPorProceso::where('proceso_id', $proceso_id)->get(["nombre", "id"])->pluck('nombre','id');
        $listaCampos = CamposPorProceso::where('proceso_id', $proceso_id)->get(["id", "tipo_campo", "nombre", "variable", "seccion_campo"]);
        $tiposCatalogos = TipoCatalogo::get(["nombre", "id"])->pluck('nombre','id');
        $actores = Admin::get(["name", "id"])->pluck('name','id');

        return view('backend.pages.secuenciaProcesos.create', [
            'actores' => $actores,
            'proceso_id' => $proceso_id,
            'secuenciaProceso' => $secuenciaProceso,
            'listaActividades' => $listaActividades,
            'listaCampos' => $listaCampos,
            'tiposCatalogos' => $tiposCatalogos,
            'campos' => $campos
        ]);
    }

    public function store(SecuenciaProcesoRequest $request, int $proceso_id): RedirectResponse
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
        if(!$request->configuracion_campos || !isset($request->configuracion_campos) || empty($request->configuracion_campos) || is_null($request->configuracion_campos)){
            $configuracion_campos = "";
        }else{
            $configuracion_campos = $request->configuracion_campos;
        }

        $secuenciaProceso = new SecuenciaProceso();
        $secuenciaProceso->proceso_id = $proceso_id;
        $secuenciaProceso->nombre = $nombre;
        $secuenciaProceso->descripcion = $descripcion;
        $secuenciaProceso->estatus = $estatus;
        $secuenciaProceso->tiempo_procesamiento = $tiempo_procesamiento;
        $secuenciaProceso->actores = $actores;
        $secuenciaProceso->configuracion = $configuracion;
        $secuenciaProceso->configuracion_campos = $configuracion_campos;
        $secuenciaProceso->creado_por = $creado_por;
        $secuenciaProceso->save();

        session()->flash('success', __('Secuencia Proceso ha sido creado satisfactoriamente. '));
        return redirect('admin/secuenciaProcesos/'.$proceso_id);
    }

    public function edit(int $proceso_id, int $id): Renderable
    {
        $this->checkAuthorization(auth()->user(), ['proceso.edit']);

        $secuenciaProceso = SecuenciaProceso::findOrFail($id);
        if($secuenciaProceso->creado_por != Auth::id() || $secuenciaProceso->proceso_id != $proceso_id){
            abort(403, 'Lo sentimos !! Usted no está autorizado para realizar esta acción.');
        }

        $listaActividades = SecuenciaProceso::where('proceso_id', $proceso_id)->where('id','<>',$id)->get(["nombre", "id"])->pluck('nombre','id');
        $campos = CamposPorProceso::where('proceso_id', $proceso_id)->get(["nombre", "id"])->pluck('nombre','id');
        $listaCampos = $secuenciaProceso->configuracion_campos;
        $tiposCatalogos = TipoCatalogo::get(["nombre", "id"])->pluck('nombre','id');
        $actores = Admin::get(["name", "id"])->pluck('name','id');

        return view('backend.pages.secuenciaProcesos.edit', [
            'actores' => $actores,
            'secuenciaProceso' => $secuenciaProceso,
            'proceso_id' => $proceso_id,
            'listaActividades' => $listaActividades,
            'listaCampos' => $listaCampos,
            'tiposCatalogos' => $tiposCatalogos,
            'campos' => $campos
        ]);
    }

    public function update(SecuenciaProcesoRequest $request, int $proceso_id, int $id): RedirectResponse
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
        if(!$request->configuracion_campos || !isset($request->configuracion_campos) || empty($request->configuracion_campos) || is_null($request->configuracion_campos)){
            $configuracion_campos = "";
        }else{
            $configuracion_campos = $request->configuracion_campos;
        }

        $secuenciaProceso = SecuenciaProceso::findOrFail($id);
        $secuenciaProceso->nombre = $nombre;
        $secuenciaProceso->descripcion = $descripcion;
        $secuenciaProceso->estatus = $estatus;
        $secuenciaProceso->tiempo_procesamiento = $tiempo_procesamiento;
        $secuenciaProceso->actores = $actores;
        $secuenciaProceso->configuracion = $configuracion;
        $secuenciaProceso->configuracion_campos = $configuracion_campos;
        $secuenciaProceso->save();

        session()->flash('success', 'Secuencia Proceso ha sido actualizado satisfactoriamente.');
        return redirect('admin/secuenciaProcesos/'.$proceso_id);
    }

    public function destroy(int $proceso_id, int $id): JsonResponse
    {
        $this->checkAuthorization(auth()->user(), ['proceso.delete']);

        $secuenciaProceso = SecuenciaProceso::findOrFail($id);
        if($secuenciaProceso->creado_por != Auth::id() || $secuenciaProceso->proceso_id != $proceso_id){
            abort(403, 'Lo sentimos !! Usted no está autorizado para realizar esta acción.');
        }

        $secuenciaProceso->delete();

        $data['status'] = 200;
        $data['message'] = "Secuencia Proceso ha sido borrado satisfactoriamente.";
  
        return response()->json($data);

    }

    public function getSecuenciaProcesosByFilters(Request $request, int $proceso_id): JsonResponse
    {
        $this->checkAuthorization(auth()->user(), ['proceso.view']);

        $secuenciaProcesos = SecuenciaProceso::where('proceso_id',$proceso_id);

        $filtroNombreSearch = $request->nombre_search;
        $filtroDescripcionSearch = $request->descripcion_search;
        $filtroEstatus = json_decode($request->estatus_search, true);
        $filtroTiempoProcesamiento = $request->tiempo_procesamiento_search;
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
            $secuenciaProcesos = $secuenciaProcesos->where('tiempo_procesamiento', $filtroTiempoProcesamiento);
        }
        if(isset($request->actores) && !empty($request->actores)){
            $filtroActores = json_decode($request->actores, true);
            $secuenciaProcesos = $secuenciaProcesos->whereIn('actores', $filtroActores);
        }
        if(isset($request->configuracion) && !empty($request->configuracion)){
            $filtroConfiguracion = $request->configuracion;
            $secuenciaProcesos = $secuenciaProcesos->where('configuracion', 'like', '%'.$filtroConfiguracion.'%');
        }
        if(isset($request->configuracion_campos) && !empty($request->configuracion_campos)){
            $filtroConfiguracionCampos = $request->configuracion_campos;
            $secuenciaProcesos = $secuenciaProcesos->where('configuracion_campos', 'like', '%'.$filtroConfiguracionCampos.'%');
        }
        if(isset($filtroCreadoPorSearch) && !empty($filtroCreadoPorSearch)){
            $secuenciaProcesos = $secuenciaProcesos->whereIn('creado_por', $filtroCreadoPorSearch);
        }
        
        $secuenciaProcesos = $secuenciaProcesos->orderBy('id', 'asc')->get();

        $actores = Admin::all();

        $actores_temp = [];
        foreach($actores as $actor){
            $actores_temp[$actor->id] = $actor->name;
        }

        $usuario_actual_id = Auth::id();

        foreach($secuenciaProcesos as $secuenciaProceso){
            $secuenciaProceso->creado_por_nombre = array_key_exists($secuenciaProceso->creado_por, $actores_temp) ? $actores_temp[$secuenciaProceso->creado_por] : "";
            $secuenciaProceso->actores_nombre = array_key_exists($secuenciaProceso->actores, $actores_temp) ? $actores_temp[$secuenciaProceso->actores] : "";
            $secuenciaProceso->esCreadorRegistro = $usuario_actual_id == $secuenciaProceso->creado_por ? true : false;
        }

        $data['secuenciaProcesos'] = $secuenciaProcesos;
        $data['actores'] = $actores;
  
        return response()->json($data);
    }

}