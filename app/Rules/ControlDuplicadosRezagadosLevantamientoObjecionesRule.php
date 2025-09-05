<?php

namespace App\Rules;

use App\Models\RezagadoLevantamientoObjecion;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ControlDuplicadosRezagadosLevantamientoObjecionesRule implements ValidationRule
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
        /*$request = $this->get_request();
        $rezagadoLevantamientoObjecion = RezagadoLevantamientoObjecion::where('monto_planilla',$request->monto_planilla)->where('documento_externo',$request->documento_externo)->get();
        if($rezagadoLevantamientoObjecion->count() > 0){
            $fail('Ya existe un rezagado levantamiento objecion de número: '.$request->documento_externo.' y de monto de planilla: '.$request->monto_planilla);
        }*/


        $request = $this->get_request();
        $id = $this->get_id();
        if($id > 0){
            $validar = true;
            $rezagadoLevantamientoObjecion = RezagadoLevantamientoObjecion::find($id);
            $rezagadosLevantamientoObjeciones = RezagadoLevantamientoObjecion::where('monto_planilla',$request->monto_planilla)->where('documento_externo',$request->documento_externo)->get();
            foreach($rezagadosLevantamientoObjeciones as $of){
                if($rezagadoLevantamientoObjecion->monto_planilla == $of->monto_planilla && $rezagadoLevantamientoObjecion->documento_externo == $of->documento_externo){
                    $validar = false;
                }
            }
            if($validar && $rezagadosLevantamientoObjeciones->count() > 0){
                $fail('Ya existe un Rezagado Levantamiento Objeción de Número: '.$request->documento_externo. ' y Monto Planilla: '.$request->monto_planilla);
            }
        }else{
            $rezagadosLevantamientoObjeciones = RezagadoLevantamientoObjecion::where('monto_planilla',$request->monto_planilla)->where('documento_externo',$request->documento_externo)->get();
            if($rezagadosLevantamientoObjeciones->count() > 0){
                $fail('Ya existe un Rezagado Levantamiento Objeción de Número: '.$request->documento_externo. ' y Monto Planilla: '.$request->monto_planilla);
            }
        }
    }
}
