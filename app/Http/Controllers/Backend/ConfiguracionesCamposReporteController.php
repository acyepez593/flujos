<?php
    
declare(strict_types=1);

namespace App\Http\Controllers\Backend;
    
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ConfiguracionCamposReporteRequest;
use App\Models\Admin;
use App\Models\ConfiguracionCamposReporte;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class ConfiguracionesCamposReporteController extends Controller
{
    public function index(): Renderable
    {
        $this->checkAuthorization(auth()->user(), ['configuracionCamposReporte.view']);

        $configuracionesCamposReporte = ConfiguracionCamposReporte::all();
        $responsables = Admin::get(["name", "id"]);

        $opcionesHabilitar=array
        (
            array("id"=>"1","nombre"=>"SI"),
            array("id"=>"0","nombre"=>"NO"),
        );

        return view('backend.pages.configuracionesCamposReporte.index', [
            'opcionesHabilitar' => $opcionesHabilitar,
            'responsables' => $responsables,
            'configuracionesCamposReporte' => $configuracionesCamposReporte
        ]);
    }

    public function create(): Renderable
    {
        $this->checkAuthorization(auth()->user(), ['configuracionCamposReporte.create']);

        $opcionesHabilitar=array
        (
            array("id"=>"1","nombre"=>"SI"),
            array("id"=>"0","nombre"=>"NO"),
        );
        $columnas = Schema::getColumnListing('oficios');
        $campos = [];
        foreach($columnas as $index => $col){
            if($col != 'created_at' && $col != 'updated_at' && $col != 'deleted_at'){
                if($col == 'id'){
                    $nombreCampo = strtoupper($col);
                    $obj = [];
                    
                }else{
                    $partes = explode("_", $col);
                    $nombreCampo = "";
                    $obj = [];
                    foreach($partes as $parte){
                        if($parte != 'id'){
                            $nombreCampo .= strtoupper($parte) . " ";
                        }
                    }
                }
                $obj["nombre_campo"] = $nombreCampo;
                $obj["campo"] = $col;
                $obj["orden"] = $index;
                $obj["habilitado"] = false;
                $campos[] = $obj;
            }
        }

        $responsables = Admin::get(["name", "id"]);

        return view('backend.pages.configuracionesCamposReporte.create', [
            'opcionesHabilitar' => $opcionesHabilitar,
            'objCampos' => $campos,
            'responsables' => $responsables
        ]);
    }

    public function store(ConfiguracionCamposReporteRequest $request): RedirectResponse
    {
        $this->checkAuthorization(auth()->user(), ['configuracionCamposReporte.create']);

        if($request->nombre && isset($request->nombre) && !empty($request->nombre && !is_null($request->nombre))){
            $nombre = $request->nombre;
        }
        if($request->habilitar && isset($request->habilitar) && !empty($request->habilitar) && !is_null($request->habilitar)){
            $habilitar = $request->habilitar;
        }
        if($request->campos && isset($request->campos) && !empty($request->campos) && !is_null($request->campos)){
            $campos = $request->campos;
        }
        if($request->responsable_id && isset($request->responsable_id) && !empty($request->responsable_id) && !is_null($request->responsable_id)){
            $responsable_id = $request->responsable_id;
        }

        $configuracionCamposReporte = new ConfiguracionCamposReporte();
        $configuracionCamposReporte->nombre = $nombre;
        $configuracionCamposReporte->habilitar = $habilitar;
        $configuracionCamposReporte->campos = $campos;
        $configuracionCamposReporte->responsable_id = $responsable_id;
        $configuracionCamposReporte->save();

        session()->flash('success', __('La Configuración de Campos Reporte, ha sido creada satisfactoriamente. '));
        return redirect()->route('admin.configuracionesCamposReporte.index');
        
    }

    public function edit(int $id): Renderable
    {
        $this->checkAuthorization(auth()->user(), ['configuracionCamposReporte.edit']);

        $configuracionCamposReporte = ConfiguracionCamposReporte::findOrFail($id);

        $opcionesHabilitar=array
        (
            array("id"=>"1","nombre"=>"SI"),
            array("id"=>"0","nombre"=>"NO"),
        );
        
        $campos = json_decode($configuracionCamposReporte->campos, true);

        $responsables = Admin::get(["name", "id"]);

        return view('backend.pages.configuracionesCamposReporte.edit', [
            'configuracionCamposReporte' => $configuracionCamposReporte,
            'opcionesHabilitar' => $opcionesHabilitar,
            'objCampos' => $campos,
            'responsables' => $responsables
        ]);
    }

    public function update(ConfiguracionCamposReporteRequest $request, int $id): RedirectResponse
    {
        $this->checkAuthorization(auth()->user(), ['configuracionCamposReporte.edit']);

        if($request->nombre && isset($request->nombre) && !empty($request->nombre && !is_null($request->nombre))){
            $nombre = $request->nombre;
        }
        
        if($request->campos && isset($request->campos) && !empty($request->campos) && !is_null($request->campos)){
            $campos = $request->campos;
        }
        if($request->responsable_id && isset($request->responsable_id) && !empty($request->responsable_id) && !is_null($request->responsable_id)){
            $responsable_id = $request->responsable_id;
        }
        $habilitar = $request->habilitar;
        
        $configuracionCamposReporte = ConfiguracionCamposReporte::findOrFail($id);
        $configuracionCamposReporte->nombre = $nombre;
        $configuracionCamposReporte->habilitar = $habilitar;
        $configuracionCamposReporte->campos = $campos;
        $configuracionCamposReporte->responsable_id = $responsable_id; 
        $configuracionCamposReporte->save();

        session()->flash('success', 'La Configuración de Campos Reporte ha sido actualizada satisfactoriamente.');
        return redirect()->route('admin.configuracionesCamposReporte.index');
    }

    public function destroy(int $id): JsonResponse
    {
        $this->checkAuthorization(auth()->user(), ['configuracionCamposReporte.delete']);

        $configuracionCamposReporte = ConfiguracionCamposReporte::findOrFail($id);

        $configuracionCamposReporte->delete();

        $data['status'] = 200;
        $data['message'] = "La Configuración de Campos Reporte ha sido borrado satisfactoriamente.";
  
        return response()->json($data);

    }

    public function getConfiguracionesCamposReporteByFilters(Request $request): JsonResponse
    {
        $this->checkAuthorization(auth()->user(), ['configuracionCamposReporte.view']);

        $configuracionesCamposReporte = ConfiguracionCamposReporte::where('id',">",0);

        $filtroNombreSearch = $request->nombre_search;
        $filtroHabilitarSearch = json_decode($request->habilitar_search, true);
        $filtroResponsableIdSearch = json_decode($request->responsable_id_search, true);

        if(isset($filtroNombreSearch) && !empty($filtroNombreSearch)){
            $configuracionesCamposReporte = $configuracionesCamposReporte->where('nombre', $filtroNombreSearch);
        }
        if(isset($filtroHabilitarSearch) && !empty($filtroHabilitarSearch)){
            $configuracionesCamposReporte = $configuracionesCamposReporte->where('habilitar', $filtroHabilitarSearch);
        }
        if(isset($filtroResponsableIdSearch) && !empty($filtroResponsableIdSearch)){
            $configuracionesCamposReporte = $configuracionesCamposReporte->whereIn('responsable_id', $filtroResponsableIdSearch);
        }

        $configuracionesCamposReporte = $configuracionesCamposReporte->orderBy('id', 'desc')->get();

        $responsables = Admin::all();

        $responsables_temp = [];
        foreach($responsables as $responsable){
            $responsables_temp[$responsable->id] = $responsable->name;
        }

        foreach($configuracionesCamposReporte as $conf){
            $conf->responsable_nombre = array_key_exists($conf->responsable_id, $responsables_temp) ? $responsables_temp[$conf->responsable_id] : "";
        }

        $data['configuracionesCamposReporte'] = $configuracionesCamposReporte;
  
        return response()->json($data);
    }

}