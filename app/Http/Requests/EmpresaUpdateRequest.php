<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmpresaUpdateRequest extends FormRequest
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
            'ruc' => 'required|numeric|digits:11|unique:empresas,ruc,' . $this->request->get('id'),
            'razon_social' => 'required|max:250',
            'email' => 'required|unique:empresas,email,' . $this->request->get('id'),            
            'direccion' => 'required|max:250',            
        ];
    }

    public function messages()
    {
        return [
            'razon_social.required' => 'El campo razón social es obligatorio.',                        
        ];
    }
}
