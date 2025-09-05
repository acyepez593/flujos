<?php

namespace App\Rules;

use App\Models\PrestadorSalud;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ControlDuplicadosPrestadoresSaludRule implements ValidationRule
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
            $prestadorSalud = PrestadorSalud::find($id);
            $prestadoresSalud = PrestadorSalud::where('ruc',$request->ruc)->where('establecimiento',$request->establecimiento)->get();
            foreach($prestadoresSalud as $ps){
                if($prestadorSalud->ruc == $ps->ruc && $prestadorSalud->establecimiento == $ps->establecimiento){
                    $validar = false;
                }
            }
            if($validar && $prestadoresSalud->count() > 0){
                $fail('Ya existe un Prestador de Salud con RUC: '.$request->ruc. ' y establecimiento: '.$request->establecimiento);
            }
        }else{
            $prestadoresSalud = PrestadorSalud::where('ruc',$request->ruc)->where('establecimiento',$request->establecimiento)->get();
            if($prestadoresSalud->count() > 0){
                $fail('Ya existe un Prestador de Salud con RUC: '.$request->ruc. ' y establecimiento: '.$request->establecimiento);
            }
        }
        
    }
}
