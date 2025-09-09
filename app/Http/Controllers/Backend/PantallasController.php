<?php
    
declare(strict_types=1);

namespace App\Http\Controllers\Backend;
    
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\PantallaRequest;
use App\Models\Admin;
use App\Models\Pantalla;
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

class PantallasController extends Controller
{
    public function index(): Renderable
    {
        $this->checkAuthorization(auth()->user(), ['pantalla.view']);

        $creadores = Admin::get(["name", "id"]);
        $responsable_id = Auth::id();

        return view('backend.pages.pantallas.index', [
            'creadores' => $creadores
        ]);
    }

    public function create(): Renderable
    {
        $this->checkAuthorization(auth()->user(), ['pantalla.create']);

        $creadores = Admin::get(["name", "id"])->pluck('nombre','id');

        return view('backend.pages.pantallas.create', [
            'responsables' => $creadores
        ]);
    }

    public function store(PantallaRequest $request): RedirectResponse
    {
        $this->checkAuthorization(auth()->user(), ['pantalla.create']);
        
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

        $pantalla = new Pantalla();
        $pantalla->nombre = $nombre;
        $pantalla->descripcion = $descripcion;
        $pantalla->estatus = $estatus;
        $pantalla->creado_por = $creado_por;
        $pantalla->save();

        session()->flash('success', __('Pantalla ha sido creada satisfactoriamente. '));
        return redirect()->route('admin.pantallas.index');
    }

    public function edit(int $id): Renderable
    {
        $this->checkAuthorization(auth()->user(), ['pantalla.edit']);

        $pantalla = Pantalla::findOrFail($id);
        if($pantalla->creado_por != Auth::id()){
            abort(403, 'Lo sentimos !! Usted no está autorizado para realizar esta acción.');
        }

        $creadores = Admin::get(["name", "id"])->pluck('nombre','id');

        return view('backend.pages.pantallas.edit', [
            'pantalla' => $pantalla,
            'creadores' => $creadores
        ]);
    }

    public function update(PantallaRequest $request, int $id): RedirectResponse
    {
        $this->checkAuthorization(auth()->user(), ['pantalla.edit']);

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

        $pantalla = Pantalla::findOrFail($id);
        $pantalla->nombre = $nombre;
        $pantalla->descripcion = $descripcion;
        $pantalla->estatus = $estatus;
        $pantalla->save();

        session()->flash('success', 'Pantalla ha sido actualizada satisfactoriamente.'.$request);
        return redirect()->route('admin.pantallas.index');
    }

    public function destroy(int $id): JsonResponse
    {
        $this->checkAuthorization(auth()->user(), ['pantalla.delete']);

        $pantalla = Pantalla::findOrFail($id);
        if($pantalla->creado_por != Auth::id()){
            abort(403, 'Lo sentimos !! Usted no está autorizado para realizar esta acción.');
        }

        $pantalla->delete();

        $data['status'] = 200;
        $data['message'] = "Pantalla ha sido borrado satisfactoriamente.";
  
        return response()->json($data);

    }

    public function getPantallasByFilters(Request $request): JsonResponse
    {
        $this->checkAuthorization(auth()->user(), ['pantalla.view']);

        $pantallas = Pantalla::where('id',">",0);

        $filtroNombreSearch = $request->nombre_search;
        $filtroDescripcionSearch = $request->descripcion_search;
        $filtroEstatus = json_decode($request->estatus_search, true);
        $filtroCreadoPorSearch = json_decode($request->creado_por_search, true);
        
        if(isset($filtroNombreSearch) && !empty($filtroNombreSearch)){
            $pantallas = $pantallas->where('nombre', 'like', '%'.$filtroNombreSearch.'%');
        }
        if(isset($filtroDescripcionSearch) && !empty($filtroDescripcionSearch)){
            $pantallas = $pantallas->where('fecha_registro', 'like', '%'.$filtroDescripcionSearch.'%');
        }
        if(isset($filtroEstatus) && !empty($filtroEstatus)){
            $pantallas = $pantallas->whereIn('estatus', $filtroEstatus);
        }
        if(isset($filtroCreadoPorSearch) && !empty($filtroCreadoPorSearch)){
            $pantallas = $pantallas->whereIn('creado_por', $filtroCreadoPorSearch);
        }
        
        $pantallas = $pantallas->orderBy('id', 'desc')->get();

        $creadores = Admin::all();

        $creadores_temp = [];
        foreach($creadores as $creador){
            $creadores_temp[$creador->id] = $creador->name;
        }

        $usuario_actual_id = Auth::id();

        foreach($pantallas as $pantalla){
            $pantalla->creado_por_nombre = array_key_exists($pantalla->creado_por, $creadores_temp) ? $creadores_temp[$pantalla->creado_por] : "";
            $pantalla->esCreadorRegistro = $usuario_actual_id == $pantalla->creado_por ? true : false;
        }

        $data['pantallas'] = $pantallas;
        $data['creadores'] = $creadores;
  
        return response()->json($data);
    }

}