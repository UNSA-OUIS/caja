<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UnidadMedidaStoreRequest extends FormRequest
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
            'nombre' => 'required|max:50|unique:unidades_medida',
            'codigo' => 'required|max:5|unique:unidades_medida',
        ];
    }

    public function messages()
    {
        return [
            //'nombre.max' => 'El campo nombre no debe ser mayor que :max caracteres.',
        ];
    }
}
