<?php

namespace App\Rules;

use App\Models\Oficio;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ControlDuplicadosExpedientesPorQuipuxRule implements ValidationRule
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
        if($id > 0){
            $validar = true;
            $bitacora = Oficio::find($id);
            $expedientes = Oficio::where('numero_quipux',$request->numero_quipux)->get();
            foreach($expedientes as $ex){
                if($bitacora->numero_quipux == $ex->numero_quipux){
                    $validar = false;
                }
            }
            if($validar && $expedientes->count() > 0){
                $fail('Ya existe un Expediente con Número de Quipux: '.$request->numero_quipux);
            }
        }else{
            $expedientes = Oficio::where('numero_quipux',$request->numero_quipux)->get();
            if($expedientes->count() > 0){
                $fail('Ya existe un Expediente con Número de Quipux: '.$request->numero_quipux);
            }
        }
    }
}
