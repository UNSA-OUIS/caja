<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MiUsuarioUpdateRequest extends FormRequest
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
            'codigo' => 'unique:personas,codigo,'.$this->request->get('id'),
            'celular' => 'digits:9',
            'direccion' => 'max:250',
            'email_personal' => 'email',
        ];
    }

    public function messages()
    {
        return [
            'celular.required' => 'El campo celular debe ser un número válido.',                        
            'email_personal.required' => 'El campo correo personal debe ser un email válido.',                        
        ];
    }
}
