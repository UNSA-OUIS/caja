<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AccesoGoogleUpdateRequest extends FormRequest
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
            'nombre' => 'required|max:75',
            'correo' => 'required|email|unique:accesos_google,correo,' . $this->request->get('id'),
            'cargo' => 'max:30',
        ];
    }
}
