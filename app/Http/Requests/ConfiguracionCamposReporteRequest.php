<?php

declare(strict_types=1);
  
namespace App\Http\Requests;
  
use Illuminate\Foundation\Http\FormRequest;
  
class ConfiguracionCamposReporteRequest extends FormRequest
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
            'proceso_id' => 'required',
            'habilitar' => 'required',
            'campos' => 'required',
            'funcionario_id' => 'required'
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
            'proceso_id.required' => 'El campo :attribute es requerido',
            'habilitar.required' => 'El campo :attribute es requerido',
            'campos.required' => 'El campo :attribute es requerido',
            'funcionario_id.required' => 'El campo :attribute es requerido'
        ];
    }
}