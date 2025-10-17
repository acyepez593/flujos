<?php
    
declare(strict_types=1);

namespace App\Http\Controllers\Backend;
    
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\CatalogoRequest;
use App\Models\Admin;
use App\Models\Catalogo;
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

class CatalogosController extends Controller
{
    public function index(): Renderable
    {
        $this->checkAuthorization(auth()->user(), ['catalogo.view']);

        $creadores = Admin::get(["name", "id"]);
        $tipoCatalogos = TipoCatalogo::get(["nombre", "id"]);

        return view('backend.pages.catalogos.index', [
            'creadores' => $creadores,
            'tipoCatalogos' => $tipoCatalogos
        ]);
    }

    public function create(): Renderable
    {
        $this->checkAuthorization(auth()->user(), ['catalogo.create']);

        $creadores = Admin::get(["name", "id"])->pluck('name','id');
        $tipoCatalogos = TipoCatalogo::where('estatus','ACTIVO')->get(['nombre', 'id', 'tipo_catalogo_relacionado_id']);
        
        $tiposCatalogosRelacionadosIds = [];
        $tiposCatalogosIds = [];
        foreach($tipoCatalogos as $tipoCatalogo){
            if(!empty($tipoCatalogo->tipo_catalogo_relacionado_id)){
                $tiposCatalogosRelacionadosIds[] = $tipoCatalogo->tipo_catalogo_relacionado_id;
            }
            $tiposCatalogosIds[] = $tipoCatalogo->id;
        }
        
        $catalogosRelacionadosByTipoCatalogo = Catalogo::whereIn('tipo_catalogo_id',$tiposCatalogosRelacionadosIds)->where('estatus','ACTIVO')->get(['tipo_catalogo_id','catalogo_id','id','nombre'])->groupBy('tipo_catalogo_id');
        $catalogosByTipoCatalogo = Catalogo::whereIn('tipo_catalogo_id',$tiposCatalogosIds)->where('estatus','ACTIVO')->get(['id','nombre']);

        return view('backend.pages.catalogos.create', [
            'creadores' => $creadores,
            'tipoCatalogos' => $tipoCatalogos,
            'catalogosRelacionadosByTipoCatalogo' => $catalogosRelacionadosByTipoCatalogo,
            'catalogosByTipoCatalogo' => $catalogosByTipoCatalogo
        ]);
    }

    public function store(CatalogoRequest $request): RedirectResponse
    {
        $this->checkAuthorization(auth()->user(), ['catalogo.create']);
        
        $creado_por = Auth::id();

        if(!$request->nombre || !isset($request->nombre) || empty($request->nombre || is_null($request->nombre))){
            $nombre = "";
        }else{
            $nombre = $request->nombre;
        }
        if(!$request->tipo_catalogo_id || !isset($request->tipo_catalogo_id) || empty($request->tipo_catalogo_id || is_null($request->tipo_catalogo_id))){
            $tipo_catalogo_id = "";
        }else{
            $tipo_catalogo_id = $request->tipo_catalogo_id;
        }
        if(!$request->catalogo_id || !isset($request->catalogo_id) || empty($request->catalogo_id || is_null($request->catalogo_id))){
            $catalogo_id = NULL;
        }else{
            $catalogo_id = $request->catalogo_id;
        }
        if(!$request->estatus || !isset($request->estatus) || empty($request->estatus) || is_null($request->estatus)){
            $estatus = "";
        }else{
            $estatus = $request->estatus;
        }

        $catalogo = new Catalogo();
        $catalogo->nombre = $nombre;
        $catalogo->tipo_catalogo_id = $tipo_catalogo_id;
        $catalogo->catalogo_id = $catalogo_id;
        $catalogo->estatus = $estatus;
        $catalogo->creado_por = $creado_por;
        $catalogo->save();

        session()->flash('success', __('Catálogo ha sido creado satisfactoriamente. '));
        return redirect()->route('admin.catalogos.index');
    }

    public function edit(int $id): Renderable
    {
        $this->checkAuthorization(auth()->user(), ['catalogo.edit']);

        $catalogo = Catalogo::findOrFail($id);
        if($catalogo->creado_por != Auth::id()){
            abort(403, 'Lo sentimos !! Usted no está autorizado para realizar esta acción.');
        }

        $creadores = Admin::get(["name", "id"])->pluck('name','id');
        $tipoCatalogos = TipoCatalogo::where('estatus','ACTIVO')->get(['nombre', 'id', 'tipo_catalogo_relacionado_id']);

        $tiposCatalogosRelacionadosIds = [];
        $tiposCatalogosIds = [];
        foreach($tipoCatalogos as $tipoCatalogo){
            if(!empty($tipoCatalogo->tipo_catalogo_relacionado_id)){
                $tiposCatalogosRelacionadosIds[] = $tipoCatalogo->tipo_catalogo_relacionado_id;
            }
            $tiposCatalogosIds[] = $tipoCatalogo->id;
        }

        $catalogosRelacionadosByTipoCatalogo = Catalogo::whereIn('tipo_catalogo_id',$tiposCatalogosRelacionadosIds)->where('estatus','ACTIVO')->get(['tipo_catalogo_id','catalogo_id','id','nombre'])->groupBy('tipo_catalogo_id');
        $catalogosByTipoCatalogo = Catalogo::whereIn('tipo_catalogo_id',$tiposCatalogosIds)->where('estatus','ACTIVO')->get(['id','nombre']);

        return view('backend.pages.catalogos.edit', [
            'catalogo' => $catalogo,
            'creadores' => $creadores,
            'tipoCatalogos' => $tipoCatalogos,
            'catalogosRelacionadosByTipoCatalogo' => $catalogosRelacionadosByTipoCatalogo,
            'catalogosByTipoCatalogo' => $catalogosByTipoCatalogo
        ]);
    }

    public function update(CatalogoRequest $request, int $id): RedirectResponse
    {
        $this->checkAuthorization(auth()->user(), ['catalogo.edit']);

        if(!$request->nombre || !isset($request->nombre) || empty($request->nombre || is_null($request->nombre))){
            $nombre = "";
        }else{
            $nombre = $request->nombre;
        }
        if(!$request->tipo_catalogo_id || !isset($request->tipo_catalogo_id) || empty($request->tipo_catalogo_id || is_null($request->tipo_catalogo_id))){
            $tipo_catalogo_id = "";
        }else{
            $tipo_catalogo_id = $request->tipo_catalogo_id;
        }
        if(!$request->catalogo_id || !isset($request->catalogo_id) || empty($request->catalogo_id || is_null($request->catalogo_id))){
            $catalogo_id = NULL;
        }else{
            $catalogo_id = $request->catalogo_id;
        }
        if(!$request->estatus || !isset($request->estatus) || empty($request->estatus) || is_null($request->estatus)){
            $estatus = "";
        }else{
            $estatus = $request->estatus;
        }

        $catalogo = Catalogo::findOrFail($id);
        $catalogo->nombre = $nombre;
        $catalogo->tipo_catalogo_id = $tipo_catalogo_id;
        $catalogo->catalogo_id = $catalogo_id;
        $catalogo->estatus = $estatus;
        $catalogo->save();

        session()->flash('success', 'Catálogo ha sido actualizado satisfactoriamente.');
        return redirect()->route('admin.catalogos.index');
    }

    public function destroy(int $id): JsonResponse
    {
        $this->checkAuthorization(auth()->user(), ['catalogo.delete']);

        $catalogo = Catalogo::findOrFail($id);
        if($catalogo->creado_por != Auth::id()){
            abort(403, 'Lo sentimos !! Usted no está autorizado para realizar esta acción.');
        }

        $catalogo->delete();

        $data['status'] = 200;
        $data['message'] = "Catálogo ha sido borrado satisfactoriamente.";
  
        return response()->json($data);

    }

    public function getCatalogosByFilters(Request $request): JsonResponse
    {
        $this->checkAuthorization(auth()->user(), ['catalogo.view']);

        $catalogos = Catalogo::where('id',">",0);

        $filtroNombreSearch = $request->nombre_search;
        $filtroTipoCatalogoIdSearch = json_decode($request->tipo_catalogo_id_search, true);
        $filtroEstatus = json_decode($request->estatus_search, true);
        $filtroCreadoPorSearch = json_decode($request->creado_por_search, true);
        
        if(isset($filtroNombreSearch) && !empty($filtroNombreSearch)){
            $catalogos = $catalogos->where('nombre', 'like', '%'.$filtroNombreSearch.'%');
        }
         if(isset($filtroTipoCatalogoIdSearch) && !empty($filtroTipoCatalogoIdSearch)){
            $catalogos = $catalogos->whereIn('tipo_catalogo_id', $filtroTipoCatalogoIdSearch);
        }
        if(isset($filtroEstatus) && !empty($filtroEstatus)){
            $catalogos = $catalogos->whereIn('estatus', $filtroEstatus);
        }
        if(isset($filtroEstatus) && !empty($filtroEstatus)){
            $catalogos = $catalogos->whereIn('estatus', $filtroEstatus);
        }
        if(isset($filtroCreadoPorSearch) && !empty($filtroCreadoPorSearch)){
            $catalogos = $catalogos->whereIn('creado_por', $filtroCreadoPorSearch);
        }
        
        $catalogos = $catalogos->orderBy('id', 'asc')->get();

        $tipos_catalogos = TipoCatalogo::all();
        $catalogos = Catalogo::all();
        $creadores = Admin::all();

        $tipos_catalogos_temp = [];
        foreach($tipos_catalogos as $tipo_catalogo){
            $tipos_catalogos_temp[$tipo_catalogo->id] = $tipo_catalogo->nombre;
        }

        $catalogos_temp = [];
        foreach($catalogos as $catalogo){
            $catalogos_temp[$catalogo->id] = $catalogo->nombre;
        }

        $creadores_temp = [];
        foreach($creadores as $creador){
            $creadores_temp[$creador->id] = $creador->name;
        }

        $usuario_actual_id = Auth::id();

        foreach($catalogos as $catalogo){
            $catalogo->tipo_catalogo_nombre = array_key_exists($catalogo->tipo_catalogo_id, $tipos_catalogos_temp) ? $tipos_catalogos_temp[$catalogo->tipo_catalogo_id] : "";
            $catalogo->catalogo_relacionado_nombre = array_key_exists($catalogo->catalogo_id, $catalogos_temp) ? $catalogos_temp[$catalogo->catalogo_id] : "";
            $catalogo->creado_por_nombre = array_key_exists($catalogo->creado_por, $creadores_temp) ? $creadores_temp[$catalogo->creado_por] : "";
            $catalogo->esCreadorRegistro = $usuario_actual_id == $catalogo->creado_por ? true : false;
        }

        $data['catalogos'] = $catalogos;
        $data['creadores'] = $creadores;
  
        return response()->json($data);
    }

    public function getCatalogoByTipoCatalogoId(Request $request): JsonResponse
    {
        $this->checkAuthorization(auth()->user(), ['catalogo.view']);

        $filtroTipoCatalogoId = $request->tipo_catalogo_id;
        if(isset($filtroTipoCatalogoId) && !empty($filtroTipoCatalogoId)){
            $catalogos = Catalogo::where('tipo_catalogo_id', $filtroTipoCatalogoId)->get(["nombre", "id"]);
        }

        $data['catalogos'] = $catalogos;
        return response()->json($data);
    }

}