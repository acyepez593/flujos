<?php

namespace App\Rules;

use App\Models\RegistroBitacora;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ControlDuplicadosPorQuipuxRule implements ValidationRule
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
            $bitacora = RegistroBitacora::find($id);
            $registros_bitacora = RegistroBitacora::where('numero_quipux',$request->numero_quipux)->get();
            foreach($registros_bitacora as $rb){
                if($bitacora->numero_quipux == $rb->numero_quipux){
                    $validar = false;
                }
            }
            if($validar && $registros_bitacora->count() > 0){
                $fail('Ya existe un Registro Bitácora de Número: '.$request->numero_quipux);
            }
        }else{
            $registros_bitacora = RegistroBitacora::where('numero_quipux',$request->numero_quipux)->get();
            if($registros_bitacora->count() > 0){
                $fail('Ya existe un Registro de Bitácora de Número: '.$request->numero_quipux);
            }
        }
    }
}
