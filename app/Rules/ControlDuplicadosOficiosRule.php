<?php

namespace App\Rules;

use App\Models\Oficio;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ControlDuplicadosOficiosRule implements ValidationRule
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
        $oficio = Oficio::where('monto_planilla',$request->monto_planilla)->where('documento_externo',$request->documento_externo)->get();
        if($oficio->count() > 0){
            $fail('Ya existe un oficio de número: '.$request->documento_externo.' y de monto de planilla: '.$request->monto_planilla);
        }*/


        $request = $this->get_request();
        $id = $this->get_id();
        if($id > 0){
            $validar = true;
            $oficio = Oficio::find($id);
            $oficios = Oficio::where('monto_planilla',$request->monto_planilla)->where('documento_externo',$request->documento_externo)->get();
            foreach($oficios as $of){
                if($oficio->monto_planilla == $of->monto_planilla && $oficio->documento_externo == $of->documento_externo){
                    $validar = false;
                }
            }
            if($validar && $oficios->count() > 0){
                $fail('Ya existe un Oficio de Número: '.$request->documento_externo. ' y Monto Planilla: '.$request->monto_planilla);
            }
        }else{
            $oficios = Oficio::where('monto_planilla',$request->monto_planilla)->where('documento_externo',$request->documento_externo)->get();
            if($oficios->count() > 0){
                $fail('Ya existe un Oficio de Número: '.$request->documento_externo. ' y Monto Planilla: '.$request->monto_planilla);
            }
        }
    }
}
