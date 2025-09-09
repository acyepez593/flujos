<?php
    
declare(strict_types=1);

namespace App\Http\Controllers\Backend;
    
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\SeccionPantallaRequest;
use App\Models\Admin;
use App\Models\Pantalla;
use App\Models\SeccionPantalla;
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

class SeccionPantallasController extends Controller
{
    public function index(): Renderable
    {
        $this->checkAuthorization(auth()->user(), ['pantalla.view']);

        $creadores = Admin::get(["name", "id"]);
        $pantallas = Pantalla::get(["nombre", "id"]);

        return view('backend.pages.seccionPantallas.index', [
            'creadores' => $creadores,
            'pantallas' => $pantallas
        ]);
    }

    public function create(): Renderable
    {
        $this->checkAuthorization(auth()->user(), ['pantalla.create']);

        $creadores = Admin::get(["name", "id"])->pluck('nombre','id');
        $pantallas = Pantalla::get(["nombre", "id"])->pluck('nombre','id');

        return view('backend.pages.seccionPantallas.create', [
            'responsables' => $creadores,
            'pantallas' => $pantallas
        ]);
    }

    public function store(SeccionPantallaRequest $request): RedirectResponse
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
        if(!$request->pantalla_id || !isset($request->pantalla_id) || empty($request->pantalla_id) || is_null($request->pantalla_id)){
            $pantalla_id = 0;
        }else{
            $pantalla_id = $request->pantalla_id;
        }

        $seccionPantalla = new SeccionPantalla();
        $seccionPantalla->nombre = $nombre;
        $seccionPantalla->descripcion = $descripcion;
        $seccionPantalla->estatus = $estatus;
        $seccionPantalla->pantalla_id = $pantalla_id;
        $seccionPantalla->creado_por = $creado_por;
        $seccionPantalla->save();

        session()->flash('success', __('Sección Pantalla ha sido creada satisfactoriamente. '));
        return redirect()->route('admin.seccionPantallas.index');
    }

    public function edit(int $id): Renderable
    {
        $this->checkAuthorization(auth()->user(), ['pantalla.edit']);

        $seccionPantalla = SeccionPantalla::findOrFail($id);
        if($seccionPantalla->creado_por != Auth::id()){
            abort(403, 'Lo sentimos !! Usted no está autorizado para realizar esta acción.');
        }

        $creadores = Admin::get(["name", "id"])->pluck('nombre','id');
        $pantallas = Pantalla::get(["nombre", "id"])->pluck('nombre','id');

        return view('backend.pages.seccionPantallas.edit', [
            'pantallas' => $pantallas,
            'seccionPantalla' => $seccionPantalla,
            'creadores' => $creadores
        ]);
    }

    public function update(SeccionPantallaRequest $request, int $id): RedirectResponse
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
        if(!$request->pantalla_id || !isset($request->pantalla_id) || empty($request->pantalla_id) || is_null($request->pantalla_id)){
            $pantalla_id = 0;
        }else{
            $pantalla_id = $request->pantalla_id;
        }

        $seccionPantalla = SeccionPantalla::findOrFail($id);
        $seccionPantalla->nombre = $nombre;
        $seccionPantalla->descripcion = $descripcion;
        $seccionPantalla->estatus = $estatus;
        $seccionPantalla->pantalla_id = $pantalla_id;
        $seccionPantalla->save();

        session()->flash('success', 'Seccion Pantalla ha sido actualizada satisfactoriamente.');
        return redirect()->route('admin.seccionPantallas.index');
    }

    public function destroy(int $id): JsonResponse
    {
        $this->checkAuthorization(auth()->user(), ['pantalla.delete']);

        $seccionPantalla = SeccionPantalla::findOrFail($id);
        if($seccionPantalla->creado_por != Auth::id()){
            abort(403, 'Lo sentimos !! Usted no está autorizado para realizar esta acción.');
        }

        $seccionPantalla->delete();

        $data['status'] = 200;
        $data['message'] = "Sección Pantalla ha sido borrado satisfactoriamente.";
  
        return response()->json($data);

    }

    public function getSeccionPantallasByFilters(Request $request): JsonResponse
    {
        $this->checkAuthorization(auth()->user(), ['pantalla.view']);

        $seccionPantallas = SeccionPantalla::where('id',">",0);

        $filtroNombreSearch = $request->nombre_search;
        $filtroDescripcionSearch = $request->descripcion_search;
        $filtroEstatus = json_decode($request->estatus_search, true);
        $filtroPantallas = json_decode($request->pantalla_id_search, true);
        $filtroCreadoPorSearch = json_decode($request->creado_por_search, true);
        
        if(isset($filtroNombreSearch) && !empty($filtroNombreSearch)){
            $seccionPantallas = $seccionPantallas->where('nombre', 'like', '%'.$filtroNombreSearch.'%');
        }
        if(isset($filtroDescripcionSearch) && !empty($filtroDescripcionSearch)){
            $seccionPantallas = $seccionPantallas->where('fecha_registro', 'like', '%'.$filtroDescripcionSearch.'%');
        }
        if(isset($filtroEstatus) && !empty($filtroEstatus)){
            $seccionPantallas = $seccionPantallas->whereIn('estatus', $filtroEstatus);
        }
        if(isset($filtroPantallas) && !empty($filtroPantallas)){
            $seccionPantallas = $seccionPantallas->whereIn('pantalla_id', $filtroPantallas);
        }
        if(isset($filtroCreadoPorSearch) && !empty($filtroCreadoPorSearch)){
            $seccionPantallas = $seccionPantallas->whereIn('creado_por', $filtroCreadoPorSearch);
        }
        
        $seccionPantallas = $seccionPantallas->orderBy('id', 'desc')->get();

        $creadores = Admin::all();
        $pantallas = Pantalla::all();

        $creadores_temp = [];
        foreach($creadores as $creador){
            $creadores_temp[$creador->id] = $creador->name;
        }

        $pantallas_temp = [];
        foreach($pantallas as $pantalla){
            $pantallas_temp[$pantalla->id] = $pantalla->nombre;
        }

        $usuario_actual_id = Auth::id();

        foreach($seccionPantallas as $seccionPantalla){
            $seccionPantalla->creado_por_nombre = array_key_exists($seccionPantalla->creado_por, $creadores_temp) ? $creadores_temp[$seccionPantalla->creado_por] : "";
            $seccionPantalla->pantalla_nombre = array_key_exists($seccionPantalla->pantalla_id, $pantallas_temp) ? $pantallas_temp[$seccionPantalla->pantalla_id] : "";
            $seccionPantalla->esCreadorRegistro = $usuario_actual_id == $seccionPantalla->creado_por ? true : false;
        }

        $data['seccionPantallas'] = $seccionPantallas;
        $data['creadores'] = $creadores;
  
        return response()->json($data);
    }

}