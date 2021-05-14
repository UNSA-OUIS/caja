<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PuntosVentaStoreRequest extends FormRequest
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
            'ip' => 'required|unique:puntos_venta',
            'nombre' => 'required',
            'direccion' => 'required|max:250',
            'user_id' => 'required|unique:puntos_venta',
        ];
    }

    public function messages()
    {
        return [
            'ip.required' => 'El campo IP es obligatorio.',
            'nombre.required' => 'El campo nombre es obligatorio.',
            'direccion.required' => 'El campo dirección es obligatorio.',
            'user_id.required' => 'El campo usuario es obligatorio.',
            'direccion.max' => 'El campo dirección no debe ser mayor que :max caracteres.',
            'ip.unique' => 'Ya existe un punto de venta con esta IP.',
            'user_id.unique' => 'Ya existe un punto de venta con este cajero.',
        ];
    }
}
