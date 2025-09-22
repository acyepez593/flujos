<?php

declare(strict_types=1);
  
namespace App\Http\Requests;
  
use Illuminate\Foundation\Http\FormRequest;
  
class SecuenciaProcesoRequest extends FormRequest
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
            'nombre' => 'required',
            'descripcion' => 'required',
            'estatus' => 'required',
            'tiempo_procesamiento' => 'required',
            'actores' => 'required',
            'configuracion' => 'required',
            'configuracion_campos' => 'required'
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
            'nombre.required' => 'El campo :attribute es requerido',
            'descripcion.required' => 'El campo :attribute es requerido',
            'estatus.required' => 'El campo :attribute es requerido',
            'tiempo_procesamiento.required' => 'El campo :attribute es requerido',
            'actores.required' => 'El campo :attribute es requerido',
            'configuracion.required' => 'El campo :attribute es requerido',
            'configuracion_campos.required' => 'El campo :attribute es requerido',
        ];
    }
}