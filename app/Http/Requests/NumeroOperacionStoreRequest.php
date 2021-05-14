<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NumeroOperacionStoreRequest extends FormRequest
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
            'serie' => 'required|unique:numeros_operacion',
            'correlativo' => 'required',
            'tipo_comprobante_id' => 'required',
            'punto_venta_id' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'serie.required' => 'El campo serie es obligatorio.',
            'correlativo.required' => 'El campo correlativo es obligatorio.',
            'tipo_comprobante_id.required' => 'El campo tipo de comprobante es obligatorio.',
            'punto_venta_id.required' => 'El campo punto de venta es obligatorio.',
            'serie.unique' => 'Ya existe un punto de venta con esta serie.',
        ];
    }
}
