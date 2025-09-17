<?php
    
declare(strict_types=1);

namespace App\Http\Controllers\Backend;
    
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\CamposPorProcesoRequest;
use App\Models\Admin;
use App\Models\CamposPorProceso;
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

        return view('backend.pages.camposPorProcesos.index', [
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
        return redirect('admin/camposPorProcesos/'.$proceso_id);
    }

    public function edit(int $proceso_id, int $id): Renderable
    {
        $this->checkAuthorization(auth()->user(), ['proceso.edit']);

        $campoPorSeccion = CamposPorProceso::findOrFail($id);
        if($campoPorSeccion->creado_por != Auth::id() || $campoPorSeccion->proceso_id != $proceso_id){
            abort(403, 'Lo sentimos !! Usted no está autorizado para realizar esta acción.');
        }

        $creadores = Admin::get(["name", "id"])->pluck('name','id');

        return view('backend.pages.camposPorProcesos.edit', [
            'campoPorSeccion' => $campoPorSeccion,
            'proceso_id' => $proceso_id,
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
        return redirect('admin/camposPorProcesos/'.$proceso_id);
    }

    public function destroy(int $proceso_id, int $id): JsonResponse
    {
        $this->checkAuthorization(auth()->user(), ['proceso.delete']);

        $camposPorProceso = CamposPorProceso::findOrFail($id);
        if($camposPorProceso->creado_por != Auth::id() || $camposPorProceso->proceso_id != $proceso_id){
            abort(403, 'Lo sentimos !! Usted no está autorizado para realizar esta acción.');
        }

        $camposPorProceso->delete();

        $data['status'] = 200;
        $data['message'] = "Campo del Proceso ha sido borrado satisfactoriamente.";
  
        return response()->json($data);

    }

    public function getCamposPorProcesosByFilters(Request $request, int $proceso_id): JsonResponse
    {
        $this->checkAuthorization(auth()->user(), ['proceso.view']);

        $camposPorProcesos = CamposPorProceso::where('proceso_id', $proceso_id);

        $filtroNombreSearch = $request->nombre_search;
        $filtroSeccionCampoSearch = json_decode($request->seccion_campo_search, true);
        $filtroEstatus = json_decode($request->estatus_search, true);
        $filtroCreadoPorSearch = json_decode($request->creado_por_search, true);
        
        if(isset($filtroNombreSearch) && !empty($filtroNombreSearch)){
            $camposPorProcesos = $camposPorProcesos->where('nombre', 'like', '%'.$filtroNombreSearch.'%');
        }
        if(isset($filtroSeccionCampoSearch) && !empty($filtroSeccionCampoSearch)){
            $camposPorProcesos = $camposPorProcesos->whereIn('seccion_campo', $filtroSeccionCampoSearch);
        }
        if(isset($filtroEstatus) && !empty($filtroEstatus)){
            $camposPorProcesos = $camposPorProcesos->whereIn('estatus', $filtroEstatus);
        }
        if(isset($filtroCreadoPorSearch) && !empty($filtroCreadoPorSearch)){
            $camposPorProcesos = $camposPorProcesos->whereIn('creado_por', $filtroCreadoPorSearch);
        }
        
        $camposPorProcesos = $camposPorProcesos->orderBy('id', 'desc')->get();

        $creadores = Admin::all();

        $creadores_temp = [];
        foreach($creadores as $creador){
            $creadores_temp[$creador->id] = $creador->name;
        }

        $usuario_actual_id = Auth::id();

        foreach($camposPorProcesos as $camposPorProceso){
            $camposPorProceso->creado_por_nombre = array_key_exists($camposPorProceso->creado_por, $creadores_temp) ? $creadores_temp[$camposPorProceso->creado_por] : "";
            $camposPorProceso->esCreadorRegistro = $usuario_actual_id == $camposPorProceso->creado_por ? true : false;
        }

        $data['camposPorProcesos'] = $camposPorProcesos;
        $data['creadores'] = $creadores;
  
        return response()->json($data);
    }

}