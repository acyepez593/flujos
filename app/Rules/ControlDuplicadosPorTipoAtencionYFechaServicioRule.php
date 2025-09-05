<?php

namespace App\Rules;

use App\Models\PrestadorSalud;
use App\Models\RegistroBitacora;
use App\Models\TipoAtencion;
use Carbon\Carbon;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ControlDuplicadosPorTipoAtencionYFechaServicioRule implements ValidationRule
{
    public $request;
    public $id;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct($request,$id)
    {
        $this->request = $request;
        $this->id = $id;
        
    }

    function get_request() {
        return $this->request;
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
        $id = $this->get_id();
        $tipo_atencion = TipoAtencion::find($request->tipo_atencion_id);
        $prestador_salud = PrestadorSalud::find($request->prestador_salud_id);
        $tipo_atencion_nombre = $tipo_atencion->nombre;
        $prestador_salud_nombre = $prestador_salud->prestador_salud;
        $meses = ["01" => "Enero","02" => "Febrero","03" => "Marzo","04" => "Abril","05" => "Mayo","06" => "Junio","07" => "Julio","08" => "Agosto","09" => "Septiembre","10" => "Octubre","11" => "Noviembre","12" => "Diciembre"];
        $fs = explode("-", $request->fecha_servicio);
        $numeroMes = array_search($fs[0],$meses);
        $separador = "-";
        $fechaServicio = $fs[1].$separador.$numeroMes.$separador."01";

        if($id > 0){
            $validar = true;
            $bitacora = RegistroBitacora::find($id);
            $registros_bitacora = RegistroBitacora::where('prestador_salud_id',$request->prestador_salud_id)->where('tipo_atencion_id',$request->tipo_atencion_id)->where('fecha_servicio',$fechaServicio)->get();
            foreach($registros_bitacora as $rb){
                if($bitacora->prestador_salud_id == $rb->prestador_salud_id && $bitacora->tipo_atencion_id == $rb->tipo_atencion_id && $bitacora->fecha_servicio == $rb->fecha_servicio){
                    $validar = false;
                }
            }
            if($validar && $registros_bitacora->count() > 0){
                $fail('Ya existe un Registro Bitácora del prestador de salud: '. $prestador_salud_nombre. ' con tipo de atención: '.$tipo_atencion_nombre. ' y año y mes de servicio '. $fechaServicio);
            }
        }else{
            $registros_bitacora = RegistroBitacora::where('prestador_salud_id',$request->prestador_salud_id)->where('tipo_atencion_id',$request->tipo_atencion_id)->where('fecha_servicio',$fechaServicio)->get();
            if($registros_bitacora->count() > 0){
                $fail('Ya existe un Registro Bitácora del prestador de salud: '. $prestador_salud_nombre. ' con tipo de atención: '.$tipo_atencion_nombre. ' y año y mes de servicio '. $fechaServicio);
            }
        }
    }
}
