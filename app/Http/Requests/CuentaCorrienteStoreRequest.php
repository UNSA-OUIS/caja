<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CuentaCorrienteStoreRequest extends FormRequest
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
            'numero_cuenta' => 'required|unique:cuentas_corrientes',
            'descripcion' => 'required',
            'moneda' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'numero_cuenta.required' => 'El campo número de cuenta es obligatorio.',
            'descripcion.required' => 'El campo descripción es obligatorio.',
            'moneda.required' => 'El campo moneda es obligatorio.',
        ];
    }
}
