<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ParticularUpdateRequest extends FormRequest
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
            'dni' => 'required|unique:particulares,dni,' . $this->request->get('id'),
            'nombres' => 'required|max:75',
            'apellidos' => 'required|max:75',
            'email' => 'required|unique:particulares,email,' . $this->request->get('id'),            
        ];
    }
}
