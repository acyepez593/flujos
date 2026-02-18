<?php
    
declare(strict_types=1);

namespace App\Http\Controllers\Backend;
    
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\RangoDiscapacidadRequest;
use App\Http\Requests\TramiteRequest;
use App\Mail\Notification;
use App\Models\Admin;
use App\Models\Catalogo;
use App\Models\NormativaDiscapacidad;
use App\Models\Proceso;
use App\Models\RangoDiscapacidad;
use App\Models\Tramite;
use App\Models\SecuenciaProceso;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Throwable;

class RangoDiscapacidadesController extends Controller
{
    public function index(): Renderable
    {
        $this->checkAuthorization(auth()->user(), ['rangoDiscapacidad.view']);

        $creadores = Admin::get(["name", "id"]);
        $normativas = NormativaDiscapacidad::where('estatus','ACTIVO')->get(['nombre', 'id']);

        return view('backend.pages.rangoDiscapacidades.index', [
            'creadores' => $creadores,
            'normativas' => $normativas
        ]);
    }

    public function create(): Renderable
    {
        $this->checkAuthorization(auth()->user(), ['rangoDiscapacidad.create']);

        $creadores = Admin::get(["name", "id"])->pluck('nombre','id');
        $normativas = NormativaDiscapacidad::where('estatus','ACTIVO')->get(['nombre', 'id'])->pluck('nombre','id');

        return view('backend.pages.rangoDiscapacidades.create', [
            'creadores' => $creadores,
            'normativas' => $normativas
        ]);
    }

    public function store(RangoDiscapacidadRequest $request): RedirectResponse
    {
        $this->checkAuthorization(auth()->user(), ['rangoDiscapacidad.create']);
        
        $creado_por = Auth::id();

        if(!$request->normativa_id || !isset($request->normativa_id) || empty($request->normativa_id || is_null($request->normativa_id))){
            $normativa_id = "";
        }else{
            $normativa_id = $request->normativa_id;
        }
        if(!$request->grado_discapacidad || !isset($request->grado_discapacidad) || empty($request->grado_discapacidad || is_null($request->grado_discapacidad))){
            $grado_discapacidad = "";
        }else{
            $grado_discapacidad = $request->grado_discapacidad;
        }
        if(!$request->rango_desde || !isset($request->rango_desde) || empty($request->rango_desde || is_null($request->rango_desde))){
            $rango_desde = "";
        }else{
            $rango_desde = $request->rango_desde;
        }
        if(!$request->rango_hasta || !isset($request->rango_hasta) || empty($request->rango_hasta || is_null($request->rango_hasta))){
            $rango_hasta = "";
        }else{
            $rango_hasta = $request->rango_hasta;
        }
        if(!$request->valor_cobertura || !isset($request->valor_cobertura) || empty($request->valor_cobertura || is_null($request->valor_cobertura))){
            $valor_cobertura = "";
        }else{
            $valor_cobertura = $request->valor_cobertura;
        }
        if(!$request->estatus || !isset($request->estatus) || empty($request->estatus || is_null($request->estatus))){
            $estatus = "";
        }else{
            $estatus = $request->estatus;
        }

        $rangoDiscapacidad = new RangoDiscapacidad();
        $rangoDiscapacidad->normativa_id = $normativa_id;
        $rangoDiscapacidad->grado_discapacidad = $grado_discapacidad;
        $rangoDiscapacidad->rango_desde = $rango_desde;
        $rangoDiscapacidad->rango_hasta = $rango_hasta;
        $rangoDiscapacidad->valor_cobertura = $valor_cobertura;
        $rangoDiscapacidad->estatus = $estatus;
        $rangoDiscapacidad->creado_por = $creado_por;
        $rangoDiscapacidad->save();

        session()->flash('success', __('El Rango de discapacidad ha sido creado satisfactoriamente. '));
        return redirect()->route('admin.rangoDiscapacidades.index'); 
    }

    public function edit(int $id): Renderable
    {
        $this->checkAuthorization(auth()->user(), ['rangoDiscapacidad.edit']);

        $rangoDiscapacidad = RangoDiscapacidad::findOrFail($id);
        if($rangoDiscapacidad->creado_por != Auth::id()){
            abort(403, 'Lo sentimos !! Usted no está autorizado para realizar esta acción.');
        }
        $creadores = Admin::get(["name", "id"])->pluck('nombre','id');
        $normativas = NormativaDiscapacidad::where('estatus','ACTIVO')->get(['nombre', 'id']);

        return view('backend.pages.rangoDiscapacidades.edit', [
            'creadores' => $creadores,
            'normativas' => $normativas,
            'rangoDiscapacidad' => $rangoDiscapacidad
        ]);
    }

    public function update(RangoDiscapacidadRequest $request, int $id): RedirectResponse
    {
        $this->checkAuthorization(auth()->user(), ['rangoDiscapacidad.edit']);

        if(!$request->normativa_id || !isset($request->normativa_id) || empty($request->normativa_id || is_null($request->normativa_id))){
            $normativa_id = "";
        }else{
            $normativa_id = $request->normativa_id;
        }
        if(!$request->grado_discapacidad || !isset($request->grado_discapacidad) || empty($request->grado_discapacidad || is_null($request->grado_discapacidad))){
            $grado_discapacidad = "";
        }else{
            $grado_discapacidad = $request->grado_discapacidad;
        }
        if(!$request->rango_desde || !isset($request->rango_desde) || empty($request->rango_desde || is_null($request->rango_desde))){
            $rango_desde = "";
        }else{
            $rango_desde = $request->rango_desde;
        }
        if(!$request->rango_hasta || !isset($request->rango_hasta) || empty($request->rango_hasta || is_null($request->rango_hasta))){
            $rango_hasta = "";
        }else{
            $rango_hasta = $request->rango_hasta;
        }
        if(!$request->valor_cobertura || !isset($request->valor_cobertura) || empty($request->valor_cobertura || is_null($request->valor_cobertura))){
            $valor_cobertura = "";
        }else{
            $valor_cobertura = $request->valor_cobertura;
        }
        if(!$request->estatus || !isset($request->estatus) || empty($request->estatus || is_null($request->estatus))){
            $estatus = "";
        }else{
            $estatus = $request->estatus;
        }

        $rangoDiscapacidad = RangoDiscapacidad::findOrFail($id);;
        $rangoDiscapacidad->normativa_id = $normativa_id;
        $rangoDiscapacidad->grado_discapacidad = $grado_discapacidad;
        $rangoDiscapacidad->rango_desde = $rango_desde;
        $rangoDiscapacidad->rango_hasta = $rango_hasta;
        $rangoDiscapacidad->valor_cobertura = $valor_cobertura;
        $rangoDiscapacidad->estatus = $estatus;
        $rangoDiscapacidad->save();

        session()->flash('success', 'El Rango de discapacidad ha sido actualizado satisfactoriamente.');
        return redirect()->route('admin.rangoDiscapacidades.index');
        
    }

    public function destroy(int $id): JsonResponse
    {
        $this->checkAuthorization(auth()->user(), ['rangoDiscapacidad.delete']);

        $rangoDiscapacidad = RangoDiscapacidad::findOrFail($id);
        if($rangoDiscapacidad->creado_por != Auth::id()){
            abort(403, 'Lo sentimos !! Usted no está autorizado para realizar esta acción.');
        }

        $rangoDiscapacidad->delete();

        $data['status'] = 200;
        $data['message'] = "El Rango de discapacidad ha sido borrado satisfactoriamente.";
  
        return response()->json($data);

    }

    public function getRangoDiscapacidadesByFilters(Request $request): JsonResponse
    {
        $this->checkAuthorization(auth()->user(), ['rangoDiscapacidad.view']);

        $rangoDiscapacidades = RangoDiscapacidad::where('id',">",0);

        $filtroNormativaIdSearch = json_decode($request->normativa_id_search, true);
        $filtroGradoDiscapacidadSearch = $request->grado_discapacidad_search;
        $filtroRangoDesdeSearch = $request->rango_desde_search;
        $filtroRangoHastaSearch = $request->rango_hasta_search;
        $filtroValorCoberturaSearch = $request->valor_cobertura_search;
        $filtroVigenciaDesdeSearch = $request->vigencia_desde_search;
        $filtroEstatus = json_decode($request->estatus_search, true);
        $filtroCreadoPorSearch = json_decode($request->creado_por_search, true);
        
        if(isset($filtroNormativaIdSearch) && !empty($filtroNormativaIdSearch)){
            $rangoDiscapacidades = $rangoDiscapacidades->whereIn('normativa_id', $filtroNormativaIdSearch);
        }
        if(isset($filtroGradoDiscapacidadSearch) && !empty($filtroGradoDiscapacidadSearch)){
            $rangoDiscapacidades = $rangoDiscapacidades->where('grado_discapacidad', $filtroGradoDiscapacidadSearch);
        }
        if(isset($filtroRangoDesdeSearch) && !empty($filtroRangoDesdeSearch)){
            $rangoDiscapacidades = $rangoDiscapacidades->where('rango_desde', $filtroRangoDesdeSearch);
        }
        if(isset($filtroRangoHastaSearch) && !empty($filtroRangoHastaSearch)){
            $rangoDiscapacidades = $rangoDiscapacidades->where('rango_hasta', $filtroRangoHastaSearch);
        }
        if(isset($filtroValorCoberturaSearch) && !empty($filtroValorCoberturaSearch)){
            $rangoDiscapacidades = $rangoDiscapacidades->where('valor_cobertura', $filtroValorCoberturaSearch);
        }
        if(isset($filtroVigenciaDesdeSearch) && !empty($filtroVigenciaDesdeSearch)){
            $rangoDiscapacidades = $rangoDiscapacidades->where('vigencia_desde', $filtroVigenciaDesdeSearch);
        }
        if(isset($filtroEstatus) && !empty($filtroEstatus)){
            $rangoDiscapacidades = $rangoDiscapacidades->whereIn('estatus', $filtroEstatus);
        }
        if(isset($filtroCreadoPorSearch) && !empty($filtroCreadoPorSearch)){
            $rangoDiscapacidades = $rangoDiscapacidades->whereIn('creado_por', $filtroCreadoPorSearch);
        }
        
        $rangoDiscapacidades = $rangoDiscapacidades->orderBy('id', 'asc')->get();

        $creadores = Admin::all();

        $creadores_temp = [];
        foreach($creadores as $creador){
            $creadores_temp[$creador->id] = $creador->name;
        }

        $normativas = NormativaDiscapacidad::all();

        $normativas_temp = [];
        foreach($normativas as $normativa){
            $normativas_temp[$normativa->id] = $normativa->nombre;
        }

        $usuario_actual_id = Auth::id();

        foreach($rangoDiscapacidades as $rango){
            $rango->creado_por_nombre = array_key_exists($rango->creado_por, $creadores_temp) ? $creadores_temp[$rango->creado_por] : "";
            $rango->normativa_nombre = array_key_exists($rango->normativa_id, $normativas_temp) ? $normativas_temp[$rango->normativa_id] : "";
            $rango->esCreadorRegistro = $usuario_actual_id == $rango->creado_por ? true : false;
        }

        $data['rangoDiscapacidades'] = $rangoDiscapacidades;
        $data['creadores'] = $creadores;
        $data['normativas'] = $normativas;
  
        return response()->json($data);
    }

}