<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ConceptoStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'descripcion' => 'required|max:50',
            'descripcion_imp' => 'required|max:25',
            'tipo_concepto_id' => 'required',
            'clasificador_id' => 'required',
            'unidad_medida_id' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'descripcion.required' => 'El campo descripción es obligatorio.',                        
            'descripcion_imp.required' => 'El campo descripción corta es obligatorio.',                        
            'descripcion_imp.max' => 'El campo descripción corta no debe ser mayor que :max caracteres.',                        
            'tipo_concepto_id.required' => 'El campo tipo de concepto es obligatorio.',
            'clasificador_id.required' => 'El campo clasificador es obligatorio.',
            'unidad_medida_id.required' => 'El campo unidad de medida es obligatorio.'
        ];
    }
}