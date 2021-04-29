<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ParticularStoreRequest extends FormRequest
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
            'dni' => 'required|numeric|digits:8|unique:particulares',
            'nombres' => 'required|max:75',
            'apellidos' => 'required|max:75',
            'email' => 'nullable|max:100|unique:particulares',            
        ];
    }

    public function messages()
    {
        return [
            
        ];
    }
}
