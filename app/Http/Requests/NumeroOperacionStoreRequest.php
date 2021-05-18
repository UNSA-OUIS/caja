<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'serie' => 'required|size:4|unique:numeros_operacion',
            'correlativo' => 'required',
            'tipo_comprobante_id' => 'required',
            'punto_venta_id' => ['required',
            Rule::unique('numeros_operacion')->where(function ($query) {

                return $query
                    ->where('punto_venta_id', $this->request->get('punto_venta_id'))
                    ->where('tipo_comprobante_id', $this->request->get('tipo_comprobante_id'));
            }),
            ]
            //|unique:numeros_operacion,punto_venta_id,'.$this->request->get('id').',NULL,id,tipo_comprobante_id,'.$this->request->get('tipo_comprobante_id')
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
            'punto_venta_id.unique' => 'Recuerda que sólo se puede registrar un tipo de comprobante por punto de venta.',
            'serie.size' => 'El campo serie debe tener exactamente :size caracteres.',
        ];
    }
}
