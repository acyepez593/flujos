<?php

declare(strict_types=1);
  
namespace App\Http\Requests;
  
use Illuminate\Foundation\Http\FormRequest;
  
class PrestadorSaludRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
  
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'ruc' => 'required|min:13|max:13',
            'establecimiento' => 'required|max:100',
            'prestador1' => 'required|max:300',
            'prestador2' => 'max:300',
            'prestador_salud' => 'required|max:600',
            'publico_privado_achpe' => 'required|max:500',
            'provincia_id' => 'required',
            'canton_id' => 'required',
            'parroquia_id' => 'required',
            'direccion' => 'required|max:100',
            'responsable_planillaje' => 'max:50',
            'telefono' => 'max:100',
            'ext' => 'max:50',
            'numero_celular' => 'max:100',
            'mail' => 'max:300',
            'unicodigo' => 'required',
            'nivel_atencion' => 'required'
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'ruc.required' => 'El campo :attribute es requerido',
            'ruc.min' => 'La longitud mínima del campo :attribute es :min caracteres',
            'ruc.max' => 'La longitud máxima del campo :attribute es :max caracteres',
            'establecimiento.required' => 'El campo :attribute es requerido',
            'establecimiento.max' => 'La longitud máxima del campo :attribute es :max caracteres',
            'prestador1.required' => 'El campo :attribute es requerido',
            'prestador1.max' => 'La longitud máxima del campo :attribute es :max caracteres',
            'prestador2.required' => 'El campo :attribute es requerido',
            'prestador2.max' => 'La longitud máxima del campo :attribute es :max caracteres',
            'prestador_salud.required' => 'El campo :attribute es requerido',
            'prestador_salud.max' => 'La longitud máxima del campo :attribute es :max caracteres',
            'publico_privado_achpe.required' => 'El campo :attribute es requerido',
            'publico_privado_achpe.max' => 'La longitud máxima del campo :attribute es :max caracteres',
            'provincia_id.required' => 'El campo :attribute es requerido',
            'canton_id.required' => 'El campo :attribute es requerido',
            'parroquia_id.required' => 'El campo :attribute es requerido',
            'direccion.required' => 'El campo :attribute es requerido',
            'direccion.max' => 'La longitud máxima del campo :attribute es :max caracteres',
            'responsable_planillaje.required' => 'El campo :attribute es requerido',
            'responsable_planillaje.max' => 'La longitud máxima del campo :attribute es :max caracteres',
            'telefono.max' => 'La longitud máxima del campo :attribute es :max caracteres',
            'ext.max' => 'La longitud máxima del campo :attribute es :max caracteres',
            'numero_celular.max' => 'La longitud máxima del campo :attribute es :max caracteres',
            'mail.max' => 'La longitud máxima del campo :attribute es :max caracteres',
            'unicodigo.required' => 'El campo :attribute es requerido',
            'nivel_atencion.required' => 'El campo :attribute es requerido',
        ];
    }
}