<?php
    
declare(strict_types=1);

namespace App\Http\Controllers\Backend;
    
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\OficioRequest;
use App\Models\Admin;
use App\Models\EstadoTramite;
use App\Models\Institucion;
use App\Models\Oficio;
use App\Models\Provincia;
use App\Models\Tipo;
use App\Models\TipoAtencion;
use App\Models\TipoEstadoCaja;
use App\Models\TipoFirma;
use App\Models\File;
use App\Models\PrestadorSalud;
use App\Rules\ControlDuplicadosExpedientesPorQuipuxRule;
use App\Rules\ControlDuplicadosExpedientesPorTipoAtencionYFechaServicioRule;
use App\Rules\ControlDuplicadosOficiosRule;
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

class OficiosController extends Controller
{
    public function index(): Renderable
    {
        $this->checkAuthorization(auth()->user(), ['oficio.view']);

        $tipos = Tipo::get(["nombre", "id"]);
        $tiposAtencion = TipoAtencion::get(["nombre", "id"]);
        $tiposEstadoCaja = TipoEstadoCaja::get(["nombre", "id"]);
        $provincias = Provincia::get(["nombre", "id"]);
        $instituciones = Institucion::get(["nombre", "id"]);
        $tiposFirma = TipoFirma::get(["nombre", "id"]);
        $estadosTramite = EstadoTramite::get(["nombre", "id"]);
        $responsables = Admin::get(["name", "id"]);

        $responsable_id = Auth::id();

        $cajasAbiertas = Oficio::where('estado_caja_id',1)->where('responsable_id',$responsable_id)->groupBy('numero_caja')->get(["numero_caja"]);

        return view('backend.pages.oficios.index', [
            'tipos' => $tipos,
            'tiposAtencion' => $tiposAtencion,
            'tiposEstadoCaja' => $tiposEstadoCaja,
            'provincias' => $provincias,
            'instituciones' => $instituciones,
            'tiposFirma' => $tiposFirma,
            'estadosTramite' => $estadosTramite,
            'responsables' => $responsables,
            'cajasAbiertas' => $cajasAbiertas,
        ]);
    }

    public function create(): Renderable
    {
        $this->checkAuthorization(auth()->user(), ['oficio.create']);

        $tipos = Tipo::get(["nombre", "id"])->pluck('nombre','id');
        $tiposAtencion = TipoAtencion::get(["nombre", "id"])->pluck('nombre','id');
        $tiposEstadoCaja = TipoEstadoCaja::get(["nombre", "id"])->pluck('nombre','id');
        $provincias = Provincia::get(["nombre", "id"])->pluck('nombre','id');
        $instituciones = Institucion::get(["nombre", "id"])->pluck('nombre','id');
        $tiposFirma = TipoFirma::get(["nombre", "id"])->pluck('nombre','id');
        $estadosTramite = EstadoTramite::get(["nombre", "id"])->pluck('nombre','id');
        $responsables = Admin::get(["name", "id"])->pluck('nombre','id');

        $responsable_id = Auth::id();

        $usuario = Admin::where('id',$responsable_id)->first();

        $oficioPublico = Oficio::where('numero_caja', 'like', '%PU%')->where('estado_caja_id',2)->where('responsable_id',$responsable_id)->orderBy('id', 'DESC')->first();
        if(!isset($oficioPublico)){
            $ultimoSecuencialPublico = 0;
            $siguienteSecuencialPu = $ultimoSecuencialPublico + 1;
            $siguienteSecuencialPu = str_pad((string)$siguienteSecuencialPu, 3, "0", STR_PAD_LEFT).$usuario->initials;
        }else{
            $ultimoSecuencialPublico = substr($oficioPublico->numero_caja, 2);
            $ultimoSecuencialPublico = substr($ultimoSecuencialPublico, 0, -2);
            $longitudPu = strlen($ultimoSecuencialPublico);
            $ultimoSecuencialPublico = intval($ultimoSecuencialPublico);
            $siguienteSecuencialPu = 0;
            if($ultimoSecuencialPublico <= 999){
                $siguienteSecuencialPu = $ultimoSecuencialPublico + 1;
                $siguienteSecuencialPu = str_pad((string)$siguienteSecuencialPu, $longitudPu, "0", STR_PAD_LEFT).$usuario->initials;
            }else{
                $siguienteSecuencialPu = $ultimoSecuencialPublico + 1;
                $siguienteSecuencialPu = str_pad((string)$siguienteSecuencialPu, $longitudPu + 1, "0", STR_PAD_LEFT).$usuario->initials;
            }
        }
        
        $oficioPrivado = Oficio::where('numero_caja', 'like', '%PR%')->where('estado_caja_id',2)->where('responsable_id',$responsable_id)->orderBy('id', 'DESC')->first();
        if(!isset($oficioPrivado)){
            $ultimoSecuencialPrivado = 0;
            $siguienteSecuencialPr = $ultimoSecuencialPrivado + 1;
            $siguienteSecuencialPr = str_pad((string)$siguienteSecuencialPr, 3, "0", STR_PAD_LEFT).$usuario->initials;
        }else{
            $ultimoSecuencialPrivado = substr($oficioPrivado->numero_caja, 2);
            $ultimoSecuencialPrivado = substr($ultimoSecuencialPrivado, 0, -2);
            $longitudPr = strlen($ultimoSecuencialPrivado);
            $ultimoSecuencialPrivado = intval($ultimoSecuencialPrivado);
            $siguienteSecuencialPr = 0;
            if($ultimoSecuencialPrivado <= 999){
                $siguienteSecuencialPr = $ultimoSecuencialPrivado + 1;
                $siguienteSecuencialPr = str_pad((string)$siguienteSecuencialPr, $longitudPr, "0", STR_PAD_LEFT).$usuario->initials;
            }else{
                $siguienteSecuencialPr = $ultimoSecuencialPrivado + 1;
                $siguienteSecuencialPr = str_pad((string)$siguienteSecuencialPr, $longitudPr + 1, "0", STR_PAD_LEFT).$usuario->initials;
            }
        }

        return view('backend.pages.oficios.create', [
            'tipos' => $tipos,
            'tiposAtencion' => $tiposAtencion,
            'tiposEstadoCaja' => $tiposEstadoCaja,
            'provincias' => $provincias,
            'instituciones' => $instituciones,
            'tiposFirma' => $tiposFirma,
            'estadosTramite' => $estadosTramite,
            'responsables' => $responsables,
            'ultimoSecuencialPublico' => $ultimoSecuencialPublico,
            'siguienteSecuencialPu' => $siguienteSecuencialPu,
            'ultimoSecuencialPrivado' => $ultimoSecuencialPrivado,
            'siguienteSecuencialPr' => $siguienteSecuencialPr,
            'roles' => Role::all(),
        ]);
    }

    public function store(OficioRequest $request): RedirectResponse
    {
        $this->checkAuthorization(auth()->user(), ['oficio.create']);
        
        $id = 0;
        /*$validatedData = $request->validate([
            'monto_planilla' => ['required','numeric', new ControlDuplicadosOficiosRule($request,$id)],
            'documento_externo' => ['required','max:100', new ControlDuplicadosOficiosRule($request,$id)],
        ]);*/
        $validatedData = $request->validate([
            'numero_quipux' => ['required','max:600', new ControlDuplicadosExpedientesPorQuipuxRule($request,$id)],
            'prestador_salud_id' => ['required','exists:prestadores_salud,id', new ControlDuplicadosExpedientesPorTipoAtencionYFechaServicioRule($request,'Oficio',$id)],
            'tipo_atencion_id' => ['required','exists:tipo_atencion,id', new ControlDuplicadosExpedientesPorTipoAtencionYFechaServicioRule($request,'Oficio',$id)],
            'fecha_servicio' => ['required', new ControlDuplicadosExpedientesPorTipoAtencionYFechaServicioRule($request,'Oficio',$id)],
        ]);

        $meses = ["01" => "Enero","02" => "Febrero","03" => "Marzo","04" => "Abril","05" => "Mayo","06" => "Junio","07" => "Julio","08" => "Agosto","09" => "Septiembre","10" => "Octubre","11" => "Noviembre","12" => "Diciembre"];
        $fs = explode("-", $request->fecha_servicio);
        $numeroMes = array_search($fs[0],$meses);
        $separador = "-";
        $fechaServicio = $fs[1].$separador.$numeroMes.$separador."1";

        $duplicar = $request->duplicar;
        $cerrarCaja = $request->cerrar_caja;

        $fecha_registro = Carbon::createFromFormat('Y-m-d', $request->fecha_registro);
        $fecha_recepcion = Carbon::createFromFormat('Y-m-d', $request->fecha_recepcion);
        $fecha_servicio = Carbon::createFromFormat('Y-m-d', $fechaServicio);
        $prestador_salud_id = "";
        $estado_tramite_id = "";
        $fecha_devolucion_auditoria = "";
        $observaciones_devolucion_auditoria = "";
        $fecha_devolucion_prestador = "";
        $observaciones_devolucion_prestador = "";
        $responsable_id = Auth::id();

        if(!$request->numero_establecimiento || !isset($request->numero_establecimiento) || empty($request->numero_establecimiento || is_null($request->numero_establecimiento))){
            $numero_establecimiento = "";
        }else{
            $numero_establecimiento = $request->numero_establecimiento;
        }
        if(!$request->numero_caja_auditoria || !isset($request->numero_caja_auditoria) || empty($request->numero_caja_auditoria) || is_null($request->numero_caja_auditoria)){
            $numero_caja_auditoria = "";
        }else{
            $numero_caja_auditoria = $request->numero_caja_auditoria;
        }
        if(!$request->numero_caja_ant || !isset($request->numero_caja_ant) || empty($request->numero_caja_ant) || is_null($request->numero_caja_ant)){
            $numero_caja_ant = "";
        }else{
            $numero_caja_ant = $request->numero_caja_ant;
        }
        if(!$request->fecha_envio_auditoria || !isset($request->fecha_envio_auditoria) || empty($request->fecha_envio_auditoria) || is_null($request->fecha_envio_auditoria)){
            $fecha_envio_auditoria = NULL;
        }else{
            $fecha_envio_auditoria = $request->fecha_envio_auditoria;
        }
        if(!$request->tipo_firma_id || !isset($request->tipo_firma_id) || empty($request->tipo_firma_id) || is_null($request->tipo_firma_id)){
            $tipo_firma_id = 0;
        }else{
            $tipo_firma_id = $request->tipo_firma_id;
        }
        if(!$request->observaciones || !isset($request->observaciones) || empty($request->observaciones) || is_null($request->observaciones)){
            $observaciones = "";
        }else{
            $observaciones = $request->observaciones;
        }
        if(!$request->estado_caja_id || !isset($request->estado_caja_id) || empty($request->estado_caja_id) || is_null($request->estado_caja_id)){
            $estado_caja_id = 0;
        }else{
            $estado_caja_id = $request->estado_caja_id;
        }
        if(!$request->prestador_salud_id || !isset($request->prestador_salud_id) || empty($request->prestador_salud_id) || is_null($request->prestador_salud_id)){
            $prestador_salud_id = 0;
        }else{
            $prestador_salud_id = $request->prestador_salud_id;
        }
        if(!$request->estado_tramite_id || !isset($request->estado_tramite_id) || empty($request->estado_tramite_id) || is_null($request->estado_tramite_id)){
            $estado_tramite_id = 0;
        }else{
            $estado_tramite_id = $request->estado_tramite_id;
        }
        if(!$request->fecha_devolucion_auditoria || !isset($request->fecha_devolucion_auditoria) || empty($request->fecha_devolucion_auditoria) || is_null($request->fecha_devolucion_auditoria)){
            $fecha_devolucion_auditoria = NULL;
        }else{
            $fecha_devolucion_auditoria = Carbon::createFromFormat('Y-m-d', $request->fecha_devolucion_auditoria);
        }
        if(!$request->observaciones_devolucion_auditoria || !isset($request->observaciones_devolucion_auditoria) || empty($request->observaciones_devolucion_auditoria) || is_null($request->observaciones_devolucion_auditoria)){
            $observaciones_devolucion_auditoria = "";
        }else{
            $observaciones_devolucion_auditoria = $request->observaciones_devolucion_auditoria;
        }
        if(!$request->fecha_devolucion_prestador || !isset($request->fecha_devolucion_prestador) || empty($request->fecha_devolucion_prestador) || is_null($request->fecha_devolucion_prestador)){
            $fecha_devolucion_prestador = NULL;
        }else{
            $fecha_devolucion_prestador = Carbon::createFromFormat('Y-m-d', $request->fecha_devolucion_prestador);
        }
        if(!$request->observaciones_devolucion_prestador || !isset($request->observaciones_devolucion_prestador) || empty($request->observaciones_devolucion_prestador) || is_null($request->observaciones_devolucion_prestador)){
            $observaciones_devolucion_prestador = "";
        }else{
            $observaciones_devolucion_prestador = $request->observaciones_devolucion_prestador;
        }

        $oficio = new Oficio();
        $oficio->fecha_registro = $fecha_registro;
        $oficio->tipo_id = $request->tipo_id;
        $oficio->ruc = $request->ruc;
        $oficio->numero_establecimiento = $numero_establecimiento;
        $oficio->razon_social = $request->razon_social;
        $oficio->fecha_recepcion = $fecha_recepcion;
        $oficio->tipo_atencion_id = $request->tipo_atencion_id;
        $oficio->provincia_id = $request->provincia_id;
        $oficio->fecha_servicio = $fecha_servicio;
        $oficio->numero_casos = $request->numero_casos;
        $oficio->monto_planilla = $request->monto_planilla;
        $oficio->numero_caja_ant = $numero_caja_ant;
        $oficio->numero_caja = $request->numero_caja;
        $oficio->fecha_envio_auditoria = $fecha_envio_auditoria;
        $oficio->numero_caja_auditoria = $numero_caja_auditoria;
        $oficio->institucion_id = $request->institucion_id;
        $oficio->documento_externo = $request->documento_externo;
        $oficio->tipo_firma_id = $tipo_firma_id;
        $oficio->observaciones = $observaciones;
        $oficio->numero_quipux = $request->numero_quipux;
        $oficio->responsable_id = $responsable_id;
        $oficio->estado_caja_id = 1;
        $oficio->tipo_tramite_id = 1;
        $oficio->prestador_salud_id = $prestador_salud_id;
        $oficio->estado_tramite_id = $estado_tramite_id;
        $oficio->fecha_devolucion_auditoria = $fecha_devolucion_auditoria;
        $oficio->observaciones_devolucion_auditoria = $observaciones_devolucion_auditoria;
        $oficio->fecha_devolucion_prestador = $fecha_devolucion_prestador;
        $oficio->observaciones_devolucion_prestador = $observaciones_devolucion_prestador;
        $oficio->save();

        $files = [];
        
        if ($request->hasFile('files')){
            foreach($request->file('files') as $file) {
                
                $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/oficios'), $fileName);
                $files[] = ['name' => $fileName];
            }
        }
  
        foreach ($files as $fileData) {
            $file = new File();
            $file->name = $fileData['name'];
            $file->oficio_id = $oficio->id;
            $file->save();
        }

        session()->flash('success', __('Oficio ha sido creada satisfactoriamente. '));
        if($duplicar === true || $duplicar == 'true'){
            return redirect('admin/oficios/duplicar/'.$oficio->id);
        }else{
            if($cerrarCaja === true || $cerrarCaja == 'true'){
                $update = ['estado_caja_id' => '2'];
                Oficio::where('numero_caja', $oficio->numero_caja)->update($update);
            }
            return redirect()->route('admin.oficios.index');
        }
    }

    public function edit(int $id): Renderable
    {
        $this->checkAuthorization(auth()->user(), ['oficio.edit']);

        $oficio = Oficio::findOrFail($id);
        if($oficio->responsable_id != Auth::id()){
            abort(403, 'Lo sentimos !! Usted no está autorizado para realizar esta acción.');
        }

        $tipos = Tipo::get(["nombre", "id"])->pluck('nombre','id');
        $tiposAtencion = TipoAtencion::get(["nombre", "id"])->pluck('nombre','id');
        $tiposEstadoCaja = TipoEstadoCaja::get(["nombre", "id"])->pluck('nombre','id');
        $provincias = Provincia::get(["nombre", "id"])->pluck('nombre','id');
        $instituciones = Institucion::get(["nombre", "id"])->pluck('nombre','id');
        $tiposFirma = TipoFirma::get(["nombre", "id"])->pluck('nombre','id');
        $estadosTramite = EstadoTramite::get(["nombre", "id"])->pluck('nombre','id');
        $responsables = Admin::get(["name", "id"])->pluck('nombre','id');

        return view('backend.pages.oficios.edit', [
            'oficio' => $oficio,
            'tipos' => $tipos,
            'tiposAtencion' => $tiposAtencion,
            'tiposEstadoCaja' => $tiposEstadoCaja,
            'provincias' => $provincias,
            'instituciones' => $instituciones,
            'tiposFirma' => $tiposFirma,
            'estadosTramite' => $estadosTramite,
            'responsables' => $responsables,
            'roles' => Role::all(),
        ]);
    }

    public function update(OficioRequest $request, int $id): RedirectResponse
    {
        $this->checkAuthorization(auth()->user(), ['oficio.edit']);

        /*$validatedData = $request->validate([
            'monto_planilla' => ['required','numeric', new ControlDuplicadosOficiosRule($request,$id)],
            'documento_externo' => ['required','max:100', new ControlDuplicadosOficiosRule($request,$id)],
        ]);*/
        $validatedData = $request->validate([
            'numero_quipux' => ['required','max:600', new ControlDuplicadosExpedientesPorQuipuxRule($request,$id)],
            'prestador_salud_id' => ['required','exists:prestadores_salud,id', new ControlDuplicadosExpedientesPorTipoAtencionYFechaServicioRule($request,'Oficio',$id)],
            'tipo_atencion_id' => ['required','exists:tipo_atencion,id', new ControlDuplicadosExpedientesPorTipoAtencionYFechaServicioRule($request,'Oficio',$id)],
            'fecha_servicio' => ['required', new ControlDuplicadosExpedientesPorTipoAtencionYFechaServicioRule($request,'Oficio',$id)],
        ]);

        $meses = ["01" => "Enero","02" => "Febrero","03" => "Marzo","04" => "Abril","05" => "Mayo","06" => "Junio","07" => "Julio","08" => "Agosto","09" => "Septiembre","10" => "Octubre","11" => "Noviembre","12" => "Diciembre"];
        $fs = explode("-", $request->fecha_servicio);
        $numeroMes = array_search($fs[0],$meses);
        $separador = "-";
        $fechaServicio = $fs[1].$separador.$numeroMes.$separador."1";

        $fecha_registro = Carbon::createFromFormat('Y-m-d', $request->fecha_registro);
        $fecha_recepcion = Carbon::createFromFormat('Y-m-d', $request->fecha_recepcion);
        $fecha_servicio = Carbon::createFromFormat('Y-m-d', $fechaServicio);
        $prestador_salud_id = "";
        $estado_tramite_id = "";
        $fecha_devolucion_auditoria = "";
        $observaciones_devolucion_auditoria = "";
        $fecha_devolucion_prestador = "";
        $observaciones_devolucion_prestador = "";
        $responsable_id = Auth::id();

        if(!$request->numero_establecimiento || !isset($request->numero_establecimiento) || empty($request->numero_establecimiento || is_null($request->numero_establecimiento))){
            $numero_establecimiento = "";
        }else{
            $numero_establecimiento = $request->numero_establecimiento;
        }
        if(!$request->numero_caja_auditoria || !isset($request->numero_caja_auditoria) || empty($request->numero_caja_auditoria) || is_null($request->numero_caja_auditoria)){
            $numero_caja_auditoria = "";
        }else{
            $numero_caja_auditoria = $request->numero_caja_auditoria;
        }
        if(!$request->numero_caja_ant || !isset($request->numero_caja_ant) || empty($request->numero_caja_ant) || is_null($request->numero_caja_ant)){
            $numero_caja_ant = "";
        }else{
            $numero_caja_ant = $request->numero_caja_ant;
        }
        if(!$request->fecha_envio_auditoria || !isset($request->fecha_envio_auditoria) || empty($request->fecha_envio_auditoria) || is_null($request->fecha_envio_auditoria)){
            $fecha_envio_auditoria = NULL;
        }else{
            $fecha_envio_auditoria = $request->fecha_envio_auditoria;
        }
        if(!$request->tipo_firma_id || !isset($request->tipo_firma_id) || empty($request->tipo_firma_id) || is_null($request->tipo_firma_id)){
            $tipo_firma_id = 0;
        }else{
            $tipo_firma_id = $request->tipo_firma_id;
        }
        if(!$request->observaciones || !isset($request->observaciones) || empty($request->observaciones) || is_null($request->observaciones)){
            $observaciones = "";
        }else{
            $observaciones = $request->observaciones;
        }
        if(!$request->estado_caja_id || !isset($request->estado_caja_id) || empty($request->estado_caja_id) || is_null($request->estado_caja_id)){
            $estado_caja_id = 0;
        }else{
            $estado_caja_id = $request->estado_caja_id;
        }
        if(!$request->estado_tramite_id || !isset($request->estado_tramite_id) || empty($request->estado_tramite_id) || is_null($request->estado_tramite_id)){
            $estado_tramite_id = 0;
        }else{
            $estado_tramite_id = $request->estado_tramite_id;
        }
        if(!$request->prestador_salud_id || !isset($request->prestador_salud_id) || empty($request->prestador_salud_id) || is_null($request->prestador_salud_id)){
            $prestador_salud_id = 0;
        }else{
            $prestador_salud_id = $request->prestador_salud_id;
        }
        if(!$request->fecha_devolucion_auditoria || !isset($request->fecha_devolucion_auditoria) || empty($request->fecha_devolucion_auditoria) || is_null($request->fecha_devolucion_auditoria)){
            $fecha_devolucion_auditoria = NULL;
        }else{
            $fecha_devolucion_auditoria = Carbon::createFromFormat('Y-m-d', $request->fecha_devolucion_auditoria);
        }
        if(!$request->observaciones_devolucion_auditoria || !isset($request->observaciones_devolucion_auditoria) || empty($request->observaciones_devolucion_auditoria) || is_null($request->observaciones_devolucion_auditoria)){
            $observaciones_devolucion_auditoria = "";
        }else{
            $observaciones_devolucion_auditoria = $request->observaciones_devolucion_auditoria;
        }
        if(!$request->fecha_devolucion_prestador || !isset($request->fecha_devolucion_prestador) || empty($request->fecha_devolucion_prestador) || is_null($request->fecha_devolucion_prestador)){
            $fecha_devolucion_prestador = NULL;
        }else{
            $fecha_devolucion_prestador = Carbon::createFromFormat('Y-m-d', $request->fecha_devolucion_prestador);
        }
        if(!$request->observaciones_devolucion_prestador || !isset($request->observaciones_devolucion_prestador) || empty($request->observaciones_devolucion_prestador) || is_null($request->observaciones_devolucion_prestador)){
            $observaciones_devolucion_prestador = "";
        }else{
            $observaciones_devolucion_prestador = $request->observaciones_devolucion_prestador;
        }

        $oficio = Oficio::findOrFail($id);
        $oficio->fecha_registro = $fecha_registro;
        $oficio->tipo_id = $request->tipo_id;
        $oficio->ruc = $request->ruc;
        $oficio->numero_establecimiento = $numero_establecimiento; 
        $oficio->razon_social = $request->razon_social;
        $oficio->fecha_recepcion = $fecha_recepcion;
        $oficio->tipo_atencion_id = $request->tipo_atencion_id;
        $oficio->provincia_id = $request->provincia_id;
        $oficio->fecha_servicio = $fecha_servicio;
        $oficio->numero_casos = $request->numero_casos;
        $oficio->monto_planilla = $request->monto_planilla;
        $oficio->numero_caja_ant = $numero_caja_ant;
        $oficio->numero_caja = $request->numero_caja;
        $oficio->fecha_envio_auditoria = $fecha_envio_auditoria;
        $oficio->numero_caja_auditoria = $numero_caja_auditoria;
        $oficio->institucion_id = $request->institucion_id;
        $oficio->documento_externo = $request->documento_externo;
        $oficio->tipo_firma_id = $tipo_firma_id;
        $oficio->observaciones = $observaciones;
        $oficio->numero_quipux = $request->numero_quipux;
        $oficio->responsable_id = $responsable_id;
        $oficio->tipo_tramite_id = 1;
        $oficio->prestador_salud_id = $prestador_salud_id;
        $oficio->estado_tramite_id = $estado_tramite_id;
        $oficio->fecha_devolucion_auditoria = $fecha_devolucion_auditoria;
        $oficio->observaciones_devolucion_auditoria = $observaciones_devolucion_auditoria;
        $oficio->fecha_devolucion_prestador = $fecha_devolucion_prestador;
        $oficio->observaciones_devolucion_prestador = $observaciones_devolucion_prestador;
        //$oficio->estado_caja_id = $estado_caja_id;
        $oficio->save();

        $files = [];
        
        if ($request->hasFile('files')){
            foreach($request->file('files') as $file) {
                
                $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/oficios'), $fileName);
                $files[] = ['name' => $fileName];
            }
        }
  
        foreach ($files as $fileData) {
            $file = new File();
            $file->name = $fileData['name'];
            $file->oficio_id = $oficio->id;
            $file->save();
        }

        session()->flash('success', 'Oficio ha sido actualizado satisfactoriamente.');
        return back();
    }

    public function destroy(int $id): JsonResponse
    {
        $this->checkAuthorization(auth()->user(), ['oficio.delete']);

        $oficio = Oficio::findOrFail($id);
        if($oficio->responsable_id != Auth::id()){
            abort(403, 'Lo sentimos !! Usted no está autorizado para realizar esta acción.');
        }

        $oficio->delete();

        $data['status'] = 200;
        $data['message'] = "Oficio ha sido borrado satisfactoriamente.";
  
        return response()->json($data);

    }

    public function getOficiosByFilters(Request $request): JsonResponse
    {
        $this->checkAuthorization(auth()->user(), ['oficio.view']);

        $oficios = Oficio::where('id',">",0);

        $filtroFechaRegistroDesdeSearch = $request->fecha_registro_desde_search;
        $filtroFechaRegistroHastaSearch = $request->fecha_registro_hasta_search;
        $filtroTipoIdSearch = json_decode($request->tipo_id_search, true);
        $filtroRucSearch = $request->ruc_search;
        $filtroNumeroEstablecimientoSearch = $request->numero_establecimiento_search;
        $filtroRazonSocialSearch = $request->razon_social_search;
        $filtroFechaRecepcionDesdeSearch = $request->fecha_recepcion_desde_search;
        $filtroFechaRecepcionHastaSearch = $request->fecha_recepcion_hasta_search;
        $filtrotipoAtencionIdSearch = json_decode($request->tipo_atencion_id_search, true);
        $filtroProvinciaIdSearch = json_decode($request->provincia_id_search, true);
        $filtroFechaServicioDesdeSearch = $request->fecha_servicio_desde_search;
        $filtroFechaServicioHastaSearch = $request->fecha_servicio_hasta_search;
        $filtroNumeroCasosSearch = $request->numero_casos_search;
        $filtroMontoPlanillaSearch = $request->monto_planilla_search;
        $filtroNumeroCajaAntSearch = $request->numero_caja_ant_search;
        $filtroNumeroCajaSearch = $request->numero_caja_search;
        $filtroTipoEstadoCajaIdSearch = json_decode($request->tipo_estado_caja_id_search, true);
        $filtroNumeroCajaAuditoriaSearch = $request->numero_caja_auditoria_search;
        $filtroFechaEnvioAuditoriaDesdeSearch = $request->fecha_envio_auditoria_desde_search;
        $filtroFechaEnvioAuditoriaHastaSearch = $request->fecha_envio_auditoria_hasta_search;
        $filtroInstitucionIdSearch = json_decode($request->institucion_id_search, true);
        $filtroDocumentoExternoSearch = $request->documento_externo_search;
        $filtroTipoFirmaIdSearch = json_decode($request->tipo_firma_id_search, true);
        $filtroObservacionSearch = $request->observacion_search;
        $filtroNumeroQuipuxSearch = $request->numero_quipux_search;
        $filtroEstadoTramiteIdSearch = json_decode($request->estado_tramite_id_search, true);
        $filtroFechaDevolucionAuditoriaDesdeSearch = $request->fecha_devolucion_auditoria_desde_search;
        $filtroFechaDevolucionAuditoriaHastaSearch = $request->fecha_devolucion_auditoria_hasta_search;
        $filtroFechaDevolucionPrestadorDesdeSearch = $request->fecha_devolucion_prestador_desde_search;
        $filtroFechaDevolucionPrestadorHastaSearch = $request->fecha_devolucion_prestador_hasta_search;
        $filtroObservacionDevolucionAuditoriaSearch = $request->observacion_devolucion_auditoria_search;
        $filtroObservacionDevolucionPrestadorSearch = $request->observacion_devolucion_prestador_search;
        $filtroResponsableSearch = json_decode($request->responsable_id_search, true);
        
        if(isset($filtroFechaRegistroDesdeSearch) && !empty($filtroFechaRegistroDesdeSearch)){
            $oficios = $oficios->where('fecha_registro', '>=', $filtroFechaRegistroDesdeSearch);
        }
        if(isset($filtroFechaRegistroHastaSearch) && !empty($filtroFechaRegistroHastaSearch)){
            $oficios = $oficios->where('fecha_registro', '<=', $filtroFechaRegistroHastaSearch);
        }
        if(isset($filtroTipoIdSearch) && !empty($filtroTipoIdSearch)){
            $oficios = $oficios->whereIn('tipo_id', $filtroTipoIdSearch);
        }
        if(isset($filtroRucSearch) && !empty($filtroRucSearch)){
            $oficios = $oficios->where('ruc', 'like', '%'.$filtroRucSearch.'%');
        }
        if(isset($filtroNumeroEstablecimientoSearch) && !empty($filtroNumeroEstablecimientoSearch)){
            $oficios = $oficios->where('numero_establecimiento', 'like', '%'.$filtroNumeroEstablecimientoSearch.'%');
        }
        if(isset($filtroRazonSocialSearch) && !empty($filtroRazonSocialSearch)){
            $oficios = $oficios->where('razon_social', 'like', '%'.$filtroRazonSocialSearch.'%');
        }
        if(isset($filtroFechaRecepcionDesdeSearch) && !empty($filtroFechaRecepcionDesdeSearch)){
            $oficios = $oficios->where('fecha_recepcion', '>=', $filtroFechaRecepcionDesdeSearch);
        }
        if(isset($filtroFechaRecepcionHastaSearch) && !empty($filtroFechaRecepcionHastaSearch)){
            $oficios = $oficios->where('fecha_recepcion', '<=', $filtroFechaRecepcionHastaSearch);
        }
        if(isset($filtrotipoAtencionIdSearch) && !empty($filtrotipoAtencionIdSearch)){
            $oficios = $oficios->whereIn('tipo_atencion_id', $filtrotipoAtencionIdSearch);
        }
        if(isset($filtroProvinciaIdSearch) && !empty($filtroProvinciaIdSearch)){
            $oficios = $oficios->whereIn('provincia_id', $filtroProvinciaIdSearch);
        }
        if(isset($filtroFechaServicioDesdeSearch) && !empty($filtroFechaServicioDesdeSearch)){
            $oficios = $oficios->where('fecha_servicio', '>=', $filtroFechaServicioDesdeSearch);
        }
        if(isset($filtroFechaServicioHastaSearch) && !empty($filtroFechaServicioHastaSearch)){
            $oficios = $oficios->where('fecha_servicio', '<=', $filtroFechaServicioHastaSearch);
        }
        if(isset($filtroNumeroCasosSearch) && !empty($filtroNumeroCasosSearch)){
            $oficios = $oficios->where('numero_casos', $filtroNumeroCasosSearch);
        }
        if(isset($filtroMontoPlanillaSearch) && !empty($filtroMontoPlanillaSearch)){
            $oficios = $oficios->where('monto_planilla', $filtroMontoPlanillaSearch);
        }
        if(isset($filtroNumeroCajaAntSearch) && !empty($filtroNumeroCajaAntSearch)){
            $oficios = $oficios->where('numero_caja_ant', 'like', '%'.$filtroNumeroCajaAntSearch.'%');
        }
        if(isset($filtroNumeroCajaSearch) && !empty($filtroNumeroCajaSearch)){
            $oficios = $oficios->where('numero_caja', 'like', '%'.$filtroNumeroCajaSearch.'%');
        }
        if(isset($filtroTipoEstadoCajaIdSearch) && !empty($filtroTipoEstadoCajaIdSearch)){
            $oficios = $oficios->whereIn('estado_caja_id', $filtroTipoEstadoCajaIdSearch);
        }
        if(isset($filtroNumeroCajaAuditoriaSearch) && !empty($filtroNumeroCajaAuditoriaSearch)){
            $oficios = $oficios->where('numero_caja_auditoria', 'like', '%'.$filtroNumeroCajaAuditoriaSearch.'%');
        }
        if(isset($filtroFechaEnvioAuditoriaDesdeSearch) && !empty($filtroFechaEnvioAuditoriaDesdeSearch)){
            $oficios = $oficios->where('fecha_envio_auditoria','>=', $filtroFechaEnvioAuditoriaDesdeSearch);
        }
        if(isset($filtroFechaEnvioAuditoriaHastaSearch) && !empty($filtroFechaEnvioAuditoriaHastaSearch)){
            $oficios = $oficios->where('fecha_envio_auditoria','<=', $filtroFechaEnvioAuditoriaHastaSearch);
        }
        if(isset($filtroInstitucionIdSearch) && !empty($filtroInstitucionIdSearch)){
            $oficios = $oficios->whereIn('institucion_id', $filtroInstitucionIdSearch);
        }
        if(isset($filtroDocumentoExternoSearch) && !empty($filtroDocumentoExternoSearch)){
            $oficios = $oficios->where('documento_externo', 'like', '%'.$filtroDocumentoExternoSearch.'%');
        }
        if(isset($filtroTipoFirmaIdSearch) && !empty($filtroTipoFirmaIdSearch)){
            $oficios = $oficios->whereIn('tipo_firma_id', $filtroTipoFirmaIdSearch);
        }
        if(isset($filtroObservacionSearch) && !empty($filtroObservacionSearch)){
            $oficios = $oficios->where('observaciones', 'like', '%'.$filtroObservacionSearch.'%');
        }
        if(isset($filtroNumeroQuipuxSearch) && !empty($filtroNumeroQuipuxSearch)){
            $oficios = $oficios->where('numero_quipux', 'like', '%'.$filtroNumeroQuipuxSearch.'%');
        }
        if(isset($filtroEstadoTramiteIdSearch) && !empty($filtroEstadoTramiteIdSearch)){
            $oficios = $oficios->whereIn('estado_tramite_id', $filtroEstadoTramiteIdSearch);
        }
        if(isset($filtroFechaDevolucionAuditoriaDesdeSearch) && !empty($filtroFechaDevolucionAuditoriaDesdeSearch)){
            $oficios = $oficios->where('fecha_devolucion_auditoria', '>=', $filtroFechaDevolucionAuditoriaDesdeSearch);
        }
        if(isset($filtroFechaDevolucionAuditoriaHastaSearch) && !empty($filtroFechaDevolucionAuditoriaHastaSearch)){
            $oficios = $oficios->where('fecha_devolucion_auditoria', '<=', $filtroFechaDevolucionAuditoriaHastaSearch);
        }
        if(isset($filtroFechaDevolucionPrestadorDesdeSearch) && !empty($filtroFechaDevolucionPrestadorDesdeSearch)){
            $oficios = $oficios->where('fecha_devolucion_prestador', '>=', $filtroFechaDevolucionPrestadorDesdeSearch);
        }
        if(isset($filtroFechaDevolucionPrestadorHastaSearch) && !empty($filtroFechaDevolucionPrestadorHastaSearch)){
            $oficios = $oficios->where('fecha_devolucion_prestador', '<=', $filtroFechaDevolucionPrestadorHastaSearch);
        }
        if(isset($filtroObservacionDevolucionAuditoriaSearch) && !empty($filtroObservacionDevolucionAuditoriaSearch)){
            $oficios = $oficios->where('observaciones_devolucion_auditoria', 'like', '%'.$filtroObservacionDevolucionAuditoriaSearch.'%');
        }
        if(isset($filtroObservacionDevolucionPrestadorSearch) && !empty($filtroObservacionDevolucionPrestadorSearch)){
            $oficios = $oficios->where('observaciones_devolucion_prestador', 'like', '%'.$filtroObservacionDevolucionPrestadorSearch.'%');
        }
        if(isset($filtroResponsableSearch) && !empty($filtroResponsableSearch)){
            $oficios = $oficios->whereIn('responsable_id', $filtroResponsableSearch);
        }
        
        $oficios = $oficios->orderBy('id', 'desc')->get();
        $oficiosIds = $oficios->pluck('id');
        $oficiosFiles = File::whereIn('oficio_id',$oficiosIds)->get();

        $oficiosFiles = collect($oficiosFiles)->groupBy('oficio_id');

        $tipos = Tipo::all();
        $tipos_atencion = TipoAtencion::all();
        $tipos_estado_caja = TipoEstadoCaja::all();
        $provincias = Provincia::all();
        $instituciones = Institucion::all();
        $tipos_firma = TipoFirma::all();
        $estados_tramite = EstadoTramite::all();
        $responsables = Admin::all();

        $tipos_temp = [];
        foreach($tipos as $tipo){
            $tipos_temp[$tipo->id] = $tipo->nombre;
        }
        $tipos_atencion_temp = [];
        foreach($tipos_atencion as $tipo_atencion){
            $tipos_atencion_temp[$tipo_atencion->id] = $tipo_atencion->nombre;
        }
        $provincias_temp = [];
        foreach($provincias as $provincia){
            $provincias_temp[$provincia->id] = $provincia->nombre;
        }
        $instituciones_temp = [];
        foreach($instituciones as $institucion){
            $instituciones_temp[$institucion->id] = $institucion->nombre;
        }
        $tipos_firma_temp = [];
        foreach($tipos_firma as $tipo_firma){
            $tipos_firma_temp[$tipo_firma->id] = $tipo_firma->nombre;
        }
        $responsables_temp = [];
        foreach($responsables as $responsable){
            $responsables_temp[$responsable->id] = $responsable->name;
        }

        $tipos_estado_caja_temp = [];
        foreach($tipos_estado_caja as $tipo_estado_caja){
            $tipos_estado_caja_temp[$tipo_estado_caja->id] = $tipo_estado_caja->nombre;
        }

        $estados_tramite_temp = [];
        foreach($estados_tramite as $estado_tramite){
            $estados_tramite_temp[$estado_tramite->id] = $estado_tramite->nombre;
        }

        $responsable_id = Auth::id();

        foreach($oficios as $oficio){
            $oficio->tipo_nombre = array_key_exists($oficio->tipo_id, $tipos_temp) ? $tipos_temp[$oficio->tipo_id] : "";
            $oficio->tipo_atencion_nombre = array_key_exists($oficio->tipo_atencion_id, $tipos_atencion_temp) ? $tipos_atencion_temp[$oficio->tipo_atencion_id] : "";
            $oficio->provincia_nombre = array_key_exists($oficio->provincia_id, $provincias_temp) ? $provincias_temp[$oficio->provincia_id] : "";
            $oficio->institucion_nombre = array_key_exists($oficio->institucion_id, $instituciones_temp) ? $instituciones_temp[$oficio->institucion_id] : "";
            $oficio->tipo_firma_nombre = array_key_exists($oficio->tipo_firma_id, $tipos_firma_temp) ? $tipos_firma_temp[$oficio->tipo_firma_id] : "";
            $oficio->responsable_nombre = array_key_exists($oficio->responsable_id, $responsables_temp) ? $responsables_temp[$oficio->responsable_id] : "";
            $oficio->tipo_estado_caja_nombre = array_key_exists($oficio->estado_caja_id, $tipos_estado_caja_temp) ? $tipos_estado_caja_temp[$oficio->estado_caja_id] : "";
            $oficio->estado_tramite_nombre = array_key_exists($oficio->estado_tramite_id, $estados_tramite_temp) ? $estados_tramite_temp[$oficio->estado_tramite_id] : "";
            $oficio->esCreadorRegistro = $responsable_id == $oficio->responsable_id ? true : false;
            //$oficio->files = $oficio->files;
        }

        $cajasAbiertas = Oficio::where('estado_caja_id',1)->where('responsable_id',$responsable_id)->groupBy('numero_caja')->get(["numero_caja"]);
        $cajasCerradasp1 = Oficio::where('estado_caja_id',2)->where('numero_caja_auditoria','=','')->groupBy('numero_caja')->select('numero_caja');
        $cajasCerradasp2 = Oficio::where('estado_caja_id',2)->where('fecha_envio_auditoria','=','')->groupBy('numero_caja')->select('numero_caja');

        $cajasCerradas = $cajasCerradasp1->union($cajasCerradasp2)->distinct()->get();

        $data['oficios'] = $oficios;
        $data['oficiosFiles'] = $oficiosFiles;
        $data['tipos'] = $tipos;
        $data['tipos_atencion'] = $tipos_atencion;
        $data['tipos_estado_caja'] = $tipos_estado_caja;
        $data['estados_tramite'] = $estados_tramite;
        $data['provincias'] = $provincias;
        $data['instituciones'] = $instituciones;
        $data['tipos_firma'] = $tipos_firma;
        $data['responsables'] = $responsables;
        $data['cajasAbiertas'] = $cajasAbiertas;
        $data['cajasCerradas'] = $cajasCerradas;
        $data['roles'] = Role::all();
  
        return response()->json($data);
    }

    public function getPrestadorSaludByRuc(Request $request): JsonResponse
    {
        if($request->tipo_busqueda == "ruc"){
            $data['prestadoresSalud'] = PrestadorSalud::where("ruc", $request->valor_busqueda)
            ->get(["id" ,"ruc", "establecimiento", "prestador1", "prestador2", "prestador_salud", "provincia_id","publico_privado_achpe"]);
        }else if($request->tipo_busqueda == "prestador_salud"){
            $data['prestadoresSalud'] = PrestadorSalud::where("prestador_salud",'like', '%'. $request->valor_busqueda .'%')
            ->get(["id" ,"ruc", "establecimiento", "prestador1", "prestador2", "prestador_salud", "provincia_id","publico_privado_achpe"]);
        }
  
        return response()->json($data);
    }

    public function download(string $fileName)
    {
        if(public_path('uploads/oficios/'.$fileName)){
            $myFile = public_path('uploads/oficios/'.$fileName);

            $headers = ['Content-Type: application/pdf'];
    
            $newName = $fileName;
    
            return response()->download($myFile, $newName, $headers);
        }
    }

    public function duplicar(int $id): Renderable
    {
        $this->checkAuthorization(auth()->user(), ['oficio.create']);
        $this->checkAuthorization(auth()->user(), ['oficio.duplicar']);

        $oficio = Oficio::findOrFail($id);

        $tipos = Tipo::get(["nombre", "id"])->pluck('nombre','id');
        $tiposAtencion = TipoAtencion::get(["nombre", "id"])->pluck('nombre','id');
        $tiposEstadoCaja = TipoEstadoCaja::get(["nombre", "id"])->pluck('nombre','id');
        $provincias = Provincia::get(["nombre", "id"])->pluck('nombre','id');
        $instituciones = Institucion::get(["nombre", "id"])->pluck('nombre','id');
        $tiposFirma = TipoFirma::get(["nombre", "id"])->pluck('nombre','id');
        $estadosTramite = EstadoTramite::get(["nombre", "id"])->pluck('nombre','id');
        $responsables = Admin::get(["name", "id"])->pluck('nombre','id');

        $responsable_id = Auth::id();

        $usuario = Admin::where('id',$responsable_id)->first();

        $oficioPublico = Oficio::where('numero_caja', 'like', '%PU%')->where('estado_caja_id',2)->where('responsable_id',$responsable_id)->orderBy('id', 'DESC')->first();
        if(!isset($oficioPublico)){
            $ultimoSecuencialPublico = 0;
            $siguienteSecuencialPu = $ultimoSecuencialPublico + 1;
            $siguienteSecuencialPu = str_pad((string)$siguienteSecuencialPu, 3, "0", STR_PAD_LEFT).$usuario->initials;
        }else{
            $ultimoSecuencialPublico = substr($oficioPublico->numero_caja, 2);
            $ultimoSecuencialPublico = substr($ultimoSecuencialPublico, 0, -2);
            $longitudPu = strlen($ultimoSecuencialPublico);
            $ultimoSecuencialPublico = intval($ultimoSecuencialPublico);
            $siguienteSecuencialPu = 0;
            if($ultimoSecuencialPublico <= 999){
                $siguienteSecuencialPu = $ultimoSecuencialPublico + 1;
                $siguienteSecuencialPu = str_pad((string)$siguienteSecuencialPu, $longitudPu, "0", STR_PAD_LEFT).$usuario->initials;
            }else{
                $siguienteSecuencialPu = $ultimoSecuencialPublico + 1;
                $siguienteSecuencialPu = str_pad((string)$siguienteSecuencialPu, $longitudPu + 1, "0", STR_PAD_LEFT).$usuario->initials;
            }
        }
        
        $oficioPrivado = Oficio::where('numero_caja', 'like', '%PR%')->where('estado_caja_id',2)->where('responsable_id',$responsable_id)->orderBy('id', 'DESC')->first();
        if(!isset($oficioPrivado)){
            $ultimoSecuencialPrivado = 0;
            $siguienteSecuencialPr = $ultimoSecuencialPrivado + 1;
            $siguienteSecuencialPr = str_pad((string)$siguienteSecuencialPr, 3, "0", STR_PAD_LEFT).$usuario->initials;
        }else{
            $ultimoSecuencialPrivado = substr($oficioPrivado->numero_caja, 2);
            $ultimoSecuencialPrivado = substr($ultimoSecuencialPrivado, 0, -2);
            $longitudPr = strlen($ultimoSecuencialPrivado);
            $ultimoSecuencialPrivado = intval($ultimoSecuencialPrivado);
            $siguienteSecuencialPr = 0;
            if($ultimoSecuencialPrivado <= 999){
                $siguienteSecuencialPr = $ultimoSecuencialPrivado + 1;
                $siguienteSecuencialPr = str_pad((string)$siguienteSecuencialPr, $longitudPr, "0", STR_PAD_LEFT).$usuario->initials;
            }else{
                $siguienteSecuencialPr = $ultimoSecuencialPrivado + 1;
                $siguienteSecuencialPr = str_pad((string)$siguienteSecuencialPr, $longitudPr + 1, "0", STR_PAD_LEFT).$usuario->initials;
            }
        }

        return view('backend.pages.oficios.duplicar', [
            'oficio' => $oficio,
            'tipos' => $tipos,
            'tiposAtencion' => $tiposAtencion,
            'tiposEstadoCaja' => $tiposEstadoCaja,
            'provincias' => $provincias,
            'instituciones' => $instituciones,
            'tiposFirma' => $tiposFirma,
            'estadosTramite' => $estadosTramite,
            'responsables' => $responsables,
            'ultimoSecuencialPublico' => $ultimoSecuencialPublico,
            'siguienteSecuencialPu' => $siguienteSecuencialPu,
            'ultimoSecuencialPrivado' => $ultimoSecuencialPrivado,
            'siguienteSecuencialPr' => $siguienteSecuencialPr,
            'roles' => Role::all(),
        ]);
    }

    public function asignarNumeroCajaAuditoria(Request $request): JsonResponse
    {
        $this->checkAuthorization(auth()->user(), ['oficio.edit']);

        $numeroCajaAuditoria = $request->valor_numero_caja_auditoria;
        $ids = json_decode($request->selected_table_items, true);

        if(isset($numeroCajaAuditoria) && !empty($numeroCajaAuditoria)){
            $update = ['numero_caja_auditoria' => $numeroCajaAuditoria];
            $oficios = Oficio::whereIn('id', $ids)->update($update);
            $data['oficios'] = $oficios;
        }

        $data['numeroCajaAuditoria'] = $numeroCajaAuditoria;

        session()->flash('success', 'Actualización satisfactoria.');
        return response()->json($data);
    }

    public function asignarFechaEnvioAuditoria(Request $request): JsonResponse
    {
        $this->checkAuthorization(auth()->user(), ['oficio.edit']);

        $fechaEnvioAuditoria = $request->valor_fecha_envio_auditoria;
        $ids = json_decode($request->selected_table_items, true);

        if(isset($fechaEnvioAuditoria) && !empty($fechaEnvioAuditoria)){
            $update = ['fecha_envio_auditoria' => $fechaEnvioAuditoria];
            $oficios = Oficio::whereIn('id', $ids)->update($update);
            $data['oficios'] = $oficios;
        }
        
        $data['fechaEnvioAuditoria'] = $fechaEnvioAuditoria;

        session()->flash('success', 'Actualización satisfactoria.');
        return response()->json($data);
    }

    public function cerrarNumeroCaja(Request $request): JsonResponse
    {
        $this->checkAuthorization(auth()->user(), ['oficio.edit']);

        $numeroCaja = $request->valor_numero_caja;

        if(isset($numeroCaja) && !empty($numeroCaja)){
            $update = ['estado_caja_id' => '2'];
            $oficios = Oficio::where('numero_caja', $numeroCaja)->update($update);
            $data['oficios'] = $oficios;
        }
        
        $data['numeroCaja'] = $numeroCaja;

        session()->flash('success', 'Actualización satisfactoria.');
        return response()->json($data);
    }

    public function asignarPorNumeroCaja(Request $request): JsonResponse
    {
        $this->checkAuthorization(auth()->user(), ['oficio.edit']);

        $numerosCajas = json_decode($request->valor_numero_caja, true);
        $numeroCajaAuditoria = $request->valor_numero_caja_auditoria;
        $fechaEnvioAuditoria = $request->valor_fecha_envio_auditoria;

        if(isset($numeroCajaAuditoria) && !empty($numeroCajaAuditoria) && isset($fechaEnvioAuditoria) && !empty($fechaEnvioAuditoria)){
            $update = [
                'numero_caja_auditoria' => $numeroCajaAuditoria,
                'fecha_envio_auditoria' => $fechaEnvioAuditoria
            ];
            $oficios = Oficio::whereIn('numero_caja', $numerosCajas)->update($update);
            $data['oficios'] = $oficios;
        }
        
        $data['numerosCajas'] = $numerosCajas;

        return response()->json($data);
    }

    public function getOficiosByPagination(Request $request): JsonResponse
    {
        $this->checkAuthorization(auth()->user(), ['oficio.view']);

        // Page Length
        $pageNumber = ( $request->start / $request->length )+1;
        $pageLength = $request->length;
        $skip       = ($pageNumber-1) * $pageLength;

        // Page Order
        $orderColumnIndex = $request->order[0]['column'] ?? '0';
        $orderBy = $request->order[0]['dir'] ?? 'desc';

        $oficios = Oficio::where('id',">",0);

        $filtroFechaRegistroDesdeSearch = $request->fecha_registro_desde_search;
        $filtroFechaRegistroHastaSearch = $request->fecha_registro_hasta_search;
        $filtroTipoIdSearch = json_decode($request->tipo_id_search, true);
        $filtroRucSearch = $request->ruc_search;
        $filtroNumeroEstablecimientoSearch = $request->numero_establecimiento_search;
        $filtroRazonSocialSearch = $request->razon_social_search;
        $filtroFechaRecepcionDesdeSearch = $request->fecha_recepcion_desde_search;
        $filtroFechaRecepcionHastaSearch = $request->fecha_recepcion_hasta_search;
        $filtrotipoAtencionIdSearch = json_decode($request->tipo_atencion_id_search, true);
        $filtroProvinciaIdSearch = json_decode($request->provincia_id_search, true);
        $filtroFechaServicioDesdeSearch = $request->fecha_servicio_desde_search;
        $filtroFechaServicioHastaSearch = $request->fecha_servicio_hasta_search;
        $filtroNumeroCasosSearch = $request->numero_casos_search;
        $filtroMontoPlanillaSearch = $request->monto_planilla_search;
        $filtroNumeroCajaAntSearch = $request->numero_caja_ant_search;
        $filtroNumeroCajaSearch = $request->numero_caja_search;
        $filtroTipoEstadoCajaIdSearch = json_decode($request->tipo_estado_caja_id_search, true);
        $filtroNumeroCajaAuditoriaSearch = $request->numero_caja_auditoria_search;
        $filtroFechaEnvioAuditoriaDesdeSearch = $request->fecha_envio_auditoria_desde_search;
        $filtroFechaEnvioAuditoriaHastaSearch = $request->fecha_envio_auditoria_hasta_search;
        $filtroInstitucionIdSearch = json_decode($request->institucion_id_search, true);
        $filtroDocumentoExternoSearch = $request->documento_externo_search;
        $filtroTipoFirmaIdSearch = json_decode($request->tipo_firma_id_search, true);
        $filtroObservacionSearch = $request->observacion_search;
        $filtroNumeroQuipuxSearch = $request->numero_quipux_search;
        $filtroEstadoTramiteIdSearch = json_decode($request->estado_tramite_id_search, true);
        $filtroFechaDevolucionAuditoriaDesdeSearch = $request->fecha_devolucion_auditoria_desde_search;
        $filtroFechaDevolucionAuditoriaHastaSearch = $request->fecha_devolucion_auditoria_hasta_search;
        $filtroFechaDevolucionPrestadorDesdeSearch = $request->fecha_devolucion_prestador_desde_search;
        $filtroFechaDevolucionPrestadorHastaSearch = $request->fecha_devolucion_prestador_hasta_search;
        $filtroObservacionDevolucionAuditoriaSearch = $request->observacion_devolucion_auditoria_search;
        $filtroObservacionDevolucionPrestadorSearch = $request->observacion_devolucion_prestador_search;
        $filtroResponsableSearch = json_decode($request->responsable_id_search, true);
        
        if(isset($filtroFechaRegistroDesdeSearch) && !empty($filtroFechaRegistroDesdeSearch)){
            $oficios = $oficios->where('fecha_registro', '>=', $filtroFechaRegistroDesdeSearch);
        }
        if(isset($filtroFechaRegistroHastaSearch) && !empty($filtroFechaRegistroHastaSearch)){
            $oficios = $oficios->where('fecha_registro', '<=', $filtroFechaRegistroHastaSearch);
        }
        if(isset($filtroTipoIdSearch) && !empty($filtroTipoIdSearch)){
            $oficios = $oficios->whereIn('tipo_id', $filtroTipoIdSearch);
        }
        if(isset($filtroRucSearch) && !empty($filtroRucSearch)){
            $oficios = $oficios->where('ruc', 'like', '%'.$filtroRucSearch.'%');
        }
        if(isset($filtroNumeroEstablecimientoSearch) && !empty($filtroNumeroEstablecimientoSearch)){
            $oficios = $oficios->where('numero_establecimiento', 'like', '%'.$filtroNumeroEstablecimientoSearch.'%');
        }
        if(isset($filtroRazonSocialSearch) && !empty($filtroRazonSocialSearch)){
            $oficios = $oficios->where('razon_social', 'like', '%'.$filtroRazonSocialSearch.'%');
        }
        if(isset($filtroFechaRecepcionDesdeSearch) && !empty($filtroFechaRecepcionDesdeSearch)){
            $oficios = $oficios->where('fecha_recepcion', '>=', $filtroFechaRecepcionDesdeSearch);
        }
        if(isset($filtroFechaRecepcionHastaSearch) && !empty($filtroFechaRecepcionHastaSearch)){
            $oficios = $oficios->where('fecha_recepcion', '<=', $filtroFechaRecepcionHastaSearch);
        }
        if(isset($filtrotipoAtencionIdSearch) && !empty($filtrotipoAtencionIdSearch)){
            $oficios = $oficios->whereIn('tipo_atencion_id', $filtrotipoAtencionIdSearch);
        }
        if(isset($filtroProvinciaIdSearch) && !empty($filtroProvinciaIdSearch)){
            $oficios = $oficios->whereIn('provincia_id', $filtroProvinciaIdSearch);
        }
        if(isset($filtroFechaServicioDesdeSearch) && !empty($filtroFechaServicioDesdeSearch)){
            $oficios = $oficios->where('fecha_servicio', '>=', $filtroFechaServicioDesdeSearch);
        }
        if(isset($filtroFechaServicioHastaSearch) && !empty($filtroFechaServicioHastaSearch)){
            $oficios = $oficios->where('fecha_servicio', '<=', $filtroFechaServicioHastaSearch);
        }
        if(isset($filtroNumeroCasosSearch) && !empty($filtroNumeroCasosSearch)){
            $oficios = $oficios->where('numero_casos', $filtroNumeroCasosSearch);
        }
        if(isset($filtroMontoPlanillaSearch) && !empty($filtroMontoPlanillaSearch)){
            $oficios = $oficios->where('monto_planilla', $filtroMontoPlanillaSearch);
        }
        if(isset($filtroNumeroCajaAntSearch) && !empty($filtroNumeroCajaAntSearch)){
            $oficios = $oficios->where('numero_caja_ant', 'like', '%'.$filtroNumeroCajaAntSearch.'%');
        }
        if(isset($filtroNumeroCajaSearch) && !empty($filtroNumeroCajaSearch)){
            $oficios = $oficios->where('numero_caja', 'like', '%'.$filtroNumeroCajaSearch.'%');
        }
        if(isset($filtroTipoEstadoCajaIdSearch) && !empty($filtroTipoEstadoCajaIdSearch)){
            $oficios = $oficios->whereIn('estado_caja_id', $filtroTipoEstadoCajaIdSearch);
        }
        if(isset($filtroNumeroCajaAuditoriaSearch) && !empty($filtroNumeroCajaAuditoriaSearch)){
            $oficios = $oficios->where('numero_caja_auditoria', 'like', '%'.$filtroNumeroCajaAuditoriaSearch.'%');
        }
        if(isset($filtroFechaEnvioAuditoriaDesdeSearch) && !empty($filtroFechaEnvioAuditoriaDesdeSearch)){
            $oficios = $oficios->where('fecha_envio_auditoria','>=', $filtroFechaEnvioAuditoriaDesdeSearch);
        }
        if(isset($filtroFechaEnvioAuditoriaHastaSearch) && !empty($filtroFechaEnvioAuditoriaHastaSearch)){
            $oficios = $oficios->where('fecha_envio_auditoria','<=', $filtroFechaEnvioAuditoriaHastaSearch);
        }
        if(isset($filtroInstitucionIdSearch) && !empty($filtroInstitucionIdSearch)){
            $oficios = $oficios->whereIn('institucion_id', $filtroInstitucionIdSearch);
        }
        if(isset($filtroDocumentoExternoSearch) && !empty($filtroDocumentoExternoSearch)){
            $oficios = $oficios->where('documento_externo', 'like', '%'.$filtroDocumentoExternoSearch.'%');
        }
        if(isset($filtroTipoFirmaIdSearch) && !empty($filtroTipoFirmaIdSearch)){
            $oficios = $oficios->whereIn('tipo_firma_id', $filtroTipoFirmaIdSearch);
        }
        if(isset($filtroObservacionSearch) && !empty($filtroObservacionSearch)){
            $oficios = $oficios->where('observaciones', 'like', '%'.$filtroObservacionSearch.'%');
        }
        if(isset($filtroNumeroQuipuxSearch) && !empty($filtroNumeroQuipuxSearch)){
            $oficios = $oficios->where('numero_quipux', 'like', '%'.$filtroNumeroQuipuxSearch.'%');
        }
        if(isset($filtroEstadoTramiteIdSearch) && !empty($filtroEstadoTramiteIdSearch)){
            $oficios = $oficios->whereIn('estado_tramite_id', $filtroEstadoTramiteIdSearch);
        }
        if(isset($filtroFechaDevolucionAuditoriaDesdeSearch) && !empty($filtroFechaDevolucionAuditoriaDesdeSearch)){
            $oficios = $oficios->where('fecha_devolucion_auditoria', '>=', $filtroFechaDevolucionAuditoriaDesdeSearch);
        }
        if(isset($filtroFechaDevolucionAuditoriaHastaSearch) && !empty($filtroFechaDevolucionAuditoriaHastaSearch)){
            $oficios = $oficios->where('fecha_devolucion_auditoria', '<=', $filtroFechaDevolucionAuditoriaHastaSearch);
        }
        if(isset($filtroFechaDevolucionPrestadorDesdeSearch) && !empty($filtroFechaDevolucionPrestadorDesdeSearch)){
            $oficios = $oficios->where('fecha_devolucion_prestador', '>=', $filtroFechaDevolucionPrestadorDesdeSearch);
        }
        if(isset($filtroFechaDevolucionPrestadorHastaSearch) && !empty($filtroFechaDevolucionPrestadorHastaSearch)){
            $oficios = $oficios->where('fecha_devolucion_prestador', '<=', $filtroFechaDevolucionPrestadorHastaSearch);
        }
        if(isset($filtroObservacionDevolucionAuditoriaSearch) && !empty($filtroObservacionDevolucionAuditoriaSearch)){
            $oficios = $oficios->where('observaciones_devolucion_auditoria', 'like', '%'.$filtroObservacionDevolucionAuditoriaSearch.'%');
        }
        if(isset($filtroObservacionDevolucionPrestadorSearch) && !empty($filtroObservacionDevolucionPrestadorSearch)){
            $oficios = $oficios->where('observaciones_devolucion_prestador', 'like', '%'.$filtroObservacionDevolucionPrestadorSearch.'%');
        }
        if(isset($filtroResponsableSearch) && !empty($filtroResponsableSearch)){
            $oficios = $oficios->whereIn('responsable_id', $filtroResponsableSearch);
        }

        $recordsFiltered = $oficios->count();

        $totalNumeroCasos = $oficios->sum('numero_casos');
        $totalMontoPlanilla = $oficios->sum('monto_planilla');

        $orderByName = 'id';
        switch($orderColumnIndex){
            case '0':
                $orderByName = 'id';
                break;
            case '1':
                $orderByName = 'fecha_registro';
                break;
            case '2':
                $orderByName = 'tipo_id';
                break;
            case '3':
                $orderByName = 'ruc';
                break;
            case '4':
                $orderByName = 'numero_establecimiento';
                break;
            case '5':
                $orderByName = 'razon_social';
                break;
            case '6':
                $orderByName = 'fecha_recepcion';
                break;
            case '7':
                $orderByName = 'tipo_atencion_id';
                break;
            case '8':
                $orderByName = 'provincia_id';
                break;
            case '9':
                $orderByName = 'fecha_servicio';
                break;
            case '10':
                $orderByName = 'numero_casos';
                break;
            case '11':
                $orderByName = 'monto_planilla';
                break;
            case '12':
                $orderByName = 'numero_caja_ant';
                break;
            case '14':
                $orderByName = 'numero_caja';
                break;
            case '15':
                $orderByName = 'tipo_estado_caja_id';
                break;
            case '16':
                $orderByName = 'numero_caja_auditoria';
                break;
            case '17':
                $orderByName = 'fecha_envio_auditoria';
                break;
            case '18':
                $orderByName = 'institucion_id';
                break;
            case '19':
                $orderByName = 'documento_externo';
                break;
            case '20':
                $orderByName = 'tipo_firma_id';
                break;
            case '21':
                $orderByName = 'observaciones';
                break;                    
            case '22':
                $orderByName = 'numero_quipux';
                break;
            case '23':
                $orderByName = 'estado_tramite_id';
                break;
            case '24':
                $orderByName = 'fecha_devolucion_auditoria';
                break;
            case '25':
                $orderByName = 'observaciones_devolucion_auditoria';
                break;
            case '26':
                $orderByName = 'fecha_devolucion_prestador';
                break;
            case '27':
                $orderByName = 'observaciones_devolucion_prestador';
                break;
            case '28':
                $orderByName = 'responsable_id';
                break;
            default:
                $orderByName = 'id';
        
        }
        
        $registrosOf = $oficios->get();

        $oficiosIds = $registrosOf->pluck('id');
        $oficiosFiles = File::whereIn('oficio_id',$oficiosIds)->get();

        $oficiosFiles = collect($oficiosFiles)->groupBy('oficio_id');
        
        //$oficios = $oficios->orderBy('id', 'desc')->get();

        $tipos = Tipo::all();
        $tipos_atencion = TipoAtencion::all();
        $tipos_estado_caja = TipoEstadoCaja::all();
        $provincias = Provincia::all();
        $instituciones = Institucion::all();
        $tipos_firma = TipoFirma::all();
        $estados_tramite = EstadoTramite::all();
        $responsables = Admin::all();

        $tipos_temp = [];
        foreach($tipos as $tipo){
            $tipos_temp[$tipo->id] = $tipo->nombre;
        }
        $tipos_atencion_temp = [];
        foreach($tipos_atencion as $tipo_atencion){
            $tipos_atencion_temp[$tipo_atencion->id] = $tipo_atencion->nombre;
        }
        $provincias_temp = [];
        foreach($provincias as $provincia){
            $provincias_temp[$provincia->id] = $provincia->nombre;
        }
        $instituciones_temp = [];
        foreach($instituciones as $institucion){
            $instituciones_temp[$institucion->id] = $institucion->nombre;
        }
        $tipos_firma_temp = [];
        foreach($tipos_firma as $tipo_firma){
            $tipos_firma_temp[$tipo_firma->id] = $tipo_firma->nombre;
        }
        $responsables_temp = [];
        foreach($responsables as $responsable){
            $responsables_temp[$responsable->id] = $responsable->name;
        }

        $tipos_estado_caja_temp = [];
        foreach($tipos_estado_caja as $tipo_estado_caja){
            $tipos_estado_caja_temp[$tipo_estado_caja->id] = $tipo_estado_caja->nombre;
        }
        $estados_tramite_temp = [];
        foreach($estados_tramite as $estado_tramite){
            $estados_tramite_temp[$estado_tramite->id] = $estado_tramite->nombre;
        }

        $responsable_id = Auth::id();

        foreach($registrosOf as $oficio){
            $oficio->tipo_nombre = array_key_exists($oficio->tipo_id, $tipos_temp) ? $tipos_temp[$oficio->tipo_id] : "";
            $oficio->tipo_atencion_nombre = array_key_exists($oficio->tipo_atencion_id, $tipos_atencion_temp) ? $tipos_atencion_temp[$oficio->tipo_atencion_id] : "";
            $oficio->provincia_nombre = array_key_exists($oficio->provincia_id, $provincias_temp) ? $provincias_temp[$oficio->provincia_id] : "";
            $oficio->institucion_nombre = array_key_exists($oficio->institucion_id, $instituciones_temp) ? $instituciones_temp[$oficio->institucion_id] : "";
            $oficio->tipo_firma_nombre = array_key_exists($oficio->tipo_firma_id, $tipos_firma_temp) ? $tipos_firma_temp[$oficio->tipo_firma_id] : "";
            $oficio->responsable_nombre = array_key_exists($oficio->responsable_id, $responsables_temp) ? $responsables_temp[$oficio->responsable_id] : "";
            $oficio->tipo_estado_caja_nombre = array_key_exists($oficio->estado_caja_id, $tipos_estado_caja_temp) ? $tipos_estado_caja_temp[$oficio->estado_caja_id] : "";
            $oficio->estado_tramite_nombre = array_key_exists($oficio->estado_tramite_id, $estados_tramite_temp) ? $estados_tramite_temp[$oficio->estado_tramite_id] : "";
            $oficio->esCreadorRegistro = $responsable_id == $oficio->responsable_id ? true : false;
            //$oficio->files = $oficio->files;
        }

        switch($orderColumnIndex){
            case '2':
                $sorted = $registrosOf->sortBy([['tipo_nombre', $orderBy]]);
                $registrosOf = $sorted->values()->all();
                break;
            case '7':
                $sorted = $registrosOf->sortBy([['tipo_atencion_nombre', $orderBy]]);
                $registrosOf = $sorted->values()->all();
                break;
            case '8':
                $sorted = $registrosOf->sortBy([['provincia_nombre', $orderBy]]);
                $registrosOf = $sorted->values()->all();
                break;
            case '15':
                $sorted = $registrosOf->sortBy([['tipo_estado_caja_nombre', $orderBy]]);
                $registrosOf = $sorted->values()->all();
                break;
            case '18':
                $sorted = $registrosOf->sortBy([['institucion_nombre', $orderBy]]);
                $registrosOf = $sorted->values()->all();
                break;
            case '20':
                $sorted = $registrosOf->sortBy([['tipo_firma_nombre', $orderBy]]);
                $registrosOf = $sorted->values()->all();
                break;
            case '23':
                $sorted = $registrosOf->sortBy([['estado_tramite_nombre', $orderBy]]);
                $registrosOf = $sorted->values()->all();
                break;
            case '28':
                $sorted = $registrosOf->sortBy([['responsable_nombre', $orderBy]]);
                $registrosOf = $sorted->values()->all();
                break;
            default:
                $sorted = $registrosOf->sortBy([[$orderByName, $orderBy]]);
                $registrosOf = $sorted->values()->all();
        }

        $cajasAbiertas = Oficio::where('estado_caja_id',1)->where('responsable_id',$responsable_id)->groupBy('numero_caja')->get(["numero_caja"]);
        $cajasCerradasp1 = Oficio::where('estado_caja_id',2)->where('numero_caja_auditoria','=','')->groupBy('numero_caja')->select('numero_caja');
        $cajasCerradasp2 = Oficio::where('estado_caja_id',2)->where('fecha_envio_auditoria','=','')->groupBy('numero_caja')->select('numero_caja');

        $cajasCerradas = $cajasCerradasp1->union($cajasCerradasp2)->distinct()->get();
  
        $registrosOf = collect($registrosOf);
        $registrosOf = $registrosOf->skip($skip)->take($pageLength);
        $recordsTotal = $registrosOf->count();

        $datosOf = [];
        foreach($registrosOf as $rb){
            array_push($datosOf, $rb);
        }
            
        return response()->json(["draw"=> $request->draw, "recordsTotal"=> $recordsTotal, "recordsFiltered" => $recordsFiltered, 'data' => $datosOf, 'totalNumeroCasos' => $totalNumeroCasos, 'totalMontoPlanilla' => $totalMontoPlanilla, 'cajasAbiertas' => $cajasAbiertas, 'cajasCerradas' => $cajasCerradas,'tipos' => $tipos_temp, 'tipos_atencion' => $tipos_atencion_temp, 'tipos_estado_caja' => $tipos_estado_caja_temp, 'provincias' => $provincias_temp, 'instituciones' => $instituciones_temp, 'tipos_firma' => $tipos_firma_temp, 'estados_tramite' => $estados_tramite_temp, 'responsables' => $responsables_temp,'oficiosFiles' => $oficiosFiles], 200);
    }

    /*public function asignarNumeroCajasEnviadasAuditoria(Request $request): JsonResponse
    {
        $this->checkAuthorization(auth()->user(), ['oficio.edit']);

        $numeroCaja = $request->valor_numero_caja;
        $ids = json_decode($request->selected_table_items, true);

        $update = ['fecha_envio_auditoria' => $fechaEnvioAuditoria];
        $oficios = Oficio::whereIn('id', $ids)->update($update);
        $data['oficios'] = $oficios;
        $data['fechaEnvioAuditoria'] = $fechaEnvioAuditoria;

        session()->flash('success', 'Actualización satisfactoria.');
        return response()->json($data);
    }*/

}