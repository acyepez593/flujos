<?php
    
declare(strict_types=1);

namespace App\Http\Controllers\Backend;
    
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\TipoCatalogoRequest;
use App\Models\Admin;
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

class TipoCatalogosController extends Controller
{
    public function index(): Renderable
    {
        $this->checkAuthorization(auth()->user(), ['catalogo.view']);

        $creadores = Admin::get(["name", "id"]);
        $tipoCatalogosRelacionados = TipoCatalogo::where('estatus','ACTIVO')->get(['nombre', 'id', 'tipo_catalogo_relacionado_id']);

        return view('backend.pages.tipoCatalogos.index', [
            'tipoCatalogosRelacionados' => $tipoCatalogosRelacionados,
            'creadores' => $creadores
        ]);
    }

    public function create(): Renderable
    {
        $this->checkAuthorization(auth()->user(), ['catalogo.create']);

        $creadores = Admin::get(["name", "id"])->pluck('nombre','id');
        $tipoCatalogosRelacionados = TipoCatalogo::where('estatus','ACTIVO')->get(['nombre', 'id', 'tipo_catalogo_relacionado_id'])->pluck('nombre','id','tipo_catalogo_relacionado_id');

        return view('backend.pages.tipoCatalogos.create', [
            'tipoCatalogosRelacionados' => $tipoCatalogosRelacionados,
            'creadores' => $creadores
        ]);
    }

    public function store(TipoCatalogoRequest $request): RedirectResponse
    {
        $this->checkAuthorization(auth()->user(), ['catalogo.create']);
        
        $creado_por = Auth::id();

        if(!$request->nombre || !isset($request->nombre) || empty($request->nombre || is_null($request->nombre))){
            $nombre = "";
        }else{
            $nombre = $request->nombre;
        }
        if(!$request->tipo_catalogo_relacionado_id || !isset($request->tipo_catalogo_relacionado_id) || empty($request->tipo_catalogo_relacionado_id) || is_null($request->tipo_catalogo_relacionado_id)){
            $tipo_catalogo_relacionado_id = NULL;
        }else{
            $tipo_catalogo_relacionado_id = $request->tipo_catalogo_relacionado_id;
        }
        if(!$request->estatus || !isset($request->estatus) || empty($request->estatus) || is_null($request->estatus)){
            $estatus = "";
        }else{
            $estatus = $request->estatus;
        }

        $tipoCatalogo = new TipoCatalogo();
        $tipoCatalogo->nombre = $nombre;
        $tipoCatalogo->tipo_catalogo_relacionado_id = $tipo_catalogo_relacionado_id;
        $tipoCatalogo->estatus = $estatus;
        $tipoCatalogo->creado_por = $creado_por;
        $tipoCatalogo->save();

        session()->flash('success', __('Tipo Catálogo ha sido creado satisfactoriamente. '));
        return redirect()->route('admin.tipoCatalogos.index');
    }

    public function edit(int $id): Renderable
    {
        $this->checkAuthorization(auth()->user(), ['catalogo.edit']);

        $tipoCatalogo = TipoCatalogo::findOrFail($id);
        if($tipoCatalogo->creado_por != Auth::id()){
            abort(403, 'Lo sentimos !! Usted no está autorizado para realizar esta acción.');
        }

        $tipoCatalogosRelacionados = TipoCatalogo::where('estatus','ACTIVO')->get(['nombre', 'id', 'tipo_catalogo_relacionado_id'])->pluck('nombre','id','tipo_catalogo_relacionado_id');
        $creadores = Admin::get(["name", "id"])->pluck('nombre','id');

        return view('backend.pages.tipoCatalogos.edit', [
            'tipoCatalogo' => $tipoCatalogo,
            'tipoCatalogosRelacionados' => $tipoCatalogosRelacionados,
            'creadores' => $creadores
        ]);
    }

    public function update(TipoCatalogoRequest $request, int $id): RedirectResponse
    {
        $this->checkAuthorization(auth()->user(), ['catalogo.edit']);

        if(!$request->nombre || !isset($request->nombre) || empty($request->nombre || is_null($request->nombre))){
            $nombre = "";
        }else{
            $nombre = $request->nombre;
        }
        if(!$request->tipo_catalogo_relacionado_id || !isset($request->tipo_catalogo_relacionado_id) || empty($request->tipo_catalogo_relacionado_id) || is_null($request->tipo_catalogo_relacionado_id)){
            $tipo_catalogo_relacionado_id = NULL;
        }else{
            $tipo_catalogo_relacionado_id = $request->tipo_catalogo_relacionado_id;
        }
        if(!$request->estatus || !isset($request->estatus) || empty($request->estatus) || is_null($request->estatus)){
            $estatus = "";
        }else{
            $estatus = $request->estatus;
        }

        $tipoCatalogo = TipoCatalogo::findOrFail($id);
        $tipoCatalogo->nombre = $nombre;
        $tipoCatalogo->tipo_catalogo_relacionado_id = $tipo_catalogo_relacionado_id;
        $tipoCatalogo->estatus = $estatus;
        $tipoCatalogo->save();

        session()->flash('success', 'Tipo Catálogo ha sido actualizado satisfactoriamente.');
        return redirect()->route('admin.tipoCatalogos.index');
    }

    public function destroy(int $id): JsonResponse
    {
        $this->checkAuthorization(auth()->user(), ['catalogo.delete']);

        $tipoCatalogo = TipoCatalogo::findOrFail($id);
        if($tipoCatalogo->creado_por != Auth::id()){
            abort(403, 'Lo sentimos !! Usted no está autorizado para realizar esta acción.');
        }

        $tipoCatalogo->delete();

        $data['status'] = 200;
        $data['message'] = "Tipo Catálogo ha sido borrado satisfactoriamente.";
  
        return response()->json($data);

    }

    public function getTipoCatalogosByFilters(Request $request): JsonResponse
    {
        $this->checkAuthorization(auth()->user(), ['catalogo.view']);

        $tipoCatalogos = TipoCatalogo::where('estatus','ACTIVO');

        $filtroNombreSearch = $request->nombre_search;
        $filtroTipoCatalogoRelacionadoIdSearch = json_decode($request->tipo_catalogo_relacionado_id_search, true);
        $filtroEstatusSearch = json_decode($request->estatus_search, true);
        $filtroCreadoPorSearch = json_decode($request->creado_por_search, true);
        
        if(isset($filtroNombreSearch) && !empty($filtroNombreSearch)){
            $tipoCatalogos = $tipoCatalogos->where('nombre', 'like', '%'.$filtroNombreSearch.'%');
        }
        if(isset($filtroTipoCatalogoRelacionadoIdSearch) && !empty($filtroTipoCatalogoRelacionadoIdSearch)){
            $tipoCatalogos = $tipoCatalogos->whereIn('tipo_catalogo_relacionado_id', $filtroTipoCatalogoRelacionadoIdSearch);
        }
        if(isset($filtroEstatusSearch) && !empty($filtroEstatusSearch)){
            $tipoCatalogos = $tipoCatalogos->whereIn('estatus', $filtroEstatusSearch);
        }
        if(isset($filtroCreadoPorSearch) && !empty($filtroCreadoPorSearch)){
            $tipoCatalogos = $tipoCatalogos->whereIn('creado_por', $filtroCreadoPorSearch);
        }
        
        $tipoCatalogos = $tipoCatalogos->orderBy('id', 'asc')->get();

        $creadores = Admin::all();

        $creadores_temp = [];
        foreach($creadores as $creador){
            $creadores_temp[$creador->id] = $creador->name;
        }

        $usuario_actual_id = Auth::id();

        $tipoCatalogosRelacionados = TipoCatalogo::where('estatus','ACTIVO')->get(['nombre', 'id']);
        $tipo_catalogos_relacionados_temp = [];
        foreach($tipoCatalogosRelacionados as $tipo){
            $tipo_catalogos_relacionados_temp[$tipo->id] = $tipo->nombre;
        }

        foreach($tipoCatalogos as $tipoCatalogo){
            $tipoCatalogo->tipo_catalogo_relacionado_nombre = array_key_exists($tipoCatalogo->tipo_catalogo_relacionado_id, $tipo_catalogos_relacionados_temp) ? $tipo_catalogos_relacionados_temp[$tipoCatalogo->tipo_catalogo_relacionado_id] : "";
            $tipoCatalogo->creado_por_nombre = array_key_exists($tipoCatalogo->creado_por, $creadores_temp) ? $creadores_temp[$tipoCatalogo->creado_por] : "";
            $tipoCatalogo->esCreadorRegistro = $usuario_actual_id == $tipoCatalogo->creado_por ? true : false;
        }

        $data['tipoCatalogos'] = $tipoCatalogos;
        $data['creadores'] = $creadores;
  
        return response()->json($data);
    }

}