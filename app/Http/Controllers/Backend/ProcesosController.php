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
        if($proceso->creador_por != Auth::id()){
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
        $proceso->descripciion = $descripcion;
        $proceso->estatus = $estatus;
        $proceso->razon_social = $request->razon_social;
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

        $procesos = Proceso::where('id',">",0);

        $filtroNombreSearch = $request->nombre_search;
        $filtroDescripcionSearch = $request->descripcion_search;
        $filtroEstatus = json_decode($request->estatus_search, true);
        $filtroCreadoPorSearch = json_decode($request->creado_por_search, true);
        
        if(isset($filtroNombreSearch) && !empty($filtroNombreSearch)){
            $procesos = $procesos->where('nombre', 'like', '%'.$filtroNombreSearch.'%');
        }
        if(isset($filtroDescripcionSearch) && !empty($filtroDescripcionSearch)){
            $procesos = $procesos->where('fecha_registro', 'like', '%'.$filtroDescripcionSearch.'%');
        }
        if(isset($filtroEstatus) && !empty($filtroEstatus)){
            $procesos = $procesos->whereIn('estatus', $filtroEstatus);
        }
        if(isset($filtroCreadoPorSearch) && !empty($filtroCreadoPorSearch)){
            $procesos = $procesos->whereIn('creado_por', $filtroCreadoPorSearch);
        }
        
        $procesos = $procesos->orderBy('id', 'desc')->get();

        $creadores = Admin::all();

        $responsables_temp = [];
        foreach($creadores as $creador){
            $responsables_temp[$creador->id] = $creador->name;
        }

        $responsable_id = Auth::id();

        foreach($procesos as $proceso){
            $proceso->responsable_nombre = array_key_exists($proceso->responsable_id, $responsables_temp) ? $responsables_temp[$proceso->responsable_id] : "";
            $proceso->esCreadorRegistro = $responsable_id == $proceso->responsable_id ? true : false;
        }

        $data['procesos'] = $procesos;
        $data['creadores'] = $creadores;
  
        return response()->json($data);
    }

}