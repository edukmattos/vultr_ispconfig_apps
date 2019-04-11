<?php

namespace SisCad\Http\Requests;

use SisCad\Http\Requests\Request;

class GenderRequest extends Request
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
            'code' => 'max:1|required|unique:genders,code,'.$this->id.',id,deleted_at,NULL',
            'description' => 'max:20|required|unique:genders,description,'.$this->id.',id,deleted_at,NULL'
        ];
    }

    public function messages()
    {
        return [
            'code.max' => '<b>Código</b> >> MÁXIMO 1 caracter.',
            'code.required' => '<b>Código</b> >> Preenchimento obrigatório.',
            'code.unique' => '<b>Código</b> >> Indisponível.',
           
            'description.max' => '<b>Descrição</b> >> MÁXIMO 20 caracteres.',
            'description.required' => '<b>Descrição</b> >> Preenchimento obrigatório.',
            'description.unique' => '<b>Descrição</b> >> Indisponível.'
        ];
    }
}
