<?php
    
declare(strict_types=1);

namespace App\Http\Controllers\Backend;
    
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\TramiteRequest;
use App\Mail\Notification;
use App\Models\Admin;
use App\Models\Beneficiario;
use App\Models\CamposPorProceso;
use App\Models\Catalogo;
use App\Models\Proceso;
use App\Models\Tramite;
use App\Models\SecuenciaProceso;
use App\Models\TipoCatalogo;
use App\Models\TrazabilidadTramite;
use App\Rules\ReCaptcha;
use Carbon\Carbon;
use ErrorException;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Facades\Validator;
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
use Symfony\Component\HttpFoundation\StreamedResponse;
use Throwable;

class ConsultaCiudadanaTramitesController extends Controller
{
    public function index(): Renderable
    {
        $procesos = Proceso::where('estatus','ACTIVO')->get(["nombre", "id"]);

        return view('backend.pages.consultaCiudadanaTramites.index', [
            'procesos' => $procesos,
        ]);
    }

    public function getTrazabilidadByTramite(Request $request): JsonResponse
    {
        try{

        
        
            $recaptchaResponse = $request->input('g_recaptcha');
            $validatedData = $request->validate([
                'g_recaptcha' => ['required', new ReCaptcha($recaptchaResponse)],
            ]);

            $tramiteId = $request->tramite_id;
            $tramite = Tramite::find($tramiteId);
            if(!is_null($tramite)){
                $secuenciaProceso = SecuenciaProceso::findOrFail($tramite->secuencia_proceso_id);
                $listaCampos = collect($secuenciaProceso->configuracion_campos)->sortBy('seccion_campo');
                $secuenciasProceso = $secuenciaProceso->get();

                $data['listaCampos'] = $listaCampos;

                $trazabilidad_tramite = TrazabilidadTramite::where('tramite_id', $tramiteId)->whereIn('tipo',['CREACION','CAMBIO SECCION','CONDICINAL','FINALIZACION'])->get(["id","secuencia_proceso_id","funcionario_actual_id","estatus","tipo","created_at"]);

                $secuencias_proceso_temp = [];
                foreach($secuenciasProceso as $secuencia){
                    $secuencias_proceso_temp[$secuencia->id] = $secuencia->nombre;
                }

                $funcionarios = Admin::all();

                $funcionarios_temp = [];
                foreach($funcionarios as $creador){
                    $funcionarios_temp[$creador->id] = $creador->name;
                }

                foreach($trazabilidad_tramite as $trazabilidad){
                    $trazabilidad->secuencia_proceso_nombre = array_key_exists($trazabilidad->secuencia_proceso_id, $secuencias_proceso_temp) ? $secuencias_proceso_temp[$trazabilidad->secuencia_proceso_id] : "";
                    $trazabilidad->funcionario_actual_nombre = array_key_exists($trazabilidad->funcionario_actual_id, $funcionarios_temp) ? $funcionarios_temp[$trazabilidad->funcionario_actual_id] : "";
                }

                $data['trazabilidad'] = $trazabilidad_tramite;

                return response()->json($data);
            }else{
                $data['status'] = 500;
                $data['message'] = '¡Trámite no encontrado!';
                return response()->json($data, 500);
            }
            
            
        }catch (ErrorException $e) {
            $data['status'] = 500;
            $data['message'] = 'Se ha producido un error, por favor intente nuevamente';
            return response()->json($data, 500);
        }
        
    }

}