<?php

declare(strict_types=1);
  
namespace App\Http\Requests;
  
use Illuminate\Foundation\Http\FormRequest;
  
class RangoDiscapacidadRequest extends FormRequest
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
            'normativa_id' => 'required',
            'grado_discapacidad' => 'required',
            'rango_desde' => 'required',
            'rango_hasta' => 'required',
            'valor_cobertura' => 'required',
            'estatus' => 'required'
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
            'normativa_id.required' => 'El campo :attribute es requerido',
            'grado_discapacidad.required' => 'El campo :attribute es requerido',
            'rango_desde.required' => 'El campo :attribute es requerido',
            'rango_hasta.required' => 'El campo :attribute es requerido',
            'valor_cobertura.required' => 'El campo :attribute es requerido',
            'estatus.required' => 'El campo :attribute es requerido',
        ];
    }
}