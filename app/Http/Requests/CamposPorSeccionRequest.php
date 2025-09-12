<?php

declare(strict_types=1);
  
namespace App\Http\Requests;
  
use Illuminate\Foundation\Http\FormRequest;
  
class CamposPorSeccionRequest extends FormRequest
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
            'seccion_pantalla_id' => 'required',
            'nombre' => 'required',
            'tipo' => 'required',
            'configuracion' => 'required'
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
            'seccion_pantalla_id.required' => 'El campo :attribute es requerido',
            'nombre.required' => 'El campo :attribute es requerido',
            'tipo.required' => 'El campo :attribute es requerido',
            'configuracion.required' => 'El campo :attribute es requerido'
        ];
    }
}