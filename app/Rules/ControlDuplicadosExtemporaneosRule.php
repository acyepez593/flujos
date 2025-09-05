<?php

namespace App\Rules;

use App\Models\Extemporaneo;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ControlDuplicadosExtemporaneosRule implements ValidationRule
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
        $extemporaneo = Extemporaneo::where('monto_planilla',$request->monto_planilla)->where('documento_externo',$request->documento_externo)->get();
        if($extemporaneo->count() > 0){
            $fail('Ya existe un extemporaneo de número: '.$request->documento_externo.' y de monto de planilla: '.$request->monto_planilla);
        }*/


        $request = $this->get_request();
        $id = $this->get_id();
        if($id > 0){
            $validar = true;
            $extemporaneo = Extemporaneo::find($id);
            $extemporaneos = Extemporaneo::where('monto_planilla',$request->monto_planilla)->where('documento_externo',$request->documento_externo)->get();
            foreach($extemporaneos as $of){
                if($extemporaneo->monto_planilla == $of->monto_planilla && $extemporaneo->documento_externo == $of->documento_externo){
                    $validar = false;
                }
            }
            if($validar && $extemporaneos->count() > 0){
                $fail('Ya existe un Extemporaneo de Número: '.$request->documento_externo. ' y Monto Planilla: '.$request->monto_planilla);
            }
        }else{
            $extemporaneos = Extemporaneo::where('monto_planilla',$request->monto_planilla)->where('documento_externo',$request->documento_externo)->get();
            if($extemporaneos->count() > 0){
                $fail('Ya existe un Extemporaneo de Número: '.$request->documento_externo. ' y Monto Planilla: '.$request->monto_planilla);
            }
        }
    }
}
