<?php
    
declare(strict_types=1);

namespace App\Http\Controllers\Backend;
    
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProcesoRequest;
use App\Models\Admin;
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

class ProcesosController extends Controller
{
    public function index(): Renderable
    {
        $this->checkAuthorization(auth()->user(), ['proceso.view']);

        $creadores = Admin::get(["name", "id"]);
        $responsable_id = Auth::id();

        return view('backend.pages.procesos.index', [
            'creadores' => $creadores
        ]);
    }

    public function create(): Renderable
    {
        $this->checkAuthorization(auth()->user(), ['proceso.create']);

        $creadores = Admin::get(["name", "id"])->pluck('nombre','id');

        return view('backend.pages.procesos.create', [
            'responsables' => $creadores
        ]);
    }

    public function store(ProcesoRequest $request): RedirectResponse
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

        $proceso = new Proceso();
        $proceso->nombre = $nombre;
        $proceso->descripcion = $descripcion;
        $proceso->estatus = $estatus;
        $proceso->creado_por = $creado_por;
        $proceso->save();

        session()->flash('success', __('Proceso ha sido creado satisfactoriamente. '));
        return redirect()->route('admin.procesos.index');
    }

    public function edit(int $id): Renderable
    {
        $this->checkAuthorization(auth()->user(), ['proceso.edit']);

        $proceso = Proceso::findOrFail($id);
        if($proceso->creado_por != Auth::id()){
            abort(403, 'Lo sentimos !! Usted no está autorizado para realizar esta acción.');
        }

        $creadores = Admin::get(["name", "id"])->pluck('nombre','id');

        return view('backend.pages.procesos.edit', [
            'proceso' => $proceso,
            'creadores' => $creadores
        ]);
    }

    public function update(ProcesoRequest $request, int $id): RedirectResponse
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

        $proceso = Proceso::findOrFail($id);
        $proceso->nombre = $nombre;
        $proceso->descripcion = $descripcion;
        $proceso->estatus = $estatus;
        $proceso->save();

        session()->flash('success', 'Proceso ha sido actualizado satisfactoriamente.');
        return redirect()->route('admin.procesos.index');
    }

    public function destroy(int $id): JsonResponse
    {
        $this->checkAuthorization(auth()->user(), ['proceso.delete']);

        $proceso = Proceso::findOrFail($id);
        if($proceso->creado_por != Auth::id()){
            abort(403, 'Lo sentimos !! Usted no está autorizado para realizar esta acción.');
        }

        $proceso->delete();

        $data['status'] = 200;
        $data['message'] = "Proceso ha sido borrado satisfactoriamente.";
  
        return response()->json($data);

    }

    public function getProcesosByFilters(Request $request): JsonResponse
    {
        $this->checkAuthorization(auth()->user(), ['proceso.view']);

        $usuario_actual_id = Auth::id();

        $user = Admin::find($usuario_actual_id);
        $roles_names = $user->getRoleNames()->toArray();

        $procesos_temp = Proceso::where('estatus','ACTIVO')->get(['id','nombre']);
        
        $procesosIds = [];
        foreach($procesos_temp as $proceso_temp){
            $searchString = $proceso_temp->nombre;

            $result = array_filter($roles_names, function ($element) use ($searchString) {
                return strpos(strtolower($element), strtolower($searchString)) !== false;
            });
            if($result){
                $procesosIds[] = $proceso_temp->id;
            }
        }

        $procesos = Proceso::whereIn('id',$procesosIds)->where('estatus','ACTIVO');

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
        
        $procesos = $procesos->orderBy('id', 'asc')->get();

        $creadores = Admin::all();

        $creadores_temp = [];
        foreach($creadores as $creador){
            $creadores_temp[$creador->id] = $creador->name;
        }

        foreach($procesos as $proceso){
            $proceso->creado_por_nombre = array_key_exists($proceso->creado_por, $creadores_temp) ? $creadores_temp[$proceso->creado_por] : "";
            $proceso->esCreadorRegistro = $usuario_actual_id == $proceso->creado_por ? true : false;
        }

        $data['procesos'] = $procesos;
        $data['creadores'] = $creadores;
  
        return response()->json($data);
    }

}