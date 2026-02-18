<?php
    
declare(strict_types=1);

namespace App\Http\Controllers\Backend;
    
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\NormativaDiscapacidadRequest;
use App\Http\Requests\TipoCatalogoRequest;
use App\Models\Admin;
use App\Models\NormativaDiscapacidad;
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

class NormativaDiscapacidadesController extends Controller
{
    public function index(): Renderable
    {
        $this->checkAuthorization(auth()->user(), ['normativaDiscapacidad.view']);

        $creadores = Admin::get(["name", "id"]);

        return view('backend.pages.normativaDiscapacidades.index', [
            'creadores' => $creadores
        ]);
    }

    public function create(): Renderable
    {
        $this->checkAuthorization(auth()->user(), ['normativaDiscapacidad.create']);

        $creadores = Admin::get(["name", "id"])->pluck('nombre','id');

        return view('backend.pages.normativaDiscapacidades.create', [
            'creadores' => $creadores
        ]);
    }

    public function store(NormativaDiscapacidadRequest $request): RedirectResponse
    {
        $this->checkAuthorization(auth()->user(), ['normativaDiscapacidad.create']);
        
        $creado_por = Auth::id();

        if(!$request->nombre || !isset($request->nombre) || empty($request->nombre || is_null($request->nombre))){
            $nombre = "";
        }else{
            $nombre = $request->nombre;
        }
        if(!$request->inicio_vigencia || !isset($request->inicio_vigencia) || empty($request->inicio_vigencia || is_null($request->inicio_vigencia))){
            $inicio_vigencia = "";
        }else{
            $inicio_vigencia = $request->inicio_vigencia;
        }
        if(!$request->estatus || !isset($request->estatus) || empty($request->estatus) || is_null($request->estatus)){
            $estatus = "";
        }else{
            $estatus = $request->estatus;
        }

        $normativaDiscapacidad = new NormativaDiscapacidad();
        $normativaDiscapacidad->nombre = $nombre;
        $normativaDiscapacidad->inicio_vigencia = $inicio_vigencia;
        $normativaDiscapacidad->estatus = $estatus;
        $normativaDiscapacidad->creado_por = $creado_por;
        $normativaDiscapacidad->save();

        session()->flash('success', __('La normativa de discapacidad ha sido creado satisfactoriamente. '));
        return redirect()->route('admin.normativaDiscapacidades.index');
    }

    public function edit(int $id): Renderable
    {
        $this->checkAuthorization(auth()->user(), ['normativaDiscapacidad.edit']);

        $normativaDiscapacidad = NormativaDiscapacidad::findOrFail($id);
        if($normativaDiscapacidad->creado_por != Auth::id()){
            abort(403, 'Lo sentimos !! Usted no está autorizado para realizar esta acción.');
        }

        $creadores = Admin::get(["name", "id"])->pluck('nombre','id');

        return view('backend.pages.normativaDiscapacidades.edit', [
            'normativaDiscapacidad' => $normativaDiscapacidad,
            'creadores' => $creadores
        ]);
    }

    public function update(NormativaDiscapacidadRequest $request, int $id): RedirectResponse
    {
        $this->checkAuthorization(auth()->user(), ['normativaDiscapacidad.edit']);

        if(!$request->nombre || !isset($request->nombre) || empty($request->nombre || is_null($request->nombre))){
            $nombre = "";
        }else{
            $nombre = $request->nombre;
        }
        if(!$request->inicio_vigencia || !isset($request->inicio_vigencia) || empty($request->inicio_vigencia || is_null($request->inicio_vigencia))){
            $inicio_vigencia = "";
        }else{
            $inicio_vigencia = $request->inicio_vigencia;
        }
        if(!$request->estatus || !isset($request->estatus) || empty($request->estatus) || is_null($request->estatus)){
            $estatus = "";
        }else{
            $estatus = $request->estatus;
        }

        $normativaDiscapacidad = NormativaDiscapacidad::findOrFail($id);
        $normativaDiscapacidad->nombre = $nombre;
        $normativaDiscapacidad->inicio_vigencia = $inicio_vigencia;
        $normativaDiscapacidad->estatus = $estatus;
        $normativaDiscapacidad->save();

        session()->flash('success', 'La normativa de discapacidad ha sido actualizado satisfactoriamente.');
        return redirect()->route('admin.normativaDiscapacidades.index');
    }

    public function destroy(int $id): JsonResponse
    {
        $this->checkAuthorization(auth()->user(), ['normativaDiscapacidad.delete']);

        $normativaDiscapacidad = NormativaDiscapacidad::findOrFail($id);
        if($normativaDiscapacidad->creado_por != Auth::id()){
            abort(403, 'Lo sentimos !! Usted no está autorizado para realizar esta acción.');
        }

        $normativaDiscapacidad->delete();

        $data['status'] = 200;
        $data['message'] = "La normativa de discapacidad ha sido borrado satisfactoriamente.";
  
        return response()->json($data);

    }

    public function getNormativaDiscapacidadesByFilters(Request $request): JsonResponse
    {
        $this->checkAuthorization(auth()->user(), ['normativaDiscapacidad.view']);

        $normativaDiscapacidades = NormativaDiscapacidad::where('estatus','ACTIVO');

        $filtroNombreSearch = $request->nombre_search;
        $filtroInicioVigenciaSearch = $request->inicio_vigencia_search;
        $filtroEstatusSearch = json_decode($request->estatus_search, true);
        $filtroCreadoPorSearch = json_decode($request->creado_por_search, true);
        
        if(isset($filtroNombreSearch) && !empty($filtroNombreSearch)){
            $normativaDiscapacidades = $normativaDiscapacidades->where('nombre', 'like', '%'.$filtroNombreSearch.'%');
        }
        if(isset($filtroInicioVigenciaSearch) && !empty($filtroInicioVigenciaSearch)){
            $fecha_inicio = Carbon::createFromFormat('Y-m-d', $filtroInicioVigenciaSearch)->startOfDay();
            $normativaDiscapacidades = $normativaDiscapacidades->where('inicio_vigencia', $fecha_inicio);
        }
        if(isset($filtroEstatusSearch) && !empty($filtroEstatusSearch)){
            $normativaDiscapacidades = $normativaDiscapacidades->whereIn('estatus', $filtroEstatusSearch);
        }
        if(isset($filtroCreadoPorSearch) && !empty($filtroCreadoPorSearch)){
            $normativaDiscapacidades = $normativaDiscapacidades->whereIn('creado_por', $filtroCreadoPorSearch);
        }
        
        $normativaDiscapacidades = $normativaDiscapacidades->orderBy('id', 'asc')->get();

        $creadores = Admin::all();

        $creadores_temp = [];
        foreach($creadores as $creador){
            $creadores_temp[$creador->id] = $creador->name;
        }

        $usuario_actual_id = Auth::id();

        foreach($normativaDiscapacidades as $normativaDiscapacidad){
            $normativaDiscapacidad->creado_por_nombre = array_key_exists($normativaDiscapacidad->creado_por, $creadores_temp) ? $creadores_temp[$normativaDiscapacidad->creado_por] : "";
            $normativaDiscapacidad->esCreadorRegistro = $usuario_actual_id == $normativaDiscapacidad->creado_por ? true : false;
        }

        $data['normativaDiscapacidades'] = $normativaDiscapacidades;
        $data['creadores'] = $creadores;
  
        return response()->json($data);
    }

}