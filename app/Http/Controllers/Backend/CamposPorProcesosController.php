<?php
    
declare(strict_types=1);

namespace App\Http\Controllers\Backend;
    
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\CamposPorProcesoRequest;
use App\Http\Requests\ProcesoRequest;
use App\Models\Admin;
use App\Models\CamposPorProceso;
use App\Models\Proceso;
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

class CamposPorProcesosController extends Controller
{
    public function index(int $proceso_id): Renderable
    {
        $this->checkAuthorization(auth()->user(), ['proceso.view']);

        $camposPorProcesos = CamposPorProceso::where('proceso_id', $proceso_id)->get();
        $creadores = Admin::get(["name", "id"]);
        $responsable_id = Auth::id();

        return view('backend.pages.procesos.index', [
            'creadores' => $creadores,
            'proceso_id' => $proceso_id,
            'camposPorProcesos' => $camposPorProcesos
        ]);
    }

    public function create(int $proceso_id): Renderable
    {
        $this->checkAuthorization(auth()->user(), ['proceso.create']);

        $camposPorProcesos = CamposPorProceso::where('proceso_id', $proceso_id)->get();
        $creadores = Admin::get(["name", "id"])->pluck('nombre','id');

        return view('backend.pages.camposPorProcesos.create', [
            'responsables' => $creadores,
            'camposPorProcesos' => $camposPorProcesos,
            'proceso_id' => $proceso_id
        ]);
    }

    public function store(CamposPorProcesoRequest $request, int $proceso_id): RedirectResponse
    {
        $this->checkAuthorization(auth()->user(), ['proceso.create']);
        
        $creado_por = Auth::id();

        if(!$request->nombre || !isset($request->nombre) || empty($request->nombre || is_null($request->nombre))){
            $nombre = "";
        }else{
            $nombre = $request->nombre;
        }
        if(!$request->seccion_campo || !isset($request->seccion_campo) || empty($request->seccion_campo) || is_null($request->seccion_campo)){
            $seccion_campo = "";
        }else{
            $seccion_campo = $request->seccion_campo;
        }
        if(!$request->estatus || !isset($request->estatus) || empty($request->estatus) || is_null($request->estatus)){
            $estatus = "";
        }else{
            $estatus = $request->estatus;
        }

        $camposPorProceso = new CamposPorProceso();
        $camposPorProceso->proceso_id = $proceso_id;
        $camposPorProceso->nombre = $nombre;
        $camposPorProceso->seccion_campo = $seccion_campo;
        $camposPorProceso->estatus = $estatus;
        $camposPorProceso->creado_por = $creado_por;
        $camposPorProceso->save();

        session()->flash('success', __('El Campo del Proceso ha sido creado satisfactoriamente. '));
        return redirect()->route('admin.camposPorProcesos.index');
    }

    public function edit(int $proceso_id, int $id): Renderable
    {
        $this->checkAuthorization(auth()->user(), ['proceso.edit']);

        $camposPorProcesos = CamposPorProceso::findOrFail($id);
        if($camposPorProcesos->creado_por != Auth::id() || $camposPorProcesos->proceso_id != $proceso_id){
            abort(403, 'Lo sentimos !! Usted no está autorizado para realizar esta acción.');
        }

        $creadores = Admin::get(["name", "id"])->pluck('name','id');

        return view('backend.pages.procesos.edit', [
            'camposPorProcesos' => $camposPorProcesos,
            'creadores' => $creadores
        ]);
    }

    public function update(CamposPorProcesoRequest $request, int $proceso_id, int $id): RedirectResponse
    {
        $this->checkAuthorization(auth()->user(), ['proceso.edit']);

        if(!$request->nombre || !isset($request->nombre) || empty($request->nombre || is_null($request->nombre))){
            $nombre = "";
        }else{
            $nombre = $request->nombre;
        }
        if(!$request->seccion_campo || !isset($request->seccion_campo) || empty($request->seccion_campo) || is_null($request->seccion_campo)){
            $seccion_campo = "";
        }else{
            $seccion_campo = $request->seccion_campo;
        }
        if(!$request->estatus || !isset($request->estatus) || empty($request->estatus) || is_null($request->estatus)){
            $estatus = "";
        }else{
            $estatus = $request->estatus;
        }

        $camposPorProceso = CamposPorProceso::findOrFail($id);
        $camposPorProceso->nombre = $nombre;
        $camposPorProceso->seccion_campo = $seccion_campo;
        $camposPorProceso->estatus = $estatus;
        $camposPorProceso->save();

        session()->flash('success', 'Campo del Proceso ha sido actualizado satisfactoriamente.');
        return redirect()->route('admin.procesos.index');
    }

    public function destroy(int $proceso_id, int $id): JsonResponse
    {
        $this->checkAuthorization(auth()->user(), ['proceso.delete']);

        $camposPorProceso = CamposPorProceso::findOrFail($id);
        if($camposPorProceso->creado_por != Auth::id( || $camposPorProceso->proceso_id != $proceso_id)){
            abort(403, 'Lo sentimos !! Usted no está autorizado para realizar esta acción.');
        }

        $camposPorProceso->delete();

        $data['status'] = 200;
        $data['message'] = "Campo del Proceso ha sido borrado satisfactoriamente.";
  
        return response()->json($data);

    }

    public function getCamposPorProcesosByFilters(Request $request): JsonResponse
    {
        $this->checkAuthorization(auth()->user(), ['proceso.view']);

        $procesos = CamposPorProceso::where('id',">",0);

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