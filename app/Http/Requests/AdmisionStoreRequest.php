<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdmisionStoreRequest extends FormRequest
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
            'proceso_id' => 'required',
            'monto_total' => 'required',
            'tipo_colegio' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'proceso_id.required' => 'Debe seleccionar un proceso.',
            'monto_total.required' => 'El campo monto total es obligatorio.',
            'tipo_colegio.required' => 'Debe seleccionar un tipo de colegio.',
        ];
    }
}
