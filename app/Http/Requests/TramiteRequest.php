<?php

declare(strict_types=1);
  
namespace App\Http\Requests;
  
use Illuminate\Foundation\Http\FormRequest;
  
class TramiteRequest extends FormRequest
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
            'proceso_id' => 'required',
            'secuencia_proceso_id' => 'required',
            'funcionario_actual_id' => 'required',
            'datos' => 'required',
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
            'proceso_id.required' => 'El campo :attribute es requerido',
            'secuencia_proceso_id.required' => 'El campo :attribute es requerido',
            'funcionario_actual_id.required' => 'El campo :attribute es requerido',
            'datos.required' => 'El campo :attribute es requerido',
            'estatus.required' => 'El campo :attribute es requerido',
        ];
    }
}