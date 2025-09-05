<?php

declare(strict_types=1);
  
namespace App\Http\Requests;
  
use Illuminate\Foundation\Http\FormRequest;
  
class OficioRequest extends FormRequest
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
            'fecha_registro' => 'required',
            'tipo_id' => 'required|exists:tipos,id',
            'ruc' => 'required|max:13',
            'numero_establecimiento' => 'max:6',
            'razon_social' => 'required|max:300',
            'fecha_recepcion' => 'required',
            'tipo_atencion_id' => 'required|exists:tipo_atencion,id',
            'provincia_id' => 'required|exists:provincias,id',
            'fecha_servicio' => 'required',
            'numero_casos' => 'required|numeric',
            'monto_planilla' => 'required|numeric',
            'numero_caja' => 'required|max:20',
            'numero_caja_auditoria' => 'max:20',
            'institucion_id' => 'required|exists:instituciones,id',
            'documento_externo' => 'required|max:100',
            'observaciones' => 'max:600',
            'numero_quipux' => 'required|max:600',
            'files' => 'array',
            'files.*' => 'mimes:pdf|max:2048'

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
            'fecha_registro.required' => 'El campo :attribute es requerido',
            'tipo_id.required' => 'El campo :attribute es requerido',
            'tipo_id.exists' => 'El campo :attribute no existe en el catálogo Tipos',
            'numero_establecimiento.max' => 'La longitud máxima del campo :attribute es :max caracteres',
            'razon_social.required' => 'El campo :attribute es requerido',
            'razon_social.max' => 'La longitud máxima del campo :attribute es :max caracteres',
            'fecha_recepcion.required' => 'El campo :attribute es requerido',
            'tipo_atencion_id.required' => 'El campo :attribute es requerido',
            'tipo_atencion_id.exists' => 'El campo :attribute no existe en el catálogo Tipos de Atención',
            'provincia_id.required' => 'El campo :attribute es requerido',
            'provincia_id.exists' => 'El campo :attribute no existe en el catálogo Provincias',
            'fecha_servicio.required' => 'El campo :attribute es requerido',
            'numero_casos.required' => 'El campo :attribute es requerido',
            'numero_casos.numeric' => 'El campo :attribute debe ser de tipo numérico',
            'monto_planilla.required' => 'El campo :attribute es requerido',
            'monto_planilla.numeric' => 'El campo :attribute debe ser de tipo numérico',
            'numero_caja.required' => 'El campo :attribute es requerido',
            'numero_caja.max' => 'La longitud máxima del campo :attribute es :max caracteres',
            'numero_caja_auditoria.required' => 'El campo :attribute es requerido',
            'numero_caja_auditoria.max' => 'La longitud máxima del campo :attribute es :max caracteres',
            'institucion_id.required' => 'El campo :attribute es requerido',
            'institucion_id.exists' => 'El campo :attribute no existe en el catálogo Instituciones',
            'documento_externo.required' => 'El campo :attribute es requerido',
            'documento_externo.max' => 'La longitud máxima del campo :attribute es :max caracteres',
            'numero_informe.max' => 'La longitud máxima del campo :attribute es :max caracteres',
            'observaciones.required' => 'El campo :attribute es requerido',
            'observaciones.max' => 'La longitud máxima del campo :attribute es :max caracteres',
            'files.*.mimes' => 'El campo :attribute solo acepta documentos con extension .pdf',
            'files.*.max' => 'El tamaño máximo del campo :attribute es :max MB'
        ];
    }
}