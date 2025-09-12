<?php
    
declare(strict_types=1);

namespace App\Http\Controllers\Backend;
    
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\CamposPorSeccionRequest;
use App\Http\Requests\SeccionPantallaRequest;
use App\Models\Admin;
use App\Models\CamposPorSeccion;
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

class CamposPorSeccionesController extends Controller
{
    public function index(): Renderable
    {
        $this->checkAuthorization(auth()->user(), ['pantalla.view']);

        $creadores = Admin::get(["name", "id"]);
        $pantallas = Pantalla::get(["nombre", "id"]);
        $seccionPantallas = SeccionPantalla::get(["nombre", "id"]);

        return view('backend.pages.seccionPantallas.index', [
            'creadores' => $creadores,
            'pantallas' => $pantallas,
            'seccionPantallas' => $seccionPantallas,
        ]);
    }

    public function create(): Renderable
    {
        $this->checkAuthorization(auth()->user(), ['pantalla.create']);

        $creadores = Admin::get(["name", "id"])->pluck('nombre','id');
        $pantallas = Pantalla::get(["nombre", "id"])->pluck('nombre','id');
        $seccionPantallas = SeccionPantalla::get(["nombre", "id"])->pluck('nombre','id');

        return view('backend.pages.seccionPantallas.create', [
            'responsables' => $creadores,
            'pantallas' => $pantallas,
            'seccionPantallas' => $seccionPantallas
        ]);
    }

    public function store(CamposPorSeccionRequest $request): RedirectResponse
    {
        $this->checkAuthorization(auth()->user(), ['pantalla.create']);
        
        $creado_por = Auth::id();

        if(!$request->seccion_pantalla_id || !isset($request->seccion_pantalla_id) || empty($request->seccion_pantalla_id) || is_null($request->seccion_pantalla_id)){
            $seccion_pantalla_id = 0;
        }else{
            $seccion_pantalla_id = $request->seccion_pantalla_id;
        }
        if(!$request->nombre || !isset($request->nombre) || empty($request->nombre || is_null($request->nombre))){
            $nombre = "";
        }else{
            $nombre = $request->nombre;
        }
        if(!$request->tipo || !isset($request->tipo) || empty($request->tipo) || is_null($request->tipo)){
            $tipo = "";
        }else{
            $tipo = $request->tipo;
        }
        if(!$request->configuracion || !isset($request->configuracion) || empty($request->configuracion) || is_null($request->configuracion)){
            $configuracion = "";
        }else{
            $configuracion = $request->configuracion;
        }
        
        $campoPorSeccion = new CamposPorSeccion();
        $campoPorSeccion->seccion_pantalla_id = $seccion_pantalla_id;
        $campoPorSeccion->nombre = $nombre;
        $campoPorSeccion->tipo = $tipo;
        $campoPorSeccion->configuracion = $configuracion;
        $campoPorSeccion->creado_por = $creado_por;
        $campoPorSeccion->save();

        session()->flash('success', __('Campo de la Sección ha sido creado satisfactoriamente. '));
        return redirect()->route('admin.seccionPantallas.index');
    }

    public function edit(int $id): Renderable
    {
        $this->checkAuthorization(auth()->user(), ['pantalla.edit']);

        $campoPorSeccion = CamposPorSeccion::findOrFail($id);
        if($campoPorSeccion->creado_por != Auth::id()){
            abort(403, 'Lo sentimos !! Usted no está autorizado para realizar esta acción.');
        }

        $creadores = Admin::get(["name", "id"])->pluck('nombre','id');
        $pantallas = Pantalla::get(["nombre", "id"])->pluck('nombre','id');
        $seccionPantallas = SeccionPantalla::get(["nombre", "id"])->pluck('nombre','id');

        return view('backend.pages.seccionPantallas.edit', [
            'campoPorSeccion' => $campoPorSeccion,
            'pantallas' => $pantallas,
            'seccionPantallas' => $seccionPantallas,
            'creadores' => $creadores
        ]);
    }

    public function update(SeccionPantallaRequest $request, int $id): RedirectResponse
    {
        $this->checkAuthorization(auth()->user(), ['pantalla.edit']);

        if(!$request->seccion_pantalla_id || !isset($request->seccion_pantalla_id) || empty($request->seccion_pantalla_id) || is_null($request->seccion_pantalla_id)){
            $seccion_pantalla_id = 0;
        }else{
            $seccion_pantalla_id = $request->seccion_pantalla_id;
        }
        if(!$request->nombre || !isset($request->nombre) || empty($request->nombre || is_null($request->nombre))){
            $nombre = "";
        }else{
            $nombre = $request->nombre;
        }
        if(!$request->tipo || !isset($request->tipo) || empty($request->tipo) || is_null($request->tipo)){
            $tipo = "";
        }else{
            $tipo = $request->tipo;
        }
        if(!$request->configuracion || !isset($request->configuracion) || empty($request->configuracion) || is_null($request->configuracion)){
            $configuracion = "";
        }else{
            $configuracion = $request->configuracion;
        }

        $campoPorSeccion = CamposPorSeccion::findOrFail($id);
        $campoPorSeccion->seccion_pantalla_id = $seccion_pantalla_id;
        $campoPorSeccion->nombre = $nombre;
        $campoPorSeccion->tipo = $tipo;
        $campoPorSeccion->configuracion = $configuracion;
        $campoPorSeccion->save();

        session()->flash('success', 'Campo de la Seccion ha sido actualizado satisfactoriamente.');
        return redirect()->route('admin.seccionPantallas.index');
    }

    public function destroy(int $id): JsonResponse
    {
        $this->checkAuthorization(auth()->user(), ['pantalla.delete']);

        $campoPorSeccion = CamposPorSeccion::findOrFail($id);
        if($campoPorSeccion->creado_por != Auth::id()){
            abort(403, 'Lo sentimos !! Usted no está autorizado para realizar esta acción.');
        }

        $campoPorSeccion->delete();

        $data['status'] = 200;
        $data['message'] = "Sección Pantalla ha sido borrado satisfactoriamente.";
  
        return response()->json($data);

    }

    public function getSeccionPantallasByFilters(Request $request): JsonResponse
    {
        $this->checkAuthorization(auth()->user(), ['pantalla.view']);

        $camposPorSecciones = CamposPorSeccion::where('id',">",0);

        $filtroPantallas = json_decode($request->pantalla_id_search, true);
        $filtroNombreSearch = $request->nombre_search;
        $filtroDescripcionSearch = $request->descripcion_search;
        $filtroEstatus = json_decode($request->estatus_search, true);
        
        $filtroCreadoPorSearch = json_decode($request->creado_por_search, true);
        
        if(isset($filtroNombreSearch) && !empty($filtroNombreSearch)){
            $camposPorSecciones = $camposPorSecciones->where('nombre', 'like', '%'.$filtroNombreSearch.'%');
        }
        if(isset($filtroDescripcionSearch) && !empty($filtroDescripcionSearch)){
            $camposPorSecciones = $camposPorSecciones->where('fecha_registro', 'like', '%'.$filtroDescripcionSearch.'%');
        }
        if(isset($filtroEstatus) && !empty($filtroEstatus)){
            $camposPorSecciones = $camposPorSecciones->whereIn('estatus', $filtroEstatus);
        }
        if(isset($filtroPantallas) && !empty($filtroPantallas)){
            $camposPorSecciones = $camposPorSecciones->whereIn('pantalla_id', $filtroPantallas);
        }
        if(isset($filtroCreadoPorSearch) && !empty($filtroCreadoPorSearch)){
            $camposPorSecciones = $camposPorSecciones->whereIn('creado_por', $filtroCreadoPorSearch);
        }
        
        $camposPorSecciones = $camposPorSecciones->orderBy('id', 'desc')->get();

        $creadores = Admin::all();
        $seccionPantallas = SeccionPantalla::all();

        $creadores_temp = [];
        foreach($creadores as $creador){
            $creadores_temp[$creador->id] = $creador->name;
        }

        $seccion_pantallas_temp = [];
        foreach($seccionPantallas as $seccionPantalla){
            $seccion_pantallas_temp[$seccionPantalla->id] = $seccionPantalla->nombre;
        }

        $usuario_actual_id = Auth::id();

        foreach($camposPorSecciones as $camposPorSeccion){
            $camposPorSeccion->creado_por_nombre = array_key_exists($camposPorSeccion->creado_por, $creadores_temp) ? $creadores_temp[$camposPorSeccion->creado_por] : "";
            $camposPorSeccion->seccion_pantalla_nombre = array_key_exists($camposPorSeccion->pantalla_id, $seccion_pantallas_temp) ? $seccion_pantallas_temp[$camposPorSeccion->seccion_pantalla_id] : "";
            $camposPorSeccion->esCreadorRegistro = $usuario_actual_id == $camposPorSeccion->creado_por ? true : false;
        }

        $data['camposPorSecciones'] = $camposPorSecciones;
        $data['creadores'] = $creadores;
  
        return response()->json($data);
    }

}