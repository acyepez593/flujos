<?php

declare(strict_types=1);
  
namespace App\Http\Requests;
  
use Illuminate\Foundation\Http\FormRequest;
  
class RegistroBitacoraRequest extends FormRequest
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
            'fecha_recepcion' => 'required',
            'tipo_documento_id' => 'required|exists:tipo_documento,id',
            'tipo_ingreso_id' => 'required|exists:tipo_ingreso,id',
            'numero_casos' => 'required|numeric',
            'receptor_documental_id' => 'required|exists:admins,id',
            'monto_planilla' => 'required|numeric',
            'descripcion' => 'max:150',
            'tipo_tramite_id' => 'required|exists:tipo_tramite,id',
            'periodo' => 'required_if:tipo_tramite_id,4'
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
            'fecha_recepcion.required' => 'El campo :attribute es requerido',
            'tipo_documento_id.required' => 'El campo :attribute es requerido',
            'tipo_documento_id.exists' => 'El campo :attribute no existe en el catálogo Tipos Documento',
            'tipo_ingreso_id.required' => 'El campo :attribute es requerido',
            'tipo_ingreso_id.exists' => 'El campo :attribute no existe en el catálogo Tipos Ingreso',
            'tipo_atencion_id.required' => 'El campo :attribute es requerido',
            'tipo_atencion_id.exists' => 'El campo :attribute no existe en el catálogo Tipos Atencion',
            'numero_casos.required' => 'El campo :attribute es requerido',
            'numero_casos.numeric' => 'El campo :attribute debe ser de tipo numérico',
            'receptor_documental_id.required' => 'El campo :attribute es requerido',
            'receptor_documental_id.exists' => 'El campo :attribute no existe como usuario de Recepción Documental',
            'prestador_salud_id.required' => 'El campo :attribute es requerido',
            'prestador_salud_id.exists' => 'El campo :attribute no existe en los Prestadores de Salud',
            'monto_planilla.required' => 'El campo :attribute es requerido',
            'monto_planilla.numeric' => 'El campo :attribute debe ser de tipo numérico',
            'descripcion.required' => 'El campo :attribute es requerido',
            'descripcion.max' => 'La longitud máxima del campo :attribute es :max caracteres',
            'tipo_tramite_id.required' => 'El campo :attribute es requerido',
            'tipo_tramite_id.exists' => 'El campo :attribute no existe en el catálogo Tipos Trámite'
        ];
    }
}