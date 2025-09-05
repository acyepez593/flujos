<?php

namespace App\Rules;

use App\Models\PrestadorSalud;
use App\Models\TipoAtencion;
use Carbon\Carbon;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ControlDuplicadosExpedientesPorTipoAtencionYFechaServicioRule implements ValidationRule
{
    public $request;
    public $modelName;
    public $id;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct($request,$modelName,$id)
    {
        $this->request = $request;
        $this->modelName = $modelName;
        $this->id = $id;
        
    }

    function get_request() {
        return $this->request;
    }

    function get_model_name() {
        return $this->modelName;
    }

    function get_id() {
        return $this->id;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {

        $request = $this->get_request();
        $modelName = $this->get_model_name();
        $id = $this->get_id();

        $fullModelClass = "App\\Models\\{$modelName}";

        $tipo_atencion = TipoAtencion::find($request->tipo_atencion_id);
        $prestador_salud = PrestadorSalud::find($request->prestador_salud_id);
        $tipo_atencion_nombre = $tipo_atencion->nombre;
        $prestador_salud_nombre = $prestador_salud->prestador_salud;
        $meses = ["01" => "Enero","02" => "Febrero","03" => "Marzo","04" => "Abril","05" => "Mayo","06" => "Junio","07" => "Julio","08" => "Agosto","09" => "Septiembre","10" => "Octubre","11" => "Noviembre","12" => "Diciembre"];
        $fs = explode("-", $request->fecha_servicio);
        $numeroMes = array_search($fs[0],$meses);
        $separador = "-";
        $fechaServicio = $fs[1].$separador.$numeroMes.$separador."01";

        if (class_exists($fullModelClass)) {
            if($id > 0){
                $validar = true;
                $expediente = $fullModelClass::find($id);
                $expedientes = $fullModelClass::where('prestador_salud_id',$request->prestador_salud_id)->where('tipo_atencion_id',$request->tipo_atencion_id)->where('fecha_servicio',$fechaServicio)->get();
                foreach($expedientes as $ex){
                    if($expediente->prestador_salud_id == $ex->prestador_salud_id && $expediente->tipo_atencion_id == $ex->tipo_atencion_id && $expediente->fecha_servicio == $ex->fecha_servicio){
                        $validar = false;
                    }
                }
                if($validar && $expedientes->count() > 0){
                    $fail('Ya existe un Expediente del prestador de salud: '. $prestador_salud_nombre. ' con tipo de atención: '.$tipo_atencion_nombre. ' y año y mes de servicio '. $fechaServicio);
                }
            }else{
                $expedientes = $fullModelClass::where('prestador_salud_id',$request->prestador_salud_id)->where('tipo_atencion_id',$request->tipo_atencion_id)->where('fecha_servicio',$fechaServicio)->get();
                if($expedientes->count() > 0){
                    $fail('Ya existe un Expediente del prestador de salud: '. $prestador_salud_nombre. ' con tipo de atención: '.$tipo_atencion_nombre. ' y año y mes de servicio '. $fechaServicio);
                }
            }

        }else{
            $fail('El modelo consultado no existe, consulta con el administrador.');
        }
    }
}
